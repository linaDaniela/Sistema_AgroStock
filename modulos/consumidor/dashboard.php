<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Consumidor - AgroStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-primary bg-primary">
        <div class="container">
            <a class="navbar-brand text-white" href="index.php">
                <i class="fas fa-leaf"></i> AgroStock - Consumidor
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="index.php?modulo=consumidor&accion=dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php?modulo=consumidor&accion=pedidos">
                            <i class="fas fa-shopping-cart"></i> Mis Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php?modulo=consumidor&accion=resenas">
                            <i class="fas fa-star"></i> Mis Reseñas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php?modulo=consumidor&accion=consejos">
                            <i class="fas fa-lightbulb"></i> Consejos del Campo
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Consumidor'); ?>
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
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard Consumidor</h1>
                <p class="text-muted">Gestiona tus pedidos y reseñas</p>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4 mb-4">
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
            
            <div class="col-md-4 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo $stats['pedidos_pendientes'] ?? 0; ?></h4>
                                <p class="card-text">Pedidos Pendientes</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo $stats['resenas_realizadas'] ?? 0; ?></h4>
                                <p class="card-text">Reseñas Realizadas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-star fa-2x"></i>
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
                                <a href="index.php?modulo=inicio&accion=productos" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Buscar Productos
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=consumidor&accion=pedidos" class="btn btn-warning w-100">
                                    <i class="fas fa-shopping-cart"></i> Ver Mis Pedidos
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=consumidor&accion=resenas" class="btn btn-success w-100">
                                    <i class="fas fa-star"></i> Mis Reseñas
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=consumidor&accion=consejos" class="btn btn-info w-100">
                                    <i class="fas fa-lightbulb"></i> Consejos del Campo
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
            <!-- Pedidos Recientes -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-shopping-cart"></i> Mis Pedidos Recientes</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($pedidosRecientes)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($pedidosRecientes as $pedido): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>Pedido #<?php echo $pedido['id_pedido']; ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($pedido['nombre_producto']); ?></small>
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
                                            <a href="index.php?modulo=consumidor&accion=verPedido&id=<?php echo $pedido['id_pedido']; ?>" class="btn btn-sm btn-outline-primary mt-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <a href="index.php?modulo=consumidor&accion=pedidos" class="btn btn-outline-primary">
                                    Ver todos los pedidos
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No tienes pedidos recientes</p>
                            <a href="index.php?modulo=inicio&accion=productos" class="btn btn-primary">
                                <i class="fas fa-search"></i> Buscar productos
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Productos Recomendados -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-thumbs-up"></i> Productos Recomendados</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productosRecomendados)): ?>
                            <div class="row">
                                <?php foreach ($productosRecomendados as $producto): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body text-center">
                                                <i class="fas fa-carrot fa-2x text-success mb-2"></i>
                                                <h6 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h6>
                                                <p class="card-text text-success fw-bold">$<?php echo number_format($producto['precio'], 2); ?></p>
                                                <small class="text-muted"><?php echo htmlspecialchars($producto['productor_nombre']); ?></small>
                                                <br>
                                                <a href="index.php?modulo=inicio&accion=producto&id=<?php echo $producto['id_producto']; ?>" class="btn btn-sm btn-outline-success mt-2">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No hay productos recomendados</p>
                            <a href="index.php?modulo=inicio&accion=productos" class="btn btn-outline-success">
                                <i class="fas fa-search"></i> Explorar productos
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Consejos del Campo -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-lightbulb"></i> Consejos del Campo</h5>
                        <a href="index.php?modulo=consumidor&accion=crearConsejo" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Publicar Consejo
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <h6 class="card-title">Cultivo de Tomates</h6>
                                        <p class="card-text small">Los tomates necesitan mucha luz solar y riego regular. Es importante mantener el suelo húmedo pero no encharcado...</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Por: Juan Pérez</small>
                                            <small class="text-muted">15/01/2024</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-info">
                                    <div class="card-body">
                                        <h6 class="card-title">Cuidado de Plantas Aromáticas</h6>
                                        <p class="card-text small">Las hierbas aromáticas como la albahaca, el romero y la menta requieren poda regular para mantener su forma...</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">Por: María García</small>
                                            <small class="text-muted">12/01/2024</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="index.php?modulo=consumidor&accion=consejos" class="btn btn-outline-info">
                                Ver todos los consejos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
