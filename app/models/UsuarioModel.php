<?php
require_once __DIR__ . '/../../config/database.php';
class UsuarioModel {
    private $db;
    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }
    public function isPasswordInHistory($user_id, $new_password) {
        $sql = "SELECT password FROM historial_passwords WHERE usuario_id = ? ORDER BY fecha_creacion DESC LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            if (password_verify($new_password, $row['password'])) {
                return true;
            }
        }
        return false;
    }
    public function addToPasswordHistory($user_id, $password_hash) {
        $sql = "INSERT INTO historial_passwords (usuario_id, password) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $user_id, $password_hash);
        return $stmt->execute();
    }
    public function updatePasswordWithHistory($user_id, $new_password) {
        if ($this->isPasswordInHistory($user_id, $new_password)) {
            throw new Exception("No puedes usar una contraseña que ya has utilizado anteriormente");
        }
        $password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $this->db->begin_transaction();
        try {
            $sql = "UPDATE usuarios SET password = ?, forzar_cambio_password = 0 WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $password_hash, $user_id);
            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar la contraseña");
            }
            if (!$this->addToPasswordHistory($user_id, $password_hash)) {
                throw new Exception("Error al guardar en el historial");
            }
            $this->cleanPasswordHistory($user_id);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    private function cleanPasswordHistory($user_id) {
        $sql = "DELETE FROM historial_passwords 
                WHERE usuario_id = ? AND id NOT IN (
                    SELECT id FROM (
                        SELECT id FROM historial_passwords 
                        WHERE usuario_id = ? 
                        ORDER BY fecha_creacion DESC 
                        LIMIT 5
                    ) AS temp
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $user_id, $user_id);
        $stmt->execute();
    }
    public function forcePasswordChange($user_id) {
        $sql = "UPDATE usuarios SET forzar_cambio_password = 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
    public function requiresPasswordChange($user_id) {
        $sql = "SELECT forzar_cambio_password FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['forzar_cambio_password'] == 1;
    }
    public function updatePassword($user_id, $new_password) {
        return $this->updatePasswordWithHistory($user_id, $new_password);
    }
    public function emailExists($email) {
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
    public function createUser($data) {
        if ($this->emailExists($data['email'])) {
            throw new Exception("El correo electrónico ya está registrado");
        }
        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nombre, email, password, rol_id, verificado) VALUES (?, ?, ?, 3, 0)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $data['nombre'], $data['email'], $password_hash);
        if ($stmt->execute()) {
            $userId = $this->db->insert_id;
            $token = bin2hex(random_bytes(32));
            $expiracion = date('Y-m-d H:i:s', strtotime('+1 day'));
            $sqlToken = "INSERT INTO tokens_verificacion (usuario_id, token, tipo, expiracion) VALUES (?, ?, 'verificacion', ?)";
            $stmtToken = $this->db->prepare($sqlToken);
            $stmtToken->bind_param("iss", $userId, $token, $expiracion);
            if ($stmtToken->execute()) {
                $this->addToPasswordHistory($userId, $password_hash);
                return [
                    'success' => true,
                    'user_id' => $userId,
                    'token' => $token
                ];
            } else {
                throw new Exception("Error al crear token de verificación: " . $stmtToken->error);
            }
        } else {
            throw new Exception("Error al crear usuario: " . $stmt->error);
        }
    }
    public function verifyUser($token) {
        global $conexion;
        $sql = "SELECT usuario_id FROM tokens_verificacion 
                WHERE token = ? AND tipo = 'verificacion' AND expiracion > NOW() AND utilizado = 0";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userId = $row['usuario_id'];
            $sqlUpdate = "UPDATE usuarios SET verificado = 1 WHERE id = ?";
            $stmtUpdate = $conexion->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $userId);
            $stmtUpdate->execute();
            $sqlToken = "UPDATE tokens_verificacion SET utilizado = 1 WHERE token = ?";
            $stmtToken = $conexion->prepare($sqlToken);
            $stmtToken->bind_param("s", $token);
            $stmtToken->execute();
            return true;
        }
        return false;
    }
    public function getUserByEmail($email) {
        global $conexion;
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function createPasswordResetToken($email) {
        global $conexion;
        $user = $this->getUserByEmail($email);
        if (!$user) {
            return ['success' => false, 'error' => 'Usuario no encontrado'];
        }
        $token = bin2hex(random_bytes(32));
        $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $sql = "INSERT INTO tokens_verificacion (usuario_id, token, tipo, expiracion) VALUES (?, ?, 'recuperacion', ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iss", $user['id'], $token, $expiracion);
        if ($stmt->execute()) {
            return [
                'success' => true,
                'token' => $token,
                'user_id' => $user['id'],
                'user_email' => $user['email'],
                'user_name' => $user['nombre']
            ];
        } else {
            return ['success' => false, 'error' => 'Error al crear token'];
        }
    }
    public function validateResetToken($token) {
        global $conexion;
        $sql = "SELECT usuario_id FROM tokens_verificacion 
                WHERE token = ? AND tipo = 'recuperacion' AND expiracion > NOW() AND utilizado = 0";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['usuario_id'];
        }
        return false;
    }
    public function markTokenAsUsed($token) {
        global $conexion;
        $sql = "UPDATE tokens_verificacion SET utilizado = 1 WHERE token = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $token);
        return $stmt->execute();
    }
    public function getTotalUsers() {
    global $conexion;
    $sql = "SELECT COUNT(*) as total FROM usuarios";
    $result = $conexion->query($sql);
    return $result->fetch_assoc()['total'];
    }
    public function getVerifiedUsersCount() {
        global $conexion;
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE verificado = 1";
        $result = $conexion->query($sql);
        return $result->fetch_assoc()['total'];
    }
    public function getUsersCountByRole() {
        global $conexion;
        $sql = "SELECT r.nombre as rol, COUNT(u.id) as cantidad 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                GROUP BY u.rol_id";
        $result = $conexion->query($sql);
        $stats = [];
        while ($row = $result->fetch_assoc()) {
            $stats[$row['rol']] = $row['cantidad'];
        }
        return $stats;
    }
    public function getAllUsers() {
    global $conexion;
    $sql = "SELECT u.*, r.nombre as rol_nombre 
            FROM usuarios u 
            INNER JOIN roles r ON u.rol_id = r.id 
            ORDER BY u.id DESC";
    $result = $conexion->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getUserById($id) {
        global $conexion;
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function createUserByAdmin($data) {
        global $conexion;
        $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nombre, email, password, rol_id, verificado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $verificado = isset($data['verificado']) ? 1 : 0;
        $stmt->bind_param("sssii", $data['nombre'], $data['email'], $password_hash, $data['rol_id'], $verificado);
        return $stmt->execute();
    }
    public function updateUser($id, $data) {
        global $conexion;
        if (!empty($data['password'])) {
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET nombre = ?, email = ?, password = ?, rol_id = ?, verificado = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $verificado = isset($data['verificado']) ? 1 : 0;
            $stmt->bind_param("sssiii", $data['nombre'], $data['email'], $password_hash, $data['rol_id'], $verificado, $id);
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, email = ?, rol_id = ?, verificado = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $verificado = isset($data['verificado']) ? 1 : 0;
            $stmt->bind_param("ssiii", $data['nombre'], $data['email'], $data['rol_id'], $verificado, $id);
        }
        return $stmt->execute();
    }
    public function deleteUser($id) {
        global $conexion;
        
        try {
            $sqlTokens = "DELETE FROM tokens_verificacion WHERE usuario_id = ?";
            $stmtTokens = $conexion->prepare($sqlTokens);
            $stmtTokens->bind_param("i", $id);
            $stmtTokens->execute();
            $sqlInscripciones = "DELETE FROM inscripciones WHERE estudiante_id = ?";
            $stmtInscripciones = $conexion->prepare($sqlInscripciones);
            $stmtInscripciones->bind_param("i", $id);
            $stmtInscripciones->execute();
            $sqlCursos = "SELECT COUNT(*) as total FROM cursos WHERE profesor_id = ?";
            $stmtCursos = $conexion->prepare($sqlCursos);
            $stmtCursos->bind_param("i", $id);
            $stmtCursos->execute();
            $result = $stmtCursos->get_result();
            $cursos = $result->fetch_assoc();
            if ($cursos['total'] > 0) {
                throw new Exception("El usuario tiene cursos asignados");
            }
            $sql = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                return true;
            } else {
                throw new Exception("Error al ejecutar DELETE en usuarios");
            }
        } catch (Exception $e) {
            error_log("Error eliminando usuario ID {$id}: " . $e->getMessage());
            return false;
        }
    }
    public function getUsersByRole($rol_id) {
        global $conexion;
        $sql = "SELECT u.*, r.nombre as rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.rol_id = ?
                ORDER BY u.id DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $rol_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function verificarCodigo2FA($user_id, $codigo) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            error_log("Error conexión 2FA: " . $conn->connect_error);
            return false;
        }
        $sql = "SELECT id, expiracion FROM codigos_2fa 
                WHERE usuario_id = ? AND codigo = ? AND utilizado = 0 AND expirado = 0
                AND expiracion > NOW()
                ORDER BY created_at DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Error preparando consulta: " . $conn->error);
            $conn->close();
            return false;
        }
        $stmt->bind_param("is", $user_id, $codigo);
        if (!$stmt->execute()) {
            error_log("Error ejecutando consulta: " . $stmt->error);
            $stmt->close();
            $conn->close();
            return false;
        }
        $result = $stmt->get_result();
        $codigo_valido = false;
        $codigo_id = null;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $codigo_valido = true;
            $codigo_id = $row['id'];
        }
        $stmt->close();
        if ($codigo_valido && $codigo_id) {
            $sqlUpdate = "UPDATE codigos_2fa SET utilizado = 1 WHERE id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $codigo_id);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }
        $conn->close();
        return $codigo_valido;
    }
    public function generarCodigo2FA($user_id) {
        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiracion = date('Y-m-d H:i:s', strtotime('+60 seconds'));
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            error_log("Error conexión 2FA: " . $conn->connect_error);
            return false;
        }
        $sql = "INSERT INTO codigos_2fa (usuario_id, codigo, expiracion) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $codigo, $expiracion);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $result ? $codigo : false;
    }
    private function ejecutarLimpiezaCodigos($user_id) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) return;
        if ($conn->query("SHOW PROCEDURE STATUS LIKE 'limpiar_codigos_expirados'")->num_rows > 0) {
            $stmt = $conn->prepare("CALL limpiar_codigos_expirados(?)");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $sql = "UPDATE codigos_2fa SET utilizado = 1, expirado = 1 
                    WHERE usuario_id = ? AND expiracion <= NOW() AND utilizado = 0";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }
        $conn->close();
    }
    private function marcarCodigoUsado($codigo_id) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) return;
        $sql = "UPDATE codigos_2fa SET utilizado = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $codigo_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
    private function marcarCodigoExpirado($codigo_id) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) return;
        $sql = "UPDATE codigos_2fa SET utilizado = 1, expirado = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $codigo_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
    private function marcarComoUsadoConConexionSeparada($codigo_id) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            error_log("Error conexión separada 2FA: " . $conn->connect_error);
            return;
        }
        $sql = "UPDATE codigos_2fa SET utilizado = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $codigo_id);
        if (!$stmt->execute()) {
            error_log("Error marcando código como usado: " . $stmt->error);
        }
        $stmt->close();
        $conn->close();
    }
    public function limpiarCodigosExpirados($user_id) {
        $cleanupConn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($cleanupConn->connect_error) {
            error_log("Error en limpieza 2FA: " . $cleanupConn->connect_error);
            return;
        }
        $sql = "UPDATE codigos_2fa SET utilizado = 1 
                WHERE usuario_id = ? AND expiracion <= NOW() AND utilizado = 0";
        $stmt = $cleanupConn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cleanupConn->close();
    }
    public function tiene2FAHabilitado($user_id) {
        $sql = "SELECT habilitar_2fa FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['habilitar_2fa'] == 1;
    }
    public function habilitar2FA($user_id) {
        $sql = "UPDATE usuarios SET habilitar_2fa = 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
    public function deshabilitar2FA($user_id) {
        $sql = "UPDATE usuarios SET habilitar_2fa = 0 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
}
?>