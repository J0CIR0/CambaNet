<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso - Panel de Profesor</title>
</head>
<body>
    <?php
    $this->checkProfesorAuth();
    $curso = $data['curso'] ?? [];
    $user_nombre = $data['user_nombre'] ?? $_SESSION['user_nombre'] ?? 'Profesor';
    $user_email = $data['user_email'] ?? $_SESSION['user_email'] ?? '';
    if (empty($curso)) {
        header("Location: 172.20.10.3/CambaNet/public/?action=profesor/mis-cursos");
        exit();
    }
    ?>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel del Profesor</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/dashboard">Dashboard</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/mis-cursos">Mis Cursos</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/estudiantes-general">Estudiantes</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/material">Material</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/calificaciones">Calificaciones</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Editar Curso</h1>
                <div class="user-welcome">Bienvenido, <?php echo htmlspecialchars($user_nombre); ?></div>
            </div>
            <a href="172.20.10.3/CambaNet/public/?action=profesor/mis-cursos" class="btn btn-secondary">
                Volver
            </a>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close">&times;</button>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5>Editando: <?php echo htmlspecialchars($curso['nombre']); ?></h5>
                </div>
                <div class="card-body">
                    <form action="172.20.10.3/CambaNet/public/?action=profesor/editar-curso&id=<?php echo $curso['id']; ?>" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nombre del Curso *</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($curso['nombre']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label>Estado del Curso</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="activo" id="activo" <?php echo $curso['activo'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="activo">Curso activo</label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label>Descripción *</label>
                            <textarea class="form-control" name="descripcion" rows="4" required><?php echo htmlspecialchars($curso['descripcion']); ?></textarea>
                        </div>
                        <div class="alert-info">
                            Solo puedes editar el nombre, descripción y estado del curso. Para cambios mayores contacta al administrador.
                        </div>
                        <div class="button-group">
                            <a href="172.20.10.3/CambaNet/public/?action=profesor/mis-cursos" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>