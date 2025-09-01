<?php
$actividad = $data['actividad'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Actividad - <?php echo SITE_NAME; ?></title>
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
                <h1>Editar Actividad</h1>
                <a href="<?php echo url('profesor/calificaciones'); ?>&curso_id=<?php echo $actividad['curso_id']; ?>" 
                   class="btn btn-secondary">
                    Volver a Calificaciones
                </a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3>Editando: <?php echo htmlspecialchars($actividad['titulo']); ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('profesor/editar-actividad'); ?>&id=<?php echo $actividad['id']; ?>" method="POST">
                        <div class="form-group">
                            <label class="form-label">Título de la Actividad</label>
                            <input type="text" class="form-control" name="titulo" 
                                   value="<?php echo htmlspecialchars($actividad['titulo']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="4"><?php echo htmlspecialchars($actividad['descripcion']); ?></textarea>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Tipo de Actividad</label>
                                <select class="form-select" name="tipo" required>
                                    <option value="tarea" <?php echo $actividad['tipo'] == 'tarea' ? 'selected' : ''; ?>>Tarea</option>
                                    <option value="examen" <?php echo $actividad['tipo'] == 'examen' ? 'selected' : ''; ?>>Examen</option>
                                    <option value="proyecto" <?php echo $actividad['tipo'] == 'proyecto' ? 'selected' : ''; ?>>Proyecto</option>
                                    <option value="participacion" <?php echo $actividad['tipo'] == 'participacion' ? 'selected' : ''; ?>>Participación</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Puntaje Máximo</label>
                                <input type="number" class="form-control" name="puntaje_maximo" 
                                       step="0.01" min="0" value="<?php echo $actividad['puntaje_maximo']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Fecha Límite (opcional)</label>
                            <input type="datetime-local" class="form-control" name="fecha_limite" 
                                   value="<?php echo $actividad['fecha_limite'] ? date('Y-m-d\TH:i', strtotime($actividad['fecha_limite'])) : ''; ?>">
                        </div>
                        
                        <div class="form-check" style="margin-bottom: 20px;">
                            <input type="checkbox" name="activo" id="activo" 
                                   <?php echo $actividad['activo'] ? 'checked' : ''; ?>>
                            <label for="activo">Actividad activa</label>
                        </div>

                        <div style="display: flex; gap: 15px;">
                            <a href="<?php echo url('profesor/calificaciones'); ?>&curso_id=<?php echo $actividad['curso_id']; ?>" 
                               class="btn btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>