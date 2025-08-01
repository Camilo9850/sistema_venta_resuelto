-- =====================================================
-- 🔧 VERIFICAR ESTRUCTURA DE TABLA CLIENTES
-- =====================================================

USE `abmventas`;

-- Verificar que la tabla clientes existe
SHOW TABLES LIKE 'clientes';

-- Mostrar estructura de la tabla
DESCRIBE clientes;

-- Verificar datos existentes
SELECT 
    'TABLA CLIENTES VERIFICADA' AS mensaje,
    COUNT(*) AS total_clientes
FROM clientes;

-- Mostrar algunos datos de ejemplo
SELECT 
    idcliente,
    nombre,
    apellido,
    documento,
    email,
    telefono
FROM clientes 
LIMIT 5;

-- =====================================================
-- ✅ TABLA CLIENTES CORREGIDA Y VERIFICADA
-- =====================================================
