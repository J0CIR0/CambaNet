<?php
$usuarios = $usuarios ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - <?php echo SITE_NAME; ?></title>
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
                <li><a href="<?php echo url('admin/usuarios'); ?>" class="active">Usuarios</a></li>
                <li><a href="<?php echo url('admin/profesores'); ?>">Profesores</a></li>
                <li><a href="<?php echo url('admin/estudiantes'); ?>">Estudiantes</a></li>
                <li><a href="<?php echo url('admin/cursos'); ?>">Cursos</a></li>
                <li><a href="<?php echo url('admin/inscripciones'); ?>">Inscripciones</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="<?php echo url('logout'); ?>" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Gestión de Usuarios</h1>
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

            <div class="card">
                <div class="card-header">
                    <h3>Lista de Usuarios</h3>
                    <button onclick="openModal('crearUsuarioModal')" class="btn btn-primary">Nuevo Usuario</button>
                </div>
                <div class="card-body">
                    <?php if (empty($usuarios)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No hay usuarios registrados</p>
                            <button onclick="openModal('crearUsuarioModal')" class="btn btn-primary">Crear Primer Usuario</button>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Verificado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?php echo $usuario['id']; ?></td>
                                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td>
                                            <span class="badge">
                                                <?php echo htmlspecialchars($usuario['rol_nombre']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $usuario['verificado'] ? 'badge-verified' : 'badge-not-verified'; ?>">
                                                <?php echo $usuario['verificado'] ? 'Sí' : 'No'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button onclick="editarUsuario(<?php echo $usuario['id']; ?>)" class="btn btn-sm">Editar</button>
                                            <button onclick="eliminarUsuario(<?php echo $usuario['id']; ?>)" class="btn btn-sm btn-danger">Eliminar</button>
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
    <div id="crearUsuarioModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Crear Nuevo Usuario</h3>
                <button class="modal-close" onclick="closeModal('crearUsuarioModal')">×</button>
            </div>
            <form action="<?php echo url('admin/crear-usuario'); ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rol</label>
                        <select class="form-select" name="rol_id" required>
                            <option value="1">Administrador</option>
                            <option value="2">Profesor</option>
                            <option value="3" selected>Estudiante</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" minlength="6">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="verificado" id="verificado" checked>
                        <label for="verificado">Usuario verificado</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('crearUsuarioModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
<script>
    function editarUsuario(id) {
        window.location.href = '<?php echo url('admin/editar-usuario'); ?>&id=' + id;
    }

    function eliminarUsuario(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            window.location.href = '<?php echo url('admin/eliminar-usuario'); ?>&id=' + id;
        }
    }
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                closeModal(modal.id);
            }
        });
    }
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (modal.style.display === 'block') {
                    closeModal(modal.id);
                }
            });
        }
    });
</script>
</body>
</html>