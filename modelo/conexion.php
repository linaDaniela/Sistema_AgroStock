<?php
/**
 * Archivo de conexión a la base de datos
 * AgroStock - Plataforma de venta directa de productos agrícolas
 */

class Conexion {
    private $host = 'localhost';
    private $dbname = 'Agrostock';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $pdo;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }

    public function getConexion() {
        return $this->pdo;
    }

    public function cerrarConexion() {
        $this->pdo = null;
    }

    public function ejecutarConsulta($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }

    public function obtenerFila($sql, $params = []) {
        $stmt = $this->ejecutarConsulta($sql, $params);
        return $stmt->fetch();
    }

    public function obtenerFilas($sql, $params = []) {
        $stmt = $this->ejecutarConsulta($sql, $params);
        return $stmt->fetchAll();
    }

    public function insertar($tabla, $datos) {
        $campos = implode(', ', array_keys($datos));
        $valores = ':' . implode(', :', array_keys($datos));
        
        $sql = "INSERT INTO {$tabla} ({$campos}) VALUES ({$valores})";
        
        $stmt = $this->ejecutarConsulta($sql, $datos);
        return $this->pdo->lastInsertId();
    }

    public function actualizar($tabla, $datos, $where) {
        $campos = [];
        foreach (array_keys($datos) as $campo) {
            $campos[] = "{$campo} = :{$campo}";
        }
        
        $sql = "UPDATE {$tabla} SET " . implode(', ', $campos) . " WHERE {$where}";
        
        return $this->ejecutarConsulta($sql, $datos);
    }

    public function eliminar($tabla, $where) {
        $sql = "DELETE FROM {$tabla} WHERE {$where}";
        return $this->ejecutarConsulta($sql);
    }

    public function contar($tabla, $where = '1') {
        $sql = "SELECT COUNT(*) as total FROM {$tabla} WHERE {$where}";
        $resultado = $this->obtenerFila($sql);
        return $resultado['total'];
    }

    public function existe($tabla, $where) {
        return $this->contar($tabla, $where) > 0;
    }
}

// Función global para obtener conexión
function obtenerConexion() {
    static $conexion = null;
    if ($conexion === null) {
        $conexion = new Conexion();
    }
    return $conexion->getConexion();
}

// Función para iniciar transacción
function iniciarTransaccion() {
    $conexion = obtenerConexion();
    $conexion->beginTransaction();
}

// Función para confirmar transacción
function confirmarTransaccion() {
    $conexion = obtenerConexion();
    $conexion->commit();
}

// Función para revertir transacción
function revertirTransaccion() {
    $conexion = obtenerConexion();
    $conexion->rollBack();
}
?>
