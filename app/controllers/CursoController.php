<?php
require_once __DIR__ . '/../models/CursoModel.php';
class CursoController {
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
        $cursoModel = new CursoModel();
        $cursos = $cursoModel->getAllCursos();
        $profesores = $cursoModel->getProfesores();
        require __DIR__ . '/../views/admin/cursos.php';
    }
    public function crearCurso() {
        $this->checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cursoModel = new CursoModel();
            if (empty($_POST['nombre']) || empty($_POST['profesor_id'])) {
                $_SESSION['error'] = "Nombre y profesor son requeridos";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/cursos");
                exit();
            }
            $success = $cursoModel->createCurso($_POST);
            if ($success) {
                $_SESSION['success'] = "Curso creado exitosamente";
            } else {
                $_SESSION['error'] = "Error al crear el curso";
            }
            header("Location: 172.20.10.3/CambaNet/public/?action=admin/cursos");
            exit();
        }
    }
    public function editarCurso($id) {
        $this->checkAdminAuth();
        $cursoModel = new CursoModel();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $curso = $cursoModel->getCursoById($id);
            if (!$curso) {
                $_SESSION['error'] = "Curso no encontrado";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/cursos");
                exit();
            }
            $profesores = $cursoModel->getProfesores();
            require __DIR__ . '/../views/admin/editar-curso.php';
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['nombre']) || empty($_POST['profesor_id'])) {
                $_SESSION['error'] = "Nombre y profesor son requeridos";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/editar-curso&id=" . $id);
                exit();
            }
            $success = $cursoModel->updateCurso($id, $_POST);
            if ($success) {
                $_SESSION['success'] = "Curso actualizado exitosamente";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/cursos");
            } else {
                $_SESSION['error'] = "Error al actualizar el curso";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/editar-curso&id=" . $id);
            }
            exit();
        }
    }
    public function eliminarCurso($id) {
        $this->checkAdminAuth();
        $cursoModel = new CursoModel();
        $success = $cursoModel->deleteCurso($id);
        if ($success) {
            $_SESSION['success'] = "Curso eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el curso. Puede que tenga estudiantes inscritos.";
        }
        header("Location: 172.20.10.3/CambaNet/public/?action=admin/cursos");
        exit();
    }
}
?>