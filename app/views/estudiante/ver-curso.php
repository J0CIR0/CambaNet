<?php
$curso = $data['curso'] ?? [];
$material = $data['material'] ?? [];
$profesor = $data['profesor'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($curso['nombre']); ?> - <?php echo SITE_NAME; ?></title>
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
                <li><a href="<?php echo url('estudiante/mis-cursos'); ?>">Mis Cursos</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
                <li><a href="<?php echo url('logout'); ?>">Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1><?php echo htmlspecialchars($curso['nombre']); ?></h1>
                <a href="<?php echo url('estudiante/mis-cursos'); ?>" class="btn btn-secondary">Volver a Mis Cursos</a>
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
                    <h3>Información del Curso</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                        <div>
                            <h4 style="margin-bottom: 15px;">Descripción</h4>
                            <p style="color: var(--text-color); line-height: 1.6;">
                                <?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?>
                            </p>
                        </div>
                        <div>
                            <h4 style="margin-bottom: 15px;">Detalles</h4>
                            <div style="display: grid; gap: 15px;">
                                <div>
                                    <strong>Profesor:</strong>
                                    <p style="margin: 5px 0; color: var(--text-color);">
                                        <?php echo htmlspecialchars($profesor['nombre']); ?>
                                    </p>
                                    <p style="margin: 0; color: var(--text-light); font-size: 14px;">
                                        Email: <?php echo htmlspecialchars($profesor['email']); ?>
                                    </p>
                                </div>
                                <div>
                                    <strong>Estado de Inscripción:</strong>
                                    <p style="margin: 5px 0;">
                                        <span class="badge <?php echo $curso['estado'] == 'activo' ? 'badge-active' : 'badge-inactive'; ?>">
                                            <?php echo ucfirst($curso['estado']); ?>
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <strong>Fecha de Inscripción:</strong>
                                    <p style="margin: 5px 0; color: var(--text-color);">
                                        <?php echo date('d/m/Y', strtotime($curso['fecha_inscripcion'])); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Material de Estudio</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($material)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No hay material disponible para este curso</p>
                            <p style="color: var(--text-light);">El profesor aún no ha subido material.</p>
                        </div>
                    <?php else: ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                            <?php foreach ($material as $item): ?>
                            <div style="border: 1px solid var(--border-color); padding: 20px; border-radius: var(--border-radius);">
                                <h5 style="margin-bottom: 10px; color: var(--text-color);">
                                    <?php echo htmlspecialchars($item['titulo']); ?>
                                </h5>
                                <p style="color: var(--text-light); margin-bottom: 15px; font-size: 14px;">
                                    <?php echo htmlspecialchars(substr($item['descripcion'] ?? 'Sin descripción', 0, 80)); ?>...
                                </p>
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 12px; color: var(--text-light);">
                                        <?php echo date('d/m/Y', strtotime($item['fecha_creacion'])); ?>
                                    </span>
                                    <a href="/CambaNet/uploads/material/<?php echo basename($item['archivo_ruta']); ?>" 
                                       class="btn btn-primary btn-sm" download>
                                        Descargar
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="card">
                    <div class="card-header">
                        <h3>Estadísticas del Curso</h3>
                    </div>
                    <div class="card-body">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; text-align: center;">
                            <div>
                                <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">
                                    <?php echo count($material); ?>
                                </div>
                                <div style="color: var(--text-light);">Materiales</div>
                            </div>
                            <div>
                                <div style="font-size: 24px; font-weight: bold; color: var(--primary-color);">
                                    0
                                </div>
                                <div style="color: var(--text-light);">Progreso</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Tu Progreso</h3>
                    </div>
                    <div class="card-body">
                        <div style="text-align: center;">
                            <div style="font-size: 32px; font-weight: bold; color: var(--primary-color); margin-bottom: 10px;">
                                0%
                            </div>
                            <div style="color: var(--text-light); margin-bottom: 15px;">
                                Contenido completado
                            </div>
                            <div style="background: var(--gray-light); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="width: 0%; height: 100%; background: var(--primary-color);"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>