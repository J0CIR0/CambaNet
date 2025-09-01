<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../services/EmailService.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../models/SessionModel.php';
class AuthController extends BaseController {
    public function showLoginForm($errors = [], $data = []) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['user_role']);
            return;
        }
        $errorMessages = $errors;
        $formData = $data;
        $this->renderView('auth/login.php', compact('errorMessages', 'formData'));
    }
    public function login($data) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLoginForm();
            return;
        }
        $errors = $this->validateLogin($data);
        if (!empty($errors)) {
            $this->showLoginForm($errors, $data);
            return;
        }
        $usuarioModel = new UsuarioModel();
        $user = $usuarioModel->getUserByEmail($data['email']);
        if (!$user) {
            $errors['general'] = 'No tienes cuenta?, registrate';
            $this->showLoginForm($errors, $data);
            return;
        }
        if (!password_verify($data['password'], $user['password'])) {
            $errors['general'] = 'Contraseña incorrecta';
            $this->showLoginForm($errors, $data);
            return;
        }
        if (!$user['verificado']) {
            $errors['general'] = 'Verifica tu correo con el enlace de verificacion';
            $this->showLoginForm($errors, $data);
            return;
        }
        $sessionModel = new SessionModel();
        if (!$sessionModel->puedeIniciarSesion($user['id'])) {
            $max_sesiones = $sessionModel->getMaxSesionesPermitidas($user['id']);
            $errors['general'] = "Límite de sesiones alcanzado. Máximo $max_sesiones sesiones concurrentes permitidas.";
            $this->showLoginForm($errors, $data);
            return;
        }
        if ($usuarioModel->tiene2FAHabilitado($user['id'])) {
            $codigo = $usuarioModel->generarCodigo2FA($user['id']);
            if ($codigo) {
                $emailService = new EmailService();
                $emailService->send2FACode($user['email'], $user['nombre'], $codigo);
                $this->show2FAForm($user['id']);
                return;
            } else {
                $errors['general'] = 'Error al generar el código de verificación';
                $this->showLoginForm($errors, $data);
                return;
            }
        }
        $this->completeLogin($user);
    }
    private function completeLogin($user) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $session_id = session_id();
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $sessionModel = new SessionModel();
        if ($sessionModel->registrarSesion($user['id'], $session_id, $ip_address, $user_agent)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['rol_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['session_registered'] = true;
            $this->redirectToDashboard($user['rol_id']);
        } else {
            $errors['general'] = 'Error al iniciar sesión';
            $this->showLoginForm($errors, $_POST);
        }
    }
    private function validateLogin($data) {
        $errors = [];
        if (empty($data['email'])) {
            $errors['email'] = 'Ingresa el correo electronico';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'ingresa un corro valido';
        }
        if (empty($data['password'])) {
            $errors['password'] = 'falta la contraseña';
        }
        return $errors;
    }
    public function showRegisterForm() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['user_role']);
            return;
        }
        $this->renderView('auth/register.php');
    }
    public function register($data) {
        try {
            $errors = $this->validateRegistration($data);
            if (!empty($errors)) {
                $this->showRegisterFormWithErrors($errors, $data);
                return;
            }
            $usuarioModel = new UsuarioModel();
            $result = $usuarioModel->createUser($data);
            if ($result['success']) {
                $emailService = new EmailService();
                $emailSent = $emailService->sendVerificationEmail(
                    $data['email'], 
                    $data['nombre'], 
                    $result['token']
                );
                if ($emailSent) {
                    echo "<script>
                            alert('Registro exitoso, verifica tu cuenta');
                            window.location.href = '" . url('login') . "';
                        </script>";
                } else {
                    echo "<script>
                            alert('Registro exitoso, pero no se pudo enviar el correo, revisa tu conexion');
                            window.location.href = '" . url('login') . "';
                        </script>";
                }
                exit();
            }
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'correo electrónico ya existe') !== false) {
                $errors = ['email' => 'El correo electrónico ya existe'];
                $this->showRegisterFormWithErrors($errors, $data);
                return;
            }
            error_log("Error en registro: " . $e->getMessage());
            $errors = ['general' => 'Error al registrar, por favor intenta de nuevo'];
            $this->showRegisterFormWithErrors($errors, $data);
        }
    }
    private function validateRegistration($data) {
        $errors = [];
        if (empty($data['nombre'])) {
            $errors['nombre'] = 'El nombre es requerido';
        } elseif (strlen(trim($data['nombre'])) < 2) {
            $errors['nombre'] = 'El nombre debe tener al menos 2 letras';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'Ingresa el correo';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Ingresa un formato valido de correo';
        }
        if (empty($data['password'])) {
            $errors['password'] = 'Ingresa la contraseña';
        } else {
            $passwordValidation = Validator::validatePassword($data['password']);
            if ($passwordValidation !== true) {
                $errors['password'] = $passwordValidation;
            }
        }
        return $errors;
    }
    public function resend2FACode() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['2fa_user_id'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Sesión no válida']);
        }
        $user_id = $_SESSION['2fa_user_id'];
        $usuarioModel = new UsuarioModel();
        $user = $usuarioModel->getUserById($user_id);
        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'Usuario no encontrado']);
        }
        $codigo = $usuarioModel->generarCodigo2FA($user_id);
        if ($codigo) {
            $emailService = new EmailService();
            $emailSent = $emailService->send2FACode($user['email'], $user['nombre'], $codigo);
            if ($emailSent) {
                $this->jsonResponse(['success' => true, 'message' => 'Código reenviado']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Error al enviar email']);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Error al generar código']);
        }
    }
    private function showRegisterFormWithErrors($errors, $data = []) {
        $errorMessages = $errors;
        $formData = $data;
        $this->renderView('auth/register.php', compact('errorMessages', 'formData'));
    }
    public function verifyEmail($token) {
        error_log("Intentando verificar token: " . $token);
        if (empty($token)) {
            echo "<script>
                    alert('Token de verificación no proporcionado');
                    window.location.href = '" . url('login') . "';
                </script>";
            exit();
        }
        $usuarioModel = new UsuarioModel();
        $verified = $usuarioModel->verifyUser($token);
        if ($verified) {
            echo "<script>
                    alert('cuenta verificada, puedes iniciar sesion');
                    window.location.href = '" . url('login') . "';
                </script>";
        } else {
            echo "<script>
                    alert('el enlace ya expiró');
                    window.location.href = '" . url('login') . "';
                </script>";
        }
        exit();
    }
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id']) && isset($_SESSION['session_registered'])) {
            $sessionModel = new SessionModel();
            $sessionModel->cerrarSesion(session_id());
        }
        session_unset();
        session_destroy();
        redirect('login');
    }
    private function redirectToDashboard($rol_id) {
        $rolePath = [
            1 => 'admin/dashboard',
            2 => 'profesor/dashboard',
            3 => 'estudiante/dashboard'
        ];
        if (isset($rolePath[$rol_id])) {
            redirect($rolePath[$rol_id]);
        } else {
            die("Error: Rol no válido");
        }
    }
    public function showForgotPasswordForm() {
        $this->renderView('auth/forgot-password.php');
    }
    public function showResetPasswordForm($token) {
        $usuarioModel = new UsuarioModel();
        $user_id = $usuarioModel->validateResetToken($token);
        if ($user_id) {
            $data['token'] = $token;
            $this->renderView('auth/reset-password.php', $data);
        } else {
            echo "<script>
                    alert('enlace expirado');
                    window.location.href = '" . url('login') . "';
                </script>";
        }
    }
    public function processForgotPassword($data) {
        if (empty($data['email'])) {
            die("El email es requerido");
        }
        $usuarioModel = new UsuarioModel();
        $emailService = new EmailService();
        $result = $usuarioModel->createPasswordResetToken($data['email']);
        if ($result['success']) {
            $emailSent = $emailService->sendPasswordResetEmail(
                $result['user_email'], 
                $result['user_name'], 
                $result['token']
            );
            if ($emailSent) {
                echo "<script>
                        alert('se envió un enlace de recuperacion');
                        window.location.href = '" . url('login') . "';
                    </script>";
            } else {
                echo "<script>
                        alert('error al enviar enlace, intena mas tarde');
                        window.location.href = '" . url('forgot-password') . "';
                    </script>";
            }
        } else {
            echo "<script>
                    alert('si tienes cuenta, te llegara el codigo al correo');
                    window.location.href = '" . url('login') . "';
                </script>";
        }
        exit();
    }
    public function processResetPassword($data) {
        if (empty($data['token']) || empty($data['password']) || empty($data['confirm_password'])) {
            die("Todos los campos son requeridos");
        }
        if ($data['password'] !== $data['confirm_password']) {
            die("Las contraseñas no coinciden");
        }
        require_once __DIR__ . '/../utils/Validator.php';
        $passwordValidation = Validator::validatePassword($data['password']);
        if ($passwordValidation !== true) {
            echo "<script>
                    alert('" . addslashes($passwordValidation) . "');
                    window.location.href = '" . url('reset-password') . "&token=" . $data['token'] . "';
                </script>";
            exit();
        }
        $usuarioModel = new UsuarioModel();
        $user_id = $usuarioModel->validateResetToken($data['token']);
        if ($user_id) {
            try {
                $success = $usuarioModel->updatePasswordWithHistory($user_id, $data['password']);
                if ($success) {
                    echo "<script>
                            alert('contraseña actualizada, ya puede iniciar sesion');
                            window.location.href = '" . url('login') . "';
                        </script>";
                }
            } catch (Exception $e) {
                echo "<script>
                        alert('" . addslashes($e->getMessage()) . "');
                        window.location.href = '" . url('reset-password') . "&token=" . $data['token'] . "';
                    </script>";
            }
        } else {
            echo "<script>
                    alert('enlace ex´pirado');
                    window.location.href = '" . url('forgot-password') . "';
                </script>";
        }
        exit();
    }
    public function validateLoginAjax($data) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $errors = $this->validateLogin($data);
        if (!empty($errors)) {
            $this->jsonResponse(['success' => false, 'errors' => $errors]);
        }
        $usuarioModel = new UsuarioModel();
        $user = $usuarioModel->getUserByEmail($data['email']);
        if (!$user) {
            $this->jsonResponse(['success' => false, 'errors' => ['general' => 'Correo no encontrado']]);
        }
        if (!password_verify($data['password'], $user['password'])) {
            $this->jsonResponse(['success' => false, 'errors' => ['general' => 'Contraseña incorrecta']]);
        }
        if (!$user['verificado']) {
            $this->jsonResponse(['success' => false, 'errors' => ['general' => 'verifica tu correo antes de iniciar sesion']]);
        }
        $this->jsonResponse(['success' => true, 'user_id' => $user['id']]);
    }
    public function show2FAForm($user_id, $errors = []) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['2fa_user_id'] = $user_id;
        $errorMessages = $errors;
        $this->renderView('auth/2fa-verification.php', compact('errorMessages'));
        exit();
    }
    public function verify2FA($data) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['2fa_user_id'])) {
            $errors['general'] = 'Sesión expirada. Por favor inicia sesión nuevamente.';
            $this->showLoginForm($errors);
            return;
        }
        $user_id = $_SESSION['2fa_user_id'];
        $codigo = $data['codigo'] ?? '';
        $errors = [];
        if (empty($codigo)) {
            $errors['codigo'] = 'El código es requerido';
        } elseif (!preg_match('/^\d{6}$/', $codigo)) {
            $errors['codigo'] = 'El código debe tener 6 dígitos';
        }
        if (!empty($errors)) {
            $this->show2FAForm($user_id, $errors);
            return;
        }
        $usuarioModel = new UsuarioModel();
        if ($usuarioModel->verificarCodigo2FA($user_id, $codigo)) {
            $sessionModel = new SessionModel();
            if (!$sessionModel->puedeIniciarSesion($user_id)) {
                $max_sesiones = $sessionModel->getMaxSesionesPermitidas($user_id);
                $errors['general'] = "Límite de sesiones alcanzado. Máximo $max_sesiones sesiones concurrentes permitidas.";
                $this->show2FAForm($user_id, $errors);
                return;
            }
            $session_id = session_id();
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            if ($sessionModel->registrarSesion($user_id, $session_id, $ip_address, $user_agent)) {
                $user = $usuarioModel->getUserById($user_id);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['rol_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_nombre'] = $user['nombre'];
                $_SESSION['session_registered'] = true;
                unset($_SESSION['2fa_user_id']);
                echo "<script>
                        console.log('Bienvenido a CambaNet');
                        console.log('Usuario: " . addslashes($user['nombre']) . "');
                        console.log('Email: " . addslashes($user['email']) . "');
                        console.log('Rol: " . ($user['rol_id'] == 1 ? 'Administrador' : ($user['rol_id'] == 2 ? 'Profesor' : 'Estudiante')) . "');
                        console.log('Hora de acceso: " . date('Y-m-d H:i:s') . "');
                        console.log('Sesiones concurrentes: Control activado');
                    </script>";
                $this->redirectToDashboard($user['rol_id']);
            } else {
                $errors['general'] = 'Error al registrar la sesión. Intenta nuevamente.';
                $this->show2FAForm($user_id, $errors);
            }
        } else {
            $errors['general'] = 'Código inválido o expirado';
            $this->show2FAForm($user_id, $errors);
        }
    }
}
?>