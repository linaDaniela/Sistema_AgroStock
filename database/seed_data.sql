-- Script para insertar datos de prueba en AgroStock
-- Ejecutar después de crear la base de datos

-- Insertar regiones
INSERT INTO regiones (nombre) VALUES 
('Región Central'),
('Región Norte'),
('Región Sur');

-- Insertar departamentos
INSERT INTO departamentos (nombre, id_region) VALUES 
('Departamento Central', 1),
('Departamento Norte', 2),
('Departamento Sur', 3);

-- Insertar ciudades
INSERT INTO ciudades (nombre, id_departamento) VALUES 
('Ciudad Capital', 1),
('Ciudad Norte', 2),
('Ciudad Sur', 3);

-- Insertar usuarios de prueba
INSERT INTO usuarios (nombre, email, password, telefono, direccion, id_ciudad) VALUES 
('Administrador', 'admin@agrostock.com', 'admin123', '123456789', 'Dirección Admin', 1),
('Juan Productor', 'productor@agrostock.com', 'productor123', '987654321', 'Dirección Productor', 2),
('María Consumidor', 'consumidor@agrostock.com', 'consumidor123', '555555555', 'Dirección Consumidor', 3),
('Pedro Productor', 'pedro@agrostock.com', 'pedro123', '111111111', 'Dirección Pedro', 1),
('Ana Consumidor', 'ana@agrostock.com', 'ana123', '222222222', 'Dirección Ana', 2);

-- Asignar roles a usuarios
INSERT INTO usuario_rol (id_usuario, id_rol) VALUES 
(1, 1), -- Admin
(2, 2), -- Productor
(3, 3), -- Consumidor
(4, 2), -- Productor
(5, 3); -- Consumidor

-- Insertar productos de prueba
INSERT INTO productos (nombre, descripcion, precio, stock, id_usuario, id_ciudad_origen) VALUES 
('Tomates Orgánicos', 'Tomates frescos cultivados sin pesticidas, perfectos para ensaladas y salsas', 2.50, 100, 2, 1),
('Lechuga Fresca', 'Lechuga hidropónica de alta calidad, crujiente y nutritiva', 1.80, 50, 2, 1),
('Zanahorias', 'Zanahorias orgánicas de temporada, dulces y jugosas', 1.20, 75, 2, 1),
('Cebollas Dulces', 'Cebollas dulces cultivadas localmente, ideales para cocinar', 0.90, 60, 4, 2),
('Pimientos Rojos', 'Pimientos rojos y verdes frescos, perfectos para ensaladas', 3.20, 40, 4, 2),
('Papas Frescas', 'Papas de la mejor calidad, ideales para cualquier preparación', 1.50, 80, 2, 1),
('Brócoli Orgánico', 'Brócoli orgánico rico en nutrientes y vitaminas', 2.80, 30, 4, 2),
('Espinacas Frescas', 'Espinacas frescas y tiernas, perfectas para ensaladas', 1.60, 45, 2, 1);

-- Insertar algunos pedidos de prueba
INSERT INTO pedidos (id_consumidor, id_productor, fecha, estado) VALUES 
(3, 2, CURDATE(), 'pendiente'),
(5, 4, CURDATE(), 'confirmado');

-- Insertar detalles de pedidos
INSERT INTO detalle_pedidos (id_pedido, id_producto, cantidad, precio_unitario, Precio_total) VALUES 
(1, 1, 2, 2.50, '5.00'),
(1, 2, 1, 1.80, '1.80'),
(2, 4, 3, 0.90, '2.70'),
(2, 5, 2, 3.20, '6.40');

-- Insertar algunas reseñas
INSERT INTO resenas (id_usuario, id_producto, comentario, calificacion, fecha) VALUES 
(3, 1, 'Excelentes tomates, muy frescos y sabrosos', 5, NOW()),
(5, 4, 'Cebollas muy dulces, perfectas para cocinar', 4, NOW()),
(3, 2, 'Lechuga muy fresca y crujiente', 5, NOW());

-- Insertar algunos consejos
INSERT INTO consejos (id_usuario, titulo, contenido, fecha) VALUES 
(2, 'Cultivo de Tomates Orgánicos', 'Para obtener los mejores tomates, asegúrate de regar regularmente y mantener el suelo bien drenado. Los tomates necesitan al menos 6 horas de sol directo al día.', NOW()),
(4, 'Cultivo de Cebollas', 'Las cebollas crecen mejor en suelos sueltos y bien drenados. Plántalas en primavera y cosecha cuando las hojas se pongan amarillas.', NOW());

-- Insertar alertas de stock
INSERT INTO alertas_stock (id_producto, stock_actual, fecha, mensaje) VALUES 
(7, 5, NOW(), 'Stock bajo de brócoli orgánico'),
(8, 8, NOW(), 'Stock bajo de espinacas frescas');
