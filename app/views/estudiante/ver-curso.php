<?php
$this->checkEstudianteAuth();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($curso['nombre']); ?> - Panel de Estudiante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="text-center py-4">
            <h4>Panel del Estudiante</h4>
            <p class="text-muted small"><?php echo $user_nombre; ?></p>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link" href="172.20.10.3/CambaNet/public/?action=estudiante/dashboard">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a class="nav-link active" href="#">
                <i class="fas fa-book me-2"></i><?php echo htmlspecialchars(substr($curso['nombre'], 0, 15)) . '...'; ?>
            </a>
            <a class="nav-link" href="172.20.10.3/CambaNet/public/?action=logout">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
            </a>
        </nav>
    </div>
    <div class="main-content">
        <div class="container-fluid">
            <div class="course-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1><i class="fas fa-book me-2"></i><?php echo htmlspecialchars($curso['nombre']); ?></h1>
                        <p class="mb-0">Impartido por: <strong><?php echo htmlspecialchars($profesor['nombre']); ?></strong></p>
                    </div>
                    <a href="172.20.10.3/CambaNet/public/?action=estudiante/dashboard" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
                    </a>
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
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-info-circle me-2"></i>Descripción del Curso</h5>
                        </div>
                        <div class="card-body">
                            <p><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-user-tie me-2"></i>Profesor:</strong> <?php echo htmlspecialchars($profesor['nombre']); ?></p>
                                    <p><strong><i class="fas fa-envelope me-2"></i>Email:</strong> <?php echo htmlspecialchars($profesor['email']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-calendar me-2"></i>Inscrito desde:</strong> <?php echo date('d/m/Y', strtotime($curso['fecha_inscripcion'])); ?></p>
                                    <p><strong><i class="fas fa-star me-2"></i>Estado:</strong> 
                                        <span class="badge bg-success"><?php echo ucfirst($curso['estado']); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-bar me-2"></i>Estadísticas</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col-6">
                                    <div class="stat">
                                        <h3><?php echo count($material); ?></h3>
                                        <p class="text-muted">Materiales</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat">
                                        <h3>0</h3>
                                        <p class="text-muted">Progreso</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-file-alt me-2"></i>Material de Estudio</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($material)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5>No hay material disponible</h5>
                            <p class="text-muted">El profesor aún no ha subido material para este curso.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($material as $item): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card file-card h-100">
                                    <div class="card-body text-center">
                                        <?php 
                                        $icono = 'file';
                                        $color = 'text-primary';
                                        switch($item['tipo_archivo']) {
                                            case 'pdf': $icono = 'file-pdf'; $color = 'text-danger'; break;
                                            case 'doc': $icono = 'file-word'; $color = 'text-primary'; break;
                                            case 'ppt': $icono = 'file-powerpoint'; $color = 'text-warning'; break;
                                            case 'image': $icono = 'file-image'; $color = 'text-success'; break;
                                            case 'video': $icono = 'file-video'; $color = 'text-info'; break;
                                        }
                                        ?>
                                        <i class="fas fa-<?php echo $icono; ?> file-icon <?php echo $color; ?> mb-3"></i>
                                        <h6 class="card-title"><?php echo htmlspecialchars($item['titulo']); ?></h6>
                                        <p class="card-text small text-muted">
                                            <?php echo htmlspecialchars(substr($item['descripcion'], 0, 80)); ?>...
                                        </p>
                                        <p class="small mb-1">
                                            <strong>Tipo:</strong> <?php echo strtoupper($item['tipo_archivo']); ?>
                                        </p>
                                        <p class="small mb-2">
                                            <strong>Subido:</strong> <?php echo date('d/m/Y', strtotime($item['fecha_creacion'])); ?>
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-grid">
                                            <a href="/CambaNet/uploads/material/<?php echo basename($item['archivo_ruta']); ?>" 
                                               class="btn btn-sm btn-primary" download>
                                                <i class="fas fa-download me-1"></i> Descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-tasks me-2"></i>Próximas Actividades</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-3">
                                <i class="fas fa-calendar-plus fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No hay actividades programadas</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-line me-2"></i>Tu Progreso</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-3">
                                <i class="fas fa-spinner fa-2x text-muted mb-3"></i>
                                <p class="text-muted">Sistema de progreso en desarrollo</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>