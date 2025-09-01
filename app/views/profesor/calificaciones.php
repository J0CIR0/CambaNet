<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones - Panel de Profesor</title>
</head>
<body>
    <?php
    $this->checkProfesorAuth();
    $user_nombre = $user_nombre ?? $_SESSION['user_nombre'] ?? 'Profesor';
    $user_email = $user_email ?? $_SESSION['user_email'] ?? '';
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
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/material">Material</a></li>
                <li><a href="#" class="active">Calificaciones</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Calificaciones</h1>
                <div class="user-welcome">Bienvenido, <?php echo htmlspecialchars($user_nombre); ?></div>
            </div>
            <div class="alert alert-info">
                El sistema de calificaciones está en desarrollo. Próximamente podrás gestionar las calificaciones de tus estudiantes.
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Funcionalidades próximas</h5>
                </div>
                <div class="card-body">
                    <ul class="feature-list">
                        <li>Registro de calificaciones por curso</li>
                        <li>Reportes de progreso estudiantil</li>
                        <li>Estadísticas de rendimiento</li>
                        <li>Exportación de calificaciones</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>