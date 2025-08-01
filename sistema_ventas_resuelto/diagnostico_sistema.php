<?php
// 🔧 DIAGNÓSTICO Y CORRECCIÓN DEL SISTEMA DE VENTAS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='utf-8'><title>🔧 Diagnóstico del Sistema</title>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
.container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; }
.error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; }
.warning { background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin: 10px 0; }
.info { background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 5px; margin: 10px 0; }
.section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background: #f8f9fa; }
pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
</style></head><body>";

echo "<div class='container'>";
echo "<h1>🔧 Diagnóstico y Corrección del Sistema de Ventas</h1>";

// 1. Verificar configuración
echo "<div class='section'>";
echo "<h2>📋 1. Verificación de Configuración</h2>";

require_once 'config.php';

echo "<div class='info'>";
echo "<strong>Configuración actual:</strong><br>";
echo "Host: " . Config::BBDD_HOST . "<br>";
echo "Puerto: " . Config::BBDD_PORT . "<br>";
echo "Usuario: " . Config::BBDD_USUARIO . "<br>";
echo "Base de datos: " . Config::BBDD_NOMBRE . "<br>";
echo "</div>";

// 2. Probar conexión a la base de datos
echo "<h2>🔌 2. Prueba de Conexión a Base de Datos</h2>";

