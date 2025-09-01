<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Cursos - Panel de Estudiante</title>
</head>
<body>
    <?php
    $this->checkEstudianteAuth();
    $cursos = $cursos ?? [];
    $user_nombre = $user_nombre ?? 'Estudiante';
    ?>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel del Estudiante</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="172.20.10.3/CambaNet/public/?action=estudiante/dashboard">Dashboard</a></li>
                <li><a href="#" class="active">Mis Cursos</a></li>
                <li><a href="#">Progreso</a></li>
                <li><a href="#">Certificados</a></li>
                <li><a href="172.20.10.3/CambaNet/public/?action=profile">Mi Perfil</a></li>
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
                        <h4>No estás inscrito en ningún curso</h4>
                        <p>Visita el dashboard para explorar cursos disponibles.</p>
                        <a href="172.20.10.3/CambaNet/public/?action=estudiante/dashboard" class="btn btn-primary">
                            Volver al Dashboard
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($cursos as $curso): ?>
                    <div class="course-card">
                        <div class="course-header">
                            <h6><?php echo htmlspecialchars($curso['nombre']); ?></h6>
                        </div>
                        <div class="course-body">
                            <p class="course-description"><?php echo htmlspecialchars(substr($curso['descripcion'], 0, 100)) . '...'; ?></p>
                            <div class="course-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Profesor:</span>
                                    <span class="meta-value"><?php echo htmlspecialchars($curso['profesor_nombre']); ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Inscrito:</span>
                                    <span class="meta-value"><?php echo date('d/m/Y', strtotime($curso['fecha_inscripcion'])); ?></span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Material:</span>
                                    <span class="meta-value"><?php echo $curso['total_material']; ?> archivos</span>
                                </div>
                                <div class="meta-item">
                                    <span class="badge bg-<?php echo $curso['estado'] == 'activo' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($curso['estado']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 0%"></div>
                                </div>
                                <div class="progress-text">Progreso: 0%</div>
                            </div>
                        </div>
                        <div class="course-footer">
                            <div class="btn-grid">
                                <a href="172.20.10.3/CambaNet/public/?action=estudiante/ver-curso&id=<?php echo $curso['id']; ?>" 
                                   class="btn btn-sm btn-primary">
                                    Continuar Estudiando
                                </a>
                                <?php if ($curso['estado'] == 'activo'): ?>
                                <button onclick="cancelarInscripcion(<?php echo $curso['id']; ?>)" 
                                        class="btn btn-sm btn-outline-danger">
                                    Cancelar Inscripción
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function cancelarInscripcion(cursoId) {
            if (confirm('¿Estás seguro de que quieres cancelar tu inscripción en este curso?\n\nPodrás volver a inscribirte después.')) {
                window.location.href = '172.20.10.3/CambaNet/public/?action=estudiante/cancelar&id=' + cursoId;
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                setTimeout(() => {
                    bar.style.width = '0%';
                }, 100);
            });
        });
    </script>
</body>
</html>