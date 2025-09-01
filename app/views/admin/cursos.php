<?php
$cursos = $cursos ?? [];
$profesores = $profesores ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel de Administración</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="<?php echo url('admin/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo url('admin/usuarios'); ?>">Usuarios</a></li>
                <li><a href="<?php echo url('admin/profesores'); ?>">Profesores</a></li>
                <li><a href="<?php echo url('admin/estudiantes'); ?>">Estudiantes</a></li>
                <li><a href="<?php echo url('admin/cursos'); ?>" class="active">Cursos</a></li>
                <li><a href="<?php echo url('admin/inscripciones'); ?>">Inscripciones</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="<?php echo url('logout'); ?>" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Gestión de Cursos</h1>
                <div class="user-welcome">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></div>
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
                    <button onclick="openModal('crearCursoModal')" class="btn btn-primary">Nuevo Curso</button>
                </div>
                <div class="card-body">
                    <?php if (empty($cursos)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No hay cursos registrados</p>
                            <button onclick="openModal('crearCursoModal')" class="btn btn-primary">Crear Primer Curso</button>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Profesor</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
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
                                        <td>
                                            <button onclick="editarCurso(<?php echo $curso['id']; ?>)" class="btn btn-sm">Editar</button>
                                            <button onclick="eliminarCurso(<?php echo $curso['id']; ?>)" class="btn btn-sm btn-danger">Eliminar</button>
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
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3>Crear Nuevo Curso</h3>
                <button class="modal-close" onclick="closeModal('crearCursoModal')">×</button>
            </div>
            <form action="<?php echo url('admin/crear-curso'); ?>" method="POST">
                <div class="modal-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label class="form-label">Nombre del Curso</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Profesor</label>
                            <select class="form-select" name="profesor_id" required>
                                <option value="">Seleccionar profesor...</option>
                                <?php foreach ($profesores as $profesor): ?>
                                    <option value="<?php echo $profesor['id']; ?>"><?php echo htmlspecialchars($profesor['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
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
                    <button type="button" class="btn btn-secondary" onclick="closeModal('crearCursoModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Curso</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function editarCurso(id) {
        window.location.href = '<?php echo url('admin/editar-curso'); ?>&id=' + id;
    }

    function eliminarCurso(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este curso?\n\nNota: No se pueden eliminar cursos con estudiantes inscritos.')) {
            window.location.href = '<?php echo url('admin/eliminar-curso'); ?>&id=' + id;
        }
    }

    function openModal(id) {
        document.getElementById(id).style.display = 'block';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
    </script>
</body>
</html>