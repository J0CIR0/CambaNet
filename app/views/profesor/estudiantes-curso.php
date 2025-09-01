<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes - <?php echo htmlspecialchars($curso['nombre']); ?> - Panel de Profesor</title>
</head>
<body>
    <?php
    $this->checkProfesorAuth();
    $curso = $data['curso'] ?? [];
    $estudiantes = $data['estudiantes'] ?? [];
    $user_nombre = $data['user_nombre'] ?? $_SESSION['user_nombre'] ?? 'Profesor';
    $user_email = $data['user_email'] ?? $_SESSION['user_email'] ?? '';
    if (empty($curso)) {
        header("Location: 172.20.10.3/CambaNet/public/?action=profesor/mis-cursos");
        exit();
    }
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
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/calificaciones">Calificaciones</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profesor/profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Estudiantes Inscritos</h1>
                <div class="user-welcome">Bienvenido, <?php echo htmlspecialchars($user_nombre); ?></div>
            </div>
            <a href="172.20.10.3/CambaNet/public/?action=profesor/mis-cursos" class="btn">
                Volver a Cursos
            </a>
            <div class="card">
                <div class="card-header"><?php echo htmlspecialchars($curso['nombre']); ?></div>
                <div class="card-body">
                    <div class="info-grid">
                        <div>
                            <div class="info-item">
                                <div class="info-label">Descripción:</div>
                                <div class="info-value"><?php echo htmlspecialchars($curso['descripcion']); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Estado:</div>
                                <div class="info-value">
                                    <span class="badge"><?php echo $curso['activo'] ? 'Activo' : 'Inactivo'; ?></span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="info-item">
                                <div class="info-label">Total Estudiantes:</div>
                                <div class="info-value"><?php echo count($estudiantes); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Fecha Creación:</div>
                                <div class="info-value"><?php echo date('d/m/Y', strtotime($curso['fecha_creacion'])); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Lista de Estudiantes Inscritos</div>
                <div class="card-body">
                    <?php if (empty($estudiantes)): ?>
                        <div class="empty-state">
                            <h5>No hay estudiantes inscritos</h5>
                            <p>Aún no hay estudiantes inscritos en este curso.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Fecha Inscripción</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($estudiantes as $index => $estudiante): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($estudiante['email']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($estudiante['fecha_inscripcion'])); ?></td>
                                            <td><span class="badge"><?php echo ucfirst($estudiante['estado']); ?></span></td>
                                            <td>
                                                <button class="btn btn-sm">Ver</button>
                                                <button class="btn btn-sm">Contactar</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($estudiantes)): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo count(array_filter($estudiantes, fn($e) => $e['estado'] == 'activo')); ?></div>
                    <div class="stat-label">Activos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count(array_filter($estudiantes, fn($e) => $e['estado'] == 'completado')); ?></div>
                    <div class="stat-label">Completados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count(array_filter($estudiantes, fn($e) => $e['estado'] == 'cancelado')); ?></div>
                    <div class="stat-label">Cancelados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($estudiantes); ?></div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>