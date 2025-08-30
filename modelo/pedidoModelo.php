<?php
require_once 'conexion.php';

class PedidoModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function crearPedido($datos) {
        try {
            $datos['fecha'] = date('Y-m-d');
            $datos['estado'] = 'pendiente';
            
            $id = $this->conexion->insertar('pedidos', $datos);
            return $id;
        } catch (Exception $e) {
            throw new Exception("Error al crear pedido: " . $e->getMessage());
        }
    }

    public function obtenerPedidoPorId($id) {
        try {
            $sql = "SELECT p.*, 
                           c.nombre as consumidor_nombre, c.email as consumidor_email, c.telefono as consumidor_telefono,
                           pr.nombre as productor_nombre, pr.email as productor_email, pr.telefono as productor_telefono
                    FROM pedidos p 
                    LEFT JOIN usuarios c ON p.id_consumidor = c.id_usuario 
                    LEFT JOIN usuarios pr ON p.id_productor = pr.id_usuario 
                    WHERE p.id_pedido = :id";
            return $this->conexion->obtenerFila($sql, ['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener pedido: " . $e->getMessage());
        }
    }

    public function obtenerPedidosPorConsumidor($consumidorId) {
        try {
            $sql = "SELECT p.*, pr.nombre as productor_nombre 
                    FROM pedidos p 
                    LEFT JOIN usuarios pr ON p.id_productor = pr.id_usuario 
                    WHERE p.id_consumidor = :consumidor_id 
                    ORDER BY p.fecha DESC";
            return $this->conexion->obtenerFilas($sql, ['consumidor_id' => $consumidorId]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener pedidos del consumidor: " . $e->getMessage());
        }
    }

    public function obtenerPedidosPorProductor($productorId) {
        try {
            $sql = "SELECT p.*, c.nombre as consumidor_nombre 
                    FROM pedidos p 
                    LEFT JOIN usuarios c ON p.id_consumidor = c.id_usuario 
                    WHERE p.id_productor = :productor_id 
                    ORDER BY p.fecha DESC";
            return $this->conexion->obtenerFilas($sql, ['productor_id' => $productorId]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener pedidos del productor: " . $e->getMessage());
        }
    }

    public function actualizarEstadoPedido($id, $estado) {
        try {
            $datos = ['estado' => $estado];
            $where = "id_pedido = :id";
            $datos['id'] = $id;
            
            return $this->conexion->actualizar('pedidos', $datos, $where);
        } catch (Exception $e) {
            throw new Exception("Error al actualizar estado del pedido: " . $e->getMessage());
        }
    }

    public function agregarDetallePedido($datos) {
        try {
            return $this->conexion->insertar('detalle_pedidos', $datos);
        } catch (Exception $e) {
            throw new Exception("Error al agregar detalle del pedido: " . $e->getMessage());
        }
    }

    public function obtenerDetallesPedido($pedidoId) {
        try {
            $sql = "SELECT d.*, p.nombre as producto_nombre, p.precio as precio_actual 
                    FROM detalle_pedidos d 
                    LEFT JOIN productos p ON d.id_producto = p.id_producto 
                    WHERE d.id_pedido = :pedido_id";
            return $this->conexion->obtenerFilas($sql, ['pedido_id' => $pedidoId]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener detalles del pedido: " . $e->getMessage());
        }
    }

    public function obtenerEstadisticas() {
        try {
            $stats = [];
            
            // Total de pedidos
            $stats['total_pedidos'] = $this->conexion->contar('pedidos');
            
            // Pedidos por estado
            $stats['pendientes'] = $this->conexion->contar('pedidos', "estado = 'pendiente'");
            $stats['confirmados'] = $this->conexion->contar('pedidos', "estado = 'confirmado'");
            $stats['comprados'] = $this->conexion->contar('pedidos', "estado = 'comprado'");
            
            // Pedidos de hoy
            $stats['hoy'] = $this->conexion->contar('pedidos', "fecha = CURDATE()");
            
            return $stats;
        } catch (Exception $e) {
            throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
        }
    }
}
?>
