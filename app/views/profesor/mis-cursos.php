<?php
$cursos = $data['cursos'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos - <?php echo SITE_NAME; ?></title>
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
                <li><a href="<?php echo url('profesor/mis-cursos'); ?>" class="active">Mis Cursos</a></li>
                <li><a href="<?php echo url('profesor/estudiantes-general'); ?>">Estudiantes</a></li>
                <li><a href="<?php echo url('profesor/material'); ?>">Material</a></li>
                <li><a href="<?php echo url('profesor/calificaciones'); ?>">Calificaciones</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
                <li><a href="<?php echo url('logout'); ?>">Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Mis Cursos</h1>
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
                    <h3>Lista de Cursos Asignados</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($cursos)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No tienes cursos asignados</p>
                            <p style="color: var(--text-light);">Contacta al administrador para que te asignen cursos.</p>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Curso</th>
                                        <th>Descripción</th>
                                        <th>Estudiantes</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cursos as $curso): ?>
                                    <tr>
                                        <td><?php echo $curso['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($curso['nombre']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars(substr($curso['descripcion'] ?? '', 0, 50)); ?>...</td>
                                        <td>
                                            <span class="badge"><?php echo $curso['total_estudiantes']; ?> estudiantes</span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $curso['activo'] ? 'badge-active' : 'badge-inactive'; ?>">
                                                <?php echo $curso['activo'] ? 'Activo' : 'Inactivo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 10px;">
                                                <a href="<?php echo url('profesor/editar-curso'); ?>&id=<?php echo $curso['id']; ?>" class="btn btn-sm">
                                                    Editar
                                                </a>
                                                <a href="<?php echo url('profesor/estudiantes'); ?>&id=<?php echo $curso['id']; ?>" class="btn btn-sm">
                                                    Estudiantes
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($cursos)): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($cursos); ?></div>
                    <div class="stat-label">Total Cursos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo array_sum(array_column($cursos, 'total_estudiantes')); ?></div>
                    <div class="stat-label">Total Estudiantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count(array_filter($cursos, fn($c) => $c['activo'])); ?></div>
                    <div class="stat-label">Cursos Activos</div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>