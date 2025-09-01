<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación en Dos Pasos - CambaNet</title>
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <h2>Verificación de Seguridad</h2>
            <p>Protegiendo tu cuenta con autenticación en dos pasos</p>
            <div class="security-features">
                <div class="feature">
                    <div>Protección adicional para tu cuenta</div>
                </div>
                <div class="feature">
                    <div>Código válido por 60 segundos</div>
                </div>
                <div class="feature">
                    <div>Enviado a tu correo electrónico</div>
                </div>
            </div>
        </div>
        <div class="timer-container">
            <p>El código expirará en: <span id="countdown">60</span> segundos</p>
            <div style="width: 100%; height: 5px; background: #ecf0f1; border-radius: 3px; margin-top: 5px;">
                <div id="progress-bar" style="width: 100%; height: 100%; background: #3498db; border-radius: 3px; transition: width 1s linear;"></div>
            </div>
        </div>
        <div class="auth-right">
            <div class="auth-header">
                <h1>Verificación en Dos Pasos</h1>
                <p>Ingresa el código de 6 dígitos enviado a tu email</p>
            </div>
            <?php if (isset($errorMessages['general'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errorMessages['general']); ?>
                </div>
            <?php endif; ?>
            <form action="172.20.10.3/CambaNet/public/?action=verify-2fa" method="POST" id="2faForm">
                <div class="form-group">
                    <label for="codigo">Código de verificación</label>
                    <input type="text" id="codigo" name="codigo" 
                           placeholder="000000" 
                           maxlength="6"
                           pattern="\d{6}"
                           title="Ingresa 6 dígitos"
                           class="form-control <?php echo isset($errorMessages['codigo']) ? 'input-error' : ''; ?>" 
                           required
                           autocomplete="off">
                    <?php if (isset($errorMessages['codigo'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['codigo']); ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-verify">Verificar y Continuar</button>
            </form>
            <div style="text-align: center; margin-top: 20px;">
                <p>¿No recibiste el código? <a href="#" onclick="resendCode()">Reenviar código</a></p>
            </div>
            <div style="text-align: center; margin-top: 10px;">
                <a href="172.20.10.3/CambaNet/public/?action=login">Volver al login</a>
            </div>
        </div>
    </div>
    <script>
    let timeLeft = 60;
    const countdownElement = document.getElementById('countdown');
    let timer = null;
    let isExpired = false;
    function startCountdown() {
        if (timer) return;
        timer = setInterval(() => {
            if (isExpired) {
                clearInterval(timer);
                return;
            }
            countdownElement.textContent = timeLeft;
            const progressPercent = (timeLeft / 60) * 100;
            document.getElementById('progress-bar').style.width = progressPercent + '%';
            if (timeLeft <= 10) {
                countdownElement.style.color = '#e74c3c';
                document.getElementById('progress-bar').style.background = '#e74c3c';
            }
            if (timeLeft <= 0) {
                clearInterval(timer);
                isExpired = true;
                countdownElement.textContent = '0';
                document.getElementById('progress-bar').style.width = '0%';
                showExpirationModal();
            }
            timeLeft--;
        }, 1000);
    }
    document.addEventListener('DOMContentLoaded', function() {
        startCountdown();
    });
    function showExpirationModal() {
        const modal = document.createElement('div');
        modal.style.cssText = `
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0,0,0,0.8); display: flex; justify-content: center; 
            align-items: center; z-index: 1000;
        `;
        modal.innerHTML = `
            <div style="background: white; padding: 30px; border-radius: 10px; text-align: center; max-width: 400px;">
                <h3 style="color: #e74c3c; margin-bottom: 15px;">⏰ Código Expirado</h3>
                <p>El código de verificación ha expirado. ¿Deseas recibir un nuevo código?</p>
                <div style="margin-top: 20px;">
                    <button onclick="resendCodeAndCloseModal(this)" 
                            style="background: #3498db; color: white; border: none; padding: 10px 20px; 
                                border-radius: 5px; margin-right: 10px; cursor: pointer;">
                        Sí, enviar nuevo código
                    </button>
                    <button onclick="closeModalAndRedirect(this)" 
                            style="background: #95a5a6; color: white; border: none; padding: 10px 20px; 
                                border-radius: 5px; cursor: pointer;">
                        No, volver al login
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    function closeModalAndRedirect(button) {
        const modal = button.closest('div[style*="position: fixed"]');
        document.body.removeChild(modal);
        window.location.href = '172.20.10.3/CambaNet/public/?action=login';
    }
    function resendCodeAndCloseModal(button) {
        const modal = button.closest('div[style*="position: fixed"]');
        document.body.removeChild(modal);
        resendCode();
    }
    function resendCode() {
        fetch('172.20.10.3/CambaNet/public/?action=resend-2fa', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error al reenviar el código. Intenta recargar la página.');
            }
        })
        .catch(error => {
            alert('Error de conexión. Intenta recargar la página.');
        })
    }
    function resetCountdown() {
        clearInterval(timer);
        timeLeft = 60;
        countdownElement.textContent = timeLeft;
        countdownElement.style.color = '#2c3e50';
        document.getElementById('progress-bar').style.width = '100%';
        document.getElementById('progress-bar').style.background = '#3498db';
        timer = setInterval(updateCountdown, 1000);
        updateCountdown();
    }
    timer = setInterval(updateCountdown, 1000);
    updateCountdown();
    console.log('Bienvenido al sistema de verificación en dos pasos');
    console.log('Se ha enviado un código de 6 dígitos a tu email');
    console.log('tienes 60 segundos para ingresar el código');
    </script>
</body>
</html>