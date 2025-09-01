<?php
$curso = $curso ?? [];
$profesores = $profesores ?? [];
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
                <h1>Editar Curso</h1>
                <a href="<?php echo url('admin/cursos'); ?>" class="btn btn-secondary">Volver a Cursos</a>
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
                    <form action="<?php echo url('admin/editar-curso'); ?>&id=<?php echo $curso['id']; ?>" method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Nombre del Curso</label>
                                <input type="text" class="form-control" name="nombre" 
                                       value="<?php echo htmlspecialchars($curso['nombre']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Profesor</label>
                                <select class="form-select" name="profesor_id" required>
                                    <option value="">Seleccionar profesor...</option>
                                    <?php foreach ($profesores as $profesor): ?>
                                        <option value="<?php echo $profesor['id']; ?>" 
                                            <?php echo $profesor['id'] == $curso['profesor_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($profesor['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3"><?php echo htmlspecialchars($curso['descripcion']); ?></textarea>
                        </div>
                        <div class="form-check" style="margins-bottom: 20px;">
                            <input type="checkbox" name="activo" id="activo" 
                                   <?php echo $curso['activo'] ? 'checked' : ''; ?>>
                            <label for="activo">Curso activo</label>
                        </div>
                        <div style="display: flex; gap: 15px;">
                            <a href="<?php echo url('admin/cursos'); ?>" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Curso</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>