<?php
// Archivo para configurar la base de datos automáticamente
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Configuración de Base de Datos - AgroStock</h2>";

try {
    // Configuración de conexión
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $charset = 'utf8mb4';
    
    echo "<p>🔧 Configurando conexión a MySQL...</p>";
    
    // Conectar sin especificar base de datos
    $dsn = "mysql:host={$host};charset={$charset}";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    echo "<p>✅ Conexión a MySQL establecida</p>";
    
    // Crear base de datos si no existe
    $dbname = 'agrostock';
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    echo "<p>✅ Base de datos '{$dbname}' creada/verificada</p>";
    
    // Conectar a la base de datos específica
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset={$charset}", $username, $password, $options);
    echo "<p>✅ Conectado a la base de datos '{$dbname}'</p>";
    
    // Leer y ejecutar el archivo SQL
    $sqlFile = 'database/agrostock.sql';
    if (file_exists($sqlFile)) {
        echo "<p>📖 Leyendo archivo SQL...</p>";
        
        $sql = file_get_contents($sqlFile);
        
        // Dividir el SQL en comandos individuales
        $commands = explode(';', $sql);
        
        $executed = 0;
        foreach ($commands as $command) {
            $command = trim($command);
            if (!empty($command) && !preg_match('/^(--|\/\*|SET|START|COMMIT)/', $command)) {
                try {
                    $pdo->exec($command);
                    $executed++;
                } catch (Exception $e) {
                    // Ignorar errores de tablas que ya existen
                    if (!strpos($e->getMessage(), 'already exists')) {
                        echo "<p>⚠️ Error en comando: " . substr($command, 0, 50) . "... - " . $e->getMessage() . "</p>";
                    }
                }
            }
        }
        
        echo "<p>✅ Ejecutados {$executed} comandos SQL</p>";
    } else {
        echo "<p>❌ Archivo SQL no encontrado: {$sqlFile}</p>";
    }
    
    // Verificar que las tablas se crearon correctamente
    echo "<h3>Verificando tablas:</h3>";
    $tablas = ['usuarios', 'productos', 'ciudades', 'departamentos', 'regiones', 'pedidos', 'detalle_pedidos', 'resenas', 'alertas_stock', 'consejos'];
    
    foreach ($tablas as $tabla) {
        try {
            $resultado = $pdo->query("SELECT COUNT(*) as total FROM {$tabla}");
            $count = $resultado->fetch();
            echo "<p>✅ Tabla '{$tabla}': {$count['total']} registros</p>";
        } catch (Exception $e) {
            echo "<p>❌ Tabla '{$tabla}' no existe: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h3>🎉 Configuración completada exitosamente</h3>";
    echo "<p><a href='index.php'>Ir a la aplicación</a></p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error durante la configuración: " . $e->getMessage() . "</p>";
    echo "<h3>❌ Configuración fallida</h3>";
    
    echo "<h4>Posibles soluciones:</h4>";
    echo "<ul>";
    echo "<li>Verificar que MySQL esté ejecutándose</li>";
    echo "<li>Verificar las credenciales de MySQL (usuario: root, contraseña: vacía)</li>";
    echo "<li>Verificar que el usuario tenga permisos para crear bases de datos</li>";
    echo "<li>Verificar que el archivo database/agrostock.sql existe</li>";
    echo "</ul>";
}
?>
