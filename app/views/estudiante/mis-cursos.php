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
                <h2>Panel Estudiante</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="<?php echo url('estudiante/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo url('estudiante/mis-cursos'); ?>" class="active">Mis Cursos</a></li>
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
                    <h3>Todos Mis Cursos</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($cursos)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No estás inscrito en ningún curso</p>
                            <a href="<?php echo url('estudiante/dashboard'); ?>" class="btn btn-primary">
                                Explorar Cursos
                            </a>
                        </div>
                    <?php else: ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
                            <?php foreach ($cursos as $curso): ?>
                            <div style="border: 1px solid var(--border-color); padding: 20px; border-radius: var(--border-radius);">
                                <h4 style="margin-bottom: 10px;"><?php echo htmlspecialchars($curso['nombre']); ?></h4>
                                <p style="color: var(--text-light); margin-bottom: 15px;">
                                    <?php echo htmlspecialchars(substr($curso['descripcion'], 0, 100)); ?>...
                                </p>
                                
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                                    <div>
                                        <p style="margin: 5px 0; font-size: 14px;">
                                            <strong>Profesor:</strong><br>
                                            <?php echo htmlspecialchars($curso['profesor_nombre']); ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p style="margin: 5px 0; font-size: 14px;">
                                            <strong>Material:</strong><br>
                                            <?php echo $curso['total_material']; ?> archivos
                                        </p>
                                    </div>
                                </div>

                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <span class="badge <?php echo $curso['estado'] == 'activo' ? 'badge-active' : 'badge-inactive'; ?>">
                                        <?php echo ucfirst($curso['estado']); ?>
                                    </span>
                                    <span style="font-size: 12px; color: var(--text-light);">
                                        <?php echo date('d/m/Y', strtotime($curso['fecha_inscripcion'])); ?>
                                    </span>
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <span style="font-size: 12px; color: var(--text-light);">Progreso</span>
                                        <span style="font-size: 12px; color: var(--text-light);">0%</span>
                                    </div>
                                    <div style="width: 100%; height: 6px; background: var(--gray-light); border-radius: 3px;">
                                        <div style="width: 0%; height: 100%; background: var(--primary-color); border-radius: 3px;"></div>
                                    </div>
                                </div>

                                <div style="display: flex; gap: 10px;">
                                    <a href="<?php echo url('estudiante/ver-curso'); ?>&id=<?php echo $curso['id']; ?>" 
                                       class="btn btn-primary btn-sm">
                                        Continuar
                                    </a>
                                    <?php if ($curso['estado'] == 'activo'): ?>
                                    <button onclick="cancelarInscripcion(<?php echo $curso['id']; ?>)" 
                                            class="btn btn-danger btn-sm">
                                        Cancelar
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
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
                    <div class="stat-value"><?php echo count(array_filter($cursos, fn($c) => $c['estado'] == 'activo')); ?></div>
                    <div class="stat-label">Cursos Activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo array_sum(array_column($cursos, 'total_material')); ?></div>
                    <div class="stat-label">Total Material</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count(array_filter($cursos, fn($c) => $c['estado'] == 'completado')); ?></div>
                    <div class="stat-label">Completados</div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function cancelarInscripcion(cursoId) {
        if (confirm('¿Estás seguro de que quieres cancelar tu inscripción en este curso?\n\nPodrás volver a inscribirte después.')) {
            window.location.href = '<?php echo url('estudiante/cancelar'); ?>&id=' + cursoId;
        }
    }
    </script>
</body>
</html>