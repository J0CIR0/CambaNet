<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Estudiante</title>
</head>
<body>
    <?php
    $cursosInscritos = $data['cursosInscritos'] ?? [];
    $cursosDisponibles = $data['cursosDisponibles'] ?? [];
    $user_nombre = $data['user_nombre'] ?? 'Estudiante';
    $user_email = $data['user_email'] ?? '';
    ?>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel del Estudiante</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="172.20.10.3/CambaNet/public/?action=estudiante/dashboard" class="active">Dashboard</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=estudiante/mis-cursos">Mis Cursos</a></li>
                <li><a href="#">Progreso</a></li>
                <li><a href="#">Certificados</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile" class="profile-link">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
            <div class="mobile-logout">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>
        <div class="admin-main">
            <div class="admin-header">
                <h1>Panel de Estudiante</h1>
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
            <div class="stats-grid">
                <div class="stat-card courses">
                    <div class="stat-value"><?php echo count($cursosInscritos); ?></div>
                    <div class="stat-label">Cursos Inscritos</div>
                </div>
                <div class="stat-card active">
                    <div class="stat-value"><?php echo count(array_filter($cursosInscritos, fn($c) => $c['estado'] == 'activo')); ?></div>
                    <div class="stat-label">Cursos Activos</div>
                </div>
                <div class="stat-card available">
                    <div class="stat-value"><?php echo count($cursosDisponibles); ?></div>
                    <div class="stat-label">Cursos Disponibles</div>
                </div>
                <div class="stat-card completed">
                    <div class="stat-value"><?php echo count(array_filter($cursosInscritos, fn($c) => $c['estado'] == 'completado')); ?></div>
                    <div class="stat-label">Completados</div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Mis Cursos Inscritos</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($cursosInscritos)): ?>
                        <div class="empty-state">
                            <h5>No estás inscrito en ningún curso</h5>
                            <p>Explora los cursos disponibles e inscríbete para comenzar.</p>
                        </div>
                    <?php else: ?>
                        <div class="courses-grid">
                            <?php foreach ($cursosInscritos as $curso): ?>
                            <div class="course-card">
                                <div class="course-header">
                                    <h6><?php echo htmlspecialchars($curso['nombre']); ?></h6>
                                </div>
                                <div class="course-body">
                                    <p class="course-description"><?php echo htmlspecialchars(substr($curso['descripcion'], 0, 80)) . '...'; ?></p>
                                    <div class="course-meta">
                                        <span class="badge bg-<?php echo $curso['estado'] == 'activo' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($curso['estado']); ?>
                                        </span>
                                        <span class="badge bg-info">Inscrito: <?php echo date('d/m/Y', strtotime($curso['fecha_inscripcion'])); ?></span>
                                    </div>
                                </div>
                                <div class="course-footer">
                                    <div class="btn-grid">
                                        <a href="172.20.10.3/CambaNet/public/?action=estudiante/ver-curso&id=<?php echo $curso['id']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            Continuar
                                        </a>
                                        <?php if ($curso['estado'] == 'activo'): ?>
                                        <button onclick="cancelarInscripcion(<?php echo $curso['id']; ?>)" 
                                                class="btn btn-sm btn-outline-danger">
                                            Cancelar
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Cursos Disponibles</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($cursosDisponibles)): ?>
                        <div class="empty-state">
                            <h5>¡Felicidades!</h5>
                            <p>Estás inscrito en todos los cursos disponibles.</p>
                        </div>
                    <?php else: ?>
                        <div class="courses-grid">
                            <?php foreach ($cursosDisponibles as $curso): ?>
                            <div class="course-card">
                                <div class="course-header">
                                    <h6><?php echo htmlspecialchars($curso['nombre']); ?></h6>
                                </div>
                                <div class="course-body">
                                    <p class="course-description"><?php echo htmlspecialchars(substr($curso['descripcion'], 0, 80)) . '...'; ?></p>
                                    <p class="course-meta"><strong>Profesor:</strong> <?php echo htmlspecialchars($curso['profesor_nombre']); ?></p>
                                    <p class="course-meta"><strong>Creación:</strong> <?php echo date('d/m/Y', strtotime($curso['fecha_creacion'])); ?></p>
                                </div>
                                <div class="course-footer">
                                    <div class="btn-grid">
                                        <button onclick="inscribirCurso(<?php echo $curso['id']; ?>)" 
                                                class="btn btn-sm btn-success">
                                            Inscribirse
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function inscribirCurso(cursoId) {
            if (confirm('¿Estás seguro de que quieres inscribirte en este curso?')) {
                window.location.href = '172.20.10.3/CambaNet/public/?action=estudiante/inscribir&id=' + cursoId;
            }
        }
        function cancelarInscripcion(cursoId) {
            if (confirm('¿Estás seguro de que quieres cancelar tu inscripción en este curso?\n\nPodrás volver a inscribirte después.')) {
                window.location.href = '172.20.10.3/CambaNet/public/?action=estudiante/cancelar&id=' + cursoId;
            }
        }
    </script>
    <script>
    console.log('¡Bienvenido a CambaNet!');
    console.log('Usuario: <?php echo $_SESSION['user_nombre']; ?>');
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