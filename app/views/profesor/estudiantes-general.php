<?php
$estudiantes = $data['estudiantes'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes - <?php echo SITE_NAME; ?></title>
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
                <li><a href="<?php echo url('profesor/estudiantes-general'); ?>" class="active">Estudiantes</a></li>
                <li><a href="<?php echo url('profesor/material'); ?>">Material</a></li>
                <li><a href="<?php echo url('profesor/calificaciones'); ?>">Calificaciones</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
                <li><a href="<?php echo url('logout'); ?>">Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Gestión de Estudiantes</h1>
                <div class="user-welcome"><?php echo htmlspecialchars($user_nombre); ?></div>
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

            <div class="card">
                <div class="card-header">
                    <h3>Lista de Estudiantes</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($estudiantes)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No hay estudiantes registrados</p>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Verificado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $estudiante): ?>
                                    <tr>
                                        <td><?php echo $estudiante['id']; ?></td>
                                        <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $estudiante['verificado'] ? 'badge-verified' : 'badge-not-verified'; ?>">
                                                <?php echo $estudiante['verificado'] ? 'Sí' : 'No'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm">Ver Detalles</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($estudiantes)): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($estudiantes); ?></div>
                    <div class="stat-label">Total Estudiantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count(array_filter($estudiantes, fn($e) => $e['verificado'])); ?></div>
                    <div class="stat-label">Verificados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count(array_filter($estudiantes, fn($e) => !$e['verificado'])); ?></div>
                    <div class="stat-label">No Verificados</div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>