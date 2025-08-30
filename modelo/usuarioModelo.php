<?php
require_once 'conexion.php';

class UsuarioModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
    }

    public function registrarUsuario($datos) {
        try {
            // Verificar si el usuario ya existe
            if ($this->existeUsuario($datos['email'])) {
                throw new Exception("El email ya está registrado");
            }

            // Encriptar contraseña
            $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);

            $id = $this->conexion->insertar('usuarios', $datos);
            return $id;
        } catch (Exception $e) {
            throw new Exception("Error al registrar usuario: " . $e->getMessage());
        }
    }

    public function autenticarUsuario($email, $password) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $usuario = $this->conexion->obtenerFila($sql, ['email' => $email]);

            if ($usuario && password_verify($password, $usuario['password'])) {
                unset($usuario['password']); // No enviar contraseña en la sesión
                return $usuario;
            }
            return false;
        } catch (Exception $e) {
            throw new Exception("Error en autenticación: " . $e->getMessage());
        }
    }

    public function obtenerUsuarioPorId($id) {
        try {
            $sql = "SELECT u.*, c.nombre as ciudad_nombre, d.nombre as departamento_nombre, r.nombre as region_nombre 
                    FROM usuarios u 
                    LEFT JOIN ciudades c ON u.id_ciudad = c.id_ciudad 
                    LEFT JOIN departamentos d ON c.id_departamento = d.id_departamento 
                    LEFT JOIN regiones r ON d.id_region = r.id_region 
                    WHERE u.id_usuario = :id";
            return $this->conexion->obtenerFila($sql, ['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Error al obtener usuario: " . $e->getMessage());
        }
    }

    public function actualizarUsuario($id, $datos) {
        try {
            // Si se está actualizando la contraseña, encriptarla
            if (isset($datos['password']) && !empty($datos['password'])) {
                $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
            } else {
                unset($datos['password']);
            }

            $where = "id_usuario = :id";
            $datos['id'] = $id;
            
            return $this->conexion->actualizar('usuarios', $datos, $where);
        } catch (Exception $e) {
            throw new Exception("Error al actualizar usuario: " . $e->getMessage());
        }
    }

    public function eliminarUsuario($id) {
        try {
            $where = "id_usuario = :id";
            return $this->conexion->eliminar('usuarios', $where, ['id' => $id]);
        } catch (Exception $e) {
            throw new Exception("Error al eliminar usuario: " . $e->getMessage());
        }
    }

    public function existeUsuario($email) {
        try {
            return $this->conexion->existe('usuarios', "email = '{$email}'");
        } catch (Exception $e) {
            throw new Exception("Error al verificar usuario: " . $e->getMessage());
        }
    }

    public function obtenerProductores() {
        try {
            $sql = "SELECT u.*, c.nombre as ciudad_nombre 
                    FROM usuarios u 
                    LEFT JOIN ciudades c ON u.id_ciudad = c.id_ciudad 
                    WHERE u.rol = 'productor'";
            return $this->conexion->obtenerFilas($sql);
        } catch (Exception $e) {
            throw new Exception("Error al obtener productores: " . $e->getMessage());
        }
    }

    public function obtenerConsumidores() {
        try {
            $sql = "SELECT u.*, c.nombre as ciudad_nombre 
                    FROM usuarios u 
                    LEFT JOIN ciudades c ON u.id_ciudad = c.id_ciudad 
                    WHERE u.rol = 'consumidor'";
            return $this->conexion->obtenerFilas($sql);
        } catch (Exception $e) {
            throw new Exception("Error al obtener consumidores: " . $e->getMessage());
        }
    }

    public function cambiarContraseña($id, $passwordActual, $passwordNuevo) {
        try {
            // Verificar contraseña actual
            $sql = "SELECT password FROM usuarios WHERE id_usuario = :id";
            $usuario = $this->conexion->obtenerFila($sql, ['id' => $id]);

            if (!$usuario || !password_verify($passwordActual, $usuario['password'])) {
                throw new Exception("La contraseña actual es incorrecta");
            }

            // Actualizar con nueva contraseña
            $datos = ['password' => password_hash($passwordNuevo, PASSWORD_DEFAULT)];
            $where = "id_usuario = :id";
            $datos['id'] = $id;

            return $this->conexion->actualizar('usuarios', $datos, $where);
        } catch (Exception $e) {
            throw new Exception("Error al cambiar contraseña: " . $e->getMessage());
        }
    }

    public function obtenerCiudades() {
        try {
            $sql = "SELECT c.*, d.nombre as departamento_nombre 
                    FROM ciudades c 
                    LEFT JOIN departamentos d ON c.id_departamento = d.id_departamento 
                    ORDER BY c.nombre ASC";
            return $this->conexion->obtenerFilas($sql);
        } catch (Exception $e) {
            throw new Exception("Error al obtener ciudades: " . $e->getMessage());
        }
    }

    public function obtenerDepartamentos() {
        try {
            $sql = "SELECT d.*, r.nombre as region_nombre 
                    FROM departamentos d 
                    LEFT JOIN regiones r ON d.id_region = r.id_region 
                    ORDER BY d.nombre ASC";
            return $this->conexion->obtenerFilas($sql);
        } catch (Exception $e) {
            throw new Exception("Error al obtener departamentos: " . $e->getMessage());
        }
    }

    public function obtenerRegiones() {
        try {
            $sql = "SELECT * FROM regiones ORDER BY nombre ASC";
            return $this->conexion->obtenerFilas($sql);
        } catch (Exception $e) {
            throw new Exception("Error al obtener regiones: " . $e->getMessage());
        }
    }

    public function obtenerEstadisticas() {
        try {
            $stats = [];
            
            // Total de usuarios
            $stats['total_usuarios'] = $this->conexion->contar('usuarios');
            
            // Usuarios por rol
            $stats['productores'] = $this->conexion->contar('usuarios', "rol = 'productor'");
            $stats['consumidores'] = $this->conexion->contar('usuarios', "rol = 'consumidor'");
            $stats['admins'] = $this->conexion->contar('usuarios', "rol = 'admin'");
            
            return $stats;
        } catch (Exception $e) {
            throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
        }
    }
}
?>
