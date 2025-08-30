<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - AgroStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../vista/css/estilos.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-leaf"></i> AgroStock
            </a>
        </div>
    </nav>

    <!-- Mensajes -->
    <?php echo mostrarMensaje(); ?>

    <!-- Login Section -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="index.php?modulo=usuario&accion=login" method="POST" data-validate>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <a href="index.php?modulo=usuario&accion=recuperarContraseña" class="text-decoration-none">
                                <i class="fas fa-key"></i> ¿Olvidaste tu contraseña?
                            </a>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-0">¿No tienes una cuenta?</p>
                            <a href="index.php?modulo=usuario&accion=registrar" class="btn btn-outline-success">
                                <i class="fas fa-user-plus"></i> Registrarse
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Información adicional -->
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <h6 class="card-title">
                            <i class="fas fa-info-circle text-info"></i> ¿Eres productor?
                        </h6>
                        <p class="card-text small">
                            Regístrate como productor para comenzar a vender tus productos agrícolas directamente a los consumidores.
                        </p>
                        <a href="index.php?modulo=usuario&accion=registrar" class="btn btn-sm btn-outline-primary">
                            Registrarse como Productor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="text-center">
                <p>&copy; 2025 AgroStock. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vista/js/dashboard.js"></script>
</body>
</html>
