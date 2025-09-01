<?php
$inscripciones = $inscripciones ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Panel de Administración</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="<?php echo url('admin/dashboard'); ?>">Dashboard</a></li>
                <li><a href="<?php echo url('admin/usuarios'); ?>">Usuarios</a></li>
                <li><a href="<?php echo url('admin/profesores'); ?>">Profesores</a></li>
                <li><a href="<?php echo url('admin/estudiantes'); ?>">Estudiantes</a></li>
                <li><a href="<?php echo url('admin/cursos'); ?>">Cursos</a></li>
                <li><a href="<?php echo url('admin/inscripciones'); ?>" class="active">Inscripciones</a></li>
                <li><a href="<?php echo url('profile'); ?>">Mi Perfil</a></li>
            </ul>
            <div class="logout-section">
                <a href="<?php echo url('logout'); ?>" class="logout-link">Cerrar Sesión</a>
            </div>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Gestión de Inscripciones</h1>
                <div class="user-welcome">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3>Todas las Inscripciones</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($inscripciones)): ?>
                        <div style="text-align: center; padding: 40px;">
                            <p>No hay inscripciones registradas</p>
                        </div>
                    <?php else: ?>
                        <div style="overflow-x: auto;">
                            <table class="table">
                                <thead>
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
                                            <span class="badge">
                                                <?php echo ucfirst($inscripcion['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button onclick="eliminarInscripcion(<?php echo $inscripcion['id']; ?>)" class="btn btn-sm btn-danger">Eliminar</button>
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
    function eliminarInscripcion(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta inscripción?\n\nEsta acción no se puede deshacer.')) {
            window.location.href = '<?php echo url('admin/eliminar-inscripcion'); ?>&id=' + id;
        }
    }
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                closeModal(modal.id);
            }
        });
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (modal.style.display === 'block') {
                    closeModal(modal.id);
                }
            });
        }
    });
    </script>
</body>
</html>