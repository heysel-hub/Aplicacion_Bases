CREATE DATABASE IF NOT EXISTS tienda_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE tienda_db;

-- =====================================
-- CATEGORIAS
-- =====================================

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    impuesto DECIMAL(5,2) DEFAULT 0.00
);

INSERT INTO categorias (nombre, impuesto) VALUES
('Papelería', 7.00),
('Droguería', 3.00),
('Supermercado', 0.00),
('Aseo', 5.00);

-- =====================================
-- EMPAQUES
-- =====================================

CREATE TABLE empaques (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(30) NOT NULL
);

INSERT INTO empaques (tipo) VALUES
('Cartón'),
('Plástico'),
('Otro');

-- =====================================
-- PRODUCTOS
-- =====================================

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    peso DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    precio_unitario DECIMAL(10,2) NOT NULL,

    empaque_id INT NOT NULL,
    categoria_id INT NOT NULL,

    FOREIGN KEY (empaque_id)
        REFERENCES empaques(id),

    FOREIGN KEY (categoria_id)
        REFERENCES categorias(id)
);

-- =====================================
-- PROVEEDORES
-- =====================================

CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    ciudad VARCHAR(80) NOT NULL
);

-- =====================================
-- PRODUCTO - PROVEEDOR
-- =====================================

CREATE TABLE producto_proveedor (
    producto_id INT NOT NULL,
    proveedor_id INT NOT NULL,

    PRIMARY KEY (producto_id, proveedor_id),

    FOREIGN KEY (producto_id)
        REFERENCES productos(id)
        ON DELETE CASCADE,

    FOREIGN KEY (proveedor_id)
        REFERENCES proveedores(id)
        ON DELETE CASCADE
);

-- =====================================
-- CLIENTES
-- =====================================

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    apellido VARCHAR(80) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(120)
);

-- =====================================
-- VENTAS
-- =====================================

CREATE TABLE ventas (
    id INT AUTO_INCREMENT PRIMARY KEY,

    cliente_id INT NOT NULL,

    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,

    total DECIMAL(12,2) NOT NULL DEFAULT 0,

    estado ENUM('ACTIVA','ANULADA')
    DEFAULT 'ACTIVA',

    FOREIGN KEY (cliente_id)
        REFERENCES clientes(id)
);

-- =====================================
-- DETALLE VENTAS
-- =====================================

CREATE TABLE venta_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,

    venta_id INT NOT NULL,
    producto_id INT NOT NULL,

    cantidad INT NOT NULL,

    precio_unitario DECIMAL(10,2) NOT NULL,

    impuesto DECIMAL(5,2) DEFAULT 0,

    subtotal DECIMAL(12,2) NOT NULL,

    FOREIGN KEY (venta_id)
        REFERENCES ventas(id)
        ON DELETE CASCADE,

    FOREIGN KEY (producto_id)
        REFERENCES productos(id)
);

-- =====================================
-- COMPRAS A PROVEEDORES
-- =====================================

CREATE TABLE pagos_proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,

    proveedor_id INT NOT NULL,
    producto_id INT NOT NULL,

    cantidad INT NOT NULL,

    costo_total DECIMAL(12,2) NOT NULL,

    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (proveedor_id)
        REFERENCES proveedores(id),

    FOREIGN KEY (producto_id)
        REFERENCES productos(id)
);

-- =====================================
-- PRODUCTOS DAÑADOS
-- =====================================

CREATE TABLE productos_danados (
    id INT AUTO_INCREMENT PRIMARY KEY,

    producto_id INT NOT NULL,

    cantidad INT NOT NULL,

    motivo VARCHAR(200),

    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (producto_id)
        REFERENCES productos(id)
);