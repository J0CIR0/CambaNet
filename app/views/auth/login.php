<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plataforma Educativa</title>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <h2>CambaNet</h2>
            <em>Inspirando a Través del Código</em>
            <div class="platform-features">
                <div class="feature">
                    <div class="feature-badge"></div>
                    <div>Ingresa</div>
                </div>
                <div class="feature">
                    <div class="feature-badge"></div>
                    <div>Aprende</div>
                </div>
                <div class="feature">
                    <div class="feature-badge"></div>
                    <div>Practica</div>
                </div>
            </div>
        </div>
        <div class="login-right">
            <div class="login-header">
                <h1>Iniciar Sesión</h1>
                <p>Ingresa a tu cuenta</p>
            </div>
            <?php if (isset($errorMessages['general'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errorMessages['general']); ?>
                </div>
            <?php endif; ?>
            <form action="172.20.10.3/CambaNet/public/?action=login" method="POST">
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" 
                           placeholder="Ingresa tu correo electrónico" 
                           value="<?php echo isset($formData['email']) ? htmlspecialchars($formData['email']) : ''; ?>"
                           class="<?php echo isset($errorMessages['email']) ? 'input-error' : ''; ?>" 
                           required>
                    <?php if (isset($errorMessages['email'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['email']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" 
                           placeholder="Ingresa tu contraseña" 
                           class="<?php echo isset($errorMessages['password']) ? 'input-error' : ''; ?>" 
                           required>
                    <?php if (isset($errorMessages['password'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['password']); ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-login">Ingresar</button>
            </form>
            <div class="divider"></div>
            <div class="login-links">
                <a href="172.20.10.3/CambaNet/public/?action=register">¿No tienes cuenta? Regístrate aquí</a>
                <a href="172.20.10.3/CambaNet/public/?action=forgot-password">¿Olvidaste tu contraseña? Recupérala aquí</a>
            </div>
        </div>
    </div>
</body>
</html>