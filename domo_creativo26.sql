DROP DATABASE IF EXISTS domo_creativo26;

CREATE DATABASE domo_creativo26;
USE domo_creativo26;

---------------------
-- TABLAS MAESTRAS
---------------------

CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL,
    activo       TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE metodos_pago (
    id_metodo_pago INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(50) NOT NULL,
    activo         TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE tipos_evento (
    id_tipo_evento INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(100) NOT NULL,
    activo         TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE tipos_servicio (
    id_tipo_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre           VARCHAR(100) NOT NULL,
    activo           TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE unidades (
    id_unidad INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(50) NOT NULL,
    activo    TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE estados_pedido (
    id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(50) NOT NULL,
    orden     INT NOT NULL,
    activo    TINYINT(1) NOT NULL DEFAULT 1
);

---------------------------------------
-- TABLAS PRINCIPALES
----------------------------------------

CREATE TABLE perfiles (
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    nombre    VARCHAR(50) NOT NULL,
    codigo    VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE usuarios (
    id_usuario          INT AUTO_INCREMENT PRIMARY KEY,
    nombre              VARCHAR(100) NOT NULL,
    apellidos           VARCHAR(100),
    telefono            VARCHAR(20),
    email               VARCHAR(100) NOT NULL UNIQUE,
    password_hash       VARCHAR(255) NOT NULL,
    localidad           VARCHAR(100),       -- texto libre para registro simple
    estado              ENUM('activo','inactivo') DEFAULT 'activo',
    fecha_registro      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    codigo_recuperacion VARCHAR(10),
    codigo_expira       DATETIME,
    id_perfil           INT,
    FOREIGN KEY (id_perfil) REFERENCES perfiles(id_perfil)
);

CREATE TABLE clientes (
    id_cliente       INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario       INT NOT NULL UNIQUE,
    razon_social     VARCHAR(150),
    cuit             VARCHAR(20),
    condicion_fiscal ENUM('consumidor_final','monotributista','responsable_inscripto') DEFAULT 'consumidor_final',
    direccion        VARCHAR(200),
    localidad        VARCHAR(100),
    barrio           VARCHAR(150),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL,
    contacto     VARCHAR(100),
    telefono     VARCHAR(50),
    email        VARCHAR(100),
    cuit         VARCHAR(20),
    tipo         VARCHAR(20) DEFAULT 'empresa',
    localidad    VARCHAR(100),
    provincia    VARCHAR(100),
    activo       TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE productos (
    id_producto  INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL,
    descripcion  TEXT,
    precio       DECIMAL(10,2) NOT NULL,
    id_categoria INT,
    id_proveedor INT,
    id_unidad    INT,
    activo       TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY (id_proveedor) REFERENCES proveedores(id_proveedor),
    FOREIGN KEY (id_unidad)    REFERENCES unidades(id_unidad)
);

CREATE TABLE galeria (
    id_galeria     INT AUTO_INCREMENT PRIMARY KEY,
    titulo         VARCHAR(100) NOT NULL,
    descripcion    TEXT,
    imagen         VARCHAR(255),
    id_tipo_evento INT,
    activo         TINYINT(1) NOT NULL DEFAULT 1,
    fecha          DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tipo_evento) REFERENCES tipos_evento(id_tipo_evento)
);

--------------------------------------------
-- TABLAS TRANSACCIONALES / DE GESTIÓN
--------------------------------------------

CREATE TABLE inventario (
    id_inventario        INT AUTO_INCREMENT PRIMARY KEY,
    id_producto          INT NOT NULL,
    stock                INT DEFAULT 0,
    stock_minimo         INT DEFAULT 5,
    ultima_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE servicios_personalizados (
    id_servicio_personalizado INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario                INT,
    id_producto               INT,
    id_tipo_servicio          INT,
    color                     VARCHAR(50),
    texto_personalizado       VARCHAR(255),
    archivo_diseno            VARCHAR(255),
    talle                     VARCHAR(10),
    medidas                   VARCHAR(50),
    material                  VARCHAR(50),
    estado                    ENUM('pendiente','en_diseno','aprobado','cancelado') DEFAULT 'pendiente',
    fecha_creacion            DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario)         REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_producto)        REFERENCES productos(id_producto),
    FOREIGN KEY (id_tipo_servicio)   REFERENCES tipos_servicio(id_tipo_servicio)
);

CREATE TABLE carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha      DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado     VARCHAR(20) DEFAULT 'activo',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE carrito_detalle (
    id_detalle                INT AUTO_INCREMENT PRIMARY KEY,
    id_carrito                INT NOT NULL,
    id_producto               INT NULL,
    id_servicio_personalizado INT NULL,
    cantidad                  INT DEFAULT 1,
    precio_unitario           DECIMAL(10,2),
    FOREIGN KEY (id_carrito)                  REFERENCES carrito(id_carrito),
    FOREIGN KEY (id_producto)                 REFERENCES productos(id_producto),
    FOREIGN KEY (id_servicio_personalizado)   REFERENCES servicios_personalizados(id_servicio_personalizado)
);

CREATE TABLE presupuestos (
    id_presupuesto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario     INT NOT NULL,
    id_tipo_evento INT,
    descripcion    TEXT,
    total_estimado DECIMAL(10,2),
    estado         VARCHAR(50) DEFAULT 'pendiente',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario)     REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_tipo_evento) REFERENCES tipos_evento(id_tipo_evento)
);

CREATE TABLE detalle_presupuestos (
    id_detalle                INT AUTO_INCREMENT PRIMARY KEY,
    id_presupuesto            INT NOT NULL,
    id_producto               INT NULL,
    id_servicio_personalizado INT NULL,
    cantidad                  INT DEFAULT 1,
    precio_unitario           DECIMAL(10,2),
    FOREIGN KEY (id_presupuesto)              REFERENCES presupuestos(id_presupuesto),
    FOREIGN KEY (id_producto)                 REFERENCES productos(id_producto),
    FOREIGN KEY (id_servicio_personalizado)   REFERENCES servicios_personalizados(id_servicio_personalizado)
);

CREATE TABLE pedidos (
    id_pedido      INT AUTO_INCREMENT PRIMARY KEY,
    id_presupuesto INT NOT NULL,
    id_cliente     INT,                        -- FK directa al cliente para consultas rápidas
    id_estado      INT NOT NULL DEFAULT 1,
    fecha_pedido   DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega  DATE,
    FOREIGN KEY (id_presupuesto) REFERENCES presupuestos(id_presupuesto),
    FOREIGN KEY (id_cliente)     REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_estado)      REFERENCES estados_pedido(id_estado)
);

CREATE TABLE detalle_pedido (
    id_detalle                INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido                 INT NOT NULL,
    id_producto               INT NULL,
    id_servicio_personalizado INT NULL,
    cantidad                  INT,
    precio_unitario           DECIMAL(10,2),
    FOREIGN KEY (id_pedido)                   REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_producto)                 REFERENCES productos(id_producto),
    FOREIGN KEY (id_servicio_personalizado)   REFERENCES servicios_personalizados(id_servicio_personalizado)
);

