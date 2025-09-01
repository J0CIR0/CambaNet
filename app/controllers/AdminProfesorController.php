<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
class AdminProfesorController {
    private function checkAdminAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            header("Location: 172.20.10.3/CambaNet/public/?action=login");
            exit();
        }
    }
    public function index() {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        $profesores = $usuarioModel->getUsersByRole(2);
        require __DIR__ . '/../views/admin/profesores.php';
    }
    public function material() {
        $this->checkProfesorAuth();
        require_once __DIR__ . '/../../config/database.php';
        global $conexion;
        $sqlCursos = "SELECT * FROM cursos WHERE profesor_id = ? ORDER BY nombre";
        $stmtCursos = $conexion->prepare($sqlCursos);
        $stmtCursos->bind_param("i", $_SESSION['user_id']);
        $stmtCursos->execute();
        $cursos = $stmtCursos->get_result()->fetch_all(MYSQLI_ASSOC);
        $material = [];
        $curso_seleccionado = null;
        if (isset($_GET['curso_id']) && !empty($_GET['curso_id'])) {
            require_once __DIR__ . '/../models/MaterialModel.php';
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
        require __DIR__ . '/../views/profesor/material.php';
    }
    public function subirMaterial() {
        $this->checkProfesorAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: 172.20.10.3/CambaNet/public/?action=profesor/material");
            exit();
        }
        require_once __DIR__ . '/../../config/database.php';
        global $conexion;
        $sqlVerificar = "SELECT * FROM cursos WHERE id = ? AND profesor_id = ?";
        $stmtVerificar = $conexion->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ii", $_POST['curso_id'], $_SESSION['user_id']);
        $stmtVerificar->execute();
        $curso = $stmtVerificar->get_result()->fetch_assoc();
        if (!$curso) {
            $_SESSION['error'] = "Curso no válido";
            header("Location: 172.20.10.3/CambaNet/public/?action=profesor/material");
            exit();
        }
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['archivo'];
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $extensionesPermitidas = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'txt'];
            if (!in_array($extension, $extensionesPermitidas)) {
                $_SESSION['error'] = "Tipo de archivo no permitido";
                header("Location: 172.20.10.3/CambaNet/public/?action=profesor/material");
                exit();
            }
            $nombreArchivo = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $archivo['name']);
            $rutaArchivo = __DIR__ . '/../../uploads/material/' . $nombreArchivo;
            if (move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                require_once __DIR__ . '/../models/MaterialModel.php';
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
        header("Location: 172.20.10.3/CambaNet/public/?action=profesor/material&curso_id=" . $_POST['curso_id']);
        exit();
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
        require_once __DIR__ . '/../models/MaterialModel.php';
        $materialModel = new MaterialModel();
        if ($materialModel->deleteMaterial($id, $_SESSION['user_id'])) {
            $_SESSION['success'] = "Material eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el material o no tienes permisos";
        }
        $curso_id = $_GET['curso_id'] ?? '';
        header("Location: 172.20.10.3/CambaNet/public/?action=profesor/material&curso_id=" . $curso_id);
        exit();
    }
}
?>