<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../app/controllers/AuthController.php';
$action = $_GET['action'] ?? 'login';
define('BASE_URL', 'http://172.20.10.3/CambaNet/public');
$authController = new AuthController();
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
    case 'profile/comprar-suscripcion':
        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->comprarSuscripcion();
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
    case 'admin/dashboard':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $adminController = new AdminController();
        $adminController->dashboard();
        break;
    case 'admin/usuarios':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $adminController = new AdminController();
        $adminController->usuarios();
        break;
    case 'admin/crear-usuario':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $adminController = new AdminController();
        $adminController->crearUsuario();
        break;
    case 'admin/profesores':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $adminController = new AdminController();
        $adminController->verProfesores();
        break;
    case 'admin/eliminar-usuario':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $adminController = new AdminController();
        $id = $_GET['id'] ?? 0;
        $adminController->eliminarUsuario($id);
        break;
    case 'admin/estudiantes':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $adminController = new AdminController();
        $adminController->verEstudiantes();
        break;
    case 'admin/editar-usuario':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $adminController = new AdminController();
        $id = $_GET['id'] ?? 0;
        $adminController->editarUsuario($id);
        break;
    case 'admin/cursos':
        require_once __DIR__ . '/../app/controllers/CursoController.php';
        $cursoController = new CursoController();
        $cursoController->index();
        break;
    case 'admin/crear-curso':
        require_once __DIR__ . '/../app/controllers/CursoController.php';
        $cursoController = new CursoController();
        $cursoController->crearCurso();
        break;
    case 'admin/editar-curso':
        require_once __DIR__ . '/../app/controllers/CursoController.php';
        $cursoController = new CursoController();
        $id = $_GET['id'] ?? 0;
        $cursoController->editarCurso($id);
        break;
    case 'admin/eliminar-curso':
        require_once __DIR__ . '/../app/controllers/CursoController.php';
        $cursoController = new CursoController();
        $id = $_GET['id'] ?? 0;
        $cursoController->eliminarCurso($id);
        break;
    case 'admin/inscripciones':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $adminController = new AdminController();
        $adminController->inscripciones();
        break;

    case 'admin/eliminar-inscripcion':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $adminController = new AdminController();
        $id = $_GET['id'] ?? 0;
        $adminController->eliminarInscripcion($id);
        break;
    case 'profesor/dashboard':
        require_once __DIR__ . '/../app/controllers/ProfesorController.php';
        $profesorController = new ProfesorController();
        $profesorController->dashboard();
        break;
    case 'profesor/mis-cursos':
        require_once __DIR__ . '/../app/controllers/ProfesorController.php';
        $profesorController = new ProfesorController();
        $profesorController->misCursos();
        break;
    case 'profesor/material':
        require_once __DIR__ . '/../app/controllers/ProfesorController.php';
        $profesorController = new ProfesorController();
        $profesorController->material();
        break;
    case 'profesor/calificaciones':
        require_once __DIR__ . '/../app/controllers/ProfesorController.php';
        $profesorController = new ProfesorController();
        $profesorController->calificaciones();
        break;
    case 'profesor/material':
        require_once __DIR__ . '/../app/controllers/AdminProfesorController.php';
        $profesorController = new AdminProfesorController();
        $profesorController->material();
        break;
    case 'profesor/subir-material':
        require_once __DIR__ . '/../app/controllers/AdminProfesorController.php';
        $profesorController = new AdminProfesorController();
        $profesorController->subirMaterial();
        break;
    case 'profesor/eliminar-material':
        require_once __DIR__ . '/../app/controllers/AdminProfesorController.php';
        $profesorController = new AdminProfesorController();
        $id = $_GET['id'] ?? 0;
        $profesorController->eliminarMaterial($id);
        break;
    case 'profesor/editar-curso':
        require_once __DIR__ . '/../app/controllers/ProfesorController.php';
        $profesorController = new ProfesorController();
        $id = $_GET['id'] ?? 0;
        $profesorController->editarCurso($id);
        break;
    case 'profesor/estudiantes':
        require_once __DIR__ . '/../app/controllers/ProfesorController.php';
        $profesorController = new ProfesorController();
        $id = $_GET['id'] ?? 0;
        $profesorController->verEstudiantes($id);
        break;
    case 'profesor/estudiantes-general':
        require_once __DIR__ . '/../app/controllers/ProfesorController.php';
        $profesorController = new ProfesorController();
        $profesorController->verEstudiantesGeneral();
        break;
    case 'estudiante/dashboard':
        require_once __DIR__ . '/../app/controllers/EstudianteController.php';
        $estudianteController = new EstudianteController();
        $estudianteController->dashboard();
        break;
    case 'estudiante/mis-cursos':
        require_once __DIR__ . '/../app/controllers/EstudianteController.php';
        $estudianteController = new EstudianteController();
        $estudianteController->misCursos();
        break;
    case 'estudiante/ver-curso':
        require_once __DIR__ . '/../app/controllers/EstudianteController.php';
        $estudianteController = new EstudianteController();
        $id = $_GET['id'] ?? 0;
        $estudianteController->verCurso($id);
        break;
    case 'estudiante/inscribir':
        require_once __DIR__ . '/../app/controllers/EstudianteController.php';
        $estudianteController = new EstudianteController();
        $id = $_GET['id'] ?? 0;
        $estudianteController->inscribirCurso($id);
        break;
    case 'estudiante/cancelar':
        require_once __DIR__ . '/../app/controllers/EstudianteController.php';
        $estudianteController = new EstudianteController();
        $id = $_GET['id'] ?? 0;
        $estudianteController->cancelarInscripcion($id);
        break;
    case 'login-ajax':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $authController = new AuthController();
        $authController->validateLoginAjax($_POST);
        break;
    case 'verify':
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $authController->verifyEmail($token);
        } else {
            echo "<script>
                    alert('Token de verificación no proporcionado.');
                    window.location.href = '/CambaNet/public/?action=login';
                </script>";
        }
        break;
    case 'verify-2fa':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->verify2FA($_POST);
        } else {
            header("Location: 172.20.10.3/CambaNet/public/?action=login");
        }
        break;
    case 'profile':
        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->showProfile();
        break;
    case 'profile/update':
        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->updateProfile();
        break;
    case 'profile/toggle-2fa':
        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->toggle2FA();
        break;
    case 'resend-2fa':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $authController = new AuthController();
        $authController->resend2FACode();
        break;
    default:
        header("Location: 172.20.10.3/CambaNet/public/?action=login");
        break; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id']) && isset($_SESSION['session_registered'])) {
    require_once __DIR__ . '/../app/models/SessionModel.php';
    $sessionModel = new SessionModel();
    $sessionModel->actualizarActividad(session_id());
} 
}
?>