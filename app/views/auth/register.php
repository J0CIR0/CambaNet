<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-right">
            <div class="auth-header">
                <h1>Crear Cuenta</h1>
                <p>Regístrate en la plataforma</p>
            </div>
            
            <?php if (isset($errorMessages['general'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errorMessages['general']); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo url('register'); ?>" method="POST">
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" 
                           placeholder="Tu nombre completo" 
                           value="<?php echo isset($formData['nombre']) ? htmlspecialchars($formData['nombre']) : ''; ?>"
                           class="form-control <?php echo isset($errorMessages['nombre']) ? 'error' : ''; ?>" 
                           required>
                    <?php if (isset($errorMessages['nombre'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['nombre']); ?></div>
                    <?php endif; ?>
                </div>
                
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
                           placeholder="Crea una contraseña segura" 
                           class="form-control <?php echo isset($errorMessages['password']) ? 'error' : ''; ?>" 
                           required>
                    <?php if (isset($errorMessages['password'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['password']); ?></div>
                    <?php endif; ?>
                </div>

                <div style="background: var(--gray-light); padding: 15px; border-radius: var(--border-radius); margin: 20px 0;">
                    <p style="font-size: 12px; color: var(--text-light); margin: 0;">
                        <strong>Requisitos de contraseña:</strong> Mínimo 8 caracteres, con letras mayúsculas, minúsculas y números.
                    </p>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Registrarse
                </button>
            </form>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <div style="text-align: center;">
                    <a href="<?php echo url('login'); ?>" style="display: block; margin: 10px 0; color: var(--text-light); text-decoration: none;">
                        ¿Ya tienes cuenta? Inicia sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>