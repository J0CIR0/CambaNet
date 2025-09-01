<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/CursoModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/MaterialModel.php';
require_once __DIR__ . '/../models/CalificacionModel.php';
class ProfesorController extends BaseController {
    public function dashboard() {
        $this->checkProfesorAuth();
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        $sqlCursos = "SELECT * FROM cursos WHERE profesor_id = ? ORDER BY fecha_creacion DESC";
        $stmtCursos = $conexion->prepare($sqlCursos);
        $stmtCursos->bind_param("i", $_SESSION['user_id']);
        $stmtCursos->execute();
        $resultadoCursos = $stmtCursos->get_result();
        $cursos = $resultadoCursos->fetch_all(MYSQLI_ASSOC);
        $totalEstudiantes = 0;
        foreach ($cursos as $curso) {
            $sqlEstudiantes = "SELECT COUNT(*) as total FROM inscripciones WHERE curso_id = ?";
            $stmtEst = $conexion->prepare($sqlEstudiantes);
            $stmtEst->bind_param("i", $curso['id']);
            $stmtEst->execute();
            $result = $stmtEst->get_result();
            $row = $result->fetch_assoc();
            $totalEstudiantes += $row['total'];
        }
        $data = [
            'cursos' => $cursos,
            'totalEstudiantes' => $totalEstudiantes,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        $this->renderView('profesor/dashboard.php', $data);
    }
    public function misCursos() {
        $this->checkProfesorAuth();
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        $sqlCursos = "SELECT c.*, 
                    (SELECT COUNT(*) FROM inscripciones WHERE curso_id = c.id) as total_estudiantes
                    FROM cursos c 
                    WHERE c.profesor_id = ? 
                    ORDER BY c.fecha_creacion DESC";
        $stmtCursos = $conexion->prepare($sqlCursos);
        $stmtCursos->bind_param("i", $_SESSION['user_id']);
        $stmtCursos->execute();
        $cursos = $stmtCursos->get_result()->fetch_all(MYSQLI_ASSOC);
        $data = [
            'cursos' => $cursos,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        $this->renderView('profesor/mis-cursos.php', $data);
    }
    public function calificaciones() {
        $this->checkProfesorAuth();
        $data = [
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        $this->renderView('profesor/calificaciones.php', $data);
    }
    public function editarCurso($id) {
        $this->checkProfesorAuth();
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        $sql = "SELECT * FROM cursos WHERE id = ? AND profesor_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['user_id']);
        $stmt->execute();
        $curso = $stmt->get_result()->fetch_assoc();
        if (!$curso) {
            $_SESSION['error'] = "Curso no encontrado o no tienes permisos";
            redirect('profesor/mis-cursos');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $activo = isset($_POST['activo']) ? 1 : 0;
            $sqlUpdate = "UPDATE cursos SET nombre = ?, descripcion = ?, activo = ? WHERE id = ? AND profesor_id = ?";
            $stmtUpdate = $conexion->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ssiii", $nombre, $descripcion, $activo, $id, $_SESSION['user_id']);
            if ($stmtUpdate->execute()) {
                $_SESSION['success'] = "Curso actualizado correctamente";
                redirect('profesor/mis-cursos');
            } else {
                $_SESSION['error'] = "Error al actualizar el curso";
            }
        }
        $data = [
            'curso' => $curso,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        
        $this->renderView('profesor/editar-curso.php', $data);
    }
    public function verEstudiantes($id) {
        $this->checkProfesorAuth();
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        $sqlCurso = "SELECT * FROM cursos WHERE id = ? AND profesor_id = ?";
        $stmtCurso = $conexion->prepare($sqlCurso);
        $stmtCurso->bind_param("ii", $id, $_SESSION['user_id']);
        $stmtCurso->execute();
        $curso = $stmtCurso->get_result()->fetch_assoc();
        if (!$curso) {
            $_SESSION['error'] = "Curso no encontrado o no tienes permisos";
            redirect('profesor/mis-cursos');
        }
        $sqlEstudiantes = "SELECT u.*, i.fecha_inscripcion, i.estado 
                          FROM inscripciones i 
                          INNER JOIN usuarios u ON i.estudiante_id = u.id 
                          WHERE i.curso_id = ? 
                          ORDER BY u.nombre ASC";
        $stmtEst = $conexion->prepare($sqlEstudiantes);
        $stmtEst->bind_param("i", $id);
        $stmtEst->execute();
        $estudiantes = $stmtEst->get_result()->fetch_all(MYSQLI_ASSOC);
        $data = [
            'curso' => $curso,
            'estudiantes' => $estudiantes,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        $this->renderView('profesor/estudiantes-curso.php', $data);
    }
    public function verEstudiantesGeneral() {
        $this->checkProfesorAuth();
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.rol_id = 3 
                ORDER BY u.nombre ASC";
        $result = $conexion->query($sql);
        $estudiantes = $result->fetch_all(MYSQLI_ASSOC);
        $data = [
            'estudiantes' => $estudiantes,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        $this->renderView('profesor/estudiantes-general.php', $data);
    }
    public function material() {
        $this->checkProfesorAuth();
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        $sqlCursos = "SELECT * FROM cursos WHERE profesor_id = ? ORDER BY nombre";
        $stmtCursos = $conexion->prepare($sqlCursos);
        $stmtCursos->bind_param("i", $_SESSION['user_id']);
        $stmtCursos->execute();
        $cursos = $stmtCursos->get_result()->fetch_all(MYSQLI_ASSOC);
        $material = [];
        $curso_seleccionado = null;
        if (isset($_GET['curso_id']) && !empty($_GET['curso_id'])) {
            $materialModel = new MaterialModel();
            $material = $materialModel->getMaterialByCurso($_GET['curso_id'], $_SESSION['user_id']);
            $sqlCurso = "SELECT * FROM cursos WHERE id = ? AND profesor_id = ?";
            $stmtCurso = $conexion->prepare($sqlCurso);
            $stmtCurso->bind_param("ii", $_GET['curso_id'], $_SESSION['user_id']);
            $stmtCurso->execute();
            $curso_seleccionado = $stmtCurso->get_result()->fetch_assoc();
        }
        $data = [
            'cursos' => $cursos,
            'material' => $material,
            'curso_seleccionado' => $curso_seleccionado,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        $this->renderView('profesor/material.php', $data);
    }
    public function subirMaterial() {
        $this->checkProfesorAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('profesor/material');
        }
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        $sqlVerificar = "SELECT * FROM cursos WHERE id = ? AND profesor_id = ?";
        $stmtVerificar = $conexion->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ii", $_POST['curso_id'], $_SESSION['user_id']);
        $stmtVerificar->execute();
        $curso = $stmtVerificar->get_result()->fetch_assoc();
        if (!$curso) {
            $_SESSION['error'] = "Curso no válido";
            redirect('profesor/material');
        }
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['archivo'];
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $extensionesPermitidas = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'txt'];
            if (!in_array($extension, $extensionesPermitidas)) {
                $_SESSION['error'] = "Tipo de archivo no permitido";
                redirect('profesor/material');
            }
            $uploadDir = __DIR__ . '/../../uploads/material/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $nombreArchivo = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $archivo['name']);
            $rutaArchivo = $uploadDir . $nombreArchivo;
            if (move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                $materialModel = new MaterialModel();
                $tipoArchivo = $this->getTipoArchivo($extension);
                $data = [
                    'curso_id' => $_POST['curso_id'],
                    'profesor_id' => $_SESSION['user_id'],
                    'titulo' => $_POST['titulo'],
                    'descripcion' => $_POST['descripcion'],
                    'archivo_nombre' => $archivo['name'],
                    'archivo_ruta' => $rutaArchivo,
                    'tipo_archivo' => $tipoArchivo
                ];
                if ($materialModel->createMaterial($data)) {
                    $_SESSION['success'] = "Material subido exitosamente";
                } else {
                    $_SESSION['error'] = "Error al guardar el material";
                    if (file_exists($rutaArchivo)) {
                        unlink($rutaArchivo);
                    }
                }
            } else {
                $_SESSION['error'] = "Error al subir el archivo";
            }
        } else {
            $_SESSION['error'] = "Debe seleccionar un archivo válido";
        }
        $curso_id = $_POST['curso_id'] ?? '';
        redirect('profesor/material&curso_id=' . $curso_id);
    }
    private function getTipoArchivo($extension) {
        $tipos = [
            'pdf' => 'pdf',
            'doc' => 'doc', 'docx' => 'doc',
            'ppt' => 'ppt', 'pptx' => 'ppt',
            'jpg' => 'image', 'jpeg' => 'image', 'png' => 'image', 'gif' => 'image',
            'mp4' => 'video', 'mov' => 'video', 'avi' => 'video',
        ];
        return $tipos[$extension] ?? 'otro';
    }
    public function eliminarMaterial($id) {
        $this->checkProfesorAuth();
        $materialModel = new MaterialModel();
        if ($materialModel->deleteMaterial($id, $_SESSION['user_id'])) {
            $_SESSION['success'] = "Material eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el material o no tienes permisos";
        }
        $curso_id = $_GET['curso_id'] ?? '';
        redirect('profesor/material&curso_id=' . $curso_id);
    }

    public function gestionarCalificaciones($curso_id = null) {
        $this->checkProfesorAuth();
        $calificacionModel = new CalificacionModel();
        $cursoModel = new CursoModel();
        $cursos = $cursoModel->getCursosByProfesor($_SESSION['user_id']);
        if (!$curso_id && !empty($cursos)) {
            $curso_id = $cursos[0]['id'];
        }
        $actividades = [];
        $estadisticas = [];
        $curso_seleccionado = null;
        if ($curso_id) {
            $actividades = $calificacionModel->getActividadesPorCurso($curso_id, $_SESSION['user_id']);
            $estadisticas = $calificacionModel->getEstadisticasCurso($curso_id);
            $curso_seleccionado = $cursoModel->getCursoById($curso_id);
        }
        $data = [
            'cursos' => $cursos,
            'actividades' => $actividades,
            'estadisticas' => $estadisticas,
            'curso_seleccionado' => $curso_seleccionado,
            'curso_id' => $curso_id,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        $this->renderView('profesor/calificaciones.php', $data);
    }
    public function crearActividad() {
        $this->checkProfesorAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $calificacionModel = new CalificacionModel();
            
            $data = [
                'curso_id' => $_POST['curso_id'],
                'profesor_id' => $_SESSION['user_id'],
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'tipo' => $_POST['tipo'],
                'puntaje_maximo' => $_POST['puntaje_maximo'],
                'fecha_limite' => $_POST['fecha_limite']
            ];
            
            if ($calificacionModel->crearActividad($data)) {
                $_SESSION['success'] = "Actividad creada exitosamente";
            } else {
                $_SESSION['error'] = "Error al crear la actividad";
            }
            
            redirect('profesor/calificaciones&curso_id=' . $_POST['curso_id']);
        }
    }
    public function calificarActividad($actividad_id) {
        $this->checkProfesorAuth();
        $calificacionModel = new CalificacionModel();
        $actividad = $calificacionModel->getActividadById($actividad_id, $_SESSION['user_id']);
        if (!$actividad) {
            $_SESSION['error'] = "Actividad no encontrada";
            redirect('profesor/calificaciones');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST['calificaciones'] as $estudiante_id => $calificacion) {
                $data = [
                    'estudiante_id' => $estudiante_id,
                    'actividad_id' => $actividad_id,
                    'curso_id' => $actividad['curso_id'],
                    'puntaje_obtenido' => $calificacion['puntaje'],
                    'comentario' => $calificacion['comentario'] ?? ''
                ];
                
                $calificacionModel->registrarCalificacion($data);
            }
            $_SESSION['success'] = "Calificaciones guardadas exitosamente";
            redirect('profesor/calificaciones&curso_id=' . $actividad['curso_id']);
        }
        global $conexion;
        $sql = "SELECT u.id, u.nombre, u.email, c.puntaje_obtenido, c.comentario
                FROM inscripciones i
                INNER JOIN usuarios u ON i.estudiante_id = u.id
                LEFT JOIN calificaciones c ON u.id = c.estudiante_id AND c.actividad_id = ?
                WHERE i.curso_id = ? AND i.estado = 'activo'
                ORDER BY u.nombre";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $actividad_id, $actividad['curso_id']);
        $stmt->execute();
        $estudiantes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $data = [
            'actividad' => $actividad,
            'estudiantes' => $estudiantes,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        
        $this->renderView('profesor/calificar-actividad.php', $data);
    }
    public function eliminarActividad($id) {
        $this->checkProfesorAuth();
        $calificacionModel = new CalificacionModel();
        $actividad = $calificacionModel->getActividadById($id, $_SESSION['user_id']);
        
        if ($calificacionModel->eliminarActividad($id, $_SESSION['user_id'])) {
            $_SESSION['success'] = "Actividad eliminada exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar la actividad";
        }
        
        redirect('profesor/calificaciones&curso_id=' . $actividad['curso_id']);
    }

    public function editarActividad($id) {
        $this->checkProfesorAuth();
        $calificacionModel = new CalificacionModel();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => $_POST['titulo'],
                'descripcion' => $_POST['descripcion'],
                'tipo' => $_POST['tipo'],
                'puntaje_maximo' => $_POST['puntaje_maximo'],
                'fecha_limite' => $_POST['fecha_limite'],
                'activo' => isset($_POST['activo'])
            ];
            
            if ($calificacionModel->editarActividad($id, $_SESSION['user_id'], $data)) {
                $_SESSION['success'] = "Actividad actualizada exitosamente";
            } else {
                $_SESSION['error'] = "Error al actualizar la actividad";
            }
            
            $actividad = $calificacionModel->getActividadById($id, $_SESSION['user_id']);
            redirect('profesor/calificaciones&curso_id=' . $actividad['curso_id']);
        }
        $actividad = $calificacionModel->getActividadById($id, $_SESSION['user_id']);
        
        if (!$actividad) {
            $_SESSION['error'] = "Actividad no encontrada";
            redirect('profesor/calificaciones');
        }
        
        $data = [
            'actividad' => $actividad,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        
        $this->renderView('profesor/editar-actividad.php', $data);
    }
}
?>