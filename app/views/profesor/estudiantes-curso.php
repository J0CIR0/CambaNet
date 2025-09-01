<?php
$curso = $data['curso'] ?? [];
$estudiantes = $data['estudiantes'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes del Curso - <?php echo SITE_NAME; ?></title>
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
                <h1>Estudiantes del Curso</h1>
                <a href="<?php echo url('profesor/mis-cursos'); ?>" class="btn btn-secondary">Volver a Cursos</a>
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
                    <h3><?php echo htmlspecialchars($curso['nombre']); ?></h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Descripción</label>
                                <p style="margin: 0; padding: 10px; background: var(--gray-light); border-radius: var(--border-radius);">
                                    <?php echo htmlspecialchars($curso['descripcion']); ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <p style="margin: 0;">
                                    <span class="badge <?php echo $curso['activo'] ? 'badge-active' : 'badge-inactive'; ?>">
                                        <?php echo $curso['activo'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label class="form-label">Fecha de Creación</label>
                                <p style="margin: 0; padding: 10px; background: var(--gray-light); border-radius: var(--border-radius);">
                                    <?php echo date('d/m/Y', strtotime($curso['fecha_creacion'])); ?>
                                </p>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Total de Estudiantes</label>
                                <p style="margin: 0; padding: 10px; background: var(--gray-light); border-radius: var(--border-radius);">
                                    <?php echo count($estudiantes); ?> estudiantes
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Estudiantes Inscritos</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($estudiantes)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No hay estudiantes inscritos en este curso</p>
                            <p style="color: var(--text-light);">Los estudiantes pueden inscribirse desde su panel.</p>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Fecha de Inscripción</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $index => $estudiante): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($estudiante['nombre']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($estudiante['fecha_inscripcion'])); ?></td>
                                        <td>
                                            <span class="badge">
                                                <?php echo ucfirst($estudiante['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm" 
                                                    onclick="verDetallesEstudiante(<?php echo $estudiante['id']; ?>)">
                                                Ver Detalles
                                            </button>
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
                    <div class="stat-value">
                        <?php echo count(array_filter($estudiantes, fn($e) => $e['estado'] === 'activo')); ?>
                    </div>
                    <div class="stat-label">Estudiantes Activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php echo count(array_filter($estudiantes, fn($e) => $e['estado'] === 'completado')); ?>
                    </div>
                    <div class="stat-label">Completados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php echo count(array_filter($estudiantes, fn($e) => $e['estado'] === 'cancelado')); ?>
                    </div>
                    <div class="stat-label">Cancelados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($estudiantes); ?></div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Distribución de Estudiantes</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <?php
                        $estados = [
                            'activo' => count(array_filter($estudiantes, fn($e) => $e['estado'] === 'activo')),
                            'completado' => count(array_filter($estudiantes, fn($e) => $e['estado'] === 'completado')),
                            'cancelado' => count(array_filter($estudiantes, fn($e) => $e['estado'] === 'cancelado'))
                        ];
                        
                        foreach ($estados as $estado => $cantidad):
                            if ($cantidad > 0):
                                $porcentaje = round(($cantidad / count($estudiantes)) * 100);
                        ?>
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span style="text-transform: capitalize; color: var(--text-color);">
                                    <?php echo $estado; ?>
                                </span>
                                <span style="color: var(--text-light);"><?php echo $porcentaje; ?>%</span>
                            </div>
                            <div style="width: 100%; height: 8px; background: var(--gray-light); border-radius: 4px; overflow: hidden;">
                                <div style="width: <?php echo $porcentaje; ?>%; height: 100%; 
                                    background: <?php 
                                        echo $estado === 'activo' ? 'var(--primary-color)' : 
                                              ($estado === 'completado' ? 'var(--success-color)' : 'var(--gray-dark)');
                                    ?>;">
                                </div>
                            </div>
                            <div style="font-size: 12px; color: var(--text-light); margin-top: 5px;">
                                <?php echo $cantidad; ?> estudiantes
                            </div>
                        </div>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function verDetallesEstudiante(estudianteId) {
        alert('Funcionalidad de detalles del estudiante en desarrollo.\nID del estudiante: ' + estudianteId);
    }
    function exportarEstudiantes() {
        alert('Funcionalidad de exportación en desarrollo.');
    }
    </script>
</body>
</html>