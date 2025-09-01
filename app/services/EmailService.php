<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class EmailService {
    private $host = 'smtp.gmail.com';
    private $port = 587;
    private $username = 'clarosrocajosue@gmail.com';
    private $password = 'yljowzoaufayfjjw';
    private $fromEmail = 'clarosrocajosue@gmail.com';
    private $fromName = 'CambaNet';
    public function sendVerificationEmail($toEmail, $toName, $token) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->username;
            $mail->Password = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->port;
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = 'Verifica tu cuenta - Sistema de Gestión de Usuarios';
            $verificationUrl = "http://172.20.10.3/CambaNet/public/?action=verify&token=" . $token;
            $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='utf-8'>
                    <title>Verifica tu cuenta</title>
                </head>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;'>
                        <h2 style='color: #333;'>Verificación de Cuenta</h2>
                        <p>Hola $toName,</p>
                        <p>Gracias por registrarte en nuestro sistema. Para completar tu registro, por favor verifica tu dirección de correo electrónico haciendo clic en el siguiente enlace:</p>
                        <p style='text-align: center; margin: 30px 0;'>
                            <a href='$verificationUrl' style='background-color: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;'>Verificar mi cuenta</a>
                        </p>
                        <p>Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:</p>
                        <p style='word-break: break-all; color: #666;'>$verificationUrl</p>
                        <p>Este enlace expirará en 24 horas por motivos de seguridad.</p>
                        <p>Si no has creado una cuenta en nuestro sistema, por favor ignora este mensaje.</p>
                        <hr>
                        <p style='color: #999; font-size: 12px;'>Este es un mensaje automático, por favor no respondas a este correo.</p>
                    </div>
                </body>
                </html>
            ";
            $mail->AltBody = "Hola $toName,\n\nGracias por registrarte. Por favor verifica tu cuenta visitando este enlace: $verificationUrl\n\nEste enlace expirará en 24 horas.";
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            return false;
        }
    }
    public function sendPasswordResetEmail($toEmail, $toName, $token) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->username;
            $mail->Password = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->port;
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña - Sistema de Gestión de Usuarios';
            $resetUrl = "http://172.20.10.3/CambaNet/public/?action=reset-password&token=" . $token;
            $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='utf-8'>
                    <title>Recuperar Contraseña</title>
                </head>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;'>
                        <h2 style='color: #333;'>Recuperación de Contraseña</h2>
                        <p>Hola $toName,</p>
                        <p>Has solicitado restablecer tu contraseña. Para crear una nueva contraseña, haz clic en el siguiente enlace:</p>
                        <p style='text-align: center; margin: 30px 0;'>
                            <a href='$resetUrl' style='background-color: #dc3545; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;'>Restablecer Contraseña</a>
                        </p>
                        <p>Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:</p>
                        <p style='word-break: break-all; color: #666;'>$resetUrl</p>
                        <p><strong>Este enlace expirará en 1 hora por motivos de seguridad.</strong></p>
                        <p>Si no solicitaste restablecer tu contraseña, por favor ignora este mensaje y tu contraseña permanecerá igual.</p>
                        <hr>
                        <p style='color: #999; font-size: 12px;'>Este es un mensaje automático, por favor no respondas a este correo.</p>
                    </div>
                </body>
                </html>
            ";
            $mail->AltBody = "Hola $toName,\n\nPara restablecer tu contraseña, visita este enlace: $resetUrl\n\nEste enlace expirará en 1 hora.\n\nSi no solicitaste esto, ignora este mensaje.";
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo de recuperación: " . $mail->ErrorInfo);
            return false;
        }
    }
    public function send2FACode($toEmail, $toName, $codigo) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->username;
            $mail->Password = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->port;
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = 'Código de verificación en dos pasos - CambaNet';
            $mail->Body = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='utf-8'>
                    <title>Código de verificación</title>
                </head>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd;'>
                        <h2 style='color: #333;'>Código de Verificación</h2>
                        <p>Hola $toName,</p>
                        <p>Tu código de verificación para iniciar sesión es:</p>
                        
                        <div style='text-align: center; margin: 30px 0;'>
                            <div style='font-size: 32px; font-weight: bold; letter-spacing: 10px; 
                                        color: #2c3e50; background: #f8f9fa; padding: 20px; 
                                        border-radius: 8px; display: inline-block;'>
                                $codigo
                            </div>
                        </div>
                        
                        <p><strong>Este código expirará en 5 minutos</strong> por motivos de seguridad.</p>
                        <p>Si no intentaste iniciar sesión, por favor ignora este mensaje.</p>
                        
                        <hr>
                        <p style='color: #999; font-size: 12px;'>Este es un mensaje automático, por favor no respondas a este correo.</p>
                    </div>
                </body>
                </html>
            ";
            $mail->AltBody = "Hola $toName,\n\nTu código de verificación es: $codigo\n\nEste código expirará en 5 minutos.\n\nSi no intentaste iniciar sesión, ignora este mensaje.";
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar código 2FA: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>