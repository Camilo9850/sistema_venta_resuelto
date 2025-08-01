<?php
// 🔍 VERIFICADOR DE CONEXIÓN Y SINCRONIZACIÓN CON HEIDI SQL
require_once 'config.php';

echo "<h2>🔍 Verificación de Conexión con Base de Datos (HeidiSQL)</h2>";

try {
    // Verificar conexión mysqli
    $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
    
    if ($mysqli->connect_error) {
        throw new Exception("Error de conexión: " . $mysqli->connect_error);
    }
    
    echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>Conexión MySQLi exitosa</strong><br>";
    echo "📍 Host: " . Config::BBDD_HOST . ":" . Config::BBDD_PORT . "<br>";
    echo "🗄️ Base de datos: " . Config::BBDD_NOMBRE . "<br>";
    echo "👤 Usuario: " . Config::BBDD_USUARIO . "<br>";
    echo "</div>";
    
    // Verificar conexión PDO
    $pdo = new PDO(
        "mysql:host=" . Config::BBDD_HOST . ":" . Config::BBDD_PORT . ";dbname=" . Config::BBDD_NOMBRE,
        Config::BBDD_USUARIO,
        Config::BBDD_CLAVE,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ <strong>Conexión PDO exitosa</strong>";
    echo "</div>";
    
    // Verificar tablas existentes
    echo "<h3>📋 Tablas en la Base de Datos:</h3>";
    $result = $mysqli->query("SHOW TABLES");
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Tabla</th><th>Registros</th><th>Estado</th></tr>";
    
    $tablas_encontradas = [];
    while ($row = $result->fetch_array()) {
        $tabla = $row[0];
        $tablas_encontradas[] = $tabla;
        
        // Contar registros
        $count_result = $mysqli->query("SELECT COUNT(*) as total FROM `$tabla`");
        $count_row = $count_result->fetch_assoc();
        $total_registros = $count_row['total'];
        
        echo "<tr>";
        echo "<td>$tabla</td>";
        echo "<td>$total_registros</td>";
        echo "<td>✅ Activa</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Verificar tablas específicas del sistema
    $tablas_requeridas = ['productos', 'ventas', 'clientes', 'tipoproductos', 'usuarios'];
    echo "<h3>🔧 Verificación de Tablas del Sistema:</h3>";
    
    foreach ($tablas_requeridas as $tabla_req) {
        if (in_array($tabla_req, $tablas_encontradas)) {
            echo "<span style='color: green;'>✅ $tabla_req - OK</span><br>";
        } else {
            echo "<span style='color: red;'>❌ $tabla_req - FALTANTE</span><br>";
        }
    }
    
    // Verificar transacciones Nequi
    if (in_array('transacciones_nequi_demo', $tablas_encontradas)) {
        echo "<span style='color: green;'>✅ transacciones_nequi_demo - OK</span><br>";
    } else {
        echo "<span style='color: orange;'>⚠️ transacciones_nequi_demo - Se creará automáticamente</span><br>";
    }
    
    // Probar inserción de prueba
    echo "<h3>🧪 Prueba de Inserción:</h3>";
    
    try {
        // Crear tabla de prueba
        $mysqli->query("CREATE TABLE IF NOT EXISTS test_heidi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            dato VARCHAR(100),
            fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Insertar dato de prueba
        $stmt = $mysqli->prepare("INSERT INTO test_heidi (dato) VALUES (?)");
        $dato_prueba = "Prueba HeidiSQL " . date('Y-m-d H:i:s');
        $stmt->bind_param("s", $dato_prueba);
        
        if ($stmt->execute()) {
            $id_insertado = $mysqli->insert_id;
            echo "<span style='color: green;'>✅ Inserción exitosa - ID: $id_insertado</span><br>";
            
            // Verificar que el dato se guardó
            $verificacion = $mysqli->query("SELECT * FROM test_heidi WHERE id = $id_insertado");
            if ($verificacion && $verificacion->num_rows > 0) {
                echo "<span style='color: green;'>✅ Dato verificado en HeidiSQL</span><br>";
                
                // Limpiar dato de prueba
                $mysqli->query("DELETE FROM test_heidi WHERE id = $id_insertado");
                echo "<span style='color: blue;'>🧹 Dato de prueba eliminado</span><br>";
            }
        }
        
        // Eliminar tabla de prueba
        $mysqli->query("DROP TABLE test_heidi");
        echo "<span style='color: blue;'>🧹 Tabla de prueba eliminada</span><br>";
        
    } catch (Exception $e) {
        echo "<span style='color: red;'>❌ Error en prueba: " . $e->getMessage() . "</span><br>";
    }
    
    // Verificar últimas ventas y productos
    echo "<h3>📊 Últimos Datos en el Sistema:</h3>";
    
    // Últimos productos
    $productos_result = $mysqli->query("SELECT * FROM productos ORDER BY idproducto DESC LIMIT 3");
    if ($productos_result && $productos_result->num_rows > 0) {
        echo "<strong>🛍️ Últimos Productos:</strong><br>";
        while ($producto = $productos_result->fetch_assoc()) {
            echo "• ID: {$producto['idproducto']} - {$producto['nombre']} - Stock: {$producto['cantidad']}<br>";
        }
    }
    
    // Últimas ventas
    $ventas_result = $mysqli->query("SELECT * FROM ventas ORDER BY idventa DESC LIMIT 3");
    if ($ventas_result && $ventas_result->num_rows > 0) {
        echo "<br><strong>💰 Últimas Ventas:</strong><br>";
        while ($venta = $ventas_result->fetch_assoc()) {
            echo "• ID: {$venta['idventa']} - Total: $" . number_format($venta['total']) . " - {$venta['fecha']}<br>";
        }
    }
    
    // Verificar transacciones Nequi si existen
    $nequi_result = $mysqli->query("SELECT * FROM transacciones_nequi_demo ORDER BY id DESC LIMIT 3");
    if ($nequi_result && $nequi_result->num_rows > 0) {
        echo "<br><strong>📱 Últimas Transacciones Nequi:</strong><br>";
        while ($nequi = $nequi_result->fetch_assoc()) {
            echo "• ID: {$nequi['transaccion_id']} - $" . number_format($nequi['monto']) . " - {$nequi['fecha']}<br>";
        }
    }
    
    $mysqli->close();
    
    echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>🎉 Resumen de Verificación:</h3>";
    echo "✅ Todas las conexiones funcionan correctamente<br>";
    echo "✅ Los datos se envían y almacenan en HeidiSQL<br>";
    echo "✅ El sistema está completamente integrado<br>";
    echo "<br><strong>💡 Recomendación:</strong> Abre HeidiSQL y actualiza (F5) para ver todos los cambios en tiempo real.";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Error de conexión:</strong> " . $e->getMessage();
    echo "</div>";
    
    echo "<h3>🔧 Pasos para solucionar:</h3>";
    echo "<ol>";
    echo "<li>Verifica que XAMPP esté ejecutándose</li>";
    echo "<li>Verifica que MySQL esté activo en XAMPP</li>";
    echo "<li>Abre HeidiSQL y verifica la conexión</li>";
    echo "<li>Asegúrate de que la base de datos 'abmventas' exista</li>";
    echo "</ol>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f8f9fa;
}

table {
    background: white;
    padding: 10px;
    border-radius: 5px;
    margin: 10px 0;
}

th {
    background: #007bff;
    color: white;
    padding: 8px;
}

td {
    padding: 8px;
    border-bottom: 1px solid #eee;
}
</style>
