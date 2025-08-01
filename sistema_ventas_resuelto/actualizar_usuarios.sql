-- =====================================================
-- 🔧 SCRIPT PARA ACTUALIZAR TABLA USUARIOS
-- Soluciona el error: Unknown column 'usuario' in 'field list'
-- =====================================================

USE `abmventas`;

-- Eliminar tabla usuarios actual
DROP TABLE IF EXISTS `usuarios`;

-- Crear tabla usuarios con estructura correcta
CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `fechacreacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar usuarios de prueba
INSERT INTO `usuarios` (`usuario`, `clave`, `nombre`, `apellido`, `correo`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'Sistema', 'admin@sistema.com'),
('ntarche', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan', 'Pérez', 'juan@empresa.com'),
('mgarcia', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María', 'García', 'maria@empresa.com');

-- Verificar que se creó correctamente
SELECT 
    'TABLA USUARIOS ACTUALIZADA CORRECTAMENTE' AS mensaje,
    COUNT(*) AS total_usuarios,
    GROUP_CONCAT(usuario SEPARATOR ', ') AS usuarios_disponibles
FROM usuarios;

-- =====================================================
-- ✅ AHORA PUEDES HACER LOGIN CON:
-- Usuario: ntarche | Contraseña: password
-- Usuario: admin   | Contraseña: password  
-- Usuario: mgarcia | Contraseña: password
-- =====================================================
