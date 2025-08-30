<?php
require_once dirname(__DIR__) . '/modelo/productoModelo.php';
require_once dirname(__DIR__) . '/modelo/usuarioModelo.php';

class InicioControlador {
    private $productoModelo;
    private $usuarioModelo;

    public function __construct() {
        $this->productoModelo = new ProductoModelo();
        $this->usuarioModelo = new UsuarioModelo();
    }

    public function index() {
        try {
            // Obtener productos recientes
            $productosRecientes = $this->productoModelo->obtenerProductos(['limite' => 6]);
            
            // Obtener ciudades para el menú
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            
            // Obtener estadísticas básicas
            $stats = $this->productoModelo->obtenerEstadisticas();
            
            // Verificar autenticación
            $autenticado = verificarAutenticacion();
            $usuario = null;
            $tipoUsuario = null;
            
            if ($autenticado) {
                $usuario = $_SESSION['usuario'] ?? null;
                $tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
            }
            
            // Incluir la vista
            include dirname(__DIR__) . '/modulos/inicio.php';
            
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function productos() {
        try {
            // Obtener filtros de búsqueda
            $filtros = [
                'busqueda' => $_GET['busqueda'] ?? '',
                'id_ciudad_origen' => $_GET['id_ciudad_origen'] ?? '',
                'precio_min' => $_GET['precio_min'] ?? '',
                'precio_max' => $_GET['precio_max'] ?? '',
                'orden' => $_GET['orden'] ?? 'p.id_producto DESC',
                'limite' => 12,
                'offset' => ($_GET['pagina'] ?? 1) - 1
            ];
            
            // Obtener productos
            $productos = $this->productoModelo->obtenerProductos($filtros);
            
            // Obtener ciudades para filtros
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            
            // Verificar autenticación
            $autenticado = verificarAutenticacion();
            $usuario = null;
            $tipoUsuario = null;
            
            if ($autenticado) {
                $usuario = $_SESSION['usuario'] ?? null;
                $tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
            }
            
            // Incluir la vista
            include dirname(__DIR__) . '/modulos/productos.php';
            
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function producto() {
        try {
            $productoId = $_GET['id'] ?? 0;
            
            if (!$productoId) {
                throw new Exception("Producto no especificado");
            }
            
            // Obtener producto
            $producto = $this->productoModelo->obtenerProductoPorId($productoId);
            
            if (!$producto) {
                throw new Exception("Producto no encontrado");
            }
            
            // Obtener productos similares
            $productosSimilares = $this->productoModelo->obtenerProductosSimilares($productoId, 4);
            
            // Verificar autenticación
            $autenticado = verificarAutenticacion();
            $usuario = null;
            $tipoUsuario = null;
            
            if ($autenticado) {
                $usuario = $_SESSION['usuario'] ?? null;
                $tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
            }
            
            // Incluir la vista
            include dirname(__DIR__) . '/modulos/producto_detalle.php';
            
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function buscar() {
        try {
            $termino = $_GET['q'] ?? '';
            
            if (empty($termino)) {
                $this->redireccionar('index.php?modulo=inicio&accion=productos');
            }
            
            // Buscar productos
            $productos = $this->productoModelo->buscarProductos($termino, 20);
            
            // Verificar autenticación
            $autenticado = verificarAutenticacion();
            $usuario = null;
            $tipoUsuario = null;
            
            if ($autenticado) {
                $usuario = $_SESSION['usuario'] ?? null;
                $tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
            }
            
            // Incluir la vista
            include dirname(__DIR__) . '/modulos/busqueda.php';
            
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function ciudad() {
        try {
            $ciudadId = $_GET['id'] ?? 0;
            
            if (!$ciudadId) {
                throw new Exception("Ciudad no especificada");
            }
            
            // Obtener productos de la ciudad
            $filtros = [
                'id_ciudad_origen' => $ciudadId,
                'limite' => 12,
                'offset' => ($_GET['pagina'] ?? 1) - 1
            ];
            
            $productos = $this->productoModelo->obtenerProductos($filtros);
            
            // Obtener información de la ciudad
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            $ciudad = null;
            foreach ($ciudades as $c) {
                if ($c['id_ciudad'] == $ciudadId) {
                    $ciudad = $c;
                    break;
                }
            }
            
            if (!$ciudad) {
                throw new Exception("Ciudad no encontrada");
            }
            
            // Verificar autenticación
            $autenticado = verificarAutenticacion();
            $usuario = null;
            $tipoUsuario = null;
            
            if ($autenticado) {
                $usuario = $_SESSION['usuario'] ?? null;
                $tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
            }
            
            // Incluir la vista
            include dirname(__DIR__) . '/modulos/ciudad.php';
            
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
        }
    }

    public function acerca() {
        // Verificar autenticación
        $autenticado = verificarAutenticacion();
        $usuario = null;
        $tipoUsuario = null;
        
        if ($autenticado) {
            $usuario = $_SESSION['usuario'] ?? null;
            $tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
        }
        
        include dirname(__DIR__) . '/modulos/acerca.php';
    }

    public function contacto() {
        // Verificar autenticación
        $autenticado = verificarAutenticacion();
        $usuario = null;
        $tipoUsuario = null;
        
        if ($autenticado) {
            $usuario = $_SESSION['usuario'] ?? null;
            $tipoUsuario = $_SESSION['tipo_usuario'] ?? null;
        }
        
        include dirname(__DIR__) . '/modulos/contacto.php';
    }

    private function mostrarError($mensaje) {
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error - AgroStock</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="vista/css/estilos.css" rel="stylesheet">
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
                                    <a href="index.php" class="btn btn-primary">
                                        <i class="fas fa-home"></i> Volver al inicio
                                    </a>
                                    <a href="javascript:history.back()" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Volver atrás
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>';
    }

    private function redireccionar($url) {
        header("Location: $url");
        exit;
    }
}
?>
