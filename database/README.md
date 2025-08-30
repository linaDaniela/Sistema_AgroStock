# Configuración de Base de Datos - AgroStock

## Pasos para Configurar la Base de Datos

### 1. Crear la Base de Datos

1. Abre phpMyAdmin (http://localhost/phpmyadmin)
2. Crea una nueva base de datos llamada `agrostock`
3. Selecciona la codificación `utf8mb4_general_ci`

### 2. Importar la Estructura

1. Selecciona la base de datos `agrostock`
2. Ve a la pestaña "Importar"
3. Selecciona el archivo `agrostock.sql`
4. Haz clic en "Continuar"

### 3. Insertar Datos de Prueba

1. Ve a la pestaña "SQL"
2. Copia y pega el contenido del archivo `seed_data.sql`
3. Ejecuta el script

### 4. Verificar la Configuración

1. Verifica que el archivo `app/config/database.php` tenga la configuración correcta:
   - **Host**: localhost
   - **Base de datos**: agrostock
   - **Usuario**: root
   - **Contraseña**: (vacía por defecto en XAMPP)

### 5. Probar la Conexión

1. Accede a `http://localhost/AgroStock`
2. Si todo está configurado correctamente, deberías ver la página principal
3. Prueba iniciar sesión con los usuarios de prueba

## Usuarios de Prueba Disponibles

| Email | Contraseña | Rol |
|-------|------------|-----|
| admin@agrostock.com | admin123 | Administrador |
| productor@agrostock.com | productor123 | Productor |
| consumidor@agrostock.com | consumidor123 | Consumidor |
| pedro@agrostock.com | pedro123 | Productor |
| ana@agrostock.com | ana123 | Consumidor |

## Estructura de Tablas

### Tablas Principales

- **usuarios**: Almacena información de usuarios
- **roles**: Define los roles del sistema
- **usuario_rol**: Relación entre usuarios y roles
- **productos**: Catálogo de productos
- **pedidos**: Órdenes de compra
- **detalle_pedidos**: Detalles de pedidos

### Tablas de Soporte

- **regiones**: Regiones geográficas
- **departamentos**: Departamentos por región
- **ciudades**: Ciudades por departamento
- **resenas**: Reseñas de productos
- **consejos**: Consejos de cultivo
- **alertas_stock**: Alertas de inventario

## Solución de Problemas

### Error de Conexión
- Verifica que MySQL esté ejecutándose
- Confirma las credenciales en `app/config/database.php`
- Asegúrate de que la base de datos `agrostock` exista

### Error de Tablas
- Verifica que el archivo `agrostock.sql` se haya importado correctamente
- Confirma que todas las tablas estén presentes

### Error de Datos
- Ejecuta el script `seed_data.sql` para insertar datos de prueba
- Verifica que los usuarios de prueba estén disponibles

## Notas Importantes

- Las contraseñas están en texto plano para facilitar las pruebas
- El sistema usa PDO para conexiones seguras a la base de datos
- Todas las consultas están preparadas para prevenir inyección SQL
- **Nota de Seguridad**: En producción, siempre usar contraseñas hasheadas
