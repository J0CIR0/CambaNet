<?php
$token = $data['token'] ?? '';
$error = $data['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-right">
            <div class="auth-header">
                <h1>Nueva Contraseña</h1>
                <p>Establece una nueva contraseña para tu cuenta</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo url('reset-password'); ?>" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group">
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Ingresa tu nueva contraseña" 
                           class="form-control" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           placeholder="Repite tu nueva contraseña" 
                           class="form-control" required minlength="8">
                </div>

                <div style="background: var(--gray-light); padding: 15px; border-radius: var(--border-radius); margin: 20px 0;">
                    <p style="font-size: 12px; color: var(--text-light); margin: 0 0 10px 0;">
                        <strong>La contraseña debe contener:</strong>
                    </p>
                    <ul style="font-size: 12px; color: var(--text-light); margin: 0; padding-left: 20px;">
                        <li>Mínimo 8 caracteres</li>
                        <li>Al menos una letra mayúscula</li>
                        <li>Al menos una letra minúscula</li>
                        <li>Al menos un número</li>
                    </ul>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Actualizar Contraseña
                </button>
            </form>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <div style="text-align: center;">
                    <a href="<?php echo url('login'); ?>" style="display: block; margin: 10px 0; color: var(--text-light); text-decoration: none;">
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            form.addEventListener('submit', function(e) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden');
                    confirmPassword.focus();
                }
            });
        });
    </script>
</body>
</html>