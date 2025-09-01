<?php
$curso = $data['curso'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel Profesor</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="<?php echo url('profesor/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo url('profesor/mis-cursos'); ?>">Mis Cursos</a></li>
                <li><a href="<?php echo url('profesor/estudiantes-general'); ?>">Estudiantes</a></li>
                <li><a href="<?php echo url('profesor/material'); ?>">Material</a></li>
                <li><a href="<?php echo url('profesor/calificaciones'); ?>">Calificaciones</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
                <li><a href="<?php echo url('logout'); ?>">Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Editar Curso</h1>
                <a href="<?php echo url('profesor/mis-cursos'); ?>" class="btn btn-secondary">Volver a Cursos</a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3>Editando: <?php echo htmlspecialchars($curso['nombre']); ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('profesor/editar-curso'); ?>&id=<?php echo $curso['id']; ?>" method="POST">
                        <div class="form-group">
                            <label class="form-label">Nombre del Curso</label>
                            <input type="text" class="form-control" name="nombre" 
                                   value="<?php echo htmlspecialchars($curso['nombre']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="4" required><?php echo htmlspecialchars($curso['descripcion']); ?></textarea>
                        </div>
                        
                        <div class="form-check" style="margin-bottom: 20px;">
                            <input type="checkbox" name="activo" id="activo" 
                                   <?php echo $curso['activo'] ? 'checked' : ''; ?>>
                            <label for="activo">Curso activo</label>
                        </div>

                        <div style="background: var(--gray-light); padding: 15px; border-radius: var(--border-radius); margin-bottom: 20px;">
                            <p style="margin: 0; color: var(--text-light); font-size: 14px;">
                                <strong>Nota:</strong> Solo puedes editar el nombre, descripción y estado del curso. 
                                Para cambios mayores contacta al administrador.
                            </p>
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <a href="<?php echo url('profesor/mis-cursos'); ?>" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>