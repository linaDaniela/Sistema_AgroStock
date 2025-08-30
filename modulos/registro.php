<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - AgroStock</title>
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

    <!-- Register Section -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0"><i class="fas fa-user-plus"></i> Crear Cuenta</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="index.php?modulo=usuario&accion=registrar" method="POST" data-validate>
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre Completo *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" class="form-control" id="telefono" name="telefono">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="id_ciudad" class="form-label">Ciudad</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-city"></i>
                                    </span>
                                    <select class="form-select" id="id_ciudad" name="id_ciudad">
                                        <option value="">Selecciona una ciudad</option>
                                        <?php if (isset($ciudades)): ?>
                                            <?php foreach ($ciudades as $ciudad): ?>
                                                <option value="<?php echo $ciudad['id_ciudad']; ?>">
                                                    <?php echo htmlspecialchars($ciudad['nombre']); ?>
                                                    <?php if (isset($ciudad['departamento_nombre'])): ?>
                                                        - <?php echo htmlspecialchars($ciudad['departamento_nombre']); ?>
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="rol" class="form-label">Tipo de Usuario *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <select class="form-select" id="rol" name="rol" required>
                                        <option value="">Selecciona tu tipo de usuario</option>
                                        <option value="productor">Productor - Vendo productos agrícolas</option>
                                        <option value="consumidor">Consumidor - Compro productos agrícolas</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                </div>
                                <div class="form-text">La contraseña debe tener al menos 6 caracteres</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password_confirmar" class="form-label">Confirmar Contraseña *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" required>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terminos" name="terminos" required>
                                <label class="form-check-label" for="terminos">
                                    Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> *
                                </label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-user-plus"></i> Crear Cuenta
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-0">¿Ya tienes una cuenta?</p>
                            <a href="index.php?modulo=usuario&accion=login" class="btn btn-outline-primary">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Información adicional -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                                <h6 class="card-title">Productor</h6>
                                <p class="card-text small">
                                    Vende tus productos agrícolas directamente a los consumidores. 
                                    Sin intermediarios, mejores precios.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-shopping-basket fa-3x text-primary mb-3"></i>
                                <h6 class="card-title">Consumidor</h6>
                                <p class="card-text small">
                                    Compra productos frescos directamente de los productores. 
                                    Calidad garantizada, precios justos.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="text-center">
                <p>&copy; 2024 AgroStock. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vista/js/dashboard.js"></script>
    
    <script>
        // Validación de contraseñas
        document.getElementById('password_confirmar').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmar = this.value;
            
            if (password !== confirmar) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
        
        document.getElementById('password').addEventListener('input', function() {
            const confirmar = document.getElementById('password_confirmar');
            if (confirmar.value) {
                if (this.value !== confirmar.value) {
                    confirmar.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    confirmar.setCustomValidity('');
                }
            }
        });
    </script>
</body>
</html>
