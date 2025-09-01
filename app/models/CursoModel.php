<?php
require_once __DIR__ . '/../config/database.php';
class CursoModel {
    private $db;
    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }
    public function getAllCursos() {
        $sql = "SELECT c.*, u.nombre as profesor_nombre 
                FROM cursos c 
                INNER JOIN usuarios u ON c.profesor_id = u.id 
                ORDER BY c.fecha_creacion DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getCursoById($id) {
        $sql = "SELECT c.*, u.nombre as profesor_nombre 
                FROM cursos c 
                INNER JOIN usuarios u ON c.profesor_id = u.id 
                WHERE c.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function createCurso($data) {
        $sql = "INSERT INTO cursos (profesor_id, nombre, descripcion, activo) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $activo = isset($data['activo']) ? 1 : 0;
        $stmt->bind_param("issi", $data['profesor_id'], $data['nombre'], $data['descripcion'], $activo);
        return $stmt->execute();
    }
    public function updateCurso($id, $data) {
        $sql = "UPDATE cursos SET profesor_id = ?, nombre = ?, descripcion = ?, activo = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $activo = isset($data['activo']) ? 1 : 0;
        $stmt->bind_param("issii", $data['profesor_id'], $data['nombre'], $data['descripcion'], $activo, $id);
        return $stmt->execute();
    }
    public function deleteCurso($id) {
        $sqlCheck = "SELECT COUNT(*) as total FROM inscripciones WHERE curso_id = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $id);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['total'] > 0) {
            return false;
        }
        $sql = "DELETE FROM cursos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function getProfesores() {
        $sql = "SELECT id, nombre FROM usuarios WHERE rol_id = 2 AND verificado = 1";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getEstadisticasCurso($curso_id) {
        $sql = "SELECT 
                COUNT(i.id) as total_estudiantes,
                SUM(CASE WHEN i.estado = 'activo' THEN 1 ELSE 0 END) as activos,
                SUM(CASE WHEN i.estado = 'completado' THEN 1 ELSE 0 END) as completados
                FROM inscripciones i 
                WHERE i.curso_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $curso_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getCursosByProfesor($profesor_id) {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM inscripciones WHERE curso_id = c.id) as total_estudiantes
                FROM cursos c 
                WHERE c.profesor_id = ? 
                ORDER BY c.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $profesor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>