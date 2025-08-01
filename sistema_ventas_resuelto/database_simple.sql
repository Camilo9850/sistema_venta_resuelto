-- =====================================================
-- 🏗️ SCRIPT SQL SIMPLE - MARIADB COMPATIBLE
-- Base de datos: abmventas
-- Fecha: 2025-07-31
-- Versión: Simplificada sin procedimientos ni triggers
-- =====================================================

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS `abmventas` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `abmventas`;

-- =====================================================
-- ELIMINAR TABLAS EXISTENTES
-- =====================================================
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `ventas`;
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `tipoproductos`;
DROP TABLE IF EXISTS `provincias`;
DROP TABLE IF EXISTS `localidades`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `configuraciones`;
DROP TABLE IF EXISTS `transacciones_nequi_demo`;
DROP TABLE IF EXISTS `notificaciones_nequi_demo`;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- 1. TABLA USUARIOS
-- =====================================================
CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `fechacreacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `usuarios` (`nombre`, `email`, `clave`) VALUES
('Administrador', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Juan Pérez', 'juan@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('María García', 'maria@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- =====================================================
-- 2. TABLA LOCALIDADES
-- =====================================================
CREATE TABLE `localidades` (
  `idlocalidad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `codigopostal` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idlocalidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `localidades` (`nombre`, `codigopostal`) VALUES
('Bogotá', '110111'),
('Medellín', '050001'),
('Cali', '760001'),
('Barranquilla', '080001'),
('Cartagena', '130001');

