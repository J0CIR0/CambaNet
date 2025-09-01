<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-right">
            <div class="auth-header">
                <h1><?php echo SITE_NAME; ?></h1>
                <p>Ingresa a tu cuenta</p>
            </div>
            
            <?php if (isset($errorMessages['general'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errorMessages['general']); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo url('login'); ?>" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" id="email" name="email" 
                           placeholder="correo@ejemplo.com" 
                           value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>"
                           class="form-control <?php echo isset($errorMessages['email']) ? 'error' : ''; ?>" 
                           required>
                    <?php if (isset($errorMessages['email'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['email']); ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Ingresa tu contraseña" 
                           class="form-control <?php echo isset($errorMessages['password']) ? 'error' : ''; ?>" 
                           required>
                    <?php if (isset($errorMessages['password'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['password']); ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">
                    Ingresar
                </button>
            </form>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <div style="text-align: center;">
                    <a href="<?php echo url('register'); ?>" style="display: block; margin: 10px 0; color: var(--primary-color); text-decoration: none;">
                        Crear cuenta nueva
                    </a>
                    <a href="<?php echo url('forgot-password'); ?>" style="display: block; margin: 10px 0; color: var(--text-light); text-decoration: none;">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
