<?php
$cursosInscritos = $data['cursosInscritos'] ?? [];
$cursosDisponibles = $data['cursosDisponibles'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
$user_email = $data['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Estudiante - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel Estudiante</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="<?php echo url('estudiante/dashboard'); ?>" class="active">Dashboard</a></li>
                <li><a href="<?php echo url('estudiante/mis-cursos'); ?>">Mis Cursos</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
                <li><a href="<?php echo url('logout'); ?>">Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Panel del Estudiante</h1>
                <div class="user-welcome">Bienvenido, <?php echo htmlspecialchars($user_nombre); ?></div>
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
                    <div class="stat-value"><?php echo count($cursosInscritos); ?></div>
                    <div class="stat-label">Cursos Inscritos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count(array_filter($cursosInscritos, fn($c) => $c['estado'] == 'activo')); ?></div>
                    <div class="stat-label">Cursos Activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($cursosDisponibles); ?></div>
                    <div class="stat-label">Cursos Disponibles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count(array_filter($cursosInscritos, fn($c) => $c['estado'] == 'completado')); ?></div>
                    <div class="stat-label">Completados</div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Mis Cursos Inscritos</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($cursosInscritos)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No estás inscrito en ningún curso</p>
                            <p style="color: var(--text-light);">Explora los cursos disponibles e inscríbete para comenzar.</p>
                        </div>
                    <?php else: ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                            <?php foreach ($cursosInscritos as $curso): ?>
                            <div style="border: 1px solid var(--border-color); padding: 20px; border-radius: var(--border-radius);">
                                <h4 style="margin-bottom: 10px;"><?php echo htmlspecialchars($curso['nombre']); ?></h4>
                                <p style="color: var(--text-light); margin-bottom: 15px;">
                                    <?php echo htmlspecialchars(substr($curso['descripcion'], 0, 80)); ?>...
                                </p>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <span class="badge <?php echo $curso['estado'] == 'activo' ? 'badge-active' : 'badge-inactive'; ?>">
                                        <?php echo ucfirst($curso['estado']); ?>
                                    </span>
                                    <span style="font-size: 12px; color: var(--text-light);">
                                        Inscrito: <?php echo date('d/m/Y', strtotime($curso['fecha_inscripcion'])); ?>
                                    </span>
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
            <div class="card">
                <div class="card-header">
                    <h3>Cursos Disponibles</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($cursosDisponibles)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>¡Felicidades!</p>
                            <p style="color: var(--text-light);">Estás inscrito en todos los cursos disponibles.</p>
                        </div>
                    <?php else: ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                            <?php foreach ($cursosDisponibles as $curso): ?>
                            <div style="border: 1px solid var(--border-color); padding: 20px; border-radius: var(--border-radius);">
                                <h4 style="margin-bottom: 10px;"><?php echo htmlspecialchars($curso['nombre']); ?></h4>
                                <p style="color: var(--text-light); margin-bottom: 15px;">
                                    <?php echo htmlspecialchars(substr($curso['descripcion'], 0, 80)); ?>...
                                </p>
                                <div style="margin-bottom: 15px;">
                                    <p style="margin: 5px 0; font-size: 14px;">
                                        <strong>Profesor:</strong> <?php echo htmlspecialchars($curso['profesor_nombre']); ?>
                                    </p>
                                    <p style="margin: 5px 0; font-size: 14px; color: var(--text-light);">
                                        <strong>Creación:</strong> <?php echo date('d/m/Y', strtotime($curso['fecha_creacion'])); ?>
                                    </p>
                                </div>
                                <button onclick="inscribirCurso(<?php echo $curso['id']; ?>)" 
                                        class="btn btn-primary btn-sm">
                                    Inscribirse
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    function inscribirCurso(cursoId) {
        if (confirm('¿Estás seguro de que quieres inscribirte en este curso?')) {
            window.location.href = '<?php echo url('estudiante/inscribir'); ?>&id=' + cursoId;
        }
    }

    function cancelarInscripcion(cursoId) {
        if (confirm('¿Estás seguro de que quieres cancelar tu inscripción en este curso?\n\nPodrás volver a inscribirte después.')) {
            window.location.href = '<?php echo url('estudiante/cancelar'); ?>&id=' + cursoId;
        }
    }
    </script>
</body>
</html>