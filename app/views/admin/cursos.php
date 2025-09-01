<?php
$this->checkAdminAuth();
$cursoModel = new CursoModel();
$cursos = $cursoModel->getAllCursos();
$profesores = $cursoModel->getProfesores();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Cursos - Plataforma Educativa</title>
</head>
<body>
<div class="admin-container">
    <div class="admin-sidebar">
        <div class="admin-brand">
            <h2>Panel de Administración</h2>
        </div>
        <ul class="admin-menu">
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/dashboard">Dashboard</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/usuarios">Usuarios</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/profesores">Profesores</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/estudiantes">Estudiantes</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=admin/cursos" class="active">Cursos</a></li>
            <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
        </ul>
        <div class="logout-section">
            <span style="color: #ecf0f1; padding: 12px 15px; display: block;"><?php echo $_SESSION['user_nombre']; ?></span>
            <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
        </div>
    </div>
    <div class="admin-main">
        <div class="admin-header">
            <h1>Gestión de Cursos</h1>
            <span class="user-welcome">Bienvenido, <?php echo $_SESSION['user_nombre']; ?></span>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo count($cursos); ?></div>
                <div class="stat-label">Total Cursos</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo array_sum(array_map(fn($c) => $c['activo'] ? 1 : 0, $cursos)); ?></div>
                <div class="stat-label">Cursos Activos</div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>Lista de Cursos</h3>
                <button class="btn-primary" onclick="openModal()">Nuevo Curso</button>
            </div>
            <div class="card-body">
                <?php if (empty($cursos)): ?>
                    <div class="empty-state">
                        <h5>No hay cursos registrados</h5>
                        <p>Comienza agregando el primer curso al sistema.</p>
                        <button class="btn-primary" onclick="openModal()">Agregar Primer Curso</button>
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Profesor</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cursos as $curso): ?>
                                <tr>
                                    <td><?php echo $curso['id']; ?></td>
                                    <td><?php echo htmlspecialchars($curso['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($curso['profesor_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($curso['descripcion'], 0, 50)) . '...'; ?></td>
                                    <td>
                                        <span class="badge <?php echo $curso['activo'] ? 'badge-active' : 'badge-inactive'; ?>">
                                            <?php echo $curso['activo'] ? 'Activo' : 'Inactivo'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($curso['fecha_creacion'])); ?></td>
                                    <td>
                                        <button class="btn-action" onclick="editarCurso(<?php echo $curso['id']; ?>)">Editar</button>
                                        <button class="btn-action" onclick="verEstadisticas(<?php echo $curso['id']; ?>)">Estadísticas</button>
                                        <button class="btn-action" onclick="eliminarCurso(<?php echo $curso['id']; ?>)">Eliminar</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div id="crearCursoModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Crear Nuevo Curso</h3>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <form action="172.20.10.3/CambaNet/public/?action=admin/crear-curso" method="POST">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nombre del Curso *</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Profesor *</label>
                            <select class="form-select" name="profesor_id" required>
                                <option value="">Seleccionar profesor...</option>
                                <?php foreach ($profesores as $profesor): ?>
                                    <option value="<?php echo $profesor['id']; ?>"><?php echo htmlspecialchars($profesor['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" name="descripcion" rows="3"></textarea>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="activo" id="activo" checked>
                    <label for="activo">Curso activo</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-primary">Crear Curso</button>
            </div>
        </form>
    </div>
</div>
<script>
function openModal() {
    document.getElementById('crearCursoModal').style.display = 'block';
}
function closeModal() {
    document.getElementById('crearCursoModal').style.display = 'none';
}
function editarCurso(id) {
    window.location.href = '172.20.10.3/CambaNet/public/?action=admin/editar-curso&id=' + id;
}
function eliminarCurso(id) {
    if(confirm('¿Estás seguro de que deseas eliminar este curso?\n\nNOTA: No se pueden eliminar cursos con estudiantes inscritos.')) {
        window.location.href = '172.20.10.3/CambaNet/public/?action=admin/eliminar-curso&id=' + id;
    }
}
function verEstadisticas(id) {
    alert('Estadísticas del curso ID: ' + id + '\n\nEsta funcionalidad se implementará próximamente.');
}
window.onclick = function(event) {
    const modal = document.getElementById('crearCursoModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
</body>
</html>