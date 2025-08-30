<?php
require_once dirname(__DIR__) . '/modelo/usuarioModelo.php';
require_once dirname(__DIR__) . '/modelo/productoModelo.php';
require_once dirname(__DIR__) . '/modelo/pedidoModelo.php';
require_once dirname(__DIR__) . '/modelo/resenaModelo.php';

class AdminControlador {
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
        // Verificar permisos de administrador
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        try {
            // Obtener estadísticas
            $stats = [
                'usuarios' => $this->usuarioModelo->obtenerEstadisticas(),
                'productos' => $this->productoModelo->obtenerEstadisticas(),
                'pedidos' => $this->pedidoModelo->obtenerEstadisticas(),
                'resenas' => $this->resenaModelo->obtenerEstadisticas()
            ];

            // Obtener datos recientes
            $usuariosRecientes = $this->usuarioModelo->obtenerUsuariosRecientes(5);
            $productosRecientes = $this->productoModelo->obtenerProductos(['limite' => 5]);
            $alertasStock = $this->productoModelo->obtenerAlertasStock();

            include dirname(__DIR__) . '/modulos/admin/dashboard.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    // GESTIÓN DE USUARIOS
    public function usuarios() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        try {
            $usuarios = $this->usuarioModelo->obtenerTodosLosUsuarios();
            include dirname(__DIR__) . '/modulos/admin/usuarios.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function crearUsuario() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $datos = $this->validarDatosUsuario($_POST);
                $this->usuarioModelo->registrarUsuario($datos);
                $this->redireccionarConMensaje('index.php?modulo=admin&accion=usuarios', 'Usuario creado exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('index.php?modulo=admin&accion=crearUsuario', $e->getMessage(), 'error');
            }
        } else {
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            include dirname(__DIR__) . '/modulos/admin/crear_usuario.php';
        }
    }

    public function editarUsuario() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=usuarios', 'ID de usuario no especificado', 'error');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $datos = $this->validarDatosUsuario($_POST);
                $this->usuarioModelo->actualizarUsuario($id, $datos);
                $this->redireccionarConMensaje('index.php?modulo=admin&accion=usuarios', 'Usuario actualizado exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje("index.php?modulo=admin&accion=editarUsuario&id={$id}", $e->getMessage(), 'error');
            }
        } else {
            $usuario = $this->usuarioModelo->obtenerUsuarioPorId($id);
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            include dirname(__DIR__) . '/modulos/admin/editar_usuario.php';
        }
    }

    public function eliminarUsuario() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=usuarios', 'ID de usuario no especificado', 'error');
        }

        try {
            $this->usuarioModelo->eliminarUsuario($id);
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=usuarios', 'Usuario eliminado exitosamente', 'success');
        } catch (Exception $e) {
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=usuarios', $e->getMessage(), 'error');
        }
    }

    // GESTIÓN DE UBICACIONES
    public function ubicaciones() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        try {
            $regiones = $this->usuarioModelo->obtenerRegiones();
            $departamentos = $this->usuarioModelo->obtenerDepartamentos();
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            include dirname(__DIR__) . '/modulos/admin/ubicaciones.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    // GESTIÓN DE PRODUCTOS
    public function productos() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        try {
            $productos = $this->productoModelo->obtenerProductos(['limite' => 50]);
            include dirname(__DIR__) . '/modulos/admin/productos.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function eliminarProducto() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=productos', 'ID de producto no especificado', 'error');
        }

        try {
            $this->productoModelo->eliminarProducto($id);
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=productos', 'Producto eliminado exitosamente', 'success');
        } catch (Exception $e) {
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=productos', $e->getMessage(), 'error');
        }
    }

    // GESTIÓN DE RESEÑAS
    public function resenas() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        try {
            $resenas = $this->resenaModelo->obtenerTodasLasResenas();
            include dirname(__DIR__) . '/modulos/admin/resenas.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function eliminarResena() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=resenas', 'ID de reseña no especificado', 'error');
        }

        try {
            $this->resenaModelo->eliminarResena($id);
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=resenas', 'Reseña eliminada exitosamente', 'success');
        } catch (Exception $e) {
            $this->redireccionarConMensaje('index.php?modulo=admin&accion=resenas', $e->getMessage(), 'error');
        }
    }

    // REPORTES
    public function reportes() {
        if (!verificarAdmin()) {
            header('Location: index.php?modulo=usuario&accion=login');
            exit;
        }

        try {
            $reportes = [
                'usuarios_por_region' => $this->usuarioModelo->obtenerUsuariosPorRegion(),
                'productos_bajo_stock' => $this->productoModelo->obtenerProductosBajoStock(),
                'productos_por_ciudad' => $this->productoModelo->obtenerEstadisticas()['por_ciudad'],
                'actividad_reciente' => $this->obtenerActividadReciente()
            ];
            include dirname(__DIR__) . '/modulos/admin/reportes.php';
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    // MÉTODOS PRIVADOS
    private function validarDatosUsuario($datos) {
        $errores = [];

        if (empty($datos['nombre'])) $errores[] = "El nombre es requerido";
        if (empty($datos['email'])) $errores[] = "El email es requerido";
        if (empty($datos['rol'])) $errores[] = "El rol es requerido";

        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }

        if (!in_array($datos['rol'], ['admin', 'productor', 'consumidor'])) {
            $errores[] = "Rol no válido";
        }

        if (!empty($errores)) {
            throw new Exception(implode(", ", $errores));
        }

        return [
            'nombre' => trim($datos['nombre']),
            'email' => trim($datos['email']),
            'password' => $datos['password'] ?? null,
            'rol' => $datos['rol'],
            'telefono' => trim($datos['telefono'] ?? ''),
            'direccion' => trim($datos['direccion'] ?? ''),
            'id_ciudad' => !empty($datos['id_ciudad']) ? $datos['id_ciudad'] : null
        ];
    }

    private function obtenerActividadReciente() {
        // Obtener actividad reciente (últimos 7 días)
        $actividad = [];
        
        // Usuarios nuevos
        $actividad['usuarios_nuevos'] = $this->usuarioModelo->obtenerUsuariosRecientes(10);
        
        // Productos nuevos
        $actividad['productos_nuevos'] = $this->productoModelo->obtenerProductos(['limite' => 10]);
        
        // Reseñas nuevas
        $actividad['resenas_nuevas'] = $this->resenaModelo->obtenerResenasRecientes(10);
        
        return $actividad;
    }

    private function mostrarError($mensaje) {
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error - Admin AgroStock</title>
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
                                    <a href="index.php?modulo=admin&accion=dashboard" class="btn btn-primary">
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
