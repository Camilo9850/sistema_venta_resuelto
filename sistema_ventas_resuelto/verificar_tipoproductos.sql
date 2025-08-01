-- =====================================================
-- 🔧 VERIFICAR ESTRUCTURA DE TABLA TIPOPRODUCTOS
-- =====================================================

USE `abmventas`;

-- Verificar que la tabla tipoproductos existe
SHOW TABLES LIKE 'tipoproductos';

-- Mostrar estructura de la tabla
DESCRIBE tipoproductos;

-- Verificar datos existentes
SELECT 
    'TABLA TIPOPRODUCTOS VERIFICADA' AS mensaje,
    COUNT(*) AS total_tipos,
    GROUP_CONCAT(nombre SEPARATOR ', ') AS tipos_disponibles
FROM tipoproductos;

-- Si no hay datos, insertar algunos básicos
INSERT IGNORE INTO tipoproductos (idtipoproducto, nombre, descripcion) VALUES
(1, 'Electrónicos', 'Productos electrónicos y tecnológicos'),
(2, 'Ropa', 'Vestimenta y accesorios'), 
(3, 'Hogar', 'Artículos para el hogar'),
(4, 'Deportes', 'Equipamiento deportivo'),
(5, 'Libros', 'Libros y material educativo');

-- Verificar resultado final
SELECT * FROM tipoproductos;

-- =====================================================
-- ✅ TABLA TIPOPRODUCTOS LISTA Y CORREGIDA
-- =====================================================
