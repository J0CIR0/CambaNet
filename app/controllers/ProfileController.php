<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
class ProfileController extends BaseController {
    public function showProfile() {
        $this->checkAuth();
        $usuarioModel = new UsuarioModel();
        $user = $usuarioModel->getUserById($_SESSION['user_id']);
        
        $sessionModel = new SessionModel();
        $sesiones = $sessionModel->getSesionesUsuario($_SESSION['user_id']);
        $sesiones_parseadas = [];
        foreach ($sesiones as $sesion) {
            $sesion['user_agent_parsed'] = $this->parseUserAgent($sesion['user_agent']);
            $sesiones_parseadas[] = $sesion;
        }
        
        $max_sesiones = $sessionModel->getMaxSesionesPermitidas($_SESSION['user_id']);
        
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        
        $sql = "SELECT * FROM tipos_suscripcion ORDER BY precio ASC";
        $result = $conexion->query($sql);
        $suscripciones = $result->fetch_all(MYSQLI_ASSOC);
        
        $sql_suscripcion_actual = "SELECT ts.* 
                                FROM usuarios u
                                INNER JOIN tipos_suscripcion ts ON u.suscripcion_id = ts.id
                                WHERE u.id = ?";
        $stmt = $conexion->prepare($sql_suscripcion_actual);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $suscripcion_actual = $stmt->get_result()->fetch_assoc();
        
        $data = [
            'user' => $user,
            'sesiones' => $sesiones_parseadas,
            'max_sesiones' => $max_sesiones,
            'suscripciones' => $suscripciones,
            'suscripcion_actual' => $suscripcion_actual,
            'user_nombre' => $_SESSION['user_nombre'],
            'user_email' => $_SESSION['user_email']
        ];
        
        $this->renderView('profile/index.php', $data);
    }
    public function comprarSuscripcion() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('profile');
        }
        
        $suscripcion_id = $_POST['suscripcion_id'] ?? null;
        if (!$suscripcion_id) {
            $_SESSION['error'] = "ID de suscripción no válido";
            redirect('profile');
        }
        
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        
        $sql_verificar = "SELECT * FROM tipos_suscripcion WHERE id = ?";
        $stmt = $conexion->prepare($sql_verificar);
        $stmt->bind_param("i", $suscripcion_id);
        $stmt->execute();
        $suscripcion = $stmt->get_result()->fetch_assoc();
        
        if (!$suscripcion) {
            $_SESSION['error'] = "Suscripción no encontrada";
            redirect('profile');
        }
        
        $sql_actualizar = "UPDATE usuarios SET suscripcion_id = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql_actualizar);
        $stmt->bind_param("ii", $suscripcion_id, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $sessionModel = new SessionModel();
            $sessionModel->cerrarOtrasSesiones($_SESSION['user_id'], session_id());
            
            $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+30 days'));
            $sql_registrar = "INSERT INTO suscripciones_compradas 
                            (usuario_id, tipo_suscripcion_id, fecha_expiracion, activa) 
                            VALUES (?, ?, ?, 1)";
            $stmt2 = $conexion->prepare($sql_registrar);
            $stmt2->bind_param("iis", $_SESSION['user_id'], $suscripcion_id, $fecha_expiracion);
            $stmt2->execute();
            
            $_SESSION['success'] = "¡Suscripción actualizada exitosamente! Ahora tienes el plan " . 
                                htmlspecialchars($suscripcion['nombre']) . " con " . 
                                $suscripcion['max_sesiones'] . " sesiones concurrentes. " .
                                "Todas las demás sesiones han sido cerradas automáticamente.";
        } else {
            $_SESSION['error'] = "Error al actualizar la suscripción";
        }
        
        redirect('profile');
    }
    public function updateProfile() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('profile');
        }
        $usuarioModel = new UsuarioModel();
        $errors = [];
        if (empty($_POST['nombre'])) {
            $errors[] = "El nombre es requerido";
        }
        if (empty($_POST['email'])) {
            $errors[] = "El email es requerido";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El formato del email no es válido";
        }
        if (!empty($errors)) {
            $user = $usuarioModel->getUserById($_SESSION['user_id']);
            $data = [
                'user' => $user,
                'errors' => $errors,
                'user_nombre' => $_SESSION['user_nombre'],
                'user_email' => $_SESSION['user_email']
            ];
            $this->renderView('profile/index.php', $data);
            return;
        }
        $success = $usuarioModel->updateUser($_SESSION['user_id'], [
            'nombre' => $_POST['nombre'],
            'email' => $_POST['email']
        ]);
        if ($success) {
            $_SESSION['user_nombre'] = $_POST['nombre'];
            $_SESSION['user_email'] = $_POST['email'];
            $_SESSION['success'] = "Perfil actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar el perfil";
        }
        redirect('profile');
    }
    public function toggle2FA() {
        $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('profile');
        }
        $usuarioModel = new UsuarioModel();
        $user = $usuarioModel->getUserById($_SESSION['user_id']);
        $nuevoEstado = $user['habilitar_2fa'] ? 0 : 1;
        if ($nuevoEstado) {
            $success = $usuarioModel->habilitar2FA($_SESSION['user_id']);
            $mensaje = $success ? "Verificación en dos pasos habilitada" : "Error al habilitar 2FA";
        } else {
            $success = $usuarioModel->deshabilitar2FA($_SESSION['user_id']);
            $mensaje = $success ? "Verificación en dos pasos deshabilitada" : "Error al deshabilitar 2FA";
        }
        if ($success) {
            $_SESSION['success'] = $mensaje;
        } else {
            $_SESSION['error'] = $mensaje;
        }
        redirect('profile');
    }
    public function cerrarSesion() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $session_id = $input['session_id'] ?? '';
            
            if ($session_id) {
                $sessionModel = new SessionModel();
                if ($sessionModel->cerrarSesion($session_id)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Sesión cerrada exitosamente']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Error al cerrar la sesión']);
                }
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'ID de sesión no válido']);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Método no permitido']);
        }
    }
    private function parseUserAgent($user_agent) {
        if (strpos($user_agent, 'iPhone') !== false) {
            return 'iPhone';
        } elseif (strpos($user_agent, 'iPad') !== false) {
            return 'iPad';
        } elseif (strpos($user_agent, 'Android') !== false) {
            return 'Android';
        } elseif (strpos($user_agent, 'Windows') !== false) {
            return 'Windows';
        } elseif (strpos($user_agent, 'Mac') !== false) {
            return 'Mac';
        } elseif (strpos($user_agent, 'Linux') !== false) {
            return 'Linux';
        } else {
            return substr($user_agent, 0, 30) . '...';
        }
    }
}
?>