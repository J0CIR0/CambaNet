<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso - Plataforma Educativa</title>
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel de Administración</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/dashboard">Dashboard</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/usuarios">Usuarios</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/profesores">Profesores</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/estudiantes">Estudiantes</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/cursos" class="active">Cursos</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <span style="color: #ecf0f1; padding: 12px 15px; display: block;"><?php echo $_SESSION['user_nombre']; ?></span>
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Editar Curso</h1>
                <div class="header-actions">
                    <span class="user-welcome">Bienvenido, <?php echo $_SESSION['user_nombre']; ?></span>
                    <a href="172.20.10.3/CambaNet/public/?action=admin/cursos" class="btn-secondary">Volver a Cursos</a>
                </div>
            </div>
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">×</button>
            </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h3>Editando curso: <?php echo htmlspecialchars($curso['nombre']); ?></h3>
                </div>
                <div class="card-body">
                    <form action="172.20.10.3/CambaNet/public/?action=admin/editar-curso&id=<?php echo $curso['id']; ?>" method="POST">
                        <div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
                            <div class="col-md-6" style="flex: 0 0 50%; max-width: 50%; padding: 0 10px;">
                                <div class="form-group">
                                    <label class="form-label">Nombre del Curso *</label>
                                    <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($curso['nombre']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6" style="flex: 0 0 50%; max-width: 50%; padding: 0 10px;">
                                <div class="form-group">
                                    <label class="form-label">Profesor *</label>
                                    <select class="form-select" name="profesor_id" required>
                                        <option value="">Seleccionar profesor...</option>
                                        <?php foreach ($profesores as $profesor): ?>
                                            <option value="<?php echo $profesor['id']; ?>" <?php echo $profesor['id'] == $curso['profesor_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($profesor['nombre']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3"><?php echo htmlspecialchars($curso['descripcion']); ?></textarea>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="activo" id="activo" <?php echo $curso['activo'] ? 'checked' : ''; ?>>
                            <label class="form-label" for="activo" style="margin-bottom: 0;">Curso activo</label>
                        </div>
                        <div class="form-actions">
                            <a href="172.20.10.3/CambaNet/public/?action=admin/cursos" class="btn-secondary">Cancelar</a>
                            <button type="submit" class="btn-primary">Actualizar Curso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>