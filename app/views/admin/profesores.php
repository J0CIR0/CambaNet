<?php
$this->checkAdminAuth();
$usuarioModel = new UsuarioModel();
$profesores = $usuarioModel->getUsersByRole(2);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Profesores - Plataforma Educativa</title>
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
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/profesores" class="active">Profesores</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/estudiantes">Estudiantes</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/cursos">Cursos</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <span style="color: #ecf0f1; padding: 12px 15px; display: block;"><?php echo $_SESSION['user_nombre']; ?></span>
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Gestión de Profesores</h1>
                <div class="header-actions">
                    <span class="user-welcome">Bienvenido, <?php echo $_SESSION['user_nombre']; ?></span>
                    <button class="btn-primary" onclick="openModal()">Nuevo Profesor</button>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Lista de Profesores Registrados</div>
                <div class="card-body">
                    <?php if (empty($profesores)): ?>
                        <div class="empty-state">
                            <h5>No hay profesores registrados</h5>
                            <p>Comienza agregando el primer profesor al sistema.</p>
                            <button class="btn-primary" onclick="openModal()">Agregar Primer Profesor</button>
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Verificado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($profesores as $profesor): ?>
                                    <tr>
                                        <td><?php echo $profesor['id']; ?></td>
                                        <td><?php echo htmlspecialchars($profesor['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($profesor['email']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $profesor['verificado'] ? 'badge-verified' : 'badge-not-verified'; ?>">
                                                <?php echo $profesor['verificado'] ? 'Sí' : 'No'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn-action" onclick="editarUsuario(<?php echo $profesor['id']; ?>)">Editar</button>
                                            <button class="btn-action" onclick="eliminarUsuario(<?php echo $profesor['id']; ?>)">Eliminar</button>
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
        <div class="modal-content">
            <div class="modal-header">
                <h3>Crear Nuevo Profesor</h3>
                <button class="modal-close" onclick="closeModal()">×</button>
            </div>
            <form action="172.20.10.3/CambaNet/public/?action=admin/crear-usuario" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="rol_id" value="2">
                    <div class="form-group">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
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
                    <button type="submit" class="btn-primary">Crear Profesor</button>
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
            if (confirm('¿Estás seguro de que deseas eliminar este profesor?')) {
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