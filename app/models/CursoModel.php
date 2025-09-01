<?php
require_once __DIR__ . '/../../config/database.php';
class CursoModel {
    public function getAllCursos() {
        global $conexion;
        $sql = "SELECT c.*, u.nombre as profesor_nombre 
                FROM cursos c 
                INNER JOIN usuarios u ON c.profesor_id = u.id 
                ORDER BY c.fecha_creacion DESC";
        $result = $conexion->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getCursoById($id) {
        global $conexion;
        $sql = "SELECT c.*, u.nombre as profesor_nombre 
                FROM cursos c 
                INNER JOIN usuarios u ON c.profesor_id = u.id 
                WHERE c.id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function createCurso($data) {
        global $conexion;
        $sql = "INSERT INTO cursos (profesor_id, nombre, descripcion, activo) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $activo = isset($data['activo']) ? 1 : 0;
        $stmt->bind_param("issi", $data['profesor_id'], $data['nombre'], $data['descripcion'], $activo);
        return $stmt->execute();
    }
    public function updateCurso($id, $data) {
        global $conexion;
        $sql = "UPDATE cursos SET profesor_id = ?, nombre = ?, descripcion = ?, activo = ? 
                WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $activo = isset($data['activo']) ? 1 : 0;
        $stmt->bind_param("issii", $data['profesor_id'], $data['nombre'], $data['descripcion'], $activo, $id);
        return $stmt->execute();
    }
    public function deleteCurso($id) {
        global $conexion;
        $sqlCheck = "SELECT COUNT(*) as total FROM inscripciones WHERE curso_id = ?";
        $stmtCheck = $conexion->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $id);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        $row = $result->fetch_assoc();
        if ($row['total'] > 0) {
            return false;
        }
        $sql = "DELETE FROM cursos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    public function getProfesores() {
        global $conexion;
        $sql = "SELECT id, nombre FROM usuarios WHERE rol_id = 2 AND verificado = 1";
        $result = $conexion->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getEstadisticasCurso($curso_id) {
        global $conexion;
        $sql = "SELECT 
                COUNT(i.id) as total_estudiantes,
                SUM(CASE WHEN i.estado = 'activo' THEN 1 ELSE 0 END) as activos,
                SUM(CASE WHEN i.estado = 'completado' THEN 1 ELSE 0 END) as completados
                FROM inscripciones i 
                WHERE i.curso_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $curso_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>