<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Productos - AgroStock</title>
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
                        <a class="nav-link text-white" href="index.php?modulo=productor&accion=dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="index.php?modulo=productor&accion=productos">
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
            <div class="col-md-8">
                <h1><i class="fas fa-box"></i> Mis Productos</h1>
                <p class="text-muted">Gestiona tu catálogo de productos</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="index.php?modulo=productor&accion=crearProducto" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nuevo Producto
                </a>
            </div>
        </div>
    </div>

    <!-- Productos -->
    <div class="container mt-4">
        <?php if (!empty($productos)): ?>
            <div class="row">
                <?php foreach ($productos as $producto): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <i class="fas fa-carrot fa-3x text-success"></i>
                                </div>
                                <h5 class="card-title text-center"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                <p class="card-text text-success fw-bold text-center">$<?php echo number_format($producto['precio'], 2); ?></p>
                                
                                <?php if (!empty($producto['descripcion'])): ?>
                                    <p class="card-text small"><?php echo htmlspecialchars(substr($producto['descripcion'], 0, 100)) . (strlen($producto['descripcion']) > 100 ? '...' : ''); ?></p>
                                <?php endif; ?>
                                
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">Stock</small>
                                        <br>
                                        <span class="badge bg-<?php echo $producto['stock'] > 0 ? 'success' : 'danger'; ?>">
                                            <?php echo $producto['stock']; ?> unidades
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Estado</small>
                                        <br>
                                        <span class="badge bg-<?php echo $producto['stock'] > 0 ? 'success' : 'secondary'; ?>">
                                            <?php echo $producto['stock'] > 0 ? 'Disponible' : 'Agotado'; ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="index.php?modulo=productor&accion=editarProducto&id=<?php echo $producto['id_producto']; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#stockModal<?php echo $producto['id_producto']; ?>">
                                        <i class="fas fa-boxes"></i> Actualizar Stock
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $producto['id_producto']; ?>">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actualizar Stock -->
                    <div class="modal fade" id="stockModal<?php echo $producto['id_producto']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Actualizar Stock - <?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="index.php?modulo=productor&accion=actualizarStock" method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                        <div class="mb-3">
                                            <label for="cantidad" class="form-label">Nueva cantidad de stock</label>
                                            <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo $producto['stock']; ?>" min="0" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-warning">Actualizar Stock</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Eliminar -->
                    <div class="modal fade" id="deleteModal<?php echo $producto['id_producto']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmar Eliminación</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de que quieres eliminar el producto "<strong><?php echo htmlspecialchars($producto['nombre']); ?></strong>"?</p>
                                    <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <a href="index.php?modulo=productor&accion=eliminarProducto&id=<?php echo $producto['id_producto']; ?>" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <h3>No tienes productos registrados</h3>
                <p class="text-muted">Comienza creando tu primer producto para vender</p>
                <a href="index.php?modulo=productor&accion=crearProducto" class="btn btn-success">
                    <i class="fas fa-plus"></i> Crear primer producto
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
