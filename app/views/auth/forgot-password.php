<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-right">
            <div class="auth-header">
                <h1>Recuperar Contraseña</h1>
                <p>Ingresa tu email para recibir el enlace de recuperación</p>
            </div>
            
            <form action="<?php echo url('forgot-password'); ?>" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" id="email" name="email" 
                           placeholder="correo@ejemplo.com" 
                           class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Enviar Enlace de Recuperación
                </button>
            </form>

            <div style="background: var(--gray-light); padding: 15px; border-radius: var(--border-radius); margin: 20px 0;">
                <p style="font-size: 12px; color: var(--text-light); margin: 0;">
                    Te enviaremos un enlace seguro a tu correo electrónico para restablecer tu contraseña.
                </p>
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <div style="text-align: center;">
                    <a href="<?php echo url('login'); ?>" style="display: block; margin: 10px 0; color: var(--text-light); text-decoration: none;">
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>