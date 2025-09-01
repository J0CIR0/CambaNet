<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
class AdminEstudianteController {
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
        $estudiantes = $usuarioModel->getUsersByRole(3);
        require __DIR__ . '/../views/admin/estudiantes.php';
    }
}
?>