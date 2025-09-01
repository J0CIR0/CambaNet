<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Plataforma Educativa</title>
</head>
<body>
    <div class="register-container">
        <div class="register-left">
            <h2>Únete a nosotros</h2>
            <p>Crea una cuenta para acceder a todos los cursos</p>
            <div class="benefits">
                <div class="benefit">
                    <div class="benefit-icon"></div>
                    <div>Acceso a materiales de estudio</div>
                </div>
                <div class="benefit">
                    <div class="benefit-icon"></div>
                    <div>Seguimiento de tu progreso</div>
                </div>
                <div class="benefit">
                    <div class="benefit-icon"></div>
                    <div>Aprende con la prácica</div>
                </div>
            </div>
        </div>
        <div class="register-right">
            <div class="register-header">
                <h1>Crear Cuenta</h1>
                <p>Completa tus datos para registrarte en la plataforma</p>
            </div>
            <?php if (isset($errorMessages['general'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errorMessages['general']); ?>
                </div>
            <?php endif; ?>
            <form action="172.20.10.3/CambaNet/public/?action=register" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" 
                           placeholder="Ingresa tu nombre completo" 
                           value="<?php echo isset($formData['nombre']) ? htmlspecialchars($formData['nombre']) : ''; ?>"
                           class="<?php echo isset($errorMessages['nombre']) ? 'input-error' : ''; ?>" 
                           required>
                    <?php if (isset($errorMessages['nombre'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['nombre']); ?></div>
                    <?php endif; ?>
                </div>
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
                           placeholder="Crea una contraseña segura" 
                           class="<?php echo isset($errorMessages['password']) ? 'input-error' : ''; ?>" 
                           required>
                    <?php if (isset($errorMessages['password'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['password']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="password-requirements">
                    <strong>La contraseña debe contener:</strong>
                    <ul>
                        <li>Al menos 8 caracteres</li>
                        <li>Recomendado: letras mayúsculas, minúsculas y números</li>
                    </ul>
                </div>
                <button type="submit" class="btn-register">Registrarse</button>
            </form>
            <div class="divider"></div>
            <div class="register-links">
                <p>¿Ya tienes una cuenta? <a href="172.20.10.3/CambaNet/public/?action=login">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>