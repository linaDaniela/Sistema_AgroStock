<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos en <?php echo htmlspecialchars($ciudad['nombre']); ?> - AgroStock</title>
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

    <!-- Header -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <h1><i class="fas fa-map-marker-alt"></i> Productos en <?php echo htmlspecialchars($ciudad['nombre']); ?></h1>
                <?php if (isset($ciudad['departamento_nombre'])): ?>
                    <p class="text-muted"><?php echo htmlspecialchars($ciudad['departamento_nombre']); ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-end">
                <p class="text-muted"><?php echo count($productos); ?> productos encontrados</p>
            </div>
        </div>
    </div>

    <!-- Productos -->
    <div class="container">
        <?php if (!empty($productos)): ?>
            <div class="row">
                <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-carrot fa-3x text-success mb-3"></i>
                            <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            <p class="card-text text-success fw-bold">$<?php echo number_format($producto['precio'], 2); ?></p>
                            
                            <?php if (!empty($producto['descripcion'])): ?>
                                <p class="card-text small"><?php echo htmlspecialchars(substr($producto['descripcion'], 0, 100)) . (strlen($producto['descripcion']) > 100 ? '...' : ''); ?></p>
                            <?php endif; ?>
                            
                            <p class="card-text small text-muted">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($producto['productor_nombre']); ?>
                            </p>
                            
                            <p class="card-text small text-muted">
                                <i class="fas fa-boxes"></i> Stock: <?php echo $producto['stock']; ?> unidades
                            </p>
                            
                            <a href="index.php?modulo=inicio&accion=producto&id=<?php echo $producto['id_producto']; ?>" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                <h3>No hay productos en <?php echo htmlspecialchars($ciudad['nombre']); ?></h3>
                <p class="text-muted">Aún no hay productores registrados en esta ciudad</p>
                <div class="mt-3">
                    <a href="index.php?modulo=inicio&accion=productos" class="btn btn-primary">
                        <i class="fas fa-shopping-basket"></i> Ver todos los productos
                    </a>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Volver al inicio
                    </a>
                </div>
            </div>
        <?php endif; ?>
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
</body>
</html>
