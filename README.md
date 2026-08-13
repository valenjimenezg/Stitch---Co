# 🧵 Stitch & Co. - Plataforma Premium de E-commerce y CRM
> Una solución de comercio electrónico empresarial, de código abierto, autogestionada y completamente adaptada a mercados de doble divisa.

---

## 🌟 Resumen Ejecutivo (El Pitch Comercial)

**Stitch & Co.** es una solución de e-commerce de extremo a extremo diseñada para marcas de moda, retail y confección que buscan independizarse de las plataformas SaaS costosas (como Shopify o Magento) y sus comisiones recurrentes. 

Esta plataforma ha sido desarrollada pensando específicamente en la realidad de mercados latinoamericanos (con foco en Venezuela), donde la **doble divisa**, la **conciliación de Pago Móvil** y la **automatización de la relación con el cliente (CRM)** son indispensables para la rentabilidad de cualquier negocio moderno.

### ¿Por qué Stitch & Co. es una inversión de alto retorno?
* **Cero Comisiones por Venta**: Al ser autogestionado, todo el margen de ganancia es para el negocio.
* **Doble Divisa Nativa**: Precios dolarizados con conversión dinámica a bolívares en base a la tasa del Banco Central de Venezuela (BCV).
* **IA y CRM Integrados**: Automatización de soporte al cliente con un Chatbot de Inteligencia Artificial para consultas rápidas de inventario y alertas de stock por correo.
* **Panel de Control Todo en Uno**: Un backend potente para administradores con analíticas gráficas, reportes automatizados en PDF y control de logs de auditoría.

---

## 🚀 Características Clave del Producto

### 1. Sistema Multidivisa Inteligente (Tasa BCV)
* **Precios Base en USD**: Los productos se cargan y editan en dólares americanos para proteger el inventario de la devaluación.
* **Conversión Automatizada**: El cliente final visualiza los precios tanto en **Bs.** como en **USD** de manera dinámica.
* **Administración BCV**: El panel administrativo cuenta con un módulo dedicado para actualizar la tasa oficial en segundos, impactando inmediatamente a todo el sitio web.

### 2. Pasarela de Pago Móvil y Conciliación
* **Checkout Optimizado**: El cliente introduce sus datos de Pago Móvil (teléfono, banco origen, referencia bancaria y fecha de pago).
* **Control de Finanzas**: Los pagos quedan en estado "Pendiente" hasta que el administrador los verifica en el panel financiero y los "Concilia", cambiando el estatus de la orden automáticamente.
* **Facturación Digital**: Generación e impresión instantánea de facturas formateadas en PDF (`Factura PDF`), enviadas automáticamente al correo del cliente con firmas firmadas criptográficamente.

### 3. CRM Inteligente con Inteligencia Artificial
* **Chatbot de Ventas**: Un asistente virtual que busca en tiempo real en la base de datos de Stitch & Co. para responder preguntas sobre disponibilidad, colores, tallas y precios.
* **Alertas de Stock Automatizadas**: Si un producto variante se agota, los clientes pueden presionar "Notificarme cuando haya stock". Cuando el administrador repone el inventario, el sistema envía automáticamente correos electrónicos a los interesados.

### 4. Gestión Completa de Productos y Variantes
* **Variantes Ilimitadas**: Permite configurar un producto (ej. *Camisa Oxford*) con múltiples combinaciones de tallas y colores, asignando imágenes y stocks individuales a cada combinación.
* **Logs de Inventario**: Auditoría detallada de cada movimiento físico en almacén para prevenir pérdidas y detectar robos hormiga.
* **Reportes de Reposición en PDF**: Generación automatizada de reportes dirigidos a proveedores con los ítems que han bajado del stock mínimo de seguridad.

---

## 🛠️ Arquitectura Tecnológica y Modularidad

Stitch & Co. está construido sobre un stack moderno, robusto y altamente escalable:

* **Backend**: Laravel (PHP 8.2), garantizando un rendimiento óptimo, seguridad criptográfica en contraseñas y un robusto motor de base de datos relacional (MySQL/MariaDB).
* **Frontend**: HTML5, JS (ES6) y CSS vainilla para mantener la carga del sitio ultrarrápida y con un diseño estético contemporáneo de alto impacto visual.
* **Generación de Reportes**: FPDF y librerías nativas para generación de PDFs instantáneos.
* **Seguridad**:
  * Control de acceso basado en roles (Admin / Cliente / Invitado).
  * Seguridad OTP (One-Time Password) para recuperación de contraseñas.
  * Throttle de peticiones para mitigar ataques de fuerza bruta en registro y logins.

