<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Plataforma Educativa</title>
</head>
<body>
    <div class="recovery-container">
        <div class="recovery-left">
            <h2>Recupera tu avance</h2>
            <p>Te ayudamos aproteger tu cuenta</p>
            <div class="security-features">
                <div class="feature">
                    <div class="feature-icon"></div>
                    <div>Enlaces de recuperación por correo</div>
                </div>
                <div class="feature">
                    <div class="feature-icon"></div>
                    <div>Protección de datos personales</div>
                </div>
                <div class="feature">
                    <div class="feature-icon"></div>
                    <div>Soporte técnico disponible proximamente xd</div>
                </div>
            </div>
        </div>
        <div class="recovery-right">
            <div class="recovery-header">
                <h1>Recuperar Contraseña</h1>
                <p>Ingresa tu email para recibir el link</p>
            </div>
            <form action="172.20.10.3/CambaNet/public/?action=forgot-password" method="POST">
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico registrado" required>
                </div>
                <button type="submit" class="btn-recovery">Enviar Enlace de Recuperación</button>
            </form>
            <div class="instructions">
                <p>Te enviaremos un enlace seguro a tu correo electrónico para que puedas restablecer tu contraseña.</p>
            </div>
            <div class="divider"></div>
            <div class="recovery-links">
                <a href="172.20.10.3/CambaNet/public/?action=login">← Volver al Inicio de Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>