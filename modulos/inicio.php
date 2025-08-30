<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroStock - Plataforma de Productos Agrícolas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../vista/css/estilos.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-leaf"></i> AgroStock
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?modulo=inicio&accion=productos">Productos</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="ciudadesDropdown" role="button" data-bs-toggle="dropdown">
                            Ciudades
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach ($ciudades as $ciudad): ?>
                                <li>
                                    <a class="dropdown-item" href="index.php?modulo=inicio&accion=ciudad&id=<?php echo $ciudad['id_ciudad']; ?>">
                                        <?php echo htmlspecialchars($ciudad['nombre']); ?>
                                        <?php if (isset($ciudad['departamento_nombre'])): ?>
                                            <small class="text-muted"> - <?php echo htmlspecialchars($ciudad['departamento_nombre']); ?></small>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?modulo=inicio&accion=acerca">Acerca de</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?modulo=inicio&accion=contacto">Contacto</a>
                    </li>
                </ul>
                
                <!-- Formulario de búsqueda -->
                <form class="d-flex me-3" action="index.php" method="GET">
                    <input type="hidden" name="modulo" value="inicio">
                    <input type="hidden" name="accion" value="buscar">
                    <input class="form-control me-2" type="search" name="q" placeholder="Buscar productos..." aria-label="Buscar">
                    <button class="btn btn-outline-success" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <ul class="navbar-nav">
                    <?php if ($autenticado): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($usuario['nombre']); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php?modulo=usuario&accion=perfil">Mi Perfil</a></li>
                                <?php if ($tipoUsuario === 'productor'): ?>
                                    <li><a class="dropdown-item" href="index.php?modulo=productor&accion=dashboard">Dashboard Productor</a></li>
                                <?php elseif ($tipoUsuario === 'admin'): ?>
                                    <li><a class="dropdown-item" href="index.php?modulo=admin&accion=dashboard">Dashboard Admin</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?modulo=usuario&accion=logout">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?modulo=usuario&accion=login">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="index.php?modulo=usuario&accion=registrar">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mensajes -->
    <?php echo mostrarMensaje(); ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Bienvenido a AgroStock</h1>
            <p class="lead mb-4">La plataforma que conecta productores agrícolas con consumidores conscientes</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="index.php?modulo=inicio&accion=productos" class="btn btn-light btn-lg">
                    <i class="fas fa-shopping-cart"></i> Ver Productos
                </a>
                <?php if (!$autenticado): ?>
                    <a href="index.php?modulo=usuario&accion=registrar" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">¿Por qué elegir AgroStock?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Productos Frescos</h5>
                            <p class="card-text">Conectamos directamente con productores locales para garantizar la frescura de nuestros productos.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-leaf fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Agricultura Sostenible</h5>
                            <p class="card-text">Promovemos prácticas agrícolas sostenibles y respetuosas con el medio ambiente.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Comercio Justo</h5>
                            <p class="card-text">Apoyamos a pequeños productores con precios justos y transparencia en las transacciones.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Productos Recientes</h2>
            <div class="row">
                <?php if (!empty($productosRecientes)): ?>
                    <?php foreach ($productosRecientes as $producto): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card product-card h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-carrot fa-3x text-success mb-3"></i>
                                <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                <p class="card-text text-success fw-bold">$<?php echo number_format($producto['precio'], 2); ?></p>
                                <p class="card-text small text-muted">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($producto['productor_nombre']); ?>
                                </p>
                                <?php if (isset($producto['ciudad_origen'])): ?>
                                <p class="card-text small text-muted">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($producto['ciudad_origen']); ?>
                                </p>
                                <?php endif; ?>
                                <a href="index.php?modulo=inicio&accion=producto&id=<?php echo $producto['id_producto']; ?>" class="btn btn-primary">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No hay productos disponibles.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a href="index.php?modulo=inicio&accion=productos" class="btn btn-primary btn-lg">
                    Ver Todos los Productos
                </a>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nuestros Números</h2>
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="dashboard-card">
                        <div class="dashboard-stat"><?php echo $stats['total_productos'] ?? 0; ?></div>
                        <div class="dashboard-label">Productos Disponibles</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="dashboard-card">
                        <div class="dashboard-stat"><?php echo count($ciudades); ?></div>
                        <div class="dashboard-label">Ciudades</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="dashboard-card">
                        <div class="dashboard-stat"><?php echo $stats['sin_stock'] ?? 0; ?></div>
                        <div class="dashboard-label">Productos Sin Stock</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="dashboard-card">
                        <div class="dashboard-stat">100%</div>
                        <div class="dashboard-label">Frescos y Naturales</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-leaf"></i> AgroStock</h5>
                    <p>Conectando productores y consumidores para un futuro más sostenible.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Enlaces Útiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php?modulo=inicio&accion=acerca" class="text-light">Acerca de</a></li>
                        <li><a href="index.php?modulo=inicio&accion=contacto" class="text-light">Contacto</a></li>
                        <li><a href="index.php?modulo=inicio&accion=productos" class="text-light">Productos</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-envelope"></i> info@agrostock.com</p>
                    <p><i class="fas fa-phone"></i> +1 234 567 890</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; 2024 AgroStock. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vista/js/dashboard.js"></script>
</body>
</html>
