<?php
$usuario = $usuario ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel de Administración</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="<?php echo url('admin/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo url('admin/usuarios'); ?>">Usuarios</a></li>
                <li><a href="<?php echo url('admin/profesores'); ?>">Profesores</a></li>
                <li><a href="<?php echo url('admin/estudiantes'); ?>">Estudiantes</a></li>
                <li><a href="<?php echo url('admin/cursos'); ?>">Cursos</a></li>
                <li><a href="<?php echo url('admin/inscripciones'); ?>">Inscripciones</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="<?php echo url('logout'); ?>" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Editar Usuario</h1>
                <a href="<?php echo url('admin/usuarios'); ?>" class="btn btn-secondary">Volver a Usuarios</a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3>Editando: <?php echo htmlspecialchars($usuario['nombre']); ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('admin/editar-usuario'); ?>&id=<?php echo $usuario['id']; ?>" method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" name="nombre" 
                                       value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Rol</label>
                                <select class="form-select" name="rol_id" required>
                                    <option value="1" <?php echo $usuario['rol_id'] == 1 ? 'selected' : ''; ?>>Administrador</option>
                                    <option value="2" <?php echo $usuario['rol_id'] == 2 ? 'selected' : ''; ?>>Profesor</option>
                                    <option value="3" <?php echo $usuario['rol_id'] == 3 ? 'selected' : ''; ?>>Estudiante</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nueva contraseña (opcional)</label>
                                <input type="password" class="form-control" name="password" minlength="6">
                            </div>
                        </div>

                        <div class="form-check" style="margin-bottom: 20px;">
                            <input type="checkbox" name="verificado" id="verificado" 
                                   <?php echo $usuario['verificado'] ? 'checked' : ''; ?>>
                            <label for="verificado">Usuario verificado</label>
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <a href="<?php echo url('admin/usuarios'); ?>" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>