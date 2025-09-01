<?php
require_once __DIR__ . '/../models/CursoModel.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/MaterialModel.php';
class EstudianteController {
    private function checkEstudianteAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 3) {
            header("Location: 172.20.10.3/CambaNet/public/?action=login");
            exit();
        }
        if (!isset($_SESSION['user_nombre'])) {
            $_SESSION['user_nombre'] = 'Estudiante';
        }
        if (!isset($_SESSION['user_email'])) {
            $_SESSION['user_email'] = '';
        }
    }
    public function dashboard() {
        $this->checkEstudianteAuth();
        require_once __DIR__ . '/../../config/database.php';
        global $conexion;
        $sqlInscritos = "SELECT c.*, i.fecha_inscripcion, i.estado 
                        FROM inscripciones i 
                        INNER JOIN cursos c ON i.curso_id = c.id 
                        WHERE i.estudiante_id = ? 
                        ORDER BY i.fecha_inscripcion DESC";
        $stmtInscritos = $conexion->prepare($sqlInscritos);
        $stmtInscritos->bind_param("i", $_SESSION['user_id']);
        $stmtInscritos->execute();
        $cursosInscritos = $stmtInscritos->get_result()->fetch_all(MYSQLI_ASSOC);
        $sqlDisponibles = "SELECT c.*, u.nombre as profesor_nombre 
                          FROM cursos c 
                          INNER JOIN usuarios u ON c.profesor_id = u.id 
                          WHERE c.activo = 1 
                          AND c.id NOT IN (
                              SELECT curso_id FROM inscripciones WHERE estudiante_id = ?
                          ) 
                          ORDER BY c.nombre ASC";
        $stmtDisp = $conexion->prepare($sqlDisponibles);
        $stmtDisp->bind_param("i", $_SESSION['user_id']);
        $stmtDisp->execute();
        $cursosDisponibles = $stmtDisp->get_result()->fetch_all(MYSQLI_ASSOC);
        $data = [
            'cursosInscritos' => $cursosInscritos,
            'cursosDisponibles' => $cursosDisponibles,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        extract($data);
        require __DIR__ . '/../views/estudiante/dashboard.php';
    }
    public function misCursos() {
        $this->checkEstudianteAuth();
        require_once __DIR__ . '/../../config/database.php';
        global $conexion;
        $sql = "SELECT c.*, i.fecha_inscripcion, i.estado, u.nombre as profesor_nombre,
                       (SELECT COUNT(*) FROM material_didactico WHERE curso_id = c.id) as total_material
                FROM inscripciones i 
                INNER JOIN cursos c ON i.curso_id = c.id 
                INNER JOIN usuarios u ON c.profesor_id = u.id
                WHERE i.estudiante_id = ? 
                ORDER BY i.fecha_inscripcion DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $cursos = $result->fetch_all(MYSQLI_ASSOC);
        $data = [
            'cursos' => $cursos,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        extract($data);
        require __DIR__ . '/../views/estudiante/mis-cursos.php';
    }
    public function inscribirCurso($id) {
        $this->checkEstudianteAuth();
        require_once __DIR__ . '/../../config/database.php';
        global $conexion;
        $sqlCurso = "SELECT * FROM cursos WHERE id = ? AND activo = 1";
        $stmtCurso = $conexion->prepare($sqlCurso);
        $stmtCurso->bind_param("i", $id);
        $stmtCurso->execute();
        $curso = $stmtCurso->get_result()->fetch_assoc();
        if (!$curso) {
            $_SESSION['error'] = "Curso no disponible";
            header("Location: 172.20.10.3/CambaNet/public/?action=estudiante/dashboard");
            exit();
        }
        $sqlCheck = "SELECT * FROM inscripciones WHERE estudiante_id = ? AND curso_id = ?";
        $stmtCheck = $conexion->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $_SESSION['user_id'], $id);
        $stmtCheck->execute();
        if ($stmtCheck->get_result()->num_rows > 0) {
            $_SESSION['error'] = "Ya estás inscrito en este curso";
            header("Location: 172.20.10.3/CambaNet/public/?action=estudiante/dashboard");
            exit();
        }
        $sqlInscribir = "INSERT INTO inscripciones (estudiante_id, curso_id, estado) VALUES (?, ?, 'activo')";
        $stmtInscribir = $conexion->prepare($sqlInscribir);
        $stmtInscribir->bind_param("ii", $_SESSION['user_id'], $id);
        if ($stmtInscribir->execute()) {
            $_SESSION['success'] = "¡Inscripción exitosa!";
        } else {
            $_SESSION['error'] = "Error al inscribirse en el curso";
        }
        header("Location: 172.20.10.3/CambaNet/public/?action=estudiante/dashboard");
        exit();
    }
    public function cancelarInscripcion($id) {
        $this->checkEstudianteAuth();
        require_once __DIR__ . '/../../config/database.php';
        global $conexion;
        $sql = "DELETE FROM inscripciones WHERE estudiante_id = ? AND curso_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $_SESSION['user_id'], $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Inscripción cancelada correctamente";
        } else {
            $_SESSION['error'] = "Error al cancelar la inscripción";
        }
        header("Location: 172.20.10.3/CambaNet/public/?action=estudiante/dashboard");
        exit();
    }
    public function verCurso($id) {
        $this->checkEstudianteAuth();
        require_once __DIR__ . '/../../config/database.php';
        global $conexion;
        $sql = "SELECT c.*, i.estado, i.fecha_inscripcion 
                FROM inscripciones i 
                INNER JOIN cursos c ON c.id = i.curso_id 
                WHERE c.id = ? AND i.estudiante_id = ? AND i.estado = 'activo'";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $curso = $result->fetch_assoc();
        if (!$curso) {
            $_SESSION['error'] = "No tienes acceso a este curso o no estás inscrito";
            header("Location: 172.20.10.3/CambaNet/public/?action=estudiante/dashboard");
            exit();
        }
        $materialModel = new MaterialModel();
        $material = $materialModel->getMaterialByCurso($id);
        $sqlProfesor = "SELECT nombre, email FROM usuarios WHERE id = ?";
        $stmtProf = $conexion->prepare($sqlProfesor);
        $stmtProf->bind_param("i", $curso['profesor_id']);
        $stmtProf->execute();
        $profesor = $stmtProf->get_result()->fetch_assoc();
        $data = [
            'curso' => $curso,
            'material' => $material,
            'profesor' => $profesor,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        extract($data);
        require __DIR__ . '/../views/estudiante/ver-curso.php';
    }
}
?>