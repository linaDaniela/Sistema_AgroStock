<?php
// Archivo de prueba para verificar la conexión a la base de datos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Prueba de Conexión a Base de Datos</h2>";

try {
    // Verificar si existe el archivo de conexión
    if (!file_exists('modelo/conexion.php')) {
        throw new Exception("El archivo modelo/conexion.php no existe");
    }
    
    echo "<p>✅ Archivo de conexión encontrado</p>";
    
    // Incluir el archivo de conexión
    require_once 'modelo/conexion.php';
    
    echo "<p>✅ Archivo de conexión incluido correctamente</p>";
    
    // Intentar crear una conexión
    $conexion = new Conexion();
    
    echo "<p>✅ Conexión creada correctamente</p>";
    
    // Verificar si la base de datos existe
    $pdo = $conexion->getConexion();
    
    // Probar una consulta simple
    $resultado = $pdo->query("SELECT 1 as test");
    $test = $resultado->fetch();
    
    echo "<p>✅ Consulta de prueba ejecutada correctamente</p>";
    
    // Verificar si las tablas existen
    $tablas = ['usuarios', 'productos', 'ciudades', 'departamentos', 'regiones'];
    
    foreach ($tablas as $tabla) {
        try {
            $resultado = $pdo->query("SELECT COUNT(*) as total FROM {$tabla}");
            $count = $resultado->fetch();
            echo "<p>✅ Tabla '{$tabla}' existe con {$count['total']} registros</p>";
        } catch (Exception $e) {
            echo "<p>❌ Tabla '{$tabla}' no existe o hay error: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h3>✅ Conexión exitosa</h3>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<h3>❌ Conexión fallida</h3>";
}

echo "<hr>";
echo "<h3>Información del Sistema:</h3>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ Disponible' : '❌ No disponible') . "</p>";
echo "<p>Directorio actual: " . __DIR__ . "</p>";
?>
