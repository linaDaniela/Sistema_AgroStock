<?php
require_once 'conexion.php';

class ResenaModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function crearResena($datos) {
        try {
            $id = $this->conexion->insertar('resenas', $datos);
            return $id;
        } catch (Exception $e) {
            throw new Exception("Error al crear reseña: " . $e->getMessage());
        }
    }

    public function obtenerResenaPorId($id) {
        try {
            $sql = "SELECT r.*, u.nombre as usuario_nombre, p.nombre as producto_nombre 
                    FROM resenas r 
                    LEFT JOIN usuarios u ON r.id_usuario = u.id_usuario 
                    LEFT JOIN productos p ON r.id_producto = p.id_producto 
                    WHERE r.id_resena = :id";
            return $this->conexion->obtenerFila($sql, ['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener reseña: " . $e->getMessage());
        }
    }

    public function obtenerResenasPorProducto($productoId) {
        try {
            $sql = "SELECT r.*, u.nombre as usuario_nombre 
                    FROM resenas r 
                    LEFT JOIN usuarios u ON r.id_usuario = u.id_usuario 
                    WHERE r.id_producto = :producto_id 
                    ORDER BY r.fecha DESC";
            return $this->conexion->obtenerFilas($sql, ['producto_id' => $productoId]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener reseñas del producto: " . $e->getMessage());
        }
    }

    public function obtenerResenasPorUsuario($usuarioId) {
        try {
            $sql = "SELECT r.*, p.nombre as producto_nombre 
                    FROM resenas r 
                    LEFT JOIN productos p ON r.id_producto = p.id_producto 
                    WHERE r.id_usuario = :usuario_id 
                    ORDER BY r.fecha DESC";
            return $this->conexion->obtenerFilas($sql, ['usuario_id' => $usuarioId]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener reseñas del usuario: " . $e->getMessage());
        }
    }

    public function actualizarResena($id, $datos) {
        try {
            $where = "id_resena = :id";
            $datos['id'] = $id;
            
            return $this->conexion->actualizar('resenas', $datos, $where);
        } catch (Exception $e) {
            throw new Exception("Error al actualizar reseña: " . $e->getMessage());
        }
    }

    public function eliminarResena($id) {
        try {
            $where = "id_resena = :id";
            return $this->conexion->eliminar('resenas', $where, ['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Error al eliminar reseña: " . $e->getMessage());
        }
    }

    public function obtenerCalificacionPromedio($productoId) {
        try {
            $sql = "SELECT AVG(calificacion) as promedio, COUNT(*) as total 
                    FROM resenas 
                    WHERE id_producto = :producto_id";
            return $this->conexion->obtenerFila($sql, ['producto_id' => $productoId]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener calificación promedio: " . $e->getMessage());
        }
    }

    public function obtenerEstadisticas() {
        try {
            $stats = [];
            
            // Total de reseñas
            $stats['total_resenas'] = $this->conexion->contar('resenas');
            
            // Calificación promedio general
            $sql = "SELECT AVG(calificacion) as promedio_general FROM resenas";
            $resultado = $this->conexion->obtenerFila($sql);
            $stats['promedio_general'] = round($resultado['promedio_general'], 1);
            
            // Reseñas por calificación
            for ($i = 1; $i <= 5; $i++) {
                $stats["calificacion_{$i}"] = $this->conexion->contar('resenas', "calificacion = {$i}");
            }
            
            return $stats;
        } catch (Exception $e) {
            throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
        }
    }
}
?>
