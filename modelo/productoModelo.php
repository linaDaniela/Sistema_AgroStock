<?php
require_once 'conexion.php';

class ProductoModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function crearProducto($datos) {
        try {
            $id = $this->conexion->insertar('productos', $datos);
            return $id;
        } catch (Exception $e) {
            throw new Exception("Error al crear producto: " . $e->getMessage());
        }
    }

    public function obtenerProductoPorId($id) {
        try {
            $sql = "SELECT p.*, u.nombre as productor_nombre, u.telefono as productor_telefono, c.nombre as ciudad_origen 
                    FROM productos p 
                    LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario 
                    LEFT JOIN ciudades c ON p.id_ciudad_origen = c.id_ciudad 
                    WHERE p.id_producto = :id";
            return $this->conexion->obtenerFila($sql, ['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener producto: " . $e->getMessage());
        }
    }

    public function obtenerProductos($filtros = []) {
        try {
            $sql = "SELECT p.*, u.nombre as productor_nombre, c.nombre as ciudad_origen 
                    FROM productos p 
                    LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario 
                    LEFT JOIN ciudades c ON p.id_ciudad_origen = c.id_ciudad 
                    WHERE 1=1";
            
            $params = [];

            // Aplicar filtros
            if (!empty($filtros['id_usuario'])) {
                $sql .= " AND p.id_usuario = :id_usuario";
                $params['id_usuario'] = $filtros['id_usuario'];
            }

            if (!empty($filtros['id_ciudad_origen'])) {
                $sql .= " AND p.id_ciudad_origen = :id_ciudad_origen";
                $params['id_ciudad_origen'] = $filtros['id_ciudad_origen'];
            }

            if (!empty($filtros['busqueda'])) {
                $sql .= " AND (p.nombre LIKE :busqueda OR p.descripcion LIKE :busqueda)";
                $params['busqueda'] = '%' . $filtros['busqueda'] . '%';
            }

            if (!empty($filtros['precio_min'])) {
                $sql .= " AND p.precio >= :precio_min";
                $params['precio_min'] = $filtros['precio_min'];
            }

            if (!empty($filtros['precio_max'])) {
                $sql .= " AND p.precio <= :precio_max";
                $params['precio_max'] = $filtros['precio_max'];
            }

            // Ordenamiento
            $orden = isset($filtros['orden']) ? $filtros['orden'] : 'p.id_producto DESC';
            $sql .= " ORDER BY {$orden}";

            // Límite y offset para paginación
            if (isset($filtros['limite'])) {
                $sql .= " LIMIT :limite";
                $params['limite'] = $filtros['limite'];
                
                if (isset($filtros['offset'])) {
                    $sql .= " OFFSET :offset";
                    $params['offset'] = $filtros['offset'];
                }
            }

            return $this->conexion->obtenerFilas($sql, $params);
        } catch (Exception $e) {
            throw new Exception("Error al obtener productos: " . $e->getMessage());
        }
    }

    public function obtenerProductosPorProductor($productorId) {
        try {
            $sql = "SELECT p.*, c.nombre as ciudad_origen 
                    FROM productos p 
                    LEFT JOIN ciudades c ON p.id_ciudad_origen = c.id_ciudad 
                    WHERE p.id_usuario = :productor_id 
                    ORDER BY p.id_producto DESC";
            return $this->conexion->obtenerFilas($sql, ['productor_id' => $productorId]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener productos del productor: " . $e->getMessage());
        }
    }

    public function actualizarProducto($id, $datos) {
        try {
            $where = "id_producto = :id";
            $datos['id'] = $id;
            
            return $this->conexion->actualizar('productos', $datos, $where);
        } catch (Exception $e) {
            throw new Exception("Error al actualizar producto: " . $e->getMessage());
        }
    }

    public function eliminarProducto($id) {
        try {
            $where = "id_producto = :id";
            return $this->conexion->eliminar('productos', $where, ['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Error al eliminar producto: " . $e->getMessage());
        }
    }

    public function actualizarStock($id, $cantidad) {
        try {
            $sql = "SELECT stock FROM productos WHERE id_producto = :id";
            $producto = $this->conexion->obtenerFila($sql, ['id' => $id]);
            
            if (!$producto) {
                throw new Exception("Producto no encontrado");
            }

            $nuevoStock = $producto['stock'] + $cantidad;
            if ($nuevoStock < 0) {
                throw new Exception("No hay suficiente stock disponible");
            }

            $datos = ['stock' => $nuevoStock];
            $where = "id_producto = :id";
            $datos['id'] = $id;
            
            return $this->conexion->actualizar('productos', $datos, $where);
        } catch (Exception $e) {
            throw new Exception("Error al actualizar stock: " . $e->getMessage());
        }
    }

    public function buscarProductos($termino, $limite = 10) {
        try {
            $sql = "SELECT p.*, u.nombre as productor_nombre, c.nombre as ciudad_origen 
                    FROM productos p 
                    LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario 
                    LEFT JOIN ciudades c ON p.id_ciudad_origen = c.id_ciudad 
                    WHERE (p.nombre LIKE :termino OR p.descripcion LIKE :termino) 
                    ORDER BY p.id_producto DESC 
                    LIMIT :limite";
            
            return $this->conexion->obtenerFilas($sql, [
                'termino' => '%' . $termino . '%',
                'limite' => $limite
            ]);
        } catch (Exception $e) {
            throw new Exception("Error al buscar productos: " . $e->getMessage());
        }
    }

    public function obtenerEstadisticas() {
        try {
            $stats = [];
            
            // Total de productos
            $stats['total_productos'] = $this->conexion->contar('productos');
            
            // Productos por ciudad
            $sql = "SELECT c.nombre, COUNT(p.id_producto) as cantidad 
                    FROM ciudades c 
                    LEFT JOIN productos p ON c.id_ciudad = p.id_ciudad_origen 
                    GROUP BY c.id_ciudad, c.nombre 
                    ORDER BY cantidad DESC";
            $stats['por_ciudad'] = $this->conexion->obtenerFilas($sql);
            
            // Productos sin stock
            $stats['sin_stock'] = $this->conexion->contar('productos', "stock = 0");
            
            // Productos con stock bajo (menos de 10 unidades)
            $stats['stock_bajo'] = $this->conexion->contar('productos', "stock < 10 AND stock > 0");
            
            return $stats;
        } catch (Exception $e) {
            throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
        }
    }

    public function obtenerProductosSimilares($productoId, $limite = 4) {
        try {
            $sql = "SELECT p.*, u.nombre as productor_nombre, c.nombre as ciudad_origen 
                    FROM productos p 
                    LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario 
                    LEFT JOIN ciudades c ON p.id_ciudad_origen = c.id_ciudad 
                    WHERE p.id_producto != :producto_id 
                    AND p.id_ciudad_origen = (SELECT id_ciudad_origen FROM productos WHERE id_producto = :producto_id2) 
                    ORDER BY p.id_producto DESC 
                    LIMIT :limite";
            
            return $this->conexion->obtenerFilas($sql, [
                'producto_id' => $productoId,
                'producto_id2' => $productoId,
                'limite' => $limite
            ]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener productos similares: " . $e->getMessage());
        }
    }

    public function crearAlertaStock($productoId, $stockActual, $mensaje) {
        try {
            $datos = [
                'id_producto' => $productoId,
                'stock_actual' => $stockActual,
                'mensaje' => $mensaje
            ];
            
            return $this->conexion->insertar('alertas_stock', $datos);
        } catch (Exception $e) {
            throw new Exception("Error al crear alerta de stock: " . $e->getMessage());
        }
    }

    public function obtenerAlertasStock() {
        try {
            $sql = "SELECT a.*, p.nombre as producto_nombre, u.nombre as productor_nombre 
                    FROM alertas_stock a 
                    LEFT JOIN productos p ON a.id_producto = p.id_producto 
                    LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario 
                    ORDER BY a.fecha DESC";
            return $this->conexion->obtenerFilas($sql);
        } catch (Exception $e) {
            throw new Exception("Error al obtener alertas de stock: " . $e->getMessage());
        }
    }
}
?>
