<?php
$this->checkAdminAuth();
$usuarioModel = new UsuarioModel();
$usuarios = $usuarioModel->getAllUsers();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Plataforma Educativa</title>
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel de Administración</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/dashboard">Dashboard</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/usuarios" class="active">Usuarios</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/profesores">Profesores</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/estudiantes">Estudiantes</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/cursos">Cursos</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Gestión de Usuarios</h1>
                <div class="header-actions">
                    <button class="btn-primary" onclick="openModal()">Nuevo Usuario</button>
                </div>
            </div>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">×</button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">×</button>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">Lista de Usuarios Registrados</div>
                <div class="card-body">
                    <div class="table-container">
                        <table>
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
                                        <span class="badge badge-<?php echo strtolower($usuario['rol_nombre']); ?>">
                                            <?php echo htmlspecialchars($usuario['rol_nombre']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $usuario['verificado'] ? 'badge-verified' : 'badge-not-verified'; ?>">
                                            <?php echo $usuario['verificado'] ? 'Sí' : 'No'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-action" onclick="editarUsuario(<?php echo $usuario['id']; ?>)">Editar</button>
                                        <button class="btn-action" onclick="eliminarUsuario(<?php echo $usuario['id']; ?>)">Eliminar</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="crearUsuarioModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Crear Nuevo Usuario</h3>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <form action="172.20.10.3/CambaNet/public/?action=admin/crear-usuario" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rol *</label>
                        <select class="form-select" name="rol_id" required>
                            <option value="1">Administrador</option>
                            <option value="2">Profesor</option>
                            <option value="3" selected>Estudiante</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contraseña (dejar vacío para generar automática)</label>
                        <input type="password" class="form-control" name="password" minlength="6">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="verificado" id="verificado" checked>
                        <label for="verificado">Usuario verificado</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openModal() {
            document.getElementById('crearUsuarioModal').style.display = 'block';
        }
        function closeModal() {
            document.getElementById('crearUsuarioModal').style.display = 'none';
        }
        function editarUsuario(id) {
            window.location.href = '172.20.10.3/CambaNet/public/?action=admin/editar-usuario&id=' + id;
        }
        function eliminarUsuario(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                window.location.href = '172.20.10.3/CambaNet/public/?action=admin/eliminar-usuario&id=' + id;
            }
        }
        window.onclick = function(event) {
            const modal = document.getElementById('crearUsuarioModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>