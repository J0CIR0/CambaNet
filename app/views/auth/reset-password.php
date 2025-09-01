<?php
$token = $data['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - Plataforma Educativa</title>
</head>
<body>
    <div class="password-container">
        <div class="password-left">
            <h2>Seguridad de Cuenta</h2>
            <p>Establece una contraseña segura para proteger tu información educativa</p>
            <div class="security-tips">
                <div class="tip">
                    <div class="tip-icon">!</div>
                    <div>No reutilices contraseñas de otros servicios</div>
                </div>
                <div class="tip">
                    <div class="tip-icon">!</div>
                    <div>Actualiza tu contraseña regularmente</div>
                </div>
                <div class="tip">
                    <div class="tip-icon">!</div>
                    <div>No compartas tu contraseña con nadie</div>
                </div>
            </div>
        </div>
        <div class="password-right">
            <div class="password-header">
                <h1>Crear Nueva Contraseña</h1>
                <p>Establece una nueva contraseña para tu cuenta</p>
            </div>
            <form action="172.20.10.3/CambaNet/public/?action=reset-password" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu nueva contraseña" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repite tu nueva contraseña" required minlength="6">
                </div>
                <div class="password-requirements">
                    <strong>La nueva contraseña debe contener:</strong>
                    <ul>
                        <li>Mínimo 8 caracteres</li>
                        <li>Al menos una letra mayúscula (A-Z)</li>
                        <li>Al menos una letra minúscula (a-z)</li>
                        <li>Al menos un número (0-9)</li>
                    </ul>
                </div>
                <button type="submit" class="btn-update">Actualizar Contraseña</button>
            </form>
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <div class="divider"></div>
            <div class="password-links">
                <a href="172.20.10.3/CambaNet/public/?action=login">← Volver al Inicio de Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>