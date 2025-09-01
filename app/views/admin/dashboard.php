<?php
$totalUsuarios = $totalUsuarios ?? 0;
$totalVerificados = $totalVerificados ?? 0;
$usuariosPorRol = $usuariosPorRol ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel de Administración</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="<?php echo url('admin/dashboard'); ?>" class="active">Dashboard</a></li>
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
                <h1>Dashboard de Administración</h1>
                <div class="user-welcome">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalUsuarios; ?></div>
                    <div class="stat-label">Total Usuarios</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalVerificados; ?></div>
                    <div class="stat-label">Verificados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $usuariosPorRol['Profesor'] ?? 0; ?></div>
                    <div class="stat-label">Profesores</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $usuariosPorRol['Estudiante'] ?? 0; ?></div>
                    <div class="stat-label">Estudiantes</div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Resumen del Sistema</h3>
                </div>
                <div class="card-body">
                    <p>Bienvenido al panel de administración. Desde aquí puedes gestionar todos los aspectos del sistema educativo.</p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);"><?php echo $totalUsuarios; ?></div>
                            <div style="color: var(--text-light);">Usuarios Totales</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);"><?php echo $totalVerificados; ?></div>
                            <div style="color: var(--text-light);">Verificados</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);"><?php echo $usuariosPorRol['Profesor'] ?? 0; ?></div>
                            <div style="color: var(--text-light);">Profesores</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);"><?php echo $usuariosPorRol['Estudiante'] ?? 0; ?></div>
                            <div style="color: var(--text-light);">Estudiantes</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                        <a href="<?php echo url('admin/usuarios'); ?>" class="btn btn-primary">Gestionar Usuarios</a>
                        <a href="<?php echo url('admin/cursos'); ?>" class="btn btn-primary">Gestionar Cursos</a>
                        <a href="<?php echo url('admin/inscripciones'); ?>" class="btn btn-primary">Ver Inscripciones</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>