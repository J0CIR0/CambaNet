<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/CursoModel.php';
class CursoController extends BaseController {
    public function index() {
        $this->checkAdminAuth();
        $cursoModel = new CursoModel();
        $cursos = $cursoModel->getAllCursos();
        $profesores = $cursoModel->getProfesores();
        $this->renderView('admin/cursos.php', compact('cursos', 'profesores'));
    }
    public function crearCurso() {
        $this->checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cursoModel = new CursoModel();
            if (empty($_POST['nombre']) || empty($_POST['profesor_id'])) {
                $_SESSION['error'] = "Nombre y profesor son requeridos";
                redirect('admin/cursos');
            }
            $success = $cursoModel->createCurso($_POST);
            if ($success) {
                $_SESSION['success'] = "Curso creado exitosamente";
            } else {
                $_SESSION['error'] = "Error al crear el curso";
            }
            redirect('admin/cursos');
        }
    }
    public function editarCurso($id) {
        $this->checkAdminAuth();
        $cursoModel = new CursoModel();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $curso = $cursoModel->getCursoById($id);
            if (!$curso) {
                $_SESSION['error'] = "Curso no encontrado";
                redirect('admin/cursos');
            }
            $profesores = $cursoModel->getProfesores();
            $this->renderView('admin/editar-curso.php', compact('curso', 'profesores'));
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['nombre']) || empty($_POST['profesor_id'])) {
                $_SESSION['error'] = "Nombre y profesor son requeridos";
                redirect('admin/editar-curso&id=' . $id);
            }
            $success = $cursoModel->updateCurso($id, $_POST);
            if ($success) {
                $_SESSION['success'] = "Curso actualizado exitosamente";
                redirect('admin/cursos');
            } else {
                $_SESSION['error'] = "Error al actualizar el curso";
                redirect('admin/editar-curso&id=' . $id);
            }
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
        redirect('admin/cursos');
    }
}
?>