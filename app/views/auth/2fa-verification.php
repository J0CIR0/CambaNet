<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset('css/styles.css'); ?>">
</head>
<body>
    <div class="auth-container">
        <div class="auth-right">
            <div style="background: var(--gray-light); padding: 15px; border-radius: var(--border-radius); margin-bottom: 20px;">
                <p style="margin: 0; text-align: center; color: var(--text-light);">
                    Código válido por: <span id="countdown" style="font-weight: bold; color: var(--primary-color);">60</span> segundos
                </p>
            </div>
            
            <div class="auth-header">
                <h1>Verificación de Seguridad</h1>
                <p>Ingresa el código de 6 dígitos enviado a tu email</p>
            </div>
            
            <?php if (isset($errorMessages['general'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($errorMessages['general']); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo url('verify-2fa'); ?>" method="POST" id="2faForm">
                <div class="form-group">
                    <label for="codigo" class="form-label">Código de verificación</label>
                    <input type="text" id="codigo" name="codigo" 
                           placeholder="000000" 
                           maxlength="6"
                           pattern="\d{6}"
                           title="Ingresa 6 dígitos"
                           class="form-control <?php echo isset($errorMessages['codigo']) ? 'error' : ''; ?>" 
                           required
                           autocomplete="off"
                           style="text-align: center; font-size: 20px; letter-spacing: 10px;">
                    <?php if (isset($errorMessages['codigo'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errorMessages['codigo']); ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Verificar y Continuar
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <p style="color: var(--text-light);">
                    ¿No recibiste el código? 
                    <a href="#" onclick="resendCode()" style="color: var(--primary-color); text-decoration: none;">
                        Reenviar código
                    </a>
                </p>
            </div>
            
            <div style="text-align: center; margin-top: 10px;">
                <a href="<?php echo url('login'); ?>" style="color: var(--text-light); text-decoration: none; font-size: 14px;">
                    Volver al login
                </a>
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
            
            if (timeLeft <= 10) {
                countdownElement.style.color = 'var(--text-light)';
            }
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                isExpired = true;
                countdownElement.textContent = '0';
                showExpirationModal();
            }
            
            timeLeft--;
        }, 1000);
    }

    function showExpirationModal() {
        if (confirm('El código ha expirado. ¿Deseas recibir un nuevo código?')) {
            resendCode();
        } else {
            window.location.href = '<?php echo url('login'); ?>';
        }
    }

    function resendCode() {
        fetch('<?php echo url('resend-2fa'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Código reenviado. Revisa tu email.');
                window.location.reload();
            } else {
                alert('Error al reenviar el código. Intenta recargar la página.');
            }
        })
        .catch(error => {
            alert('Error de conexión. Intenta recargar la página.');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        startCountdown();
        document.getElementById('codigo').focus();
        
        const codeInput = document.getElementById('codigo');
        codeInput.addEventListener('input', function(e) {
            if (this.value.length === 6) {
                document.getElementById('2faForm').submit();
            }
        });
    });
    </script>
</body>
</html>