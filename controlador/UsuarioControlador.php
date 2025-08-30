<?php
require_once dirname(__DIR__) . '/modelo/usuarioModelo.php';

class UsuarioControlador {
    private $usuarioModelo;

    public function __construct() {
        $this->usuarioModelo = new UsuarioModelo();
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar datos
                $datos = $this->validarDatosRegistro($_POST);
                
                // Registrar usuario
                $id = $this->usuarioModelo->registrarUsuario($datos);
                
                // Iniciar sesión automáticamente
                $usuario = $this->usuarioModelo->obtenerUsuarioPorId($id);
                $this->iniciarSesion($usuario);
                
                $this->redireccionarConMensaje('index.php', 'Usuario registrado exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('index.php?modulo=usuario&accion=registrar', $e->getMessage(), 'error');
            }
        } else {
            // Obtener ciudades para el formulario
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            include dirname(__DIR__) . '/modulos/registro.php';
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = trim($_POST['email']);
                $password = $_POST['password'];

                if (empty($email) || empty($password)) {
                    throw new Exception("Todos los campos son requeridos");
                }

                $usuario = $this->usuarioModelo->autenticarUsuario($email, $password);
                
                if ($usuario) {
                    $this->iniciarSesion($usuario);
                    
                    // Redireccionar según el tipo de usuario
                    switch ($usuario['rol']) {
                        case 'admin':
                            $this->redireccionarConMensaje('index.php?modulo=admin&accion=dashboard', 'Bienvenido administrador', 'success');
                            break;
                        case 'productor':
                            $this->redireccionarConMensaje('index.php?modulo=productor&accion=dashboard', 'Bienvenido productor', 'success');
                            break;
                        case 'consumidor':
                            $this->redireccionarConMensaje('index.php', 'Bienvenido consumidor', 'success');
                            break;
                        default:
                            $this->redireccionarConMensaje('index.php', 'Bienvenido', 'success');
                    }
                } else {
                    throw new Exception("Credenciales incorrectas");
                }
            } catch (Exception $e) {
                $this->redireccionarConMensaje('index.php?modulo=usuario&accion=login', $e->getMessage(), 'error');
            }
        } else {
            include dirname(__DIR__) . '/modulos/login.php';
        }
    }

    public function logout() {
        session_destroy();
        $this->redireccionarConMensaje('index.php', 'Sesión cerrada exitosamente', 'info');
    }

    public function perfil() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redireccionarConMensaje('index.php?modulo=usuario&accion=login', 'Debe iniciar sesión', 'warning');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $datos = $this->validarDatosPerfil($_POST);
                $this->usuarioModelo->actualizarUsuario($_SESSION['usuario_id'], $datos);
                
                // Actualizar datos de sesión
                $usuario = $this->usuarioModelo->obtenerUsuarioPorId($_SESSION['usuario_id']);
                $_SESSION['usuario'] = $usuario;
                
                $this->redireccionarConMensaje('index.php?modulo=usuario&accion=perfil', 'Perfil actualizado exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('index.php?modulo=usuario&accion=perfil', $e->getMessage(), 'error');
            }
        } else {
            $usuario = $this->usuarioModelo->obtenerUsuarioPorId($_SESSION['usuario_id']);
            $ciudades = $this->usuarioModelo->obtenerCiudades();
            include dirname(__DIR__) . '/modulos/perfil.php';
        }
    }

    public function cambiarContraseña() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redireccionarConMensaje('index.php?modulo=usuario&accion=login', 'Debe iniciar sesión', 'warning');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $passwordActual = $_POST['password_actual'];
                $passwordNuevo = $_POST['password_nuevo'];
                $passwordConfirmar = $_POST['password_confirmar'];

                if (empty($passwordActual) || empty($passwordNuevo) || empty($passwordConfirmar)) {
                    throw new Exception("Todos los campos son requeridos");
                }

                if ($passwordNuevo !== $passwordConfirmar) {
                    throw new Exception("Las contraseñas no coinciden");
                }

                if (strlen($passwordNuevo) < 6) {
                    throw new Exception("La contraseña debe tener al menos 6 caracteres");
                }

                $this->usuarioModelo->cambiarContraseña($_SESSION['usuario_id'], $passwordActual, $passwordNuevo);
                $this->redireccionarConMensaje('index.php?modulo=usuario&accion=perfil', 'Contraseña cambiada exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('index.php?modulo=usuario&accion=cambiarContraseña', $e->getMessage(), 'error');
            }
        } else {
            include dirname(__DIR__) . '/modulos/cambiar_password.php';
        }
    }

    public function recuperarContraseña() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = trim($_POST['email']);
                
                if (empty($email)) {
                    throw new Exception("El email es requerido");
                }

                $resultado = $this->usuarioModelo->recuperarContraseña($email);
                
                // Aquí normalmente se enviaría un email con el token
                // Por ahora, solo mostramos un mensaje
                $this->redireccionarConMensaje('modulos/login.php', 'Se ha enviado un enlace de recuperación a su email', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('modulos/recuperar.php', $e->getMessage(), 'error');
            }
        } else {
            include '../modulos/recuperar.php';
        }
    }

    public function resetearContraseña() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $token = $_POST['token'];
                $passwordNuevo = $_POST['password_nuevo'];
                $passwordConfirmar = $_POST['password_confirmar'];

                if (empty($token) || empty($passwordNuevo) || empty($passwordConfirmar)) {
                    throw new Exception("Todos los campos son requeridos");
                }

                if ($passwordNuevo !== $passwordConfirmar) {
                    throw new Exception("Las contraseñas no coinciden");
                }

                if (strlen($passwordNuevo) < 6) {
                    throw new Exception("La contraseña debe tener al menos 6 caracteres");
                }

                $this->usuarioModelo->resetearContraseña($token, $passwordNuevo);
                $this->redireccionarConMensaje('modulos/login.php', 'Contraseña restablecida exitosamente', 'success');
            } catch (Exception $e) {
                $this->redireccionarConMensaje('modulos/resetear_password.php', $e->getMessage(), 'error');
            }
        } else {
            $token = $_GET['token'] ?? '';
            include '../modulos/resetear_password.php';
        }
    }

    private function validarDatosRegistro($datos) {
        $errores = [];

        if (empty($datos['nombre'])) $errores[] = "El nombre es requerido";
        if (empty($datos['email'])) $errores[] = "El email es requerido";
        if (empty($datos['password'])) $errores[] = "La contraseña es requerida";
        if (empty($datos['rol'])) $errores[] = "El rol es requerido";

        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }

        if (strlen($datos['password']) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres";
        }

        if (!in_array($datos['rol'], ['productor', 'consumidor'])) {
            $errores[] = "Rol no válido";
        }

        if (!empty($errores)) {
            throw new Exception(implode(", ", $errores));
        }

        return [
            'nombre' => trim($datos['nombre']),
            'email' => trim($datos['email']),
            'password' => $datos['password'],
            'rol' => $datos['rol'],
            'telefono' => trim($datos['telefono'] ?? ''),
            'direccion' => trim($datos['direccion'] ?? ''),
            'id_ciudad' => !empty($datos['id_ciudad']) ? $datos['id_ciudad'] : null
        ];
    }

    private function validarDatosPerfil($datos) {
        $errores = [];

        if (empty($datos['nombre'])) $errores[] = "El nombre es requerido";
        if (empty($datos['email'])) $errores[] = "El email es requerido";

        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email no es válido";
        }

        if (!empty($errores)) {
            throw new Exception(implode(", ", $errores));
        }

        return [
            'nombre' => trim($datos['nombre']),
            'email' => trim($datos['email']),
            'telefono' => trim($datos['telefono'] ?? ''),
            'direccion' => trim($datos['direccion'] ?? ''),
            'id_ciudad' => !empty($datos['id_ciudad']) ? $datos['id_ciudad'] : null
        ];
    }

    private function iniciarSesion($usuario) {
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario'] = $usuario;
        $_SESSION['tipo_usuario'] = $usuario['rol'];
        $_SESSION['autenticado'] = true;
    }

    private function redireccionarConMensaje($url, $mensaje, $tipo) {
        $_SESSION['mensaje'] = $mensaje;
        $_SESSION['tipo_mensaje'] = $tipo;
        header("Location: $url");
        exit;
    }
}
?>
