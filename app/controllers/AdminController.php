<?php
require_once __DIR__ . '/../models/UsuarioModel.php';
$server_ip = $_SERVER['SERVER_ADDR'];
class AdminController {
    private function checkAdminAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            header("Location: 172.20.10.3/CambaNet/public/?action=login");
            exit();
        }
    }
    public function dashboard() {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        $totalUsuarios = $usuarioModel->getTotalUsers();
        $totalVerificados = $usuarioModel->getVerifiedUsersCount();
        $usuariosPorRol = $usuarioModel->getUsersCountByRole();
        require __DIR__ . '/../views/admin/dashboard.php';
    }
    public function usuarios() {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        $usuarios = $usuarioModel->getAllUsers();
        require __DIR__ . '/../views/admin/usuarios.php';
    }
    public function crearUsuario() {
        $this->checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioModel = new UsuarioModel();
            if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['rol_id'])) {
                $_SESSION['error'] = "Todos los campos obligatorios deben ser completados";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
                exit();
            }
            if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
                $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
                exit();
            }
            if (empty($_POST['password'])) {
                $_POST['password'] = bin2hex(random_bytes(8));
            }
            $success = $usuarioModel->createUserByAdmin($_POST);
            if ($success) {
                $_SESSION['success'] = "Usuario creado exitosamente";
            } else {
                $_SESSION['error'] = "Error al crear el usuario. El email puede estar en uso.";
            }
            header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
            exit();
        }
    }
    public function editarUsuario($id) {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $usuario = $usuarioModel->getUserById($id);
            if (!$usuario) {
                $_SESSION['error'] = "Usuario no encontrado";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
                exit();
            }
            require __DIR__ . '/../views/admin/editar-usuario.php';
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['rol_id'])) {
                $_SESSION['error'] = "Todos los campos obligatorios deben ser completados";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/editar-usuario&id=" . $id);
                exit();
            }
            if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
                $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/editar-usuario&id=" . $id);
                exit();
            }
            $success = $usuarioModel->updateUser($id, $_POST);
            if ($success) {
                $_SESSION['success'] = "Usuario actualizado exitosamente";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
            } else {
                $_SESSION['error'] = "Error al actualizar el usuario";
                header("Location: 172.20.10.3/CambaNet/public/?action=admin/editar-usuario&id=" . $id);
            }
            exit();
        }
    }
    public function eliminarUsuario($id) {
    $this->checkAdminAuth();
    
    if ($id == $_SESSION['user_id']) {
        $_SESSION['error'] = "No puedes eliminar tu propia cuenta";
        header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
        exit();
    }
    $usuarioModel = new UsuarioModel();
    $usuario = $usuarioModel->getUserById($id);
    
    if (!$usuario) {
        $_SESSION['error'] = "Usuario no encontrado";
        header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
        exit();
    }
    if ($usuario['rol_id'] == 2) {
        global $conexion;
        $sql = "SELECT COUNT(*) as total FROM cursos WHERE profesor_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['total'] > 0) {
            $_SESSION['error'] = "No se puede eliminar: El profesor tiene " . $row['total'] . " curso(s) asignado(s)";
            header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
            exit();
        }
    }
    $success = $usuarioModel->deleteUser($id);
    if ($success) {
        $_SESSION['success'] = "Usuario eliminado exitosamente";
    } else {
        $_SESSION['error'] = "Error al eliminar el usuario. Verifique que no tenga cursos asignados.";
    }
    header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
    exit();
    }
    public function verProfesores() {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        $profesores = $usuarioModel->getUsersByRole(2);
        require __DIR__ . '/../views/admin/profesores.php';
    }
    public function verEstudiantes() {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        $estudiantes = $usuarioModel->getUsersByRole(3);
        require __DIR__ . '/../views/admin/estudiantes.php';
    }
    public function inscripciones() {
        $this->checkAdminAuth();
        require_once __DIR__ . '/../../config/database.php';
        global $conexion;
        $sql = "SELECT i.*, u.nombre as estudiante_nombre, u.email as estudiante_email, 
                       c.nombre as curso_nombre, c.profesor_id,
                       p.nombre as profesor_nombre
                FROM inscripciones i
                INNER JOIN usuarios u ON i.estudiante_id = u.id
                INNER JOIN cursos c ON i.curso_id = c.id
                INNER JOIN usuarios p ON c.profesor_id = p.id
                ORDER BY i.fecha_inscripcion DESC";
        $result = $conexion->query($sql);
        $inscripciones = $result->fetch_all(MYSQLI_ASSOC);
        require __DIR__ . '/../views/admin/inscripciones.php';
    }
    public function eliminarInscripcion($id) {
        $this->checkAdminAuth();
        require_once __DIR__ . '/../../config/database.php';
        global $conexion;
        $sql = "DELETE FROM inscripciones WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Se eliminó la insvripción";
        } else {
            $_SESSION['error'] = "No se pudo elimnar la inscripción";
        }
        header("Location: 172.20.10.3/CambaNet/public/?action=admin/inscripciones");
        exit();
    }
}
?>