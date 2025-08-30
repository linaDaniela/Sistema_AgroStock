<?php
require_once dirname(__DIR__) . '/modelo/usuarioModelo.php';
require_once dirname(__DIR__) . '/modelo/productoModelo.php';
require_once dirname(__DIR__) . '/modelo/pedidoModelo.php';
require_once dirname(__DIR__) . '/modelo/resenaModelo.php';

class ConsumidorControlador {
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
        // Verificar permisos de consumidor
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        session_start();
        $consumidorId = $_SESSION['usuario_id'];

        try {
            // Obtener estadísticas del consumidor
            $stats = [
                'total_pedidos' => $this->pedidoModelo->contarPedidosPorConsumidor($consumidorId),
                'pedidos_pendientes' => $this->pedidoModelo->contarPedidosPendientesPorConsumidor($consumidorId),
                'resenas_realizadas' => $this->resenaModelo->contarResenasPorConsumidor($consumidorId)
            ];

            // Obtener pedidos recientes
            $pedidosRecientes = $this->pedidoModelo->obtenerPedidosPorConsumidor($consumidorId, 5);
            
            // Obtener productos favoritos o recomendados
            $productosRecomendados = $this->productoModelo->obtenerProductosRecomendados($consumidorId, 6);

            include dirname(__DIR__) . '/modulos/consumidor/dashboard.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    // GESTIÓN DE PEDIDOS
    public function pedidos() {
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        session_start();
        $consumidorId = $_SESSION['usuario_id'];

        try {
            $pedidos = $this->pedidoModelo->obtenerPedidosPorConsumidor($consumidorId);
            include dirname(__DIR__) . '/modulos/consumidor/pedidos.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function verPedido() {
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=pedidos', 'ID de pedido no especificado', 'error');
        }

        session_start();
        $consumidorId = $_SESSION['usuario_id'];

        try {
            $pedido = $this->pedidoModelo->obtenerPedidoPorId($id);
            
            // Verificar que el pedido pertenece al consumidor
            if (!$pedido || $pedido['id_consumidor'] != $consumidorId) {
                $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=pedidos', 'Pedido no encontrado', 'error');
            }

            $detalles = $this->pedidoModelo->obtenerDetallesPedido($id);
            include dirname(__DIR__) . '/modulos/consumidor/ver_pedido.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function crearPedido() {
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $productoId = $_GET['producto_id'] ?? 0;
        if (!$productoId) {
            $this->redireccionarConMensaje('index.php?modulo=inicio&accion=productos', 'ID de producto no especificado', 'error');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                session_start();
                $datos = $this->validarDatosPedido($_POST);
                $datos['id_consumidor'] = $_SESSION['usuario_id'];
                $datos['id_producto'] = $productoId;
                
                $this->pedidoModelo->crearPedido($datos);
                $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=pedidos', 'Pedido creado exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje("index.php?modulo=consumidor&accion=crearPedido&producto_id={$productoId}", $e->getMessage(), 'error');
            }
        } else {
            $producto = $this->productoModelo->obtenerProductoPorId($productoId);
            if (!$producto) {
                $this->redireccionarConMensaje('index.php?modulo=inicio&accion=productos', 'Producto no encontrado', 'error');
            }
            include dirname(__DIR__) . '/modulos/consumidor/crear_pedido.php';
        }
    }

    // GESTIÓN DE RESEÑAS
    public function resenas() {
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        session_start();
        $consumidorId = $_SESSION['usuario_id'];

        try {
            $resenas = $this->resenaModelo->obtenerResenasPorConsumidor($consumidorId);
            include dirname(__DIR__) . '/modulos/consumidor/resenas.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function crearResena() {
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $productoId = $_GET['producto_id'] ?? 0;
        if (!$productoId) {
            $this->redireccionarConMensaje('index.php?modulo=inicio&accion=productos', 'ID de producto no especificado', 'error');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                session_start();
                $datos = $this->validarDatosResena($_POST);
                $datos['id_consumidor'] = $_SESSION['usuario_id'];
                $datos['id_producto'] = $productoId;
                
                $this->resenaModelo->crearResena($datos);
                $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=resenas', 'Reseña creada exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje("index.php?modulo=consumidor&accion=crearResena&producto_id={$productoId}", $e->getMessage(), 'error');
            }
        } else {
            $producto = $this->productoModelo->obtenerProductoPorId($productoId);
            if (!$producto) {
                $this->redireccionarConMensaje('index.php?modulo=inicio&accion=productos', 'Producto no encontrado', 'error');
            }
            include dirname(__DIR__) . '/modulos/consumidor/crear_resena.php';
        }
    }

    public function editarResena() {
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=resenas', 'ID de reseña no especificado', 'error');
        }

        session_start();
        $consumidorId = $_SESSION['usuario_id'];

        // Verificar que la reseña pertenece al consumidor
        $resena = $this->resenaModelo->obtenerResenaPorId($id);
        if (!$resena || $resena['id_consumidor'] != $consumidorId) {
            $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=resenas', 'Reseña no encontrada', 'error');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $datos = $this->validarDatosResena($_POST);
                $this->resenaModelo->actualizarResena($id, $datos);
                $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=resenas', 'Reseña actualizada exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje("index.php?modulo=consumidor&accion=editarResena&id={$id}", $e->getMessage(), 'error');
            }
        } else {
            $producto = $this->productoModelo->obtenerProductoPorId($resena['id_producto']);
            include dirname(__DIR__) . '/modulos/consumidor/editar_resena.php';
        }
    }

    public function eliminarResena() {
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=resenas', 'ID de reseña no especificado', 'error');
        }

        session_start();
        $consumidorId = $_SESSION['usuario_id'];

        // Verificar que la reseña pertenece al consumidor
        $resena = $this->resenaModelo->obtenerResenaPorId($id);
        if (!$resena || $resena['id_consumidor'] != $consumidorId) {
            $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=resenas', 'Reseña no encontrada', 'error');
        }

        try {
            $this->resenaModelo->eliminarResena($id);
            $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=resenas', 'Reseña eliminada exitosamente', 'success');
        } catch (Exception $e) {
            $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=resenas', $e->getMessage(), 'error');
        }
    }

    // GESTIÓN DE CONSEJOS DEL CAMPO
    public function consejos() {
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        try {
            $consejos = $this->obtenerConsejos();
            include dirname(__DIR__) . '/modulos/consumidor/consejos.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function crearConsejo() {
        if (!verificarConsumidor()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                session_start();
                $datos = $this->validarDatosConsejo($_POST);
                $datos['id_usuario'] = $_SESSION['usuario_id'];
                
                $this->crearConsejoCampo($datos);
                $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=consejos', 'Consejo publicado exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('index.php?modulo=consumidor&accion=crearConsejo', $e->getMessage(), 'error');
            }
        } else {
            include dirname(__DIR__) . '/modulos/consumidor/crear_consejo.php';
        }
    }

    // MÉTODOS PRIVADOS
    private function validarDatosPedido($datos) {
        $errores = [];

        if (empty($datos['cantidad'])) $errores[] = "La cantidad es requerida";
        if (empty($datos['direccion_entrega'])) $errores[] = "La dirección de entrega es requerida";

        if (!is_numeric($datos['cantidad']) || $datos['cantidad'] <= 0) {
            $errores[] = "La cantidad debe ser un número mayor a 0";
        }

        if (!empty($errores)) {
            throw new Exception(implode(", ", $errores));
        }

        return [
            'cantidad' => intval($datos['cantidad']),
            'direccion_entrega' => trim($datos['direccion_entrega']),
            'notas' => trim($datos['notas'] ?? '')
        ];
    }

    private function validarDatosResena($datos) {
        $errores = [];

        if (empty($datos['calificacion'])) $errores[] = "La calificación es requerida";
        if (empty($datos['comentario'])) $errores[] = "El comentario es requerido";

        if (!is_numeric($datos['calificacion']) || $datos['calificacion'] < 1 || $datos['calificacion'] > 5) {
            $errores[] = "La calificación debe ser un número entre 1 y 5";
        }

        if (strlen($datos['comentario']) < 10) {
            $errores[] = "El comentario debe tener al menos 10 caracteres";
        }

        if (!empty($errores)) {
            throw new Exception(implode(", ", $errores));
        }

        return [
            'calificacion' => intval($datos['calificacion']),
            'comentario' => trim($datos['comentario'])
        ];
    }

    private function validarDatosConsejo($datos) {
        $errores = [];

        if (empty($datos['titulo'])) $errores[] = "El título es requerido";
        if (empty($datos['contenido'])) $errores[] = "El contenido es requerido";

        if (strlen($datos['titulo']) < 5) {
            $errores[] = "El título debe tener al menos 5 caracteres";
        }

        if (strlen($datos['contenido']) < 20) {
            $errores[] = "El contenido debe tener al menos 20 caracteres";
        }

        if (!empty($errores)) {
            throw new Exception(implode(", ", $errores));
        }

        return [
            'titulo' => trim($datos['titulo']),
            'contenido' => trim($datos['contenido']),
            'categoria' => trim($datos['categoria'] ?? 'General')
        ];
    }

    private function obtenerConsejos() {
        // Método temporal - debería conectarse a la tabla consejos
        return [
            [
                'id' => 1,
                'titulo' => 'Cultivo de Tomates',
                'contenido' => 'Los tomates necesitan mucha luz solar y riego regular...',
                'autor' => 'Juan Pérez',
                'fecha' => '2024-01-15'
            ]
        ];
    }

    private function crearConsejoCampo($datos) {
        // Método temporal - debería insertar en la tabla consejos
        // Por ahora solo simula la creación
        return true;
    }

    private function mostrarError($mensaje) {
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error - Consumidor AgroStock</title>
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
                                    <a href="index.php?modulo=consumidor&accion=dashboard" class="btn btn-primary">
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