try {
    $pdo = new PDO(
        "mysql:host=" . Config::BBDD_HOST . ":" . Config::BBDD_PORT . ";dbname=" . Config::BBDD_NOMBRE,
        Config::BBDD_USUARIO,
        Config::BBDD_CLAVE,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<div class='success'>✅ Conexión a la base de datos exitosa</div>";
    
    // Verificar tablas
    echo "<h3>📊 Tablas encontradas:</h3>";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "<table>";
        echo "<tr><th>Tabla</th><th>Registros</th><th>Estado</th></tr>";
        
        foreach ($tables as $table) {
            try {
                $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
                echo "<tr><td>$table</td><td>$count</td><td><span style='color: green;'>✅ OK</span></td></tr>";
            } catch (Exception $e) {
                echo "<tr><td>$table</td><td>Error</td><td><span style='color: red;'>❌ " . $e->getMessage() . "</span></td></tr>";
            }
        }
        echo "</table>";
    } else {
        echo "<div class='error'>❌ No se encontraron tablas. La base de datos está vacía.</div>";
        echo "<div class='warning'>💡 Ejecuta el script de restauración: <a href='restaurar_bd.php'>restaurar_bd.php</a></div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Error de conexión: " . $e->getMessage() . "</div>";
    echo "<div class='warning'>💡 Verifica que XAMPP esté ejecutándose y que la base de datos 'abmventas' exista.</div>";
}

echo "</div>";

// 3. Verificar archivos críticos
echo "<div class='section'>";
echo "<h2>📁 3. Verificación de Archivos del Sistema</h2>";

$archivos_criticos = [
    'config.php' => 'Configuración de base de datos',
    'header.php' => 'Encabezado del sistema',
    'footer.php' => 'Pie de página',
    'menu.php' => 'Menú de navegación',
    'index.php' => 'Página principal',
    'login.php' => 'Sistema de login',
    'venta-formulario.php' => 'Formulario de ventas',
    'venta-listado.php' => 'Listado de ventas',
    'cliente-formulario.php' => 'Formulario de clientes',
    'cliente-listado.php' => 'Listado de clientes',
    'producto-formulario.php' => 'Formulario de productos',
    'producto-listado.php' => 'Listado de productos'
];

echo "<table>";
echo "<tr><th>Archivo</th><th>Estado</th><th>Descripción</th></tr>";

foreach ($archivos_criticos as $archivo => $descripcion) {
    if (file_exists($archivo)) {
        $size = filesize($archivo);
        echo "<tr><td>$archivo</td><td><span style='color: green;'>✅ Existe ($size bytes)</span></td><td>$descripcion</td></tr>";
    } else {
        echo "<tr><td>$archivo</td><td><span style='color: red;'>❌ No encontrado</span></td><td>$descripcion</td></tr>";
    }
}

echo "</table>";
echo "</div>";

// 4. Verificar entidades
echo "<div class='section'>";
echo "<h2>🏗️ 4. Verificación de Entidades (Clases)</h2>";

$entidades = ['cliente.php', 'producto.php', 'venta.php', 'usuario.php', 'tipoproducto.php'];

echo "<table>";
echo "<tr><th>Entidad</th><th>Estado</th><th>Tamaño</th></tr>";

foreach ($entidades as $entidad) {
    $path = "entidades/$entidad";
    if (file_exists($path)) {
        $size = filesize($path);
        echo "<tr><td>$entidad</td><td><span style='color: green;'>✅ OK</span></td><td>$size bytes</td></tr>";
    } else {
        echo "<tr><td>$entidad</td><td><span style='color: red;'>❌ Falta</span></td><td>-</td></tr>";
    }
}

echo "</table>";
echo "</div>";

// 5. Probar funcionalidades básicas
if (isset($pdo) && count($tables) > 0) {
    echo "<div class='section'>";
    echo "<h2>⚙️ 5. Prueba de Funcionalidades</h2>";
    
    // Probar consultas básicas
    try {
        // Verificar clientes
        $clientes = $pdo->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
        echo "<div class='info'>👥 Clientes registrados: $clientes</div>";
        
        // Verificar productos
        $productos = $pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn();
        echo "<div class='info'>📦 Productos registrados: $productos</div>";
        
        // Verificar ventas
        $ventas = $pdo->query("SELECT COUNT(*) FROM ventas")->fetchColumn();
        echo "<div class='info'>🛒 Ventas registradas: $ventas</div>";
        
        // Verificar usuarios
        $usuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        echo "<div class='info'>👤 Usuarios del sistema: $usuarios</div>";
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Error al consultar datos: " . $e->getMessage() . "</div>";
    }
    
    echo "</div>";
}

// 6. Acciones recomendadas
echo "<div class='section'>";
echo "<h2>🚀 6. Acciones Recomendadas</h2>";

echo "<div class='info'>";
echo "<h3>🔧 Correcciones Automáticas Disponibles:</h3>";
echo "<ul>";
echo "<li><a href='restaurar_bd.php' style='color: #007bff;'>🗄️ Restaurar Base de Datos</a> - Si las tablas están vacías</li>";
echo "<li><a href='pagos-nequi.php' style='color: #007bff;'>💳 Probar Sistema de Pagos</a> - Módulo de pagos integrado</li>";
echo "<li><a href='index.php' style='color: #007bff;'>🏠 Ir al Sistema Principal</a> - Dashboard del sistema</li>";
echo "</ul>";
echo "</div>";

echo "<div class='warning'>";
echo "<h3>⚠️ Si hay errores:</h3>";
echo "<ol>";
echo "<li>Verifica que XAMPP esté ejecutándose</li>";
echo "<li>Ejecuta el restaurador de base de datos</li>";
echo "<li>Revisa los logs de PHP y MySQL</li>";
echo "<li>Verifica permisos de archivos</li>";
echo "</ol>";
echo "</div>";

echo "</div>";

// 7. Enlaces útiles
echo "<div class='section'>";
echo "<h2>🔗 7. Enlaces Útiles del Sistema</h2>";

echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;'>";

$enlaces = [
    'index.php' => '🏠 Dashboard Principal',
    'venta-formulario.php' => '🛒 Nueva Venta',
    'venta-listado.php' => '📊 Ver Ventas',
    'cliente-formulario.php' => '👥 Nuevo Cliente',
    'cliente-listado.php' => '👥 Ver Clientes',
    'producto-formulario.php' => '📦 Nuevo Producto',
    'producto-listado.php' => '📦 Ver Productos',
    'usuario-formulario.php' => '👤 Nuevo Usuario',
    'usuario-listado.php' => '👤 Ver Usuarios',
    'pagos-nequi.php' => '💳 Sistema de Pagos',
    'reporte.php' => '📈 Reportes'
];

foreach ($enlaces as $link => $titulo) {
    echo "<div style='background: #f8f9fa; padding: 10px; border-radius: 5px; text-align: center;'>";
    echo "<a href='$link' style='text-decoration: none; color: #007bff; font-weight: bold;'>$titulo</a>";
    echo "</div>";
}

echo "</div>";
echo "</div>";

echo "<div style='text-align: center; margin: 30px 0; padding: 20px; background: #e9ecef; border-radius: 10px;'>";
echo "<h3>🎉 Sistema de Ventas Diagnosticado</h3>";
echo "<p>Si todos los elementos están en ✅ verde, tu sistema está funcionando correctamente.</p>";
echo "<p><strong>Fecha del diagnóstico:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "</div>";

echo "</div></body></html>";
?>
