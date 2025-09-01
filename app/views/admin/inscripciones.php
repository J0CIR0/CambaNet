<?php
$this->checkAdminAuth();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inscripciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="text-center py-4">
            <h4>Sistema de Gestión</h4>
            <p class="text-muted small">Panel de Administración</p>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="172.20.10.3/CambaNet/public/?action=admin/dashboard">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a class="nav-link" href="172.20.10.3/CambaNet/public/?action=admin/usuarios">
                <i class="fas fa-users me-2"></i>Usuarios
            </a>
            <a class="nav-link" href="172.20.10.3/CambaNet/public/?action=admin/profesores">
                <i class="fas fa-chalkboard-teacher me-2"></i>Profesores
            </a>
            <a class="nav-link" href="172.20.10.3/CambaNet/public/?action=admin/estudiantes">
                <i class="fas fa-graduation-cap me-2"></i>Estudiantes
            </a>
            <a class="nav-link" href="172.20.10.3/CambaNet/public/?action=admin/cursos">
                <i class="fas fa-book me-2"></i>Cursos
            </a>
            <a class="nav-link active" href="172.20.10.3/CambaNet/public/?action=admin/inscripciones">
                <i class="fas fa-clipboard-list me-2"></i>Inscripciones
            </a>
            <a class="nav-link" href="172.20.10.3/CambaNet/public/?action=logout">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
            </a>
        </nav>
    </div>
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2><i class="fas fa-clipboard-list me-2"></i>Gestión de Inscripciones</h2>
                        <span class="text-muted">Bienvenido, <?php echo $_SESSION['user_nombre']; ?></span>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h5>Todas las Inscripciones del Sistema</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($inscripciones)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5>No hay inscripciones registradas</h5>
                            <p class="text-muted">Los estudiantes se pueden inscribir a los cursos disponibles.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Estudiante</th>
                                        <th>Email</th>
                                        <th>Curso</th>
                                        <th>Profesor</th>
                                        <th>Fecha Inscripción</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inscripciones as $inscripcion): ?>
                                    <tr>
                                        <td><?php echo $inscripcion['id']; ?></td>
                                        <td><?php echo htmlspecialchars($inscripcion['estudiante_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($inscripcion['estudiante_email']); ?></td>
                                        <td><?php echo htmlspecialchars($inscripcion['curso_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($inscripcion['profesor_nombre']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($inscripcion['fecha_inscripcion'])); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo strtolower($inscripcion['estado']); ?>">
                                                <?php echo ucfirst($inscripcion['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="eliminarInscripcion(<?php echo $inscripcion['id']; ?>)">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function eliminarInscripcion(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta inscripción?\n\nEsta acción no se puede deshacer.')) {
                window.location.href = '172.20.10.3/CambaNet/public/?action=admin/eliminar-inscripcion&id=' + id;
            }
        }
    </script>
</body>
</html>