### Arquitectura de Módulos (Carta Estructurada)
El diseño del sistema mantiene un **bajo acoplamiento** y **alta cohesión**, asegurando que cada servicio funcione de manera independiente.
* **Módulo de Autenticación**: Registro seguro, inicio de sesión y recuperación de contraseña.
* **Módulo de Catálogo**: Búsqueda textual y explorador parametrizado de categorías.
* **Módulo de Ventas**: Control de carrito de compras, cálculo de checkout y generación de facturas.
* **Módulo de Administración**: Dashboard analítico, logs de inventario y configuración de tasa.
* **Módulo CRM y Soporte**: Chatbot de IA, alertas de stock e notificaciones al cliente.

*(Puedes consultar el mapa arquitectónico visual ejecutando `php draw_structured_chart.php` en el servidor).*

---

## 📊 Modelo de Datos (Esquema Relacional)

La base de datos está normalizada para asegurar integridad y rapidez en la lectura de datos:

* **`users`**: Gestiona clientes y administradores con roles específicos.
* **`productos`**: Almacena información principal de los artículos (nombre, descripción, precios, categoría).
* **`producto_variantes`**: Controla el inventario atómico combinando Tallas y Colores con imágenes individuales.
* **`ventas`**: Guarda la cabecera de las transacciones (montos en Bs/USD, estatus de la orden, método de envío, y datos del Pago Móvil).
* **`orden_detalles`**: Detalle de los productos adquiridos por cada orden (variante, precio cobrado, cantidad).
* **`proveedores`**: Datos de los distribuidores para disparar los reportes de reposición PDF de inventario.
* **`inventario_logs`**: Tabla histórica para auditoría de ingresos/egresos del inventario físico.

---

## ⚙️ Guía de Instalación y Despliegue Rápido

### Requisitos del Sistema
* PHP >= 8.2 con extensiones habilitadas (GD, BCMath, PDO SQLite/MySQL, OpenSSL).
* Base de datos MySQL, MariaDB o SQLite.
* Composer para gestión de dependencias.

### Pasos para iniciar el sistema localmente

1. **Clonar el proyecto e instalar dependencias**:
   ```bash
   composer install
   npm install
   ```

2. **Configurar el entorno**:
   Duplica el archivo `.env.example` y renombralo como `.env`. Configura tu base de datos y llaves de correo (SMTP):
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

3. **Ejecutar Migraciones y Poblado de Datos**:
   Crea las tablas y los registros iniciales de prueba (incluyendo productos, categorías, variantes y cuentas de prueba para administradores):
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Levantar el Servidor Local**:
   ```bash
   php artisan serve
   ```
   *Accede a la aplicación en `http://localhost:8000`.*

5. **Generar Diagramas de la Exposición**:
   Si necesitas exponer el sistema ante inversores o clientes, puedes ejecutar los scripts para generar los diagramas visuales (Casos de Uso y Carta Estructurada) listos para diapositivas:
   ```bash
   php draw_diagram.php
   php draw_structured_chart.php
   ```
   *Esto generará `diagrama_casos_uso.png` y `carta_estructurada.png` en la raíz del proyecto.*

---

## 💼 Propuesta Comercial de Venta (Ideal para Agencias o Freelancers)

Si estás utilizando este repositorio para presentarlo a un cliente final, aquí tienes la estructura de venta recomendada:

1. **Licencia de Software Libre**: Al vender Stitch & Co., le otorgas al cliente la propiedad del 100% del código fuente, permitiéndole operar de por vida sin cuotas mensuales.
2. **Servicios de Valor Añadido**:
   * **Implementación y Hosting**: Cobro por configuración inicial en servidores VPS (DigitalOcean, AWS, etc.) y configuración de dominios/SSL.
   * **Soporte Mensual**: Mantenimiento preventivo, backups de bases de datos y actualización manual de tasa de cambio si el cliente no desea hacerlo.
   * **Personalización de Diseño**: Ajuste de colores corporativos, banners publicitarios y carga del catálogo inicial de productos.

---

*Stitch & Co. - Desarrollado bajo altos estándares de ingeniería de software para impulsar negocios minoristas al siguiente nivel.*
