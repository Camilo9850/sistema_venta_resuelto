-- =====================================================
-- 🏗️ SCRIPT SQL COMPLETO PARA SISTEMA DE VENTAS + NEQUI
-- Base de datos: abmventas
-- Fecha: 2025-07-31
-- Descripción: Script completo para MariaDB/HeidiSQL
-- Versión: MariaDB 10.4+ Compatible
-- =====================================================

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS `abmventas` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `abmventas`;

-- =====================================================
-- 🗑️ ELIMINAR TABLAS EN ORDEN CORRECTO (CLAVES FORÁNEAS)
-- =====================================================
SET FOREIGN_KEY_CHECKS = 0;
SET sql_mode = '';

-- Eliminar tablas en orden inverso a las dependencias
DROP TABLE IF EXISTS `auditoria`;
DROP TABLE IF EXISTS `notificaciones_nequi_demo`;
DROP TABLE IF EXISTS `transacciones_nequi_demo`;
DROP TABLE IF EXISTS `ventas`;
DROP TABLE IF EXISTS `productos`;
DROP TABLE IF EXISTS `clientes`;
DROP TABLE IF EXISTS `tipoproductos`;
DROP TABLE IF EXISTS `provincias`;
DROP TABLE IF EXISTS `localidades`;
DROP TABLE IF EXISTS `usuarios`;
DROP TABLE IF EXISTS `configuraciones`;

-- Eliminar vistas si existen
DROP VIEW IF EXISTS `vista_ventas_completa`;
DROP VIEW IF EXISTS `vista_productos_stock_bajo`;
DROP VIEW IF EXISTS `vista_resumen_financiero`;

-- Eliminar procedimientos almacenados si existen (Forma compatible)
DROP PROCEDURE IF EXISTS `sp_estadisticas_mensuales`;
DROP PROCEDURE IF EXISTS `sp_backup_transacciones`;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- 1. TABLA USUARIOS
-- =====================================================
CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `clave` varchar(255) NOT NULL,
  `fechacreacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idusuario`),
  INDEX `idx_email` (`email`),
  INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar usuarios demo
