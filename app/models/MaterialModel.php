<?php
require_once __DIR__ . '/../../config/database.php';
class MaterialModel {
    public function getMaterialByCurso($curso_id, $profesor_id = null) {
        global $conexion;
        $sql = "SELECT m.*, c.nombre as curso_nombre 
                FROM material_didactico m 
                INNER JOIN cursos c ON m.curso_id = c.id 
                WHERE m.curso_id = ?";
        if ($profesor_id) {
            $sql .= " AND m.profesor_id = ?";
        }
        $sql .= " ORDER BY m.fecha_creacion DESC";
        $stmt = $conexion->prepare($sql);
        if ($profesor_id) {
            $stmt->bind_param("ii", $curso_id, $profesor_id);
        } else {
            $stmt->bind_param("i", $curso_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function createMaterial($data) {
        global $conexion;
        $sql = "INSERT INTO material_didactico (curso_id, profesor_id, titulo, descripcion, archivo_nombre, archivo_ruta, tipo_archivo) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iisssss", 
            $data['curso_id'], 
            $data['profesor_id'], 
            $data['titulo'], 
            $data['descripcion'], 
            $data['archivo_nombre'], 
            $data['archivo_ruta'], 
            $data['tipo_archivo']
        );
        return $stmt->execute();
    }
    public function deleteMaterial($id, $profesor_id) {
        global $conexion;
        $sql = "SELECT archivo_ruta FROM material_didactico WHERE id = ? AND profesor_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $id, $profesor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $material = $result->fetch_assoc();
        if (!$material) {
            return false;
        }
        if ($material['archivo_ruta'] && file_exists($material['archivo_ruta'])) {
            unlink($material['archivo_ruta']);
        }
        $sqlDelete = "DELETE FROM material_didactico WHERE id = ? AND profesor_id = ?";
        $stmtDelete = $conexion->prepare($sqlDelete);
        $stmtDelete->bind_param("ii", $id, $profesor_id);
        return $stmtDelete->execute();
    }
    public function getMaterialById($id, $profesor_id = null) {
        global $conexion;
        $sql = "SELECT m.*, c.nombre as curso_nombre 
                FROM material_didactico m 
                INNER JOIN cursos c ON m.curso_id = c.id 
                WHERE m.id = ?";
        if ($profesor_id) {
            $sql .= " AND m.profesor_id = ?";
        }
        $stmt = $conexion->prepare($sql);
        if ($profesor_id) {
            $stmt->bind_param("ii", $id, $profesor_id);
        } else {
            $stmt->bind_param("i", $id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>