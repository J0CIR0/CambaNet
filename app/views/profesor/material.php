<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Didáctico - Panel de Profesor</title>
</head>
<body>
    <?php
    $cursos = $data['cursos'] ?? [];
    $material = $data['material'] ?? [];
    $curso_seleccionado = $data['curso_seleccionado'] ?? null;
    $user_nombre = $data['user_nombre'] ?? 'Profesor';
    $user_email = $data['user_email'] ?? '';
    ?>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel del Profesor</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/dashboard">Dashboard</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/mis-cursos">Mis Cursos</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/estudiantes-general">Estudiantes</a></li>
                <li><a href="#" class="active">Material</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/calificaciones">Calificaciones</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Material Didáctico</h1>
                <div class="user-welcome">Bienvenido, <?php echo htmlspecialchars($user_nombre); ?></div>
            </div>
            <a href="172.20.10.3/CambaNet/public/?action=profesor/mis-cursos" class="btn btn-sm">
                Volver a Cursos
            </a>
            <div class="card">
                <div class="card-header">Seleccionar Curso</div>
                <div class="card-body">
                    <form method="GET" action="172.20.10.3/CambaNet/public/?action=profesor/material">
                        <div class="row">
                            <div class="col-md-8">
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
                            <div class="col-md-4">
                                <button type="submit" class="btn" style="width: 100%">
                                    Ver Material
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php if ($curso_seleccionado): ?>
                <div class="card">
                    <div class="card-header">Subir Nuevo Material - <?php echo htmlspecialchars($curso_seleccionado['nombre']); ?></div>
                    <div class="card-body">
                        <form action="172.20.10.3/CambaNet/public/?action=profesor/subir-material" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="curso_id" value="<?php echo $curso_seleccionado['id']; ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Título *</label>
                                    <input type="text" class="form-control" name="titulo" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Archivo *</label>
                                    <input type="file" class="form-control" name="archivo" required>
                                </div>
                            </div>
                            <div>
                                <label>Descripción</label>
                                <textarea class="form-control" name="descripcion" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn">
                                Subir Material
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Material del Curso</div>
                    <div class="card-body">
                        <?php if (empty($material)): ?>
                            <div class="empty-state">
                                <h5>No hay material subido</h5>
                            </div>
                        <?php else: ?>
                            <div class="material-grid">
                                <?php foreach ($material as $item): ?>
                                <div class="file-card">
                                    <div class="file-title"><?php echo htmlspecialchars($item['titulo']); ?></div>
                                    <div class="file-description"><?php echo htmlspecialchars(substr($item['descripcion'],0,50)); ?>...</div>
                                    <div class="btn-grid">
                                        <a href="/CambaNet/uploads/material/<?php echo basename($item['archivo_ruta']); ?>" class="btn btn-sm btn-download" download>
                                            Descargar
                                        </a>
                                        <button onclick="eliminarMaterial(<?php echo $item['id']; ?>, <?php echo $curso_seleccionado['id']; ?>)" class="btn btn-sm btn-delete">
                                            Eliminar
                                        </button>
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
        if(confirm('¿Seguro deseas eliminar este material?')) {
            window.location.href = '172.20.10.3/CambaNet/public/?action=profesor/eliminar-material&id=' + materialId + '&curso_id=' + cursoId;
        }
    }
    </script>
</body>
</html>