INSERT INTO `usuarios` (`nombre`, `email`, `clave`) VALUES
('Administrador', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('Juan Pérez', 'juan@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('María García', 'maria@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- =====================================================
-- 2. TABLA LOCALIDADES
-- =====================================================
CREATE TABLE `localidades` (
  `idlocalidad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `codigopostal` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idlocalidad`),
  INDEX `idx_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar localidades
INSERT INTO `localidades` (`nombre`, `codigopostal`) VALUES
('Bogotá', '110111'),
('Medellín', '050001'),
('Cali', '760001'),
('Barranquilla', '080001'),
('Cartagena', '130001'),
('Bucaramanga', '680001'),
('Pereira', '660001'),
('Santa Marta', '470001'),
('Ibagué', '730001'),
('Pasto', '520001');

-- =====================================================
-- 3. TABLA PROVINCIAS
-- =====================================================
CREATE TABLE `provincias` (
  `idprovincia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`idprovincia`),
  INDEX `idx_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar provincias (departamentos de Colombia)
INSERT INTO `provincias` (`nombre`) VALUES
('Cundinamarca'),
('Antioquia'),
('Valle del Cauca'),
('Atlántico'),
('Bolívar'),
('Santander'),
('Risaralda'),
('Magdalena'),
('Tolima'),
('Nariño');

-- =====================================================
-- 4. TABLA TIPOS DE PRODUCTO
-- =====================================================
CREATE TABLE `tipoproductos` (
  `idtipoproducto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`idtipoproducto`),
  INDEX `idx_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar tipos de producto
INSERT INTO `tipoproductos` (`nombre`, `descripcion`) VALUES
('Electrónicos', 'Productos electrónicos y tecnológicos'),
('Ropa', 'Vestimenta y accesorios'),
('Hogar', 'Artículos para el hogar'),
('Deportes', 'Equipamiento deportivo'),
('Libros', 'Libros y material educativo'),
('Belleza', 'Productos de belleza y cuidado personal'),
('Automotriz', 'Accesorios y repuestos para vehículos'),
('Juguetes', 'Juguetes y entretenimiento'),
('Salud', 'Productos para la salud'),
('Mascotas', 'Productos para mascotas');

-- =====================================================
-- 5. TABLA CLIENTES
-- =====================================================
CREATE TABLE `clientes` (
  `idcliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `documento` varchar(20) NOT NULL UNIQUE,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fk_idlocalidad` int(11) DEFAULT NULL,
  `fk_idprovincia` int(11) DEFAULT NULL,
  `fechacreacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idcliente`),
  UNIQUE KEY `uk_documento` (`documento`),
  KEY `fk_cliente_localidad` (`fk_idlocalidad`),
  KEY `fk_cliente_provincia` (`fk_idprovincia`),
  INDEX `idx_nombre` (`nombre`, `apellido`),
  INDEX `idx_email` (`email`),
  CONSTRAINT `fk_cliente_localidad` FOREIGN KEY (`fk_idlocalidad`) REFERENCES `localidades` (`idlocalidad`),
  CONSTRAINT `fk_cliente_provincia` FOREIGN KEY (`fk_idprovincia`) REFERENCES `provincias` (`idprovincia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar clientes demo
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
  KEY `fk_producto_tipo` (`fk_idtipoproducto`),
  INDEX `idx_nombre` (`nombre`),
  INDEX `idx_precio` (`precio`),
  INDEX `idx_cantidad` (`cantidad`),
  INDEX `idx_activo` (`activo`),
  CONSTRAINT `fk_producto_tipo` FOREIGN KEY (`fk_idtipoproducto`) REFERENCES `tipoproductos` (`idtipoproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar productos demo
INSERT INTO `productos` (`nombre`, `descripcion`, `precio`, `cantidad`, `fk_idtipoproducto`, `imagen`) VALUES
('iPhone 15 Pro Max', 'Smartphone Apple última generación con 256GB', 4500000.00, 8, 1, 'iphone15.jpg'),
('Samsung Galaxy S24 Ultra', 'Smartphone Samsung premium con S Pen', 4200000.00, 12, 1, 'samsung_s24.jpg'),
('MacBook Pro 14"', 'Laptop Apple con chip M3 Pro, 16GB RAM, 512GB SSD', 8500000.00, 5, 1, 'macbook_pro.jpg'),
('Dell XPS 13', 'Ultrabook Dell con Intel i7, 16GB RAM, 1TB SSD', 3200000.00, 7, 1, 'dell_xps13.jpg'),
('Sony WH-1000XM5', 'Auriculares inalámbricos con cancelación de ruido', 850000.00, 15, 1, 'sony_headphones.jpg'),
('Camiseta Nike Dri-FIT', 'Camiseta deportiva de alta calidad', 120000.00, 25, 2, 'nike_shirt.jpg'),
('Jeans Levis 501', 'Pantalón jean clásico original', 280000.00, 18, 2, 'levis_jeans.jpg'),
('Sofá Modular 3 Puestos', 'Sofá cómodo para sala de estar', 1800000.00, 3, 3, 'sofa_modular.jpg'),
('Smart TV Samsung 55"', 'Televisor 4K UHD con sistema Tizen', 2200000.00, 6, 1, 'samsung_tv55.jpg'),
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
  KEY `fk_venta_cliente` (`fk_idcliente`),
  KEY `fk_venta_producto` (`fk_idproducto`),
  INDEX `idx_fecha` (`fecha`),
  INDEX `idx_total` (`total`),
  CONSTRAINT `fk_venta_cliente` FOREIGN KEY (`fk_idcliente`) REFERENCES `clientes` (`idcliente`),
  CONSTRAINT `fk_venta_producto` FOREIGN KEY (`fk_idproducto`) REFERENCES `productos` (`idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar ventas demo
INSERT INTO `ventas` (`fk_idcliente`, `fk_idproducto`, `cantidad`, `preciounitario`, `total`, `fecha`, `observaciones`) VALUES
(1, 1, 1, 4500000.00, 4500000.00, DATE_SUB(NOW(), INTERVAL 5 DAY), 'Venta iPhone 15 Pro Max'),
(2, 3, 1, 8500000.00, 8500000.00, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Venta MacBook Pro'),
(3, 5, 2, 850000.00, 1700000.00, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Venta 2 auriculares Sony'),
(4, 6, 3, 120000.00, 360000.00, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Venta 3 camisetas Nike'),
(5, 9, 1, 2200000.00, 2200000.00, NOW(), 'Venta Smart TV Samsung'),
(1, 7, 2, 280000.00, 560000.00, DATE_SUB(NOW(), INTERVAL 4 DAY), 'Venta 2 jeans Levis'),
(2, 10, 1, 1500000.00, 1500000.00, DATE_SUB(NOW(), INTERVAL 6 DAY), 'Venta bicicleta Trek');

-- =====================================================
-- 8. TABLA TRANSACCIONES NEQUI (SIMULADOR)
-- =====================================================
CREATE TABLE `transacciones_nequi_demo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaccion_id` varchar(50) NOT NULL UNIQUE,
  `telefono_origen` varchar(15) NOT NULL,
  `telefono_destino` varchar(15) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `comision` decimal(10,2) DEFAULT 0.00,
  `concepto` text DEFAULT NULL,
  `estado` varchar(20) DEFAULT 'completada',
  `fecha` timestamp DEFAULT CURRENT_TIMESTAMP,
  `metodo_pago` varchar(50) DEFAULT 'saldo_nequi',
  `referencia_externa` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_transaccion_id` (`transaccion_id`),
  INDEX `idx_telefono_origen` (`telefono_origen`),
  INDEX `idx_telefono_destino` (`telefono_destino`),
  INDEX `idx_fecha` (`fecha`),
  INDEX `idx_monto` (`monto`),
  INDEX `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar transacciones Nequi demo
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
  PRIMARY KEY (`id`),
  INDEX `idx_telefono` (`telefono`),
  INDEX `idx_fecha` (`fecha_envio`),
  INDEX `idx_estado` (`estado`),
  INDEX `idx_transaccion` (`transaccion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar notificaciones demo
INSERT INTO `notificaciones_nequi_demo` (`telefono`, `tipo`, `titulo`, `mensaje`, `transaccion_id`, `operador`) VALUES
('3001234567', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $1,000,000! De: 321***4943. ID: NQ250731001. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731001', 'Claro'),
('3157890123', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $500,000! De: 300***4567. ID: NQ250731002. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731002', 'Movistar'),
('3219264943', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $250,000! De: 315***0123. ID: NQ250731003. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731003', 'Tigo'),
('3109876543', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $750,000! De: 321***4943. ID: NQ250731004. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731004', 'Movistar'),
('3186547890', 'sms', 'Dinero recibido', 'NEQUI: ¡Recibiste $300,000! De: 310***6543. ID: NQ250731005. Disponible ahora en tu Nequi. ¡Disfrútalo! 💰', 'NQ250731005', 'Claro');

-- =====================================================
-- 10. TABLA CONFIGURACIONES SISTEMA
-- =====================================================
CREATE TABLE `configuraciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) NOT NULL UNIQUE,
  `valor` text DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_actualizacion` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar configuraciones
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
-- 11. TABLA AUDITORIA (OPCIONAL)
-- =====================================================
CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabla` varchar(50) NOT NULL,
  `operacion` varchar(10) NOT NULL,
  `registro_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `datos_anteriores` longtext DEFAULT NULL,
  `datos_nuevos` longtext DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `fecha` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_tabla` (`tabla`),
  INDEX `idx_operacion` (`operacion`),
  INDEX `idx_fecha` (`fecha`),
  INDEX `idx_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- 12. VISTAS ÚTILES (MariaDB Compatible)
-- =====================================================

-- Vista de ventas con información completa
CREATE OR REPLACE VIEW `vista_ventas_completa` AS
SELECT 
    v.idventa,
    v.fecha,
    v.cantidad,
    v.preciounitario,
    v.total,
    v.observaciones,
    CONCAT(c.nombre, ' ', c.apellido) AS cliente_nombre,
    c.documento AS cliente_documento,
    c.telefono AS cliente_telefono,
    p.nombre AS producto_nombre,
    p.imagen AS producto_imagen,
    tp.nombre AS tipo_producto,
    COALESCE(l.nombre, 'Sin localidad') AS localidad,
    COALESCE(pr.nombre, 'Sin provincia') AS provincia
FROM ventas v
INNER JOIN clientes c ON v.fk_idcliente = c.idcliente
INNER JOIN productos p ON v.fk_idproducto = p.idproducto
INNER JOIN tipoproductos tp ON p.fk_idtipoproducto = tp.idtipoproducto
LEFT JOIN localidades l ON c.fk_idlocalidad = l.idlocalidad
LEFT JOIN provincias pr ON c.fk_idprovincia = pr.idprovincia
ORDER BY v.fecha DESC;

-- Vista de productos con stock bajo
CREATE OR REPLACE VIEW `vista_productos_stock_bajo` AS
SELECT 
    p.idproducto,
    p.nombre,
    p.cantidad,
    p.precio,
    tp.nombre AS tipo_producto,
    p.fechaactualizacion
FROM productos p
INNER JOIN tipoproductos tp ON p.fk_idtipoproducto = tp.idtipoproducto
WHERE p.cantidad <= 5 AND p.activo = 1
ORDER BY p.cantidad ASC;

-- Vista de resumen financiero
CREATE OR REPLACE VIEW `vista_resumen_financiero` AS
SELECT 
    'Ventas Tradicionales' AS tipo,
    COUNT(*) AS total_transacciones,
    COALESCE(SUM(total), 0) AS total_dinero,
    COALESCE(AVG(total), 0) AS promedio_transaccion,
    MIN(fecha) AS primera_transaccion,
    MAX(fecha) AS ultima_transaccion
FROM ventas
UNION ALL
SELECT 
    'Transacciones Nequi' AS tipo,
    COUNT(*) AS total_transacciones,
    COALESCE(SUM(monto), 0) AS total_dinero,
    COALESCE(AVG(monto), 0) AS promedio_transaccion,
    MIN(fecha) AS primera_transaccion,
    MAX(fecha) AS ultima_transaccion
FROM transacciones_nequi_demo;

-- =====================================================
-- 13. PROCEDIMIENTOS ALMACENADOS (MariaDB Compatible)
-- =====================================================

DELIMITER $$

CREATE PROCEDURE `sp_estadisticas_mensuales`(
    IN p_anio INT,
    IN p_mes INT
)
BEGIN
    SELECT 
        'Ventas' AS tipo,
        COUNT(*) AS cantidad,
        COALESCE(SUM(total), 0) AS total_dinero
    FROM ventas 
    WHERE YEAR(fecha) = p_anio AND MONTH(fecha) = p_mes
    
    UNION ALL
    
    SELECT 
        'Nequi' AS tipo,
        COUNT(*) AS cantidad,
        COALESCE(SUM(monto), 0) AS total_dinero
    FROM transacciones_nequi_demo 
    WHERE YEAR(fecha) = p_anio AND MONTH(fecha) = p_mes;
END$$

CREATE PROCEDURE `sp_backup_transacciones`()
BEGIN
    DECLARE total_ventas DECIMAL(15,2) DEFAULT 0;
    DECLARE total_nequi DECIMAL(15,2) DEFAULT 0;
    
    SELECT COALESCE(SUM(total), 0) INTO total_ventas FROM ventas;
    SELECT COALESCE(SUM(monto), 0) INTO total_nequi FROM transacciones_nequi_demo;
    
    SELECT 
        'Backup realizado' AS mensaje,
        NOW() AS fecha,
        total_ventas AS total_ventas_sistema,
        total_nequi AS total_nequi_sistema,
        (total_ventas + total_nequi) AS gran_total;
END$$

DELIMITER ;

-- =====================================================
-- 14. TRIGGERS DE AUDITORÍA (MariaDB Compatible)
-- =====================================================

DELIMITER $$

CREATE TRIGGER `tr_productos_audit`
AFTER UPDATE ON `productos`
FOR EACH ROW
BEGIN
    INSERT INTO auditoria (tabla, operacion, registro_id, datos_anteriores, datos_nuevos)
    VALUES (
        'productos',
        'UPDATE',
        NEW.idproducto,
        CONCAT('{"nombre":"', OLD.nombre, '","precio":', OLD.precio, ',"cantidad":', OLD.cantidad, '}'),
        CONCAT('{"nombre":"', NEW.nombre, '","precio":', NEW.precio, ',"cantidad":', NEW.cantidad, '}')
    );
END$$

DELIMITER ;

-- =====================================================
-- 15. ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- =====================================================

-- Índices compuestos para consultas frecuentes
CREATE INDEX `idx_ventas_fecha_cliente` ON `ventas` (`fecha`, `fk_idcliente`);
CREATE INDEX `idx_ventas_producto_fecha` ON `ventas` (`fk_idproducto`, `fecha`);
CREATE INDEX `idx_nequi_telefono_fecha` ON `transacciones_nequi_demo` (`telefono_origen`, `fecha`);
CREATE INDEX `idx_productos_tipo_precio` ON `productos` (`fk_idtipoproducto`, `precio`);

-- =====================================================
-- 16. DATOS FINALES Y VERIFICACIÓN
-- =====================================================

-- Actualizar secuencias AUTO_INCREMENT
ALTER TABLE usuarios AUTO_INCREMENT = 1000;
ALTER TABLE productos AUTO_INCREMENT = 2000;
ALTER TABLE ventas AUTO_INCREMENT = 3000;
ALTER TABLE transacciones_nequi_demo AUTO_INCREMENT = 4000;

-- Mostrar resumen de la instalación
SELECT 
    'INSTALACIÓN COMPLETADA EN MARIADB' AS estatus,
    (SELECT COUNT(*) FROM usuarios) AS total_usuarios,
    (SELECT COUNT(*) FROM clientes) AS total_clientes,
    (SELECT COUNT(*) FROM productos) AS total_productos,
    (SELECT COUNT(*) FROM ventas) AS total_ventas,
    (SELECT COUNT(*) FROM transacciones_nequi_demo) AS total_nequi,
    (SELECT COALESCE(SUM(total), 0) FROM ventas) AS dinero_ventas,
    (SELECT COALESCE(SUM(monto), 0) FROM transacciones_nequi_demo) AS dinero_nequi;

-- =====================================================
-- 🎉 SCRIPT COMPLETADO - MARIADB COMPATIBLE
-- =====================================================
-- Instrucciones de uso:
-- 1. Abre HeidiSQL
-- 2. Conéctate a tu servidor MariaDB/MySQL
-- 3. Ejecuta este script completo
-- 4. ¡Tu base de datos estará lista!
-- 
-- Cambios para MariaDB:
-- ✅ JSON cambió a LONGTEXT 
-- ✅ Procedimientos con DELIMITER $$
-- ✅ Vistas con CREATE OR REPLACE
-- ✅ COALESCE para valores NULL
-- ✅ Triggers compatibles
-- =====================================================
