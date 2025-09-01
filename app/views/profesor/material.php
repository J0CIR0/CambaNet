<?php
$cursos = $data['cursos'] ?? [];
$material = $data['material'] ?? [];
$curso_seleccionado = $data['curso_seleccionado'] ?? null;
$user_nombre = $data['user_nombre'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Didáctico - <?php echo SITE_NAME; ?></title>
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
                <li><a href="<?php echo url('profesor/material'); ?>" class="active">Material</a></li>
                <li><a href="<?php echo url('profesor/calificaciones'); ?>">Calificaciones</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
                <li><a href="<?php echo url('logout'); ?>">Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Material Didáctico</h1>
                <div class="user-welcome"><?php echo htmlspecialchars($user_nombre); ?></div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Seleccionar Curso</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo url('profesor/material'); ?>">
                        <div style="display: grid; grid-template-columns: 1fr auto; gap: 15px; align-items: end;">
                            <div class="form-group">
                                <label class="form-label">Selecciona un curso</label>
                                <select class="form-select" name="curso_id" required>
                                    <option value="">Selecciona un curso...</option>
                                    <?php foreach ($cursos as $curso): ?>
                                        <option value="<?php echo $curso['id']; ?>" 
                                            <?php echo ($curso_seleccionado && $curso_seleccionado['id'] == $curso['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($curso['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Ver Material</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($curso_seleccionado): ?>
                <div class="card">
                    <div class="card-header">
                        <h3>Subir Nuevo Material - <?php echo htmlspecialchars($curso_seleccionado['nombre']); ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo url('profesor/subir-material'); ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="curso_id" value="<?php echo $curso_seleccionado['id']; ?>">
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div class="form-group">
                                    <label class="form-label">Título del material</label>
                                    <input type="text" class="form-control" name="titulo" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Archivo</label>
                                    <input type="file" class="form-control" name="archivo" required accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.txt">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" name="descripcion" rows="3" placeholder="Describe el contenido del material..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Subir Material</button>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Material del Curso</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($material)): ?>
                            <div style="text-align: center; padding: 40px;">
                                <p>No hay material subido para este curso</p>
                            </div>
                        <?php else: ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                                <?php foreach ($material as $item): ?>
                                <div style="border: 1px solid var(--border-color); padding: 20px; border-radius: var(--border-radius);">
                                    <h4 style="margin-bottom: 10px;"><?php echo htmlspecialchars($item['titulo']); ?></h4>
                                    <p style="color: var(--text-light); margin-bottom: 15px;">
                                        <?php echo htmlspecialchars(substr($item['descripcion'] ?? 'Sin descripción', 0, 80)); ?>...
                                    </p>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-size: 12px; color: var(--text-light);">
                                            Subido: <?php echo date('d/m/Y', strtotime($item['fecha_creacion'])); ?>
                                        </span>
                                        <div style="display: flex; gap: 10px;">
                                            <a href="/CambaNet/uploads/material/<?php echo basename($item['archivo_ruta']); ?>" 
                                               class="btn btn-sm btn-primary" download>
                                                Descargar
                                            </a>
                                            <button onclick="eliminarMaterial(<?php echo $item['id']; ?>, <?php echo $curso_seleccionado['id']; ?>)" 
                                                    class="btn btn-sm btn-danger">
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function eliminarMaterial(materialId, cursoId) {
        if (confirm('¿Estás seguro de que deseas eliminar este material?\n\nEsta acción no se puede deshacer.')) {
            window.location.href = '<?php echo url('profesor/eliminar-material'); ?>&id=' + materialId + '&curso_id=' + cursoId;
        }
    }
    </script>
</body>
</html>