<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Profesor - Plataforma Educativa</title>
</head>
<body>
    <?php 
    $cursos = $data['cursos'] ?? [];
    $totalEstudiantes = $data['totalEstudiantes'] ?? 0;
    $user_nombre = $data['user_nombre'] ?? 'Profesor';
    $user_email = $data['user_email'] ?? '';
    ?>
    <div class="professor-container">
        <div class="professor-sidebar">
            <div class="professor-brand">
                <h2>Panel del Profesor</h2>
            </div>
            <ul class="professor-menu">
                <li><a href="#" class="active">Dashboard</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/mis-cursos">Mis Cursos</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/estudiantes-general">Estudiantes</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/material">Material</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/calificaciones">Calificaciones</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a></li>
            </ul>
            <div class="logout-section">
            </div>
        </div>
        <div class="professor-main">
            <div class="professor-header">
                <h1>Dashboard de Profesor</h1>
                <div class="user-welcome">Bienvenido, <?php echo $user_nombre; ?></div>
            </div>
            <div class="stats-grid">
                <div class="stat-card courses">
                    <div class="stat-value"><?php echo count($cursos); ?></div>
                    <div class="stat-label">Total Cursos</div>
                </div>
                <div class="stat-card students">
                    <div class="stat-value"><?php echo $totalEstudiantes; ?></div>
                    <div class="stat-label">Total Estudiantes</div>
                </div>
                <div class="stat-card date">
                    <div class="stat-value"><?php echo date('d/m/Y'); ?></div>
                    <div class="stat-label">Fecha Actual</div>
                </div>
            </div>
            <div class="actions-grid">
                <div class="action-card">
                    <div class="card-header">
                        Acciones Rápidas
                    </div>
                    <div class="card-body">
                        <div class="btn-grid">
                            <a href="#" class="btn-action">Crear Material</a>
                            <a href="#" class="btn-action">Crear Evaluación</a>
                            <a href="#" class="btn-action">Ver Estadísticas</a>
                        </div>
                    </div>
                </div>
                <div class="info-card">
                    <div class="card-header">
                        Información del Sistema
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo $user_email; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Rol:</span>
                            <span class="info-value">Profesor</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Último acceso:</span>
                            <span class="info-value"><?php echo date('d/m/Y H:i:s'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    console.log('¡Bienvenido a CambaNet!');
    console.log(' Usuario: <?php echo $_SESSION['user_nombre']; ?>');
    console.log('Email: <?php echo $_SESSION['user_email']; ?>');
    console.log('Rol: <?php 
        if ($_SESSION['user_role'] == 1) echo "Administrador";
        elseif ($_SESSION['user_role'] == 2) echo "Profesor"; 
        else echo "Estudiante";
    ?>');
    console.log('Hora de acceso: <?php echo date("Y-m-d H:i:s"); ?>');
    </script>
</body>
</html>