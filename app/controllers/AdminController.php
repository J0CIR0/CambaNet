<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
class AdminController extends BaseController {
    public function dashboard() {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        $totalUsuarios = $usuarioModel->getTotalUsers();
        $totalVerificados = $usuarioModel->getVerifiedUsersCount();
        $usuariosPorRol = $usuarioModel->getUsersCountByRole();
        
        $this->renderView('admin/dashboard.php', compact('totalUsuarios', 'totalVerificados', 'usuariosPorRol'));
    }
    public function usuarios() {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        $usuarios = $usuarioModel->getAllUsers();
        
        $this->renderView('admin/usuarios.php', compact('usuarios'));
    }
    public function crearUsuario() {
        $this->checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioModel = new UsuarioModel();
            if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['rol_id'])) {
                $_SESSION['error'] = "Todos los campos obligatorios deben ser completados";
                redirect('admin/usuarios');
            }
            if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
                $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres";
                redirect('admin/usuarios');
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
            redirect('admin/usuarios');
        }
    }
    public function editarUsuario($id) {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $usuario = $usuarioModel->getUserById($id);
            if (!$usuario) {
                $_SESSION['error'] = "Usuario no encontrado";
                redirect('admin/usuarios');
            }
            $this->renderView('admin/editar-usuario.php', compact('usuario'));
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['rol_id'])) {
                $_SESSION['error'] = "Todos los campos obligatorios deben ser completados";
                redirect('admin/editar-usuario&id=' . $id);
            }
            if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
                $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres";
                redirect('admin/editar-usuario&id=' . $id);
            }
            $success = $usuarioModel->updateUser($id, $_POST);
            if ($success) {
                $_SESSION['success'] = "Usuario actualizado exitosamente";
                redirect('admin/usuarios');
            } else {
                $_SESSION['error'] = "Error al actualizar el usuario";
                redirect('admin/editar-usuario&id=' . $id);
            }
        }
    }
    public function eliminarUsuario($id) {
        $this->checkAdminAuth();
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "No puedes eliminar tu propia cuenta";
            redirect('admin/usuarios');
        }
        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->getUserById($id);
        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado";
            redirect('admin/usuarios');
        }
        if ($usuario['rol_id'] == 2) {
            require_once __DIR__ . '/../config/database.php';
            global $conexion;
            $sql = "SELECT COUNT(*) as total FROM cursos WHERE profesor_id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['total'] > 0) {
                $_SESSION['error'] = "No se puede eliminar: El profesor tiene " . $row['total'] . " curso(s) asignado(s)";
                redirect('admin/usuarios');
            }
        }
        $success = $usuarioModel->deleteUser($id);
        if ($success) {
            $_SESSION['success'] = "Usuario eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario. Verifique que no tenga cursos asignados.";
        }
        redirect('admin/usuarios');
    }
    public function verProfesores() {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        $profesores = $usuarioModel->getUsersByRole(2);
        $this->renderView('admin/profesores.php', compact('profesores'));
    }
    public function verEstudiantes() {
        $this->checkAdminAuth();
        $usuarioModel = new UsuarioModel();
        $estudiantes = $usuarioModel->getUsersByRole(3);
        $this->renderView('admin/estudiantes.php', compact('estudiantes'));
    }
    public function inscripciones() {
        $this->checkAdminAuth();
        require_once __DIR__ . '/../config/database.php';
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
        $this->renderView('admin/inscripciones.php', compact('inscripciones'));
    }
    public function eliminarInscripcion($id) {
        $this->checkAdminAuth();
        require_once __DIR__ . '/../config/database.php';
        global $conexion;
        $sql = "DELETE FROM inscripciones WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Se eliminó la inscripción";
        } else {
            $_SESSION['error'] = "No se pudo eliminar la inscripción";
        }
        redirect('admin/inscripciones');
    }
}
?>