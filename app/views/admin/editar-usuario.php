<?php
$this->checkAdminAuth();
$usuarioModel = new UsuarioModel();
$usuario = $usuarioModel->getUserById($_GET['id']);
if (!$usuario) {
    header("Location: 172.20.10.3/CambaNet/public/?action=admin/usuarios");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Plataforma Educativa</title>
</head>
<body>
<div class="admin-container">
    <div class="admin-sidebar">
        <div class="admin-brand">
            <h2>Panel de Administración</h2>
        </div>
        <ul class="admin-menu">
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/dashboard">Dashboard</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/usuarios" class="active">Usuarios</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/profesores">Profesores</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/estudiantes">Estudiantes</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/cursos">Cursos</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
        </ul>
        <div class="logout-section">
            <span style="color: #ecf0f1; padding: 12px 15px; display: block;"><?php echo $_SESSION['user_nombre']; ?></span>
            <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
        </div>
    </div>
    <div class="admin-main">
        <div class="admin-header">
            <h1>Editar Usuario</h1>
            <a href="172.20.10.3/CambaNet/public/?action=admin/usuarios" class="btn-secondary">
                Volver a Usuarios
            </a>
        </div>
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">×</button>
        </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-header">
                Editando usuario: <?php echo htmlspecialchars($usuario['nombre']); ?>
            </div>
            <div class="card-body">
                <form action="172.20.10.3/CambaNet/public/?action=admin/editar-usuario&id=<?php echo $usuario['id']; ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nombre completo *</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Rol *</label>
                                <select class="form-select" name="rol_id" required>
                                    <option value="1" <?php echo $usuario['rol_id'] == 1 ? 'selected' : ''; ?>>Administrador</option>
                                    <option value="2" <?php echo $usuario['rol_id'] == 2 ? 'selected' : ''; ?>>Profesor</option>
                                    <option value="3" <?php echo $usuario['rol_id'] == 3 ? 'selected' : ''; ?>>Estudiante</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nueva contraseña (dejar vacío para mantener la actual)</label>
                                <input type="password" class="form-control" name="password" minlength="6">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="verificado" id="verificado" <?php echo $usuario['verificado'] ? 'checked' : ''; ?>>
                            <label for="verificado">Usuario verificado</label>
                        </div>
                    </div>
                    <div class="form-actions">
                        <a href="172.20.10.3/CambaNet/public/?action=admin/usuarios" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-primary">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>