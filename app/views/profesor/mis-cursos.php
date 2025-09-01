<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos - Panel de Profesor</title>
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel del Profesor</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/dashboard">Dashboard</a></li>
                <li><a href="#" class="active">Mis Cursos</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/estudiantes-general">Estudiantes</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/material">Material</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/calificaciones">Calificaciones</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Mis Cursos</h1>
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
            <div class="courses-grid">
                <?php if (empty($cursos)): ?>
                    <div class="empty-state">
                        <h3>No tienes cursos asignados</h3>
                        <p>Contacta al administrador para que te asignen cursos.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($cursos as $curso): ?>
                    <div class="course-card">
                        <div class="course-header">
                            <h3><?php echo htmlspecialchars($curso['nombre']); ?></h3>
                        </div>
                        <div class="course-body">
                            <p class="course-description"><?php echo htmlspecialchars(substr($curso['descripcion'], 0, 100)) . '...'; ?></p>
                            <div class="course-meta">
                                <span class="course-badge <?php echo $curso['activo'] ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?php echo $curso['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                                <span class="course-badge badge-students">
                                    <?php echo $curso['total_estudiantes']; ?> estudiantes
                                </span>
                            </div>
                        </div>
                        <div class="course-footer">
                            <div class="btn-grid">
                                <a href="172.20.10.3/CambaNet/public/?action=profesor/editar-curso&id=<?php echo $curso['id']; ?>" 
                                   class="course-btn btn-edit">
                                    Editar
                                </a>
                                <a href="172.20.10.3/CambaNet/public/?action=profesor/estudiantes&id=<?php echo $curso['id']; ?>" 
                                   class="course-btn btn-students">
                                    Estudiantes
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const closeButtons = document.querySelectorAll('.btn-close');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>