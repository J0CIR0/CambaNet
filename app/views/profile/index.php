<?php
$user = $data['user'] ?? [];
$errors = $data['errors'] ?? [];
$user_nombre = $data['user_nombre'] ?? '';
$user_email = $data['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - CambaNet</title>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>Mi Perfil</h1>
            <p>Gestiona tu información personal y configuración de seguridad</p>
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
        <div class="profile-card">
            <h2>Información Personal</h2>
            <form action="172.20.10.3/CambaNet/public/?action=profile/update" method="POST">
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
                <div class="form-group">
                    <label class="form-label">Rol</label>
                    <input type="text" class="form-control" 
                           value="<?php echo htmlspecialchars($user['rol_nombre'] ?? ''); ?>" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Estado de verificación</label>
                    <input type="text" class="form-control" 
                           value="<?php echo $user['verificado'] ? 'Verificado' : 'No verificado'; ?>" disabled>
                </div>
                <button type="submit" class="btn btn-primary">Actualizar Perfil</button>
            </form>
        </div>
        <div class="profile-card">
            <h2>Seguridad y Autenticación</h2>
            <div class="security-status <?php echo $user['habilitar_2fa'] ? 'security-enabled' : 'security-disabled'; ?>">
                <div>
                    <h3>Verificación en Dos Pasos (2FA)</h3>
                    <p><?php echo $user['habilitar_2fa'] ? 'HABILITADA' : 'DESHABILITADA'; ?></p>
                </div>
                <form action="172.20.10.3/CambaNet/public/?action=profile/toggle-2fa" method="POST">
                    <label class="switch">
                        <input type="checkbox" name="habilitar_2fa" <?php echo $user['habilitar_2fa'] ? 'checked' : ''; ?> 
                               onchange="this.form.submit()">
                        <span class="slider"></span>
                    </label>
                </form>
            </div>
            <p>La verificación en dos pasos añade una capa adicional de seguridad a tu cuenta. 
               Cuando esté habilitada, necesitarás ingresar un código de verificación además de tu contraseña.</p>
            <?php if ($user['habilitar_2fa']): ?>
                <div class="alert alert-success">
                    <strong>Protección activa:</strong> Tu cuenta está protegida con verificación en dos pasos.
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <strong>Protección recomendada:</strong> Te recomendamos habilitar la verificación en dos pasos para mayor seguridad.
                </div>
            <?php endif; ?>
        </div>
        <div class="profile-card">
            <h2>Acciones Rápidas</h2>
            <div class="actions">
                <a href="172.20.10.3/CambaNet/public/?action=logout" class="btn btn-danger">Cerrar Sesión</a>
                <?php if ($_SESSION['user_role'] == 1): ?>
                    <a href="172.20.10.3/CambaNet/public/?action=admin/dashboard" class="btn btn-primary">Panel de Administración</a>
                <?php elseif ($_SESSION['user_role'] == 2): ?>
                    <a href="172.20.10.3/CambaNet/public/?action=profesor/dashboard" class="btn btn-primary">Panel de Profesor</a>
                <?php else: ?>
                    <a href="172.20.10.3/CambaNet/public/?action=estudiante/dashboard" class="btn btn-primary">Panel de Estudiante</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="profile-card">
        <h2>Gestión de Suscripciones</h2>
        <?php if ($suscripcion_actual): ?>
        <div class="current-subscription">
            <h3>Tu Plan Actual</h3>
            <div class="subscription-card active">
                <h4><?php echo htmlspecialchars($suscripcion_actual['nombre']); ?></h4>
                <p><?php echo htmlspecialchars($suscripcion_actual['descripcion']); ?></p>
                <div class="subscription-details">
                    <p><strong>Límite de sesiones:</strong> <?php echo $suscripcion_actual['max_sesiones']; ?> dispositivos</p>
                    <p><strong>Precio:</strong> Bol <?php echo number_format($suscripcion_actual['precio'], 2); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="available-subscriptions">
            <h3>Planes Disponibles</h3>
            <div class="subscriptions-grid">
                <?php foreach ($suscripciones as $suscripcion): ?>
                <div class="subscription-card">
                    <h4><?php echo htmlspecialchars($suscripcion['nombre']); ?></h4>
                    <p class="price">Bol <?php echo number_format($suscripcion['precio'], 2); ?></p>
                    <p class="sessions"><?php echo $suscripcion['max_sesiones']; ?> sesiones concurrentes</p>
                    <p class="description"><?php echo htmlspecialchars($suscripcion['descripcion']); ?></p>
                    <?php if ($suscripcion_actual && $suscripcion_actual['id'] == $suscripcion['id']): ?>
                        <button class="btn btn-current" disabled>Plan Actual</button>
                    <?php else: ?>
                        <form action="172.20.10.3/CambaNet/public/?action=profile/comprar-suscripcion" method="POST">
                            <input type="hidden" name="suscripcion_id" value="<?php echo $suscripcion['id']; ?>">
                            <button type="submit" class="btn btn-primary">Seleccionar Plan</button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
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