<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de conexión
$host = "127.0.0.1";
$port = "3306";
$usuario = "root";
$clave = "";
$base_datos = "abmventas";

// Para que funcione en el navegador
header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Restauración BD</title>";
echo "<style>body{font-family:monospace; background:#f5f5f5; padding:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";
echo "</head><body>";
echo "<h2>🗄️ RESTAURACIÓN DE BASE DE DATOS</h2><hr>";

function log_message($message, $type = 'info') {
    $class = '';
    switch($type) {
        case 'success': $class = 'success'; break;
        case 'error': $class = 'error'; break;
        case 'warning': $class = 'warning'; break;
    }
    echo "<div class='$class'>" . htmlspecialchars($message) . "</div>";
    flush();
}

try {
    // Conectar a MySQL (sin especificar base de datos para crearla)
    $pdo = new PDO("mysql:host=$host:$port", $usuario, $clave);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    log_message("✓ Conexión a MySQL exitosa", 'success');
    
    // Crear la base de datos si no existe
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$base_datos` CHARACTER SET utf8 COLLATE utf8_spanish_ci");
    log_message("✓ Base de datos '$base_datos' creada/verificada", 'success');
    
    // Seleccionar la base de datos
    $pdo->exec("USE `$base_datos`");
    log_message("✓ Base de datos '$base_datos' seleccionada", 'success');
    
    // Leer el archivo SQL
    $sql_file = 'abmventas_resuelto.sql';
    if (!file_exists($sql_file)) {
        throw new Exception("Archivo SQL no encontrado: $sql_file");
    }
    
    $sql_content = file_get_contents($sql_file);
    log_message("✓ Archivo SQL leído correctamente (" . number_format(strlen($sql_content)) . " caracteres)", 'success');
    
    // Configurar MySQL para manejar el archivo grande
    $pdo->exec("SET NAMES utf8mb4");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // Dividir el contenido por statements SQL
    $statements = array_filter(
        array_map('trim', explode(';', $sql_content)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^(\/\*.*?\*\/|--|#)/', $stmt);
        }
    );
    
    log_message("✓ " . count($statements) . " statements SQL encontrados", 'success');
    
    echo "<br><strong>Ejecutando statements...</strong><br>";
    
    // Ejecutar cada statement
    $ejecutados = 0;
    $errores = 0;
    
    foreach ($statements as $i => $statement) {
        try {
            if (trim($statement)) {
                $pdo->exec($statement);
                $ejecutados++;
                
                // Mostrar progreso cada 50 statements
                if ($ejecutados % 50 == 0) {
                    log_message("- Ejecutados: $ejecutados statements", 'info');
                }
            }
        } catch (PDOException $e) {
            $errores++;
            log_message("⚠ Error en statement " . ($i + 1) . ": " . $e->getMessage(), 'warning');
            
            // Mostrar solo los primeros 3 errores para no saturar
            if ($errores >= 3) {
                log_message("... (mostrando solo los primeros 3 errores)", 'warning');
                break;
            }
        }
    }
    
    // Restaurar configuración
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "<br><hr><h3>📊 RESUMEN</h3>";
    log_message("✓ Statements ejecutados: $ejecutados", 'success');
    log_message("⚠ Errores: $errores", $errores > 0 ? 'warning' : 'success');
    
    // Verificar las tablas creadas
    $result = $pdo->query("SHOW TABLES");
    $tablas = $result->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<br><h3>📋 TABLAS CREADAS</h3>";
    foreach ($tablas as $tabla) {
        // Contar registros en cada tabla
        $count_result = $pdo->query("SELECT COUNT(*) FROM `$tabla`");
        $count = $count_result->fetchColumn();
        log_message("- $tabla: " . number_format($count) . " registros", 'success');
    }
    
    echo "<br><hr>";
    log_message("🎉 ¡Restauración completada exitosamente!", 'success');
    echo "<br><strong>Ahora puedes usar tu sistema de ventas con la base de datos restaurada.</strong>";
    
} catch (Exception $e) {
    log_message("❌ Error: " . $e->getMessage(), 'error');
    echo "<br><strong>Verifica que XAMPP esté ejecutándose y que MySQL esté activo.</strong>";
}

echo "</body></html>";
?>