CREATE TABLE pagos (
    id_pago        INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido      INT NOT NULL,
    id_metodo_pago INT NOT NULL,
    monto          DECIMAL(10,2),
    fecha_pago     DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado         ENUM('pendiente','confirmado','rechazado') DEFAULT 'pendiente',
    FOREIGN KEY (id_pedido)        REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_metodo_pago)   REFERENCES metodos_pago(id_metodo_pago)
);

CREATE TABLE facturas (
    id_factura       INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido        INT NOT NULL,
    id_cliente       INT,
    numero_factura   VARCHAR(20),               -- número de comprobante imprimible
    tipo_comprobante ENUM('A','B','C') DEFAULT 'B',
    fecha_emision    DATETIME DEFAULT CURRENT_TIMESTAMP,
    monto_total      DECIMAL(10,2),
    archivo_factura  VARCHAR(255),
    estado           VARCHAR(50) DEFAULT 'generada',
    FOREIGN KEY (id_pedido)  REFERENCES pedidos(id_pedido),
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
);

CREATE TABLE mensajes (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    remitente  VARCHAR(100),
    mensaje    TEXT,
    leido      TINYINT(1) DEFAULT 0,
    fecha      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE arqueo_caja (
    id_arqueo      INT AUTO_INCREMENT PRIMARY KEY,
    fecha_apertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre   DATETIME,
    monto_inicial  DECIMAL(10,2) DEFAULT 0,
    monto_final    DECIMAL(10,2),
    total_ingresos DECIMAL(10,2) DEFAULT 0,
    total_egresos  DECIMAL(10,2) DEFAULT 0,
    observaciones  TEXT,
    estado         ENUM('abierta','cerrada') DEFAULT 'abierta',
    id_usuario     INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE gastos (
    id_gasto    INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL,
    monto       DECIMAL(10,2) NOT NULL,
    fecha       DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_usuario  INT,
    id_arqueo   INT,                            -- vincula el gasto al arqueo de caja correspondiente
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_arqueo)  REFERENCES arqueo_caja(id_arqueo)
);

CREATE TABLE estadisticas (
    id_estadistica INT AUTO_INCREMENT PRIMARY KEY,
    tipo           VARCHAR(50),
    descripcion    VARCHAR(100),
    valor          DECIMAL(10,2),
    periodo        VARCHAR(20),                 -- ej: '2025-06', '2025'
    id_usuario     INT,                         -- quién generó o a quién pertenece
    fecha          DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

CREATE TABLE auditoria (
    id_auditoria     INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario       INT NOT NULL,
    accion           VARCHAR(20) NOT NULL,        -- INSERT, UPDATE, DELETE
    tabla_afectada   VARCHAR(50) NOT NULL,
    registro_id      INT NOT NULL,
    descripcion      TEXT,
    datos_anteriores TEXT NULL,
    datos_nuevos     TEXT NULL,
    fecha            DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);
----------------------------------------
-- DATOS INICIALES — TABLAS MAESTRAS
----------------------------------------

INSERT INTO categorias (nombre) VALUES
('impresiones 3d'),
('manualidades');

INSERT INTO metodos_pago (nombre) VALUES
('mercado pago'),
('efectivo'),
('transferencia bancaria');

INSERT INTO tipos_evento (nombre) VALUES
('cumpleaños'),
('casamiento'),
('bautismo'),
('evento temático');

INSERT INTO tipos_servicio (nombre) VALUES
('diseño gráfico'),
('impresión 3d'),
('serigrafía'),
('ploteo de corte'),
('sublimación'),
('fotografía');

INSERT INTO unidades (nombre) VALUES
('unidad'),
('par'),
('docena'),
('set'),
('kit'),
('caja');

INSERT INTO estados_pedido (nombre, orden) VALUES
('pendiente',      1),
('presupuestado',  2),
('aprobado',       3),
('en diseño',      4),
('en producción',  5),
('en sublimación', 6),
('terminado',      7),
('entregado',      8),
('cancelado',      9);

------------------------------------------
-- DATOS INICIALES — TABLAS PRINCIPALES
------------------------------------------

INSERT INTO perfiles (nombre, codigo) VALUES
('cliente',           'perf-01'),
('administrador',     'perf-02'),
('diseñador gráfico', 'perf-03');

INSERT INTO usuarios (nombre, apellidos, telefono, email, localidad, password_hash, id_perfil) VALUES
('juan',  'perez',    '3704000000', 'juan@cliente.com',        'pirané',
 '$2y$10$OlFumEVyQblFipwQ4TTbfOLY1vhfporrWYBLXMCrx3qlpF9dC7gEm', 1),

('juana', 'admin',    '3704287279', 'domoadmin12@gmail.com',   'formosa',
 '$2y$10$4HU4L90vksnj.m8/G1bPj.S.idnG4G6MlPiWFdObRgWH7s2/v.Z.O', 2),

('luis',  'designer', '3704219205', 'domodisenador@gmail.com', 'formosa',
 '$2y$10$992t65ZIcPFwsGOWZ0F1/eTg2YtG0lWzXA2hOIfSvBPsgOWPUN1K.', 3);

INSERT INTO clientes (id_usuario, razon_social, cuit, condicion_fiscal, direccion, localidad, barrio) VALUES
(1, 'juan perez', '20-12345678-9', 'consumidor_final', 'sarmiento 123', 'formosa', 'independencia');

INSERT INTO proveedores (nombre, contacto, telefono, email, cuit, tipo, localidad, provincia) VALUES
('proveedor 3d',       'carlos', '123456789', 'proveedor3d@mail.com', '20-12345678-9', 'empresa', 'resistencia', 'chaco'),
('lucia manualidades', 'lucia',  '987654321', 'lucia@mail.com',       '27-98765432-1', 'persona', 'quilmes',     'buenos aires');

INSERT INTO productos (nombre, descripcion, precio, id_categoria, id_proveedor, id_unidad) VALUES
('figura 3d',            'impresión 3d personalizada', 2500, 1, 1, 1),
('souvenir',             'souvenir hecho a mano',       1800, 2, 2, 6),
('remera personalizada', 'sublimación full color',      3500, 2, 2, 1),
('taza personalizada',   'sublimación fotografía',      2000, 1, 1, 1);

INSERT INTO galeria (titulo, descripcion, imagen, id_tipo_evento) VALUES
('cumple temático dinosaurios', 'decoración completa',      'dino.jpg',    1),
('casamiento vintage',          'ambientación vintage',     'vintage.jpg', 2),
('bautismo celeste',            'souvenirs personalizados', 'bautismo.jpg',3);

----------------------------------------
-- DATOS DE EJEMPLO — TRANSACCIONALES
----------------------------------------

INSERT INTO inventario (id_producto, stock, stock_minimo) VALUES
(1, 20, 5),
(2, 15, 3),
(3, 10, 4),
(4, 12, 3);

INSERT INTO servicios_personalizados
(id_usuario, id_producto, id_tipo_servicio, color, texto_personalizado, talle, material)
VALUES
(1, 1, 1, 'rojo', 'feliz cumple', NULL, 'pla'),
(1, 3, 5, 'azul', 'cumple 15',    'M',  'algodón');

INSERT INTO carrito (id_usuario, estado) VALUES
(1, 'activo');

INSERT INTO carrito_detalle (id_carrito, id_producto, cantidad, precio_unitario) VALUES
(1, 1, 2, 2500),
(1, 4, 1, 2000);

INSERT INTO presupuestos (id_usuario, id_tipo_evento, descripcion, total_estimado) VALUES
(1, 1, 'decoracion tematica personalizada', 4300);

INSERT INTO detalle_presupuestos (id_presupuesto, id_producto, cantidad, precio_unitario) VALUES
(1, 1, 2, 2500),
(1, 2, 1, 1800);

INSERT INTO pedidos (id_presupuesto, id_cliente, id_estado) VALUES (1, 1, 1);

INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario)
VALUES (1, 1, 2, 2500);

INSERT INTO pagos (id_pedido, id_metodo_pago, monto, estado) VALUES
(1, 1, 4300, 'confirmado');

INSERT INTO facturas (id_pedido, id_cliente, numero_factura, tipo_comprobante, monto_total) VALUES
(1, 1, '0001-00000001', 'B', 4300);

INSERT INTO mensajes (id_usuario, remitente, mensaje, leido) VALUES
(1, 'juan', 'consulta sobre mi pedido', 0);

INSERT INTO arqueo_caja (monto_inicial, id_usuario) VALUES (10000, 2);

INSERT INTO gastos (descripcion, monto, id_usuario, id_arqueo) VALUES
('compra materiales',    1500, 2, 1),
('compra filamento pla',  800, 2, 1);

INSERT INTO estadisticas (tipo, descripcion, valor, periodo, id_usuario) VALUES
('ventas', 'primera venta registrada', 4300, '2025-06', 2);

