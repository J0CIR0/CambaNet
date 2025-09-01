<?php
require_once __DIR__ . '/../config/database.php';

class CalificacionModel {
    private $db;

    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }

    public function crearActividad($data) {
        $sql = "INSERT INTO actividades (curso_id, profesor_id, titulo, descripcion, tipo, puntaje_maximo, fecha_limite) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iisssds", 
            $data['curso_id'], 
            $data['profesor_id'], 
            $data['titulo'], 
            $data['descripcion'], 
            $data['tipo'], 
            $data['puntaje_maximo'], 
            $data['fecha_limite']
        );
        return $stmt->execute();
    }

    public function getActividadesPorCurso($curso_id, $profesor_id = null) {
        $sql = "SELECT a.*, c.nombre as curso_nombre 
                FROM actividades a 
                INNER JOIN cursos c ON a.curso_id = c.id 
                WHERE a.curso_id = ?";
        
        if ($profesor_id) {
            $sql .= " AND a.profesor_id = ?";
        }
        
        $sql .= " ORDER BY a.fecha_creacion DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($profesor_id) {
            $stmt->bind_param("ii", $curso_id, $profesor_id);
        } else {
            $stmt->bind_param("i", $curso_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getActividadById($id, $profesor_id = null) {
        $sql = "SELECT a.*, c.nombre as curso_nombre 
                FROM actividades a 
                INNER JOIN cursos c ON a.curso_id = c.id 
                WHERE a.id = ?";
        
        if ($profesor_id) {
            $sql .= " AND a.profesor_id = ?";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($profesor_id) {
            $stmt->bind_param("ii", $id, $profesor_id);
        } else {
            $stmt->bind_param("i", $id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function registrarCalificacion($data) {
        $sql = "INSERT INTO calificaciones (estudiante_id, actividad_id, curso_id, puntaje_obtenido, comentario) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                puntaje_obtenido = VALUES(puntaje_obtenido), 
                comentario = VALUES(comentario),
                fecha_actualizacion = NOW()";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiids", 
            $data['estudiante_id'], 
            $data['actividad_id'], 
            $data['curso_id'], 
            $data['puntaje_obtenido'], 
            $data['comentario']
        );
        
        return $stmt->execute();
    }

    public function getCalificacionesEstudiante($estudiante_id, $curso_id = null) {
        $sql = "SELECT c.*, a.titulo, a.tipo, a.puntaje_maximo, cr.nombre as curso_nombre
                FROM calificaciones c
                INNER JOIN actividades a ON c.actividad_id = a.id
                INNER JOIN cursos cr ON c.curso_id = cr.id
                WHERE c.estudiante_id = ?";
        
        if ($curso_id) {
            $sql .= " AND c.curso_id = ?";
        }
        
        $sql .= " ORDER BY c.fecha_calificacion DESC";
        
        $stmt = $this->db->prepare($sql);
        
        if ($curso_id) {
            $stmt->bind_param("ii", $estudiante_id, $curso_id);
        } else {
            $stmt->bind_param("i", $estudiante_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCalificacionesPorActividad($actividad_id) {
        $sql = "SELECT c.*, u.nombre as estudiante_nombre, u.email as estudiante_email
                FROM calificaciones c
                INNER JOIN usuarios u ON c.estudiante_id = u.id
                WHERE c.actividad_id = ?
                ORDER BY u.nombre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $actividad_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPromedioEstudiante($estudiante_id, $curso_id) {
        $sql = "SELECT 
                AVG(c.puntaje_obtenido / a.puntaje_maximo * 100) as promedio_percent,
                SUM(c.puntaje_obtenido) as total_obtenido,
                SUM(a.puntaje_maximo) as total_maximo,
                COUNT(*) as total_actividades
                FROM calificaciones c
                INNER JOIN actividades a ON c.actividad_id = a.id
                WHERE c.estudiante_id = ? AND c.curso_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $estudiante_id, $curso_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getEstadisticasCurso($curso_id) {
        $sql = "SELECT 
                COUNT(DISTINCT i.estudiante_id) as total_estudiantes,
                COUNT(DISTINCT a.id) as total_actividades,
                AVG(cal.puntaje_obtenido / a.puntaje_maximo * 100) as promedio_general
                FROM cursos c
                LEFT JOIN inscripciones i ON c.id = i.curso_id
                LEFT JOIN actividades a ON c.id = a.curso_id
                LEFT JOIN calificaciones cal ON a.id = cal.actividad_id
                WHERE c.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $curso_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function eliminarActividad($id, $profesor_id) {
        $sqlEliminarCalificaciones = "DELETE FROM calificaciones WHERE actividad_id = ?";
        $stmtEliminar = $this->db->prepare($sqlEliminarCalificaciones);
        $stmtEliminar->bind_param("i", $id);
        $stmtEliminar->execute();
        $sql = "DELETE FROM actividades WHERE id = ? AND profesor_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $profesor_id);
        return $stmt->execute();
    }
    public function editarActividad($id, $profesor_id, $data) {
        $sql = "UPDATE actividades SET titulo = ?, descripcion = ?, tipo = ?, 
                puntaje_maximo = ?, fecha_limite = ?, activo = ? 
                WHERE id = ? AND profesor_id = ?";
        $stmt = $this->db->prepare($sql);
        $activo = isset($data['activo']) ? 1 : 0;
        $stmt->bind_param("sssdsiii", 
            $data['titulo'], 
            $data['descripcion'], 
            $data['tipo'], 
            $data['puntaje_maximo'], 
            $data['fecha_limite'], 
            $activo,
            $id,
            $profesor_id
        );
        return $stmt->execute();
    }
}