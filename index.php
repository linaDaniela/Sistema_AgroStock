<?php
/**
 * AgroStock - Plataforma de venta directa de productos agrícolas
 * Archivo principal que maneja el enrutamiento de la aplicación
 */

// Configuración básica
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constantes
define('BASE_PATH', __DIR__);
define('MODELO_PATH', BASE_PATH . '/modelo');
define('CONTROLADOR_PATH', BASE_PATH . '/controlador');
define('VISTA_PATH', BASE_PATH . '/vista');
define('MODULOS_PATH', BASE_PATH . '/modulos');

// Autoloader simple
spl_autoload_register(function ($class) {
    $paths = [
        MODELO_PATH . '/',
        CONTROLADOR_PATH . '/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Incluir archivos necesarios
require_once MODELO_PATH . '/conexion.php';

// Función para verificar autenticación
function verificarAutenticacion() {
    session_start();
    return isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true;
}

// Función para verificar permisos de administrador
function verificarAdmin() {
    if (!verificarAutenticacion()) {
        return false;
    }
    return $_SESSION['tipo_usuario'] === 'admin';
}

// Función para verificar permisos de productor
function verificarProductor() {
    if (!verificarAutenticacion()) {
        return false;
    }
    return $_SESSION['tipo_usuario'] === 'productor';
}

// Función para verificar permisos de consumidor
function verificarConsumidor() {
    if (!verificarAutenticacion()) {
        return false;
    }
    return $_SESSION['tipo_usuario'] === 'consumidor';
}

// Función para mostrar mensajes
function mostrarMensaje() {
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        $tipo = $_SESSION['tipo_mensaje'] ?? 'info';
        unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
        
        $clase = match($tipo) {
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            default => 'alert-info'
        };
        
        return "<div class='alert {$clase} alert-dismissible fade show' role='alert'>
                    {$mensaje}
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
    }
    return '';
}

// Obtener parámetros de la URL
$modulo = $_GET['modulo'] ?? 'inicio';
$accion = $_GET['accion'] ?? 'index';

// Mapeo de módulos y controladores
$modulos = [
    'inicio' => 'InicioControlador',
    'usuario' => 'UsuarioControlador',
    'producto' => 'ProductoControlador',
    'categoria' => 'CategoriaControlador',
    'admin' => 'AdminControlador',
    'productor' => 'ProductorControlador',
    'consumidor' => 'ConsumidorControlador'
];

// Validar módulo
if (!array_key_exists($modulo, $modulos)) {
    $modulo = 'inicio';
}

$controladorClass = $modulos[$modulo];

try {
    // Verificar si el controlador existe
    $controladorFile = CONTROLADOR_PATH . '/' . $controladorClass . '.php';
    if (!file_exists($controladorFile)) {
        // Si no existe el controlador, mostrar página de inicio
        $modulo = 'inicio';
        $controladorClass = 'InicioControlador';
    }
    
    // Instanciar controlador
    $controlador = new $controladorClass();
    
    // Validar método
    if (!method_exists($controlador, $accion)) {
        $accion = 'index';
    }
    
    // Ejecutar acción
    $controlador->$accion();
    
} catch (Exception $e) {
    // Manejo de errores
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
                            <p class="lead">Ha ocurrido un error en la aplicación:</p>
                            <div class="alert alert-danger">
                                <strong>' . htmlspecialchars($e->getMessage()) . '</strong>
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
?>
