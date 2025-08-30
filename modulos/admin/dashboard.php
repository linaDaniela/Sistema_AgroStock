<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador - AgroStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-leaf"></i> AgroStock - Admin
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?modulo=admin&accion=dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?modulo=admin&accion=usuarios">
                            <i class="fas fa-users"></i> Usuarios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?modulo=admin&accion=productos">
                            <i class="fas fa-box"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?modulo=admin&accion=resenas">
                            <i class="fas fa-star"></i> Reseñas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?modulo=admin&accion=reportes">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Admin'); ?>
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
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard Administrador</h1>
                <p class="text-muted">Panel de control y gestión de AgroStock</p>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo $stats['usuarios']['total'] ?? 0; ?></h4>
                                <p class="card-text">Usuarios</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title"><?php echo $stats['productos']['total'] ?? 0; ?></h4>
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
                                <h4 class="card-title"><?php echo $stats['pedidos']['total'] ?? 0; ?></h4>
                                <p class="card-text">Pedidos</p>
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
                                <h4 class="card-title"><?php echo $stats['resenas']['total'] ?? 0; ?></h4>
                                <p class="card-text">Reseñas</p>
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
                                <a href="index.php?modulo=admin&accion=crearUsuario" class="btn btn-primary w-100">
                                    <i class="fas fa-user-plus"></i> Crear Usuario
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=admin&accion=productos" class="btn btn-success w-100">
                                    <i class="fas fa-box"></i> Gestionar Productos
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=admin&accion=resenas" class="btn btn-warning w-100">
                                    <i class="fas fa-star"></i> Moderar Reseñas
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="index.php?modulo=admin&accion=reportes" class="btn btn-info w-100">
                                    <i class="fas fa-chart-bar"></i> Ver Reportes
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
            <!-- Usuarios Recientes -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-users"></i> Usuarios Recientes</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($usuariosRecientes)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($usuariosRecientes as $usuario): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($usuario['email']); ?></small>
                                        </div>
                                        <span class="badge bg-<?php echo $usuario['rol'] === 'admin' ? 'danger' : ($usuario['rol'] === 'productor' ? 'success' : 'primary'); ?>">
                                            <?php echo ucfirst($usuario['rol']); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No hay usuarios recientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Productos Recientes -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-box"></i> Productos Recientes</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productosRecientes)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($productosRecientes as $producto): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>
                                            <br>
                                            <small class="text-muted">$<?php echo number_format($producto['precio'], 2); ?></small>
                                        </div>
                                        <span class="badge bg-<?php echo $producto['stock'] > 0 ? 'success' : 'danger'; ?>">
                                            Stock: <?php echo $producto['stock']; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No hay productos recientes</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas de Stock -->
    <?php if (!empty($alertasStock)): ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-white">
                        <h5><i class="fas fa-exclamation-triangle"></i> Alertas de Stock Bajo</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Productor</th>
                                        <th>Stock Actual</th>
                                        <th>Stock Mínimo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alertasStock as $alerta): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($alerta['nombre_producto']); ?></td>
                                            <td><?php echo htmlspecialchars($alerta['nombre_productor']); ?></td>
                                            <td><span class="badge bg-danger"><?php echo $alerta['stock']; ?></span></td>
                                            <td><?php echo $alerta['stock_minimo']; ?></td>
                                            <td>
                                                <a href="index.php?modulo=admin&accion=productos" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
