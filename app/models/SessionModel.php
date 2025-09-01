<?php
require_once __DIR__ . '/../config/database.php';
class SessionModel {
    private $db;
    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }
    public function registrarSesion($user_id, $session_id, $ip_address, $user_agent) {
        $this->limpiarSesionesExpiradas();
        $sql = "INSERT INTO sesiones_activas (usuario_id, session_id, ip_address, user_agent) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isss", $user_id, $session_id, $ip_address, $user_agent);
        return $stmt->execute();
    }
    public function getSesionesActivas($user_id) {
        $sql = "SELECT COUNT(*) as total FROM sesiones_activas 
                WHERE usuario_id = ? AND activa = 1 
                AND fecha_ultima_actividad >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    public function getMaxSesionesPermitidas($user_id) {
        $sql = "SELECT ts.max_sesiones 
                FROM usuarios u 
                INNER JOIN tipos_suscripcion ts ON u.suscripcion_id = ts.id 
                WHERE u.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['max_sesiones'] ?? 1;
    }
    public function cerrarOtrasSesiones($user_id, $current_session_id) {
        $sql = "UPDATE sesiones_activas SET activa = 0 
                WHERE usuario_id = ? AND session_id != ? AND activa = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $user_id, $current_session_id);
        return $stmt->execute();
    }
    public function cerrarSesion($session_id) {
        $sql = "UPDATE sesiones_activas SET activa = 0 WHERE session_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $session_id);
        return $stmt->execute();
    }
    public function actualizarActividad($session_id) {
        $sql = "UPDATE sesiones_activas 
                SET fecha_ultima_actividad = NOW() 
                WHERE session_id = ? AND activa = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $session_id);
        return $stmt->execute();
    }
    private function limpiarSesionesExpiradas() {
        $sql = "UPDATE sesiones_activas SET activa = 0 
                WHERE fecha_ultima_actividad < DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                AND activa = 1";
        $this->db->query($sql);
    }
    public function puedeIniciarSesion($user_id) {
        $sesiones_activas = $this->getSesionesActivas($user_id);
        $max_permitidas = $this->getMaxSesionesPermitidas($user_id);
        return $sesiones_activas < $max_permitidas;
    }
    public function getSesionesUsuario($user_id) {
        $sql = "SELECT * FROM sesiones_activas 
                WHERE usuario_id = ? AND activa = 1 
                ORDER BY fecha_ultima_actividad DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function forzarCierreSesiones($user_id, $max_permitidas) {
        $sql = "UPDATE sesiones_activas SET activa = 0 
                WHERE usuario_id = ? AND activa = 1 
                AND id NOT IN (
                    SELECT id FROM (
                        SELECT id FROM sesiones_activas 
                        WHERE usuario_id = ? AND activa = 1 
                        ORDER BY fecha_ultima_actividad DESC 
                        LIMIT ?
                    ) AS temp
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $user_id, $user_id, $max_permitidas);
        return $stmt->execute();
    }
}
?>