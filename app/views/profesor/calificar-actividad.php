<?php
$actividad = $data['actividad'] ?? [];
$estudiantes = $data['estudiantes'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificar Actividad - <?php echo SITE_NAME; ?></title>
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
                <h1>Calificar Actividad</h1>
                <a href="<?php echo url('profesor/calificaciones'); ?>&curso_id=<?php echo $actividad['curso_id']; ?>" 
                   class="btn btn-secondary">
                    Volver a Calificaciones
                </a>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3><?php echo htmlspecialchars($actividad['titulo']); ?></h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <p><strong>Curso:</strong> <?php echo htmlspecialchars($actividad['curso_nombre']); ?></p>
                            <p><strong>Tipo:</strong> <span style="text-transform: capitalize;"><?php echo $actividad['tipo']; ?></span></p>
                            <p><strong>Puntaje Máximo:</strong> <?php echo $actividad['puntaje_maximo']; ?> puntos</p>
                        </div>
                        <div>
                            <p><strong>Fecha Límite:</strong> 
                                <?php echo $actividad['fecha_limite'] ? date('d/m/Y H:i', strtotime($actividad['fecha_limite'])) : 'No especificada'; ?>
                            </p>
                            <p><strong>Estado:</strong> 
                                <span class="badge <?php echo $actividad['activo'] ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?php echo $actividad['activo'] ? 'Activa' : 'Inactiva'; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <?php if ($actividad['descripcion']): ?>
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                        <strong>Descripción:</strong>
                        <p style="color: var(--text-color); margin-top: 10px;"><?php echo nl2br(htmlspecialchars($actividad['descripcion'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Calificar Estudiantes</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('profesor/calificar-actividad'); ?>&id=<?php echo $actividad['id']; ?>" method="POST">
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Estudiante</th>
                                        <th>Email</th>
                                        <th>Puntaje (0 - <?php echo $actividad['puntaje_maximo']; ?>)</th>
                                        <th>Comentario</th>
                                        <th>Calificación Anterior</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $estudiante): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($estudiante['nombre']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                                        <td>
                                            <input type="number" 
                                                   name="calificaciones[<?php echo $estudiante['id']; ?>][puntaje]" 
                                                   class="form-control" 
                                                   step="0.01" 
                                                   min="0" 
                                                   max="<?php echo $actividad['puntaje_maximo']; ?>"
                                                   value="<?php echo $estudiante['puntaje_obtenido'] ?? ''; ?>"
                                                   required>
                                        </td>
                                        <td>
                                            <textarea name="calificaciones[<?php echo $estudiante['id']; ?>][comentario]" 
                                                      class="form-control" 
                                                      rows="2"
                                                      placeholder="Comentario opcional"><?php echo $estudiante['comentario'] ?? ''; ?></textarea>
                                        </td>
                                        <td>
                                            <?php if ($estudiante['puntaje_obtenido'] !== null): ?>
                                            <span style="color: var(--primary-color); font-weight: bold;">
                                                <?php echo $estudiante['puntaje_obtenido']; ?> pts
                                            </span>
                                            <?php else: ?>
                                            <span style="color: var(--text-light);">Sin calificar</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div style="margin-top: 20px; text-align: center;">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Guardar Todas las Calificaciones
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>