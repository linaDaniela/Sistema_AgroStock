# AgroStock - Plataforma de Venta Directa de Productos Agrícolas

## Descripción
AgroStock es una plataforma web diseñada para facilitar la venta directa de productos agrícolas, conectando a campesinos y pequeños productores con consumidores conscientes desde una interfaz simple y accesible.

## Características Principales

### 🏗️ Arquitectura MVC
- **Modelos**: Gestión de datos con PDO
- **Vistas**: Interfaz de usuario con Bootstrap 5
- **Controladores**: Lógica de negocio organizada

### 👥 Sistema de Roles

#### 1️⃣ Administrador (admin)
- **Gestión de Usuarios**: Crear, editar, eliminar usuarios y cambiar roles
- **Gestión de Ubicaciones**: Crear/editar regiones y departamentos
- **Gestión de Productos**: Ver y moderar productos publicados
- **Control de Reseñas**: Eliminar reseñas ofensivas o inapropiadas
- **Gestión de Consejos**: Eliminar o editar consejos inapropiados
- **Reportes**: Usuarios por región, productos con bajo stock, actividad en mensajería

#### 2️⃣ Productor (productor)
- **Gestión de Productos**: Crear, editar, eliminar productos
- **Gestión de Inventario**: Actualizar cantidades de stock
- **Gestión de Pedidos**: Ver y actualizar estado de pedidos
- **Reseñas**: Ver calificaciones y comentarios de sus productos
- **Dashboard**: Estadísticas de ventas y productos

#### 3️⃣ Consumidor (consumidor)
- **Búsqueda de Productos**: Filtrar por categoría, región o departamento
- **Gestión de Pedidos**: Realizar pedidos y ver su estado
- **Reseñas**: Calificar y comentar productos
- **Consejos del Campo**: Publicar y comentar artículos agrícolas
- **Dashboard**: Historial de compras y productos recomendados

## Estructura del Proyecto

```
AgroStock/
├── controlador/           # Controladores MVC
│   ├── AdminControlador.php
│   ├── ConsumidorControlador.php
│   ├── InicioControlador.php
│   ├── ProductorControlador.php
│   └── UsuarioControlador.php
├── modelo/               # Modelos de datos
│   ├── conexion.php
│   ├── productoModelo.php
│   ├── usuarioModelo.php
│   ├── pedidoModelo.php
│   └── resenaModelo.php
├── modulos/             # Vistas organizadas por rol
│   ├── admin/
│   │   └── dashboard.php
│   ├── productor/
│   │   ├── dashboard.php
│   │   ├── productos.php
│   │   └── crear_producto.php
│   ├── consumidor/
│   │   └── dashboard.php
│   ├── inicio/
│   │   ├── index.php
│   │   ├── productos.php
│   │   ├── busqueda.php
│   │   ├── ciudad.php
│   │   ├── acerca.php
│   │   └── contacto.php
│   └── auth/
│       ├── login.php
│       └── register.php
├── database/            # Archivos de base de datos
│   ├── agrostock.sql
│   └── seed_data.sql
├── index.php           # Punto de entrada principal
└── README.md
```

## Base de Datos

### Tablas Principales
- **usuarios**: Información de usuarios con roles
- **productos**: Catálogo de productos agrícolas
- **pedidos**: Gestión de pedidos y transacciones
- **resenas**: Sistema de calificaciones y comentarios
- **ciudades**: Ubicaciones geográficas
- **departamentos**: Organización territorial
- **regiones**: División administrativa
- **consejos**: Artículos y tips agrícolas

## Tecnologías Utilizadas

- **Backend**: PHP 8.0+
- **Base de Datos**: MySQL
- **Frontend**: Bootstrap 5.1.3, Font Awesome 6.0.0
- **Patrón**: MVC (Model-View-Controller)
- **Conexión BD**: PDO (PHP Data Objects)

## Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [url-del-repositorio]
   cd Agrostock
   ```

2. **Configurar la base de datos**
   - Crear base de datos MySQL llamada `agrostock`
   - Importar el archivo `database/agrostock.sql`

3. **Configurar conexión**
   - Editar `modelo/conexion.php` con tus credenciales de BD

4. **Configurar servidor web**
   - Asegurar que PHP 8.0+ esté instalado
   - Configurar servidor web (Apache/Nginx) apuntando al directorio

5. **Acceder a la aplicación**
   - Navegar a `http://localhost/Agrostock`

## Funcionalidades Implementadas

### ✅ Completadas
- [x] Sistema de autenticación y autorización
- [x] Dashboard para cada rol de usuario
- [x] Gestión de productos (CRUD)
- [x] Sistema de búsqueda y filtros
- [x] Gestión de pedidos
- [x] Sistema de reseñas
- [x] Interfaz responsive con Bootstrap
- [x] Navegación por ciudades y regiones
- [x] Validación de formularios
- [x] Manejo de errores y mensajes

### 🔄 En Desarrollo
- [ ] Sistema de chat entre usuarios
- [ ] Notificaciones en tiempo real
- [ ] Sistema de pagos
- [ ] Reportes avanzados
- [ ] API REST para móviles

## Seguridad

- Validación de entrada de datos
- Protección contra SQL Injection (PDO)
- Control de acceso basado en roles
- Sanitización de salida HTML
- Manejo seguro de sesiones

## Contribución

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Contacto

- **Email**: info@agrostock.com
- **Teléfono**: +1 234 567 890
- **Dirección**: Colombia

---

**AgroStock** - Conectando productores y consumidores para un futuro agrícola sostenible 🌱
