<?php
require_once __DIR__ . '/config.php';
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");
function limpiarDatos($data) {
    global $conexion;
    if (is_array($data)) {
        return array_map('limpiarDatos', $data);
    }
    return $conexion->real_escape_string(trim($data));
}
function ejecutarConsulta($sql, $params = []) {
    global $conexion;
    try {
        if (!empty($params)) {
            $stmt = $conexion->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error preparando consulta: " . $conexion->error);
            }
            $types = '';
            $values = [];
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_double($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
                $values[] = $param;
            }
            $stmt->bind_param($types, ...$values);
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception("Error ejecutando consulta: " . $stmt->error);
            }
            if (stripos($sql, 'SELECT') === 0) {
                $result = $stmt->get_result();
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $stmt->close();
                return $data;
            }
            if (stripos($sql, 'INSERT') === 0) {
                $insert_id = $conexion->insert_id;
                $stmt->close();
                return $insert_id;
            }
            $affected_rows = $conexion->affected_rows;
            $stmt->close();
            return $affected_rows;
        } else {
            $result = $conexion->query($sql);
            if (!$result) {
                throw new Exception("Error en consulta: " . $conexion->error);
            }
            if (stripos($sql, 'SELECT') === 0) {
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;
            }
            if (stripos($sql, 'INSERT') === 0) {
                return $conexion->insert_id;
            }
            return $conexion->affected_rows;
        }
    } catch (Exception $e) {
        error_log("Error en base de datos: " . $e->getMessage());
        return false;
    }
}
function tablaExiste($tabla) {
    global $conexion;
    $result = $conexion->query("SHOW TABLES LIKE '$tabla'");
    return $result->num_rows > 0;
}
register_shutdown_function(function() use ($conexion) {
    if ($conexion) {
        $conexion->close();
    }
});
function verificarEstructuraBD() {
    $tablasEsenciales = ['usuarios', 'roles', 'cursos', 'inscripciones'];
    $tablasFaltantes = [];
    foreach ($tablasEsenciales as $tabla) {
        if (!tablaExiste($tabla)) {
            $tablasFaltantes[] = $tabla;
        }
    }
    if (!empty($tablasFaltantes)) {
        error_log("Tablas faltantes en la base de datos: " . implode(', ', $tablasFaltantes));
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            echo "<div style='background: #ffebee; padding: 20px; border: 1px solid #f44336; margin: 20px;'>";
            echo "<h3>Error de Base de Datos</h3>";
            echo "<p>Las siguientes tablas no existen en la base de datos:</p>";
            echo "<ul>";
            foreach ($tablasFaltantes as $tabla) {
                echo "<li>$tabla</li>";
            }
            echo "</ul>";
            echo "<p>Por favor, ejecuta el script SQL de la base de datos.</p>";
            echo "</div>";
        }
    }
}
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    verificarEstructuraBD();
}
?>