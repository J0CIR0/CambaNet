<?php
require_once __DIR__ . '/../app/config/config.php';
session_start();
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../app/models/SessionModel.php';
    $sessionModel = new SessionModel();
    $sessionModel->actualizarActividad(session_id());
}
$action = $_GET['action'] ?? 'login';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/CursoController.php';
require_once __DIR__ . '/../app/controllers/EstudianteController.php';
require_once __DIR__ . '/../app/controllers/ProfesorController.php';
require_once __DIR__ . '/../app/controllers/ProfileController.php';
$authController = new AuthController();
$adminController = new AdminController();
$cursoController = new CursoController();
$estudianteController = new EstudianteController();
$profesorController = new ProfesorController();
$profileController = new ProfileController();
switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login($_POST);
        } else {
            $authController->showLoginForm();
        }
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register($_POST);
        } else {
            $authController->showRegisterForm();
        }
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'forgot-password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->processForgotPassword($_POST);
        } else {
            $authController->showForgotPasswordForm();
        }
        break;
    case 'reset-password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->processResetPassword($_POST);
        } else {
            $token = $_GET['token'] ?? '';
            $authController->showResetPasswordForm($token);
        }
        break;
    case 'verify':
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $authController->verifyEmail($token);
        } else {
            echo "<script>
                    alert('Token de verificación no proporcionado.');
                    window.location.href = '" . url('login') . "';
                </script>";
        }
        break;
    case 'verify-2fa':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->verify2FA($_POST);
        } else {
            redirect('login');
        }
        break;
    case 'resend-2fa':
        $authController->resend2FACode();
        break;
    case 'login-ajax':
        $authController->validateLoginAjax($_POST);
        break;
    case 'admin/dashboard':
        $adminController->dashboard();
        break;
    case 'admin/usuarios':
        $adminController->usuarios();
        break;
    case 'admin/crear-usuario':
        $adminController->crearUsuario();
        break;
    case 'admin/editar-usuario':
        $id = $_GET['id'] ?? 0;
        $adminController->editarUsuario($id);
        break;
    case 'admin/eliminar-usuario':
        $id = $_GET['id'] ?? 0;
        $adminController->eliminarUsuario($id);
        break;
    case 'admin/profesores':
        $adminController->verProfesores();
        break;
    case 'admin/estudiantes':
        $adminController->verEstudiantes();
        break;
    case 'admin/cursos':
        $cursoController->index();
        break;
    case 'admin/crear-curso':
        $cursoController->crearCurso();
        break;
    case 'admin/editar-curso':
        $id = $_GET['id'] ?? 0;
        $cursoController->editarCurso($id);
        break;
    case 'admin/eliminar-curso':
        $id = $_GET['id'] ?? 0;
        $cursoController->eliminarCurso($id);
        break;
    case 'admin/inscripciones':
        $adminController->inscripciones();
        break;
    case 'admin/eliminar-inscripcion':
        $id = $_GET['id'] ?? 0;
        $adminController->eliminarInscripcion($id);
        break;
    case 'profesor/dashboard':
        $profesorController->dashboard();
        break;
    case 'profesor/mis-cursos':
        $profesorController->misCursos();
        break;
    case 'profesor/editar-curso':
        $id = $_GET['id'] ?? 0;
        $profesorController->editarCurso($id);
        break;
    case 'profesor/estudiantes':
        $id = $_GET['id'] ?? 0;
        $profesorController->verEstudiantes($id);
        break;
    case 'profesor/estudiantes-general':
        $profesorController->verEstudiantesGeneral();
        break;
    case 'profesor/material':
        $profesorController->material();
        break;
    case 'profesor/subir-material':
        $profesorController->subirMaterial();
        break;
    case 'profesor/eliminar-material':
        $id = $_GET['id'] ?? 0;
        $profesorController->eliminarMaterial($id);
        break;
    case 'profesor/calificaciones':
        $profesorController->gestionarCalificaciones($_GET['curso_id'] ?? null);
        break;
    case 'profesor/crear-actividad':
        $profesorController->crearActividad();
        break;
    case 'profesor/calificar-actividad':
        $id = $_GET['id'] ?? 0;
        $profesorController->calificarActividad($id);
        break;
    case 'profesor/eliminar-actividad':
        $id = $_GET['id'] ?? 0;
        $profesorController->eliminarActividad($id);
        break;

    case 'profesor/editar-actividad':
        $id = $_GET['id'] ?? 0;
        $profesorController->editarActividad($id);
        break;

    case 'profile/cerrar-sesion':
        $profileController->cerrarSesion();
        break;
    case 'estudiante/dashboard':
        $estudianteController->dashboard();
        break;
    case 'estudiante/mis-cursos':
        $estudianteController->misCursos();
        break;
    case 'estudiante/ver-curso':
        $id = $_GET['id'] ?? 0;
        $estudianteController->verCurso($id);
        break;
    case 'estudiante/inscribir':
        $id = $_GET['id'] ?? 0;
        $estudianteController->inscribirCurso($id);
        break;
    case 'estudiante/cancelar':
        $id = $_GET['id'] ?? 0;
        $estudianteController->cancelarInscripcion($id);
        break;
    case 'profile':
        $profileController->showProfile();
        break;
    case 'profile/update':
        $profileController->updateProfile();
        break;
    case 'profile/toggle-2fa':
        $profileController->toggle2FA();
        break;
    case 'profile/comprar-suscripcion':
        $profileController->comprarSuscripcion();
        break;
    default:
        redirect('login');
        break;
}
?>