<?php
require_once __DIR__ . '/../config/config.php';
class BaseController {
    protected function checkAuth($requiredRole = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }
        if ($requiredRole && $_SESSION['user_role'] != $requiredRole) {
            $_SESSION['error'] = "No tienes permisos para acceder a esta sección";
            redirect('login');
        }
        return true;
    }
    protected function checkAdminAuth() {
        return $this->checkAuth(1);
    }
    protected function checkProfesorAuth() {
        return $this->checkAuth(2);
    }
    protected function checkEstudianteAuth() {
        return $this->checkAuth(3);
    }
    protected function renderView($viewPath, $data = []) {
        extract($data);
        require __DIR__ . '/../views/' . $viewPath;
    }
    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    protected function validateCsrf() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                die('Token CSRF inválido');
            }
        }
    }
}
?>