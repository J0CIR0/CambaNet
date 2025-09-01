<?php
$cursos = $data['cursos'] ?? [];
$actividades = $data['actividades'] ?? [];
$estadisticas = $data['estadisticas'] ?? [];
$curso_seleccionado = $data['curso_seleccionado'] ?? null;
$curso_id = $data['curso_id'] ?? null;
$user_nombre = $data['user_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones - <?php echo SITE_NAME; ?></title>
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
                <li><a href="<?php echo url('profesor/calificaciones'); ?>" class="active">Calificaciones</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
                <li><a href="<?php echo url('logout'); ?>">Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Sistema de Calificaciones</h1>
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
                    <h3>Seleccionar Curso</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo url('profesor/calificaciones'); ?>">
                        <div class="form-group">
                            <label class="form-label">Selecciona un curso</label>
                            <select class="form-select" name="curso_id" onchange="this.form.submit()">
                                <option value="">Selecciona un curso...</option>
                                <?php foreach ($cursos as $curso): ?>
                                <option value="<?php echo $curso['id']; ?>" 
                                    <?php echo $curso_id == $curso['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($curso['nombre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($curso_seleccionado): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $estadisticas['total_estudiantes'] ?? 0; ?></div>
                    <div class="stat-label">Estudiantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $estadisticas['total_actividades'] ?? 0; ?></div>
                    <div class="stat-label">Actividades</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo round($estadisticas['promedio_general'] ?? 0); ?>%</div>
                    <div class="stat-label">Promedio General</div>
                </div>
                <div class="stat-card">
                    <button onclick="openModal('crearActividadModal')" class="btn btn-primary">
                        Nueva Actividad
                    </button>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>Actividades de <?php echo htmlspecialchars($curso_seleccionado['nombre']); ?></h3>
                </div>
                <div class="card-body">
                    <?php if (empty($actividades)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No hay actividades creadas para este curso</p>
                            <button onclick="openModal('crearActividadModal')" class="btn btn-primary">
                                Crear Primera Actividad
                            </button>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Tipo</th>
                                        <th>Puntaje Máximo</th>
                                        <th>Fecha Límite</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($actividades as $actividad): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($actividad['titulo']); ?></strong>
                                            <?php if ($actividad['descripcion']): ?>
                                            <br><small style="color: var(--text-light);"><?php echo htmlspecialchars(substr($actividad['descripcion'], 0, 50)); ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge" style="text-transform: capitalize;">
                                                <?php echo $actividad['tipo']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $actividad['puntaje_maximo']; ?> pts</td>
                                        <td>
                                            <?php echo $actividad['fecha_limite'] ? date('d/m/Y', strtotime($actividad['fecha_limite'])) : 'Sin fecha'; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $actividad['activo'] ? 'badge-active' : 'badge-inactive'; ?>">
                                                <?php echo $actividad['activo'] ? 'Activa' : 'Inactiva'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 10px;">
                                                <a href="<?php echo url('profesor/calificar-actividad'); ?>&id=<?php echo $actividad['id']; ?>" 
                                                class="btn btn-sm btn-primary">
                                                    Calificar
                                                </a>
                                                <a href="<?php echo url('profesor/editar-actividad'); ?>&id=<?php echo $actividad['id']; ?>" 
                                                class="btn btn-sm">
                                                    Editar
                                                </a>
                                                <button onclick="eliminarActividad(<?php echo $actividad['id']; ?>)" 
                                                        class="btn btn-sm btn-danger">
                                                    Eliminar
                                                </button>
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
            <?php endif; ?>
        </div>
    </div>
    <div id="crearActividadModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Crear Nueva Actividad</h3>
                <button class="modal-close" onclick="closeModal('crearActividadModal')">×</button>
            </div>
            <form action="<?php echo url('profesor/crear-actividad'); ?>" method="POST">
                <input type="hidden" name="curso_id" value="<?php echo $curso_id; ?>">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Título de la Actividad</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label class="form-label">Tipo de Actividad</label>
                            <select class="form-select" name="tipo" required>
                                <option value="tarea">Tarea</option>
                                <option value="examen">Examen</option>
                                <option value="proyecto">Proyecto</option>
                                <option value="participacion">Participación</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Puntaje Máximo</label>
                            <input type="number" class="form-control" name="puntaje_maximo" step="0.01" min="0" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Fecha Límite (opcional)</label>
                        <input type="datetime-local" class="form-control" name="fecha_limite">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('crearActividadModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Actividad</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    function eliminarActividad(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta actividad?\n\nSe eliminarán todas las calificaciones asociadas.')) {
            window.location.href = '<?php echo url('profesor/eliminar-actividad'); ?>&id=' + id;
        }
    }
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => modal.style.display = 'none');
            document.body.style.overflow = 'auto';
        }
    }
    </script>
</body>
</html>