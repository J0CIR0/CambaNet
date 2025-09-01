<?php
$user = $data['user'] ?? [];
$errors = $data['errors'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
$user_email = $data['user_email'] ?? '';
$suscripciones = $data['suscripciones'] ?? [];
$suscripcion_actual = $data['suscripcion_actual'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <div class="admin-brand">
                <h2>Mi Cuenta</h2>
            </div>
            <ul class="admin-menu">
                <?php if ($_SESSION['user_role'] == 1): ?>
                    <li><a href="<?php echo url('admin/dashboard'); ?>">Panel Admin</a></li>
                <?php elseif ($_SESSION['user_role'] == 2): ?>
                    <li><a href="<?php echo url('profesor/dashboard'); ?>">Panel Profesor</a></li>
                <?php else: ?>
                    <li><a href="<?php echo url('estudiante/dashboard'); ?>">Panel Estudiante</a></li>
                <?php endif; ?>
                <li><a href="<?php echo url('profile'); ?>" class="active">Mi Perfil</a></li>
                <li><a href="<?php echo url('logout'); ?>">Cerrar Sesión</a></li>
            </ul>
        </div>

        <div class="admin-main">
            <div class="admin-header">
                <h1>Mi Perfil</h1>
                <div class="user-welcome"><?php echo htmlspecialchars($_SESSION['user_nombre']); ?></div>
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
                    <h3>Información Personal</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo url('profile/update'); ?>" method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" name="nombre" 
                                       value="<?php echo htmlspecialchars($user['nombre'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div class="form-group">
                                <label class="form-label">Rol</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['rol_nombre'] ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Estado</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo ($user['verificado'] ?? false) ? 'Verificado' : 'No verificado'; ?>" disabled>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Seguridad</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h4>Verificación en Dos Pasos (2FA)</h4>
                            <p style="color: var(--text-light); margin: 0;">
                                <?php echo ($user['habilitar_2fa'] ?? false) ? 'HABILITADA' : 'DESHABILITADA'; ?>
                            </p>
                        </div>
                        <form action="<?php echo url('profile/toggle-2fa'); ?>" method="POST">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="habilitar_2fa" 
                                       <?php echo ($user['habilitar_2fa'] ?? false) ? 'checked' : ''; ?> 
                                       onchange="this.form.submit()">
                                <span style="color: var(--text-color);">Activar/Desactivar</span>
                            </label>
                        </form>
                    </div>
                    
                    <p style="color: var(--text-light); font-size: 14px;">
                        La verificación en dos pasos añade una capa adicional de seguridad a tu cuenta. 
                        Cuando esté habilitada, necesitarás ingresar un código de verificación además de tu contraseña.
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Gestión de Suscripciones</h3>
                </div>
                <div class="card-body">
                    <?php if ($suscripcion_actual): ?>
                    <div style="margin-bottom: 30px;">
                        <h4>Plan Actual</h4>
                        <div style="border: 1px solid var(--border-color); padding: 20px; border-radius: var(--border-radius);">
                            <h5><?php echo htmlspecialchars($suscripcion_actual['nombre']); ?></h5>
                            <p style="color: var(--text-light); margin: 10px 0;">
                                <?php echo htmlspecialchars($suscripcion_actual['descripcion']); ?>
                            </p>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                                <div>
                                    <strong>Sesiones:</strong> <?php echo $suscripcion_actual['max_sesiones']; ?>
                                </div>
                                <div>
                                    <strong>Precio:</strong> Bol <?php echo number_format($suscripcion_actual['precio'], 2); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <h4>Planes Disponibles</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                        <?php foreach ($suscripciones as $suscripcion): ?>
                        <div style="border: 1px solid var(--border-color); padding: 20px; border-radius: var(--border-radius);">
                            <h5><?php echo htmlspecialchars($suscripcion['nombre']); ?></h5>
                            <p style="color: var(--primary-color); font-size: 1.5rem; font-weight: bold; margin: 10px 0;">
                                Bol <?php echo number_format($suscripcion['precio'], 2); ?>
                            </p>
                            <p style="color: var(--text-light); margin: 10px 0;">
                                <?php echo htmlspecialchars($suscripcion['descripcion']); ?>
                            </p>
                            <div style="margin: 15px 0;">
                                <strong>Sesiones concurrentes:</strong> <?php echo $suscripcion['max_sesiones']; ?>
                            </div>
                            <?php if ($suscripcion_actual && $suscripcion_actual['id'] == $suscripcion['id']): ?>
                                <button class="btn btn-secondary" disabled>Plan Actual</button>
                            <?php else: ?>
                                <form action="<?php echo url('profile/comprar-suscripcion'); ?>" method="POST">
                                    <input type="hidden" name="suscripcion_id" value="<?php echo $suscripcion['id']; ?>">
                                    <button type="submit" class="btn btn-primary">Seleccionar Plan</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
                    <div class="card">
            <div class="card-header">
                <h3>Sesiones Activas</h3>
            </div>
            <div class="card-body">
                <?php
                $sesionesData = [
                    'sesiones' => $data['sesiones'] ?? [],
                    'user_nombre' => $user_nombre,
                    'max_sesiones' => $data['max_sesiones'] ?? 1
                ];
                include __DIR__ . '/sessions.php';
                ?>
            </div>
        </div>
        </div>

    </div>

    <script>
    document.querySelectorAll('input[name="habilitar_2fa"]').forEach(checkbox => {
        checkbox.addEventListener('change', function(e) {
            const action = this.checked ? 'habilitar' : 'deshabilitar';
            if (!confirm(`¿Estás seguro de que quieres ${action} la verificación en dos pasos?`)) {
                e.preventDefault();
                this.checked = !this.checked;
            }
        });
    });
    </script>
</body>
</html>