<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Productor - AgroStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-success bg-success">
        <div class="container">
            <a class="navbar-brand text-white" href="index.php">
                <i class="fas fa-leaf"></i> AgroStock - Productor
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="index.php?modulo=productor&accion=dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php?modulo=productor&accion=productos">
                            <i class="fas fa-box"></i> Mis Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php?modulo=productor&accion=pedidos">
                            <i class="fas fa-shopping-cart"></i> Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php?modulo=productor&accion=resenas">
                            <i class="fas fa-star"></i> Reseñas
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Productor'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.php">Ir al Sitio</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?modulo=usuario&accion=logout">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mensajes -->
    <?php echo mostrarMensaje(); ?>

    <!-- Header -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard Productor</h1>
                <p class="text-muted">Gestiona tus productos y pedidos</p>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo $stats['total_productos'] ?? 0; ?></h4>
                                <p class="card-text">Productos</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo $stats['productos_bajo_stock'] ?? 0; ?></h4>
                                <p class="card-text">Stock Bajo</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo $stats['total_pedidos'] ?? 0; ?></h4>
                                <p class="card-text">Total Pedidos</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo $stats['pedidos_pendientes'] ?? 0; ?></h4>
                                <p class="card-text">Pendientes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=productor&accion=crearProducto" class="btn btn-success w-100">
                                    <i class="fas fa-plus"></i> Nuevo Producto
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=productor&accion=productos" class="btn btn-primary w-100">
                                    <i class="fas fa-box"></i> Gestionar Productos
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=productor&accion=pedidos" class="btn btn-warning w-100">
                                    <i class="fas fa-shopping-cart"></i> Ver Pedidos
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=productor&accion=resenas" class="btn btn-info w-100">
                                    <i class="fas fa-star"></i> Ver Reseñas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Datos Recientes -->
    <div class="container mt-4">
        <div class="row">
            <!-- Productos -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-box"></i> Mis Productos</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productos)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($productos, 0, 5) as $producto): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                                            <br>
                                            <small class="text-muted">$<?php echo number_format($producto['precio'], 2); ?></small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-<?php echo $producto['stock'] > 0 ? 'success' : 'danger'; ?>">
                                                Stock: <?php echo $producto['stock']; ?>
                                            </span>
                                            <br>
                                            <a href="index.php?modulo=productor&accion=editarProducto&id=<?php echo $producto['id_producto']; ?>" class="btn btn-sm btn-outline-primary mt-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($productos) > 5): ?>
                                <div class="text-center mt-3">
                                    <a href="index.php?modulo=productor&accion=productos" class="btn btn-outline-success">
                                        Ver todos los productos
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted">No tienes productos registrados</p>
                            <a href="index.php?modulo=productor&accion=crearProducto" class="btn btn-success">
                                <i class="fas fa-plus"></i> Crear primer producto
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Pedidos Recientes -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-shopping-cart"></i> Pedidos Recientes</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pedidosRecientes)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($pedidosRecientes as $pedido): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Pedido #<?php echo $pedido['id_pedido']; ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($pedido['nombre_consumidor']); ?></small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-<?php 
                                                echo match($pedido['estado']) {
                                                    'pendiente' => 'warning',
                                                    'confirmado' => 'info',
                                                    'enviado' => 'primary',
                                                    'entregado' => 'success',
                                                    'cancelado' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>">
                                                <?php echo ucfirst($pedido['estado']); ?>
                                            </span>
                                            <br>
                                            <a href="index.php?modulo=productor&accion=verPedido&id=<?php echo $pedido['id_pedido']; ?>" class="btn btn-sm btn-outline-primary mt-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No hay pedidos recientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reseñas Recientes -->
    <?php if (!empty($resenasRecientes)): ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-star"></i> Reseñas Recientes</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($resenasRecientes as $resena): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="card-title"><?php echo htmlspecialchars($resena['nombre_producto']); ?></h6>
                                                    <p class="card-text small"><?php echo htmlspecialchars($resena['comentario']); ?></p>
                                                    <small class="text-muted">Por: <?php echo htmlspecialchars($resena['nombre_consumidor']); ?></small>
                                                </div>
                                                <div class="text-end">
                                                    <div class="text-warning">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star<?php echo $i <= $resena['calificacion'] ? '' : '-o'; ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <small class="text-muted"><?php echo $resena['calificacion']; ?>/5</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
