DROP DATABASE IF EXISTS domo_creativo26;

CREATE DATABASE domo_creativo26;
USE domo_creativo26;

-- PERFILES
CREATE TABLE perfiles (
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    codigo VARCHAR(20) NOT NULL UNIQUE
);

-- USUARIOS
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    estado ENUM('activo','inactivo') DEFAULT 'activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    codigo_recuperacion VARCHAR(10),
    codigo_expira DATETIME,
    id_perfil INT,

    FOREIGN KEY (id_perfil)
    REFERENCES perfiles(id_perfil)
);

-- CATEGORIAS
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- PROVEEDORES
CREATE TABLE proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(50),
    email VARCHAR(100),
    cuit VARCHAR(20),
    tipo VARCHAR(20) DEFAULT 'empresa'
);

-- PRODUCTOS
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    id_categoria INT,
    id_proveedor INT,

    FOREIGN KEY (id_categoria)
    REFERENCES categorias(id_categoria),

    FOREIGN KEY (id_proveedor)
    REFERENCES proveedores(id_proveedor)
);

-- SERVICIOS PERSONALIZADOS
CREATE TABLE servicios_personalizados (
    id_servicio_personalizado INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_producto INT,
    color VARCHAR(50),
    texto_personalizado VARCHAR(255),
    archivo_diseno VARCHAR(255),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
    REFERENCES usuarios(id_usuario),

    FOREIGN KEY (id_producto)
    REFERENCES productos(id_producto)
);

-- INVENTARIO
CREATE TABLE inventario (
    id_inventario INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    stock INT DEFAULT 0,
    stock_minimo INT DEFAULT 5,
    ultima_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_producto)
    REFERENCES productos(id_producto)
);

-- CARRITO
CREATE TABLE carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'activo',

    FOREIGN KEY (id_usuario)
    REFERENCES usuarios(id_usuario)
);

-- CARRITO DETALLE
CREATE TABLE carrito_detalle (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_carrito INT NOT NULL,
    id_producto INT NULL,
    id_servicio_personalizado INT NULL,
    cantidad INT DEFAULT 1,
    precio_unitario DECIMAL(10,2),

    FOREIGN KEY (id_carrito)
    REFERENCES carrito(id_carrito),

    FOREIGN KEY (id_producto)
    REFERENCES productos(id_producto),

    FOREIGN KEY (id_servicio_personalizado)
    REFERENCES servicios_personalizados(id_servicio_personalizado)
);

-- PRESUPUESTOS
CREATE TABLE presupuestos (
    id_presupuesto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    descripcion TEXT,
    total_estimado DECIMAL(10,2),
    estado VARCHAR(50) DEFAULT 'pendiente',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
    REFERENCES usuarios(id_usuario)
);

-- DETALLE PRESUPUESTOS
CREATE TABLE detalle_presupuestos (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_presupuesto INT NOT NULL,
    id_producto INT NULL,
    id_servicio_personalizado INT NULL,
    cantidad INT DEFAULT 1,
    precio_unitario DECIMAL(10,2),

    FOREIGN KEY (id_presupuesto)
    REFERENCES presupuestos(id_presupuesto),

    FOREIGN KEY (id_producto)
    REFERENCES productos(id_producto),

    FOREIGN KEY (id_servicio_personalizado)
    REFERENCES servicios_personalizados(id_servicio_personalizado)
);

-- PEDIDOS
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_presupuesto INT NOT NULL,
    estado VARCHAR(50) DEFAULT 'en diseño',
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATE,

    FOREIGN KEY (id_presupuesto)
    REFERENCES presupuestos(id_presupuesto)
);

-- DETALLE PEDIDO
CREATE TABLE detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_producto INT NULL,
    id_servicio_personalizado INT NULL,
    cantidad INT,
    precio_unitario DECIMAL(10,2),

    FOREIGN KEY (id_pedido)
    REFERENCES pedidos(id_pedido),

    FOREIGN KEY (id_producto)
    REFERENCES productos(id_producto),

    FOREIGN KEY (id_servicio_personalizado)
    REFERENCES servicios_personalizados(id_servicio_personalizado)
);

-- METODOS DE PAGO
CREATE TABLE metodos_pago (
    id_metodo_pago INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- PAGOS
CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_metodo_pago INT NOT NULL,
    monto DECIMAL(10,2),
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_pedido)
    REFERENCES pedidos(id_pedido),

    FOREIGN KEY (id_metodo_pago)
    REFERENCES metodos_pago(id_metodo_pago)
);

-- FACTURAS
CREATE TABLE facturas (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    monto_total DECIMAL(10,2),
    archivo_factura VARCHAR(255),
    estado VARCHAR(50) DEFAULT 'generada',

    FOREIGN KEY (id_pedido)
    REFERENCES pedidos(id_pedido)
);

-- MENSAJES
CREATE TABLE mensajes (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    remitente VARCHAR(100),
    mensaje TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
    REFERENCES usuarios(id_usuario)
);

-- ARQUEO CAJA
CREATE TABLE arqueo_caja (
    id_arqueo INT AUTO_INCREMENT PRIMARY KEY,
    fecha_apertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME,
    monto_inicial DECIMAL(10,2) DEFAULT 0,
    monto_final DECIMAL(10,2),
    total_ingresos DECIMAL(10,2) DEFAULT 0,
    total_egresos DECIMAL(10,2) DEFAULT 0,
    observaciones TEXT,
    estado ENUM('abierta','cerrada') DEFAULT 'abierta',
    id_usuario INT,

    FOREIGN KEY (id_usuario)
    REFERENCES usuarios(id_usuario)
);

-- GASTOS
CREATE TABLE gastos (
    id_gasto INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ESTADISTICAS
CREATE TABLE estadisticas (
    id_estadistica INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50),
    descripcion VARCHAR(100),
    valor DECIMAL(10,2),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- PERFILES
INSERT INTO perfiles (nombre, codigo) VALUES
('cliente', 'perf-01'),
('administrador', 'perf-02'),
('diseñador gráfico', 'perf-03');

-- CATEGORIAS
INSERT INTO categorias (nombre) VALUES
('impresiones 3d'),
('manualidades');

-- USUARIOS --

INSERT INTO usuarios
(nombre, apellidos, telefono, email, password_hash, id_perfil)
VALUES
('juan', 'perez', '3704000000', 'juan@cliente.com',
'$2y$10$k5ogryp071ncy7UwDtJsTuA9m0S3Eh7fOalUWAbH/lxUhV/12REIK', 1),

('juana', 'davalos', '3704111111', 'juana@admin.com',
'$2y$10$Lnr0OYtFlruDUANkGtCDAu4gZicaYGwk8kA4AuG4Dn9YXH9aG2Esy', 2),

('luis', 'designer', '3704222222', 'disenador@mail.com',
'$2y$10$dHavjoa6XCJJtJE2..TCp.WeiLOgsoSql5NpUPLoz4C56yCG/QdXm', 3);

-- PROVEEDORES
INSERT INTO proveedores
(nombre, contacto, telefono, email, cuit, tipo)
VALUES
('proveedor 3d', 'carlos', '123456789', 'proveedor3d@mail.com', '20-12345678-9', 'empresa'),
('lucia manualidades', 'lucia', '987654321', 'lucia@mail.com', '27-98765432-1', 'persona');

-- PRODUCTOS
INSERT INTO productos
(nombre, descripcion, precio, id_categoria, id_proveedor)
VALUES
('figura 3d', 'impresión 3d', 2500, 1, 1),
('souvenir', 'hecho a mano', 1800, 2, 2);

-- INVENTARIO
INSERT INTO inventario
(id_producto, stock, stock_minimo)
VALUES
(1, 20, 5),
(2, 15, 3);

-- SERVICIOS PERSONALIZADOS
INSERT INTO servicios_personalizados
(id_usuario, id_producto, color, texto_personalizado, archivo_diseno)
VALUES
(1, 1, 'rojo', 'feliz cumple', NULL);

-- METODOS DE PAGO
INSERT INTO metodos_pago (nombre) VALUES
('mercado pago'),
('efectivo');

-- PRESUPUESTOS
INSERT INTO presupuestos
(id_usuario, descripcion, total_estimado)
VALUES
(1, 'decoracion tematica personalizada', 4300);

-- DETALLE PRESUPUESTOS
INSERT INTO detalle_presupuestos
(id_presupuesto, id_producto, cantidad, precio_unitario)
VALUES
(1, 1, 2, 2500),
(1, 2, 1, 1800);

-- PEDIDOS
INSERT INTO pedidos (id_presupuesto) VALUES
(1);

-- DETALLE PEDIDO
INSERT INTO detalle_pedido
(id_pedido, id_producto, cantidad, precio_unitario)
VALUES
(1, 1, 2, 2500);

-- PAGOS
INSERT INTO pagos
(id_pedido, id_metodo_pago, monto)
VALUES
(1, 1, 4300);

-- FACTURAS
INSERT INTO facturas
(id_pedido, monto_total)
VALUES
(1, 4300);

-- MENSAJES
INSERT INTO mensajes
(id_usuario, remitente, mensaje)
VALUES
(1, 'juan', 'consulta pedido');

-- ARQUEO CAJA
INSERT INTO arqueo_caja
(monto_inicial, id_usuario)
VALUES
(10000, 2);

-- GASTOS
INSERT INTO gastos (descripcion, monto)
VALUES
('compra materiales', 1500);

-- ESTADISTICAS
INSERT INTO estadisticas (tipo, descripcion, valor)
VALUES
('ventas', 'primera venta registrada', 4300);