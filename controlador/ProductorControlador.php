<?php
require_once dirname(__DIR__) . '/modelo/usuarioModelo.php';
require_once dirname(__DIR__) . '/modelo/productoModelo.php';
require_once dirname(__DIR__) . '/modelo/pedidoModelo.php';
require_once dirname(__DIR__) . '/modelo/resenaModelo.php';

class ProductorControlador {
    private $usuarioModelo;
    private $productoModelo;
    private $pedidoModelo;
    private $resenaModelo;

    public function __construct() {
        $this->usuarioModelo = new UsuarioModelo();
        $this->productoModelo = new ProductoModelo();
        $this->pedidoModelo = new PedidoModelo();
        $this->resenaModelo = new ResenaModelo();
    }

    public function dashboard() {
        // Verificar permisos de productor
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        session_start();
        $productorId = $_SESSION['usuario_id'];

        try {
            // Obtener estadísticas del productor
            $stats = [
                'total_productos' => $this->productoModelo->contarProductosPorProductor($productorId),
                'productos_bajo_stock' => $this->productoModelo->contarProductosBajoStockPorProductor($productorId),
                'total_pedidos' => $this->pedidoModelo->contarPedidosPorProductor($productorId),
                'pedidos_pendientes' => $this->pedidoModelo->contarPedidosPendientesPorProductor($productorId)
            ];

            // Obtener productos del productor
            $productos = $this->productoModelo->obtenerProductosPorProductor($productorId);
            
            // Obtener pedidos recientes
            $pedidosRecientes = $this->pedidoModelo->obtenerPedidosPorProductor($productorId, 5);
            
            // Obtener reseñas recientes
            $resenasRecientes = $this->resenaModelo->obtenerResenasRecientesPorProductor($productorId, 5);

            include dirname(__DIR__) . '/modulos/productor/dashboard.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    // GESTIÓN DE PRODUCTOS
    public function productos() {
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        session_start();
        $productorId = $_SESSION['usuario_id'];

        try {
            $productos = $this->productoModelo->obtenerProductosPorProductor($productorId);
            include dirname(__DIR__) . '/modulos/productor/productos.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function crearProducto() {
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                session_start();
                $datos = $this->validarDatosProducto($_POST);
                $datos['id_usuario'] = $_SESSION['usuario_id'];
                
                $this->productoModelo->crearProducto($datos);
                $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', 'Producto creado exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('index.php?modulo=productor&accion=crearProducto', $e->getMessage(), 'error');
            }
        } else {
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            include dirname(__DIR__) . '/modulos/productor/crear_producto.php';
        }
    }

    public function editarProducto() {
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', 'ID de producto no especificado', 'error');
        }

        session_start();
        $productorId = $_SESSION['usuario_id'];

        // Verificar que el producto pertenece al productor
        $producto = $this->productoModelo->obtenerProductoPorId($id);
        if (!$producto || $producto['id_usuario'] != $productorId) {
            $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', 'Producto no encontrado', 'error');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $datos = $this->validarDatosProducto($_POST);
                $this->productoModelo->actualizarProducto($id, $datos);
                $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', 'Producto actualizado exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje("index.php?modulo=productor&accion=editarProducto&id={$id}", $e->getMessage(), 'error');
            }
        } else {
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            include dirname(__DIR__) . '/modulos/productor/editar_producto.php';
        }
    }

    public function eliminarProducto() {
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', 'ID de producto no especificado', 'error');
        }

        session_start();
        $productorId = $_SESSION['usuario_id'];

        // Verificar que el producto pertenece al productor
        $producto = $this->productoModelo->obtenerProductoPorId($id);
        if (!$producto || $producto['id_usuario'] != $productorId) {
            $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', 'Producto no encontrado', 'error');
        }

        try {
            $this->productoModelo->eliminarProducto($id);
            $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', 'Producto eliminado exitosamente', 'success');
        } catch (Exception $e) {
            $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', $e->getMessage(), 'error');
        }
    }

    public function actualizarStock() {
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id_producto'] ?? 0;
                $cantidad = $_POST['cantidad'] ?? 0;

                if (!$id || !$cantidad) {
                    throw new Exception("Datos incompletos");
                }

                session_start();
                $productorId = $_SESSION['usuario_id'];

                // Verificar que el producto pertenece al productor
                $producto = $this->productoModelo->obtenerProductoPorId($id);
                if (!$producto || $producto['id_usuario'] != $productorId) {
                    throw new Exception("Producto no encontrado");
                }

                $this->productoModelo->actualizarStock($id, $cantidad);
                $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', 'Stock actualizado exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('index.php?modulo=productor&accion=productos', $e->getMessage(), 'error');
            }
        }
    }

    // GESTIÓN DE PEDIDOS
    public function pedidos() {
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        session_start();
        $productorId = $_SESSION['usuario_id'];

        try {
            $pedidos = $this->pedidoModelo->obtenerPedidosPorProductor($productorId);
            include dirname(__DIR__) . '/modulos/productor/pedidos.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function verPedido() {
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=productor&accion=pedidos', 'ID de pedido no especificado', 'error');
        }

        session_start();
        $productorId = $_SESSION['usuario_id'];

        try {
            $pedido = $this->pedidoModelo->obtenerPedidoPorId($id);
            
            // Verificar que el pedido pertenece al productor
            if (!$pedido || $pedido['id_productor'] != $productorId) {
                $this->redireccionarConMensaje('index.php?modulo=productor&accion=pedidos', 'Pedido no encontrado', 'error');
            }

            $detalles = $this->pedidoModelo->obtenerDetallesPedido($id);
            include dirname(__DIR__) . '/modulos/productor/ver_pedido.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function actualizarEstadoPedido() {
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id_pedido'] ?? 0;
                $estado = $_POST['estado'] ?? '';

                if (!$id || !$estado) {
                    throw new Exception("Datos incompletos");
                }

                session_start();
                $productorId = $_SESSION['usuario_id'];

                // Verificar que el pedido pertenece al productor
                $pedido = $this->pedidoModelo->obtenerPedidoPorId($id);
                if (!$pedido || $pedido['id_productor'] != $productorId) {
                    throw new Exception("Pedido no encontrado");
                }

                $this->pedidoModelo->actualizarEstadoPedido($id, $estado);
                $this->redireccionarConMensaje('index.php?modulo=productor&accion=pedidos', 'Estado del pedido actualizado', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('index.php?modulo=productor&accion=pedidos', $e->getMessage(), 'error');
            }
        }
    }

    // GESTIÓN DE RESEÑAS
    public function resenas() {
        if (!verificarProductor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        session_start();
        $productorId = $_SESSION['usuario_id'];

        try {
            $resenas = $this->resenaModelo->obtenerResenasPorProductor($productorId);
            include dirname(__DIR__) . '/modulos/productor/resenas.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    // MÉTODOS PRIVADOS
    private function validarDatosProducto($datos) {
        $errores = [];

        if (empty($datos['nombre'])) $errores[] = "El nombre del producto es requerido";
        if (empty($datos['precio'])) $errores[] = "El precio es requerido";
        if (empty($datos['stock'])) $errores[] = "El stock es requerido";

        if (!is_numeric($datos['precio']) || $datos['precio'] <= 0) {
            $errores[] = "El precio debe ser un número mayor a 0";
        }

        if (!is_numeric($datos['stock']) || $datos['stock'] < 0) {
            $errores[] = "El stock debe ser un número mayor o igual a 0";
        }

        if (!empty($errores)) {
            throw new Exception(implode(", ", $errores));
        }

        return [
            'nombre' => trim($datos['nombre']),
            'descripcion' => trim($datos['descripcion'] ?? ''),
            'precio' => floatval($datos['precio']),
            'stock' => intval($datos['stock']),
            'id_ciudad_origen' => !empty($datos['id_ciudad_origen']) ? $datos['id_ciudad_origen'] : null
        ];
    }

    private function mostrarError($mensaje) {
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error - Productor AgroStock</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h4 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Error</h4>
                            </div>
                            <div class="card-body">
                                <p class="lead">Ha ocurrido un error:</p>
                                <div class="alert alert-danger">
                                    <strong>' . htmlspecialchars($mensaje) . '</strong>
                                </div>
                                <div class="mt-3">
                                    <a href="index.php?modulo=productor&accion=dashboard" class="btn btn-primary">
                                        <i class="fas fa-home"></i> Volver al Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>';
    }

    private function redireccionarConMensaje($url, $mensaje, $tipo) {
        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['tipo_mensaje'] = $tipo;
        header("Location: $url");
        exit;
    }
}
?>