-- =====================================================
-- 3. TABLA PROVINCIAS
-- =====================================================
CREATE TABLE `provincias` (
  `idprovincia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`idprovincia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `provincias` (`nombre`) VALUES
('Cundinamarca'),
('Antioquia'),
('Valle del Cauca'),
('Atlántico'),
('Bolívar');

-- =====================================================
-- 4. TABLA TIPOS DE PRODUCTO
-- =====================================================
CREATE TABLE `tipoproductos` (
  `idtipoproducto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idtipoproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tipoproductos` (`nombre`, `descripcion`) VALUES
('Electrónicos', 'Productos electrónicos y tecnológicos'),
('Ropa', 'Vestimenta y accesorios'),
('Hogar', 'Artículos para el hogar'),
('Deportes', 'Equipamiento deportivo'),
('Libros', 'Libros y material educativo');

-- =====================================================
-- 5. TABLA CLIENTES
-- =====================================================
CREATE TABLE `clientes` (
  `idcliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fk_idlocalidad` int(11) DEFAULT NULL,
  `fk_idprovincia` int(11) DEFAULT NULL,
  `fechacreacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idcliente`),
  FOREIGN KEY (`fk_idlocalidad`) REFERENCES `localidades` (`idlocalidad`),
  FOREIGN KEY (`fk_idprovincia`) REFERENCES `provincias` (`idprovincia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `clientes` (`nombre`, `apellido`, `documento`, `email`, `telefono`, `direccion`, `fk_idlocalidad`, `fk_idprovincia`) VALUES
('Carlos', 'Rodríguez', '12345678', 'carlos@email.com', '3001234567', 'Calle 123 #45-67', 1, 1),
('Ana', 'Martínez', '87654321', 'ana@email.com', '3109876543', 'Carrera 89 #12-34', 2, 2),
('Luis', 'González', '11223344', 'luis@email.com', '3157890123', 'Avenida 56 #78-90', 3, 3),
('Patricia', 'López', '44332211', 'patricia@email.com', '3186547890', 'Calle 90 #12-45', 1, 1),
('Miguel', 'Torres', '55667788', 'miguel@email.com', '3205467891', 'Carrera 67 #89-01', 4, 4);

-- =====================================================
-- 6. TABLA PRODUCTOS
-- =====================================================
CREATE TABLE `productos` (
  `idproducto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(255) DEFAULT 'default.jpg',
  `fk_idtipoproducto` int(11) NOT NULL,
  `fechacreacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `fechaactualizacion` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idproducto`),
  FOREIGN KEY (`fk_idtipoproducto`) REFERENCES `tipoproductos` (`idtipoproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `productos` (`nombre`, `descripcion`, `precio`, `cantidad`, `fk_idtipoproducto`, `imagen`) VALUES
('iPhone 15 Pro Max', 'Smartphone Apple última generación con 256GB', 4500000.00, 8, 1, 'iphone15.jpg'),
('Samsung Galaxy S24 Ultra', 'Smartphone Samsung premium con S Pen', 4200000.00, 12, 1, 'samsung_s24.jpg'),
('MacBook Pro 14', 'Laptop Apple con chip M3 Pro, 16GB RAM, 512GB SSD', 8500000.00, 5, 1, 'macbook_pro.jpg'),
('Dell XPS 13', 'Ultrabook Dell con Intel i7, 16GB RAM, 1TB SSD', 3200000.00, 7, 1, 'dell_xps13.jpg'),
('Sony WH-1000XM5', 'Auriculares inalámbricos con cancelación de ruido', 850000.00, 15, 1, 'sony_headphones.jpg'),
('Camiseta Nike Dri-FIT', 'Camiseta deportiva de alta calidad', 120000.00, 25, 2, 'nike_shirt.jpg'),
('Jeans Levis 501', 'Pantalón jean clásico original', 280000.00, 18, 2, 'levis_jeans.jpg'),
('Sofá Modular 3 Puestos', 'Sofá cómodo para sala de estar', 1800000.00, 3, 3, 'sofa_modular.jpg'),
('Smart TV Samsung 55', 'Televisor 4K UHD con sistema Tizen', 2200000.00, 6, 1, 'samsung_tv55.jpg'),
('Bicicleta Trek Mountain', 'Bicicleta de montaña profesional 21 velocidades', 1500000.00, 4, 4, 'trek_bike.jpg');

-- =====================================================
-- 7. TABLA VENTAS
-- =====================================================
CREATE TABLE `ventas` (
  `idventa` int(11) NOT NULL AUTO_INCREMENT,
  `fk_idcliente` int(11) NOT NULL,
  `fk_idproducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `preciounitario` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` timestamp DEFAULT CURRENT_TIMESTAMP,
  `observaciones` text DEFAULT NULL,
  PRIMARY KEY (`idventa`),
  FOREIGN KEY (`fk_idcliente`) REFERENCES `clientes` (`idcliente`),
  FOREIGN KEY (`fk_idproducto`) REFERENCES `productos` (`idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `ventas` (`fk_idcliente`, `fk_idproducto`, `cantidad`, `preciounitario`, `total`, `fecha`, `observaciones`) VALUES
(1, 1, 1, 4500000.00, 4500000.00, DATE_SUB(NOW(), INTERVAL 5 DAY), 'Venta iPhone 15 Pro Max'),
(2, 3, 1, 8500000.00, 8500000.00, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Venta MacBook Pro'),
(3, 5, 2, 850000.00, 1700000.00, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Venta 2 auriculares Sony'),
(4, 6, 3, 120000.00, 360000.00, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Venta 3 camisetas Nike'),
(5, 9, 1, 2200000.00, 2200000.00, NOW(), 'Venta Smart TV Samsung'),
(1, 7, 2, 280000.00, 560000.00, DATE_SUB(NOW(), INTERVAL 4 DAY), 'Venta 2 jeans Levis'),
(2, 10, 1, 1500000.00, 1500000.00, DATE_SUB(NOW(), INTERVAL 6 DAY), 'Venta bicicleta Trek');

-- =====================================================
-- 8. TABLA TRANSACCIONES NEQUI
-- =====================================================
CREATE TABLE `transacciones_nequi_demo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaccion_id` varchar(50) NOT NULL,
  `telefono_origen` varchar(15) NOT NULL,
  `telefono_destino` varchar(15) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `comision` decimal(10,2) DEFAULT 0.00,
  `concepto` text DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'completada',
  `fecha` timestamp DEFAULT CURRENT_TIMESTAMP,
  `metodo_pago` varchar(50) DEFAULT 'saldo_nequi',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `transacciones_nequi_demo` (`transaccion_id`, `telefono_origen`, `telefono_destino`, `monto`, `comision`, `concepto`, `fecha`) VALUES
('NQ250731001', '3219264943', '3001234567', 1000000.00, 2000.00, 'Transferencia demo - 1 millón', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
('NQ250731002', '3001234567', '3157890123', 500000.00, 1000.00, 'Pago por productos vendidos', DATE_SUB(NOW(), INTERVAL 4 HOUR)),
('NQ250731003', '3157890123', '3219264943', 250000.00, 500.00, 'Devolución parcial', DATE_SUB(NOW(), INTERVAL 6 HOUR)),
('NQ250731004', '3219264943', '3109876543', 750000.00, 1500.00, 'Pago venta iPhone', DATE_SUB(NOW(), INTERVAL 1 DAY)),
('NQ250731005', '3109876543', '3186547890', 300000.00, 600.00, 'Transferencia familiar', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('NQ250731006', '3186547890', '3219264943', 2200000.00, 4400.00, 'Pago Smart TV Samsung', DATE_SUB(NOW(), INTERVAL 3 DAY)),
('NQ250731007', '3205467891', '3001234567', 150000.00, 300.00, 'Pago servicios', DATE_SUB(NOW(), INTERVAL 4 DAY));

-- =====================================================
-- 9. TABLA NOTIFICACIONES NEQUI
-- =====================================================
CREATE TABLE `notificaciones_nequi_demo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telefono` varchar(15) NOT NULL,
  `tipo` varchar(20) NOT NULL DEFAULT 'sms',
  `titulo` varchar(200) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `estado` varchar(20) DEFAULT 'enviado',
  `fecha_envio` timestamp DEFAULT CURRENT_TIMESTAMP,
  `transaccion_id` varchar(50) DEFAULT NULL,
  `operador` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `notificaciones_nequi_demo` (`telefono`, `tipo`, `titulo`, `mensaje`, `transaccion_id`, `operador`) VALUES
('3001234567', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $1,000,000! De: 321***4943. ID: NQ250731001. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731001', 'Claro'),
('3157890123', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $500,000! De: 300***4567. ID: NQ250731002. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731002', 'Movistar'),
('3219264943', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $250,000! De: 315***0123. ID: NQ250731003. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731003', 'Tigo'),
('3109876543', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $750,000! De: 321***4943. ID: NQ250731004. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731004', 'Movistar'),
('3186547890', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $300,000! De: 310***6543. ID: NQ250731005. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731005', 'Claro');

-- =====================================================
-- 10. TABLA CONFIGURACIONES
-- =====================================================
CREATE TABLE `configuraciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_actualizacion` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `configuraciones` (`clave`, `valor`, `descripcion`) VALUES
('empresa_nombre', 'Sistema de Ventas Pro', 'Nombre de la empresa'),
('empresa_nit', '900123456-7', 'NIT de la empresa'),
('empresa_direccion', 'Calle 123 #45-67, Bogotá', 'Dirección de la empresa'),
('empresa_telefono', '601-2345678', 'Teléfono de la empresa'),
('empresa_email', 'info@sistemaventas.com', 'Email de contacto'),
('moneda_simbolo', '$', 'Símbolo de la moneda'),
('moneda_codigo', 'COP', 'Código de la moneda'),
('iva_porcentaje', '19', 'Porcentaje de IVA'),
('nequi_comision_porcentaje', '0.2', 'Porcentaje de comisión Nequi'),
('sistema_version', '2.0.0', 'Versión del sistema');

-- =====================================================
-- RESUMEN FINAL
-- =====================================================
SELECT 
    'BASE DE DATOS CREADA EXITOSAMENTE' AS mensaje,
    (SELECT COUNT(*) FROM usuarios) AS total_usuarios,
    (SELECT COUNT(*) FROM clientes) AS total_clientes,
    (SELECT COUNT(*) FROM productos) AS total_productos,
    (SELECT COUNT(*) FROM ventas) AS total_ventas,
    (SELECT COUNT(*) FROM transacciones_nequi_demo) AS total_nequi,
    (SELECT SUM(total) FROM ventas) AS dinero_ventas,
    (SELECT SUM(monto) FROM transacciones_nequi_demo) AS dinero_nequi;

-- =====================================================
-- 🎉 SCRIPT SIMPLE COMPLETADO
-- =====================================================
-- Este script evita:
-- ❌ Procedimientos almacenados (causan error 1558)
-- ❌ Triggers complejos
-- ❌ Vistas complejas
-- ❌ Índices múltiples
-- ❌ Tipos JSON
-- 
-- ✅ Solo tablas básicas con datos
-- ✅ Relaciones simples
-- ✅ Compatible con MariaDB 10.4.28
-- =====================================================
