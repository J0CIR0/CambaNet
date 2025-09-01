<?php
$this->checkAdminAuth();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Plataforma Educativa</title>
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel de Administración</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="#" class="active">Dashboard</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/usuarios">Usuarios</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/profesores">Profesores</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/estudiantes">Estudiantes</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=admin/cursos">Cursos</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a></li>
            </ul>
            <div class="logout-section">
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Dashboard de Administración</h1>
                <div class="user-welcome">Bienvenido, <?php echo $_SESSION['user_nombre']; ?></div>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalUsuarios; ?></div>
                    <div class="stat-label">Total Usuarios</div>
                </div>
                <div class="stat-card verified">
                    <div class="stat-value"><?php echo $totalVerificados; ?></div>
                    <div class="stat-label">Verificados</div>
                </div>
                <div class="stat-card teachers">
                    <div class="stat-value"><?php echo $usuariosPorRol['Profesor'] ?? 0; ?></div>
                    <div class="stat-label">Profesores</div>
                </div>
                <div class="stat-card students">
                    <div class="stat-value"><?php echo $usuariosPorRol['Estudiante'] ?? 0; ?></div>
                    <div class="stat-label">Estudiantes</div>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-header">Resumen del Sistema</div>
                <div class="summary-body">
                    <p>Bienvenido al panel de administración. Desde aquí puedes gestionar todos los aspectos del sistema educativo.</p>
                    <div class="info-alert">
                        Selecciona una opción del menú lateral para comenzar a gestionar los diferentes módulos.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    console.log('Bienvenido a CambaNet!');
    console.log('Usuario: <?php echo $_SESSION['user_nombre']; ?>');
    console.log('Email: <?php echo $_SESSION['user_email']; ?>');
    console.log('Rol: <?php 
        if ($_SESSION['user_role'] == 1) echo "Administrador";
        elseif ($_SESSION['user_role'] == 2) echo "Profesor"; 
        else echo "Estudiante";
    ?>');
    console.log('hora de acceso: <?php echo date("Y-m-d H:i:s"); ?>');
    </script>
</body>
</html>