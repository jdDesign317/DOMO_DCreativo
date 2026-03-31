CREATE DATABASE IF NOT EXISTS domo_creativo26;
USE domo_creativo26;

--  PERFILES
CREATE TABLE perfiles (
    id_perfil INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

--  USUARIOS
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    id_perfil INT,
    codigo_recuperacion VARCHAR(10),
    codigo_expira DATETIME,
    FOREIGN KEY (id_perfil) REFERENCES perfiles(id_perfil)
);

CREATE INDEX idx_email_codigo 
ON usuarios (email, codigo_recuperacion);

--  PRODUCTOS
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL
);

--  PERSONALIZACIONES
CREATE TABLE personalizaciones (
    id_personalizacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_producto INT,
    color VARCHAR(50),
    texto_personalizado VARCHAR(255),
    archivo_diseno VARCHAR(255),
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

--  CARRITO
CREATE TABLE carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'activo',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE carrito_detalle (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_carrito INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT DEFAULT 1,
    FOREIGN KEY (id_carrito) REFERENCES carrito(id_carrito) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

--  PRESUPUESTOS
CREATE TABLE presupuestos (
    id_presupuesto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    total_estimado DECIMAL(10,2),
    estado VARCHAR(50) DEFAULT 'pendiente',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

CREATE TABLE detalle_presupuestos (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_presupuesto INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT DEFAULT 1,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (id_presupuesto) REFERENCES presupuestos(id_presupuesto) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE
);

--  PEDIDOS
CREATE TABLE pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_presupuesto INT NOT NULL,
    estado VARCHAR(50) DEFAULT 'en diseño',
    fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_entrega DATE,
    FOREIGN KEY (id_presupuesto) REFERENCES presupuestos(id_presupuesto) ON DELETE CASCADE
);

CREATE TABLE detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT,
    id_producto INT,
    cantidad INT,
    precio_unitario DECIMAL(10,2),
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

--  PAGOS
CREATE TABLE metodos_pago (
    id_metodo_pago INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_metodo_pago INT NOT NULL,
    monto DECIMAL(10,2),
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_metodo_pago) REFERENCES metodos_pago(id_metodo_pago)
);

--  FACTURAS
CREATE TABLE facturas (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    monto_total DECIMAL(10,2),
    archivo_factura VARCHAR(255),
    estado VARCHAR(50) DEFAULT 'generada',
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE
);

--  MENSAJES
CREATE TABLE mensajes (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    remitente VARCHAR(100),
    mensaje TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

--  ARQUEO
CREATE TABLE arqueo_caja (
    id_arqueo INT AUTO_INCREMENT PRIMARY KEY,
    fecha_apertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME,
    monto_inicial DECIMAL(10,2) DEFAULT 0.00,
    monto_final DECIMAL(10,2),
    total_ingresos DECIMAL(10,2) DEFAULT 0.00,
    total_egresos DECIMAL(10,2) DEFAULT 0.00,
    observaciones TEXT,
    estado ENUM('abierta','cerrada') DEFAULT 'abierta',
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
);


-- INSERTS (ORDEN CORRECTO)


INSERT INTO perfiles (nombre) VALUES ('Cliente'), ('Administrador');

INSERT INTO usuarios (nombre, email, password_hash, id_perfil) VALUES
('Juan Cliente', 'juan@cliente.com', 'hashed_pass_juan', 1),
('Ana Admin', 'ana@admin.com', 'hashed_pass_ana', 2);

INSERT INTO productos (nombre, descripcion, precio) VALUES
('Figura 3D', 'Impresión 3D', 2500),
('Souvenir', 'Hecho a mano', 1800);

INSERT INTO metodos_pago (nombre) VALUES ('Mercado Pago'), ('Efectivo');

INSERT INTO presupuestos (id_usuario, total_estimado) VALUES (1, 4300);

INSERT INTO detalle_presupuestos (id_presupuesto, id_producto, cantidad, precio_unitario) VALUES
(1,1,2,2500),(1,2,1,1800);

INSERT INTO pedidos (id_presupuesto) VALUES (1);

INSERT INTO pagos (id_pedido, id_metodo_pago, monto) VALUES (1,1,4300);

INSERT INTO facturas (id_pedido, monto_total) VALUES (1,4300);

INSERT INTO mensajes (id_usuario, remitente, mensaje) VALUES
(1,'Juan','Consulta pedido');

INSERT INTO personalizaciones (id_usuario, id_producto, color, texto_personalizado) VALUES
(1,1,'Rojo','Feliz Cumple');

INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES
(1,1,2,2500);

-- Verificación rápida
SHOW TABLES;
SELECT COUNT(*) FROM perfiles;
SELECT COUNT(*) FROM usuarios;
SELECT COUNT(*) FROM productos;
SELECT COUNT(*) FROM personalizaciones;
SELECT COUNT(*) FROM metodos_pago;
SELECT COUNT(*) FROM presupuestos;
SELECT COUNT(*) FROM detalle_presupuestos;
SELECT COUNT(*) FROM pedidos;
SELECT COUNT(*) FROM detalle_pedido;
SELECT COUNT(*) FROM pagos;
SELECT COUNT(*) FROM facturas;
SELECT COUNT(*) FROM mensajes;
SELECT COUNT(*) FROM arqueo_caja;
