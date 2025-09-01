<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes - Panel de Profesor</title>
</head>
<body>
    <?php
    $this->checkProfesorAuth();
    $estudiantes = $data['estudiantes'] ?? [];
    $user_nombre = $data['user_nombre'] ?? $_SESSION['user_nombre'] ?? 'Profesor';
    $user_email = $data['user_email'] ?? $_SESSION['user_email'] ?? '';
    ?>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel del Profesor</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/dashboard">Dashboard</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/mis-cursos">Mis Cursos</a></li>
                <li><a href="" class="active">Estudiantes</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/material">Material</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/calificaciones">Calificaciones</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Gestión de Estudiantes</h1>
                <div class="user-welcome">Bienvenido, <?php echo htmlspecialchars($user_nombre); ?></div>
            </div>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close">&times;</button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close">&times;</button>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5>Buscar Estudiantes</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre o email...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="filterCourse">
                                <option value="">Todos los cursos</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Lista de Estudiantes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($estudiantes)): ?>
                        <div class="empty-state">
                            <h5>No hay estudiantes registrados</h5>
                            <p>No se encontraron estudiantes en el sistema.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
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
                                    <?php foreach ($estudiantes as $estudiante): ?>
                                    <tr>
                                        <td><?php echo $estudiante['id']; ?></td>
                                        <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $estudiante['verificado'] ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo $estudiante['verificado'] ? 'Sí' : 'No'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info">Ver</button>
                                            <button class="btn btn-sm btn-warning">Contactar</button>
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
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(searchValue) ? '' : 'none';
            });
        });
        document.getElementById('filterCourse').addEventListener('change', function() {
            const filterValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const course = row.cells[3]?.textContent.toLowerCase() || '';
                row.style.display = filterValue === '' || course.includes(filterValue) ? '' : 'none';
            });
        });
    </script>
</body>
</html>