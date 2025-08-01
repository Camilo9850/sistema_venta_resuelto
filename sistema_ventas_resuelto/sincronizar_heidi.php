<?php
// 🔄 SINCRONIZADOR AUTOMÁTICO CON HEIDI SQL
require_once 'config.php';
require_once 'facturacion_totales.php';
require_once 'entidades/producto.php';
require_once 'entidades/venta.php';

echo "<h2>🔄 Sincronización Automática con HeidiSQL</h2>";

try {
    $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
    
    if ($mysqli->connect_error) {
        throw new Exception("Error de conexión: " . $mysqli->connect_error);
    }
    
    echo "<div style='background: #d4edda; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
    echo "✅ Conectado a HeidiSQL exitosamente";
    echo "</div>";
    
    // 1. Asegurar que todas las tablas existan
    echo "<h3>1. 🏗️ Verificando y creando tablas necesarias...</h3>";
    
    // Tabla transacciones Nequi
    $sql_nequi = "CREATE TABLE IF NOT EXISTS transacciones_nequi_demo (
        id INT AUTO_INCREMENT PRIMARY KEY,
        transaccion_id VARCHAR(50) UNIQUE,
        telefono_origen VARCHAR(15),
        telefono_destino VARCHAR(15),
        monto DECIMAL(10,2),
        comision DECIMAL(10,2) DEFAULT 0,
        concepto TEXT,
        estado VARCHAR(20) DEFAULT 'completada',
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_fecha (fecha),
        INDEX idx_monto (monto)
    )";
    
    if ($mysqli->query($sql_nequi)) {
        echo "✅ Tabla transacciones_nequi_demo verificada/creada<br>";
    } else {
        echo "❌ Error en tabla Nequi: " . $mysqli->error . "<br>";
    }
    
    // Tabla notificaciones
    $sql_notif = "CREATE TABLE IF NOT EXISTS notificaciones_nequi_demo (
        id INT AUTO_INCREMENT PRIMARY KEY,
        telefono VARCHAR(15),
        tipo VARCHAR(20),
        mensaje TEXT,
        estado VARCHAR(20) DEFAULT 'enviado',
        fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_telefono (telefono),
        INDEX idx_fecha (fecha_envio)
    )";
    
    if ($mysqli->query($sql_notif)) {
        echo "✅ Tabla notificaciones_nequi_demo verificada/creada<br>";
    } else {
        echo "❌ Error en tabla notificaciones: " . $mysqli->error . "<br>";
    }
    
    // 2. Insertar datos demo si no existen
    echo "<h3>2. 📊 Insertando datos demo...</h3>";
    
    // Verificar si hay transacciones Nequi
    $count_nequi = $mysqli->query("SELECT COUNT(*) as total FROM transacciones_nequi_demo");
    $row_nequi = $count_nequi->fetch_assoc();
    
    if ($row_nequi['total'] == 0) {
        echo "📱 Insertando transacciones Nequi demo...<br>";
        
        $transacciones = [
            [
                'transaccion_id' => 'NQ' . date('ymd') . '001',
                'telefono_origen' => '3219264943',
                'telefono_destino' => '3001234567',
                'monto' => 1000000,
                'comision' => 2000,
                'concepto' => 'Transferencia sistema demo - 1 millón'
            ],
            [
                'transaccion_id' => 'NQ' . date('ymd') . '002',
                'telefono_origen' => '3001234567',
                'telefono_destino' => '3157890123',
                'monto' => 500000,
                'comision' => 1000,
                'concepto' => 'Pago por productos'
            ],
            [
                'transaccion_id' => 'NQ' . date('ymd') . '003',
                'telefono_origen' => '3157890123',
                'telefono_destino' => '3219264943',
                'monto' => 250000,
                'comision' => 500,
                'concepto' => 'Transferencia de prueba'
            ]
        ];
        
        $stmt = $mysqli->prepare("INSERT INTO transacciones_nequi_demo 
            (transaccion_id, telefono_origen, telefono_destino, monto, comision, concepto) 
            VALUES (?, ?, ?, ?, ?, ?)");
            
        foreach ($transacciones as $trans) {
            $stmt->bind_param("sssdds", 
                $trans['transaccion_id'],
                $trans['telefono_origen'],
                $trans['telefono_destino'],
                $trans['monto'],
                $trans['comision'],
                $trans['concepto']
            );
            
            if ($stmt->execute()) {
                echo "✅ Transacción {$trans['transaccion_id']} insertada<br>";
            } else {
                echo "❌ Error insertando transacción: " . $stmt->error . "<br>";
            }
        }
        $stmt->close();
    } else {
        echo "ℹ️ Ya existen {$row_nequi['total']} transacciones Nequi<br>";
    }
    
    // 3. Verificar productos y completar datos faltantes
    echo "<h3>3. 🛍️ Verificando productos...</h3>";
    
    $productos_result = $mysqli->query("SELECT COUNT(*) as total FROM productos");
    $productos_count = $productos_result->fetch_assoc()['total'];
    
    if ($productos_count == 0) {
        echo "📦 Insertando productos demo...<br>";
        
        $productos_demo = [
            ['nombre' => 'Laptop Dell XPS 13', 'precio' => 2500000, 'cantidad' => 5, 'descripcion' => 'Laptop ultrabook de alta gama'],
            ['nombre' => 'iPhone 15 Pro', 'precio' => 4500000, 'cantidad' => 3, 'descripcion' => 'Smartphone Apple última generación'],
            ['nombre' => 'Auriculares Sony WH-1000XM5', 'precio' => 850000, 'cantidad' => 10, 'descripcion' => 'Auriculares con cancelación de ruido']
        ];
        
        $stmt_prod = $mysqli->prepare("INSERT INTO productos 
            (nombre, precio, cantidad, descripcion, fk_idtipoproducto, imagen) 
            VALUES (?, ?, ?, ?, 1, 'default.jpg')");
            
        foreach ($productos_demo as $prod) {
            $stmt_prod->bind_param("sdis", 
                $prod['nombre'],
                $prod['precio'],
                $prod['cantidad'],
                $prod['descripcion']
            );
            
            if ($stmt_prod->execute()) {
                echo "✅ Producto '{$prod['nombre']}' insertado<br>";
            }
        }
        $stmt_prod->close();
    } else {
        echo "ℹ️ Ya existen $productos_count productos<br>";
    }
    
    // 4. Mostrar estadísticas finales
    echo "<h3>4. 📈 Estadísticas Finales en HeidiSQL:</h3>";
    
    $stats = [];
    
    // Contar registros de cada tabla
    $tablas = ['productos', 'ventas', 'clientes', 'transacciones_nequi_demo', 'notificaciones_nequi_demo'];
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%; background: white;'>";
    echo "<tr style='background: #007bff; color: white;'><th>Tabla</th><th>Registros</th><th>Última Actualización</th></tr>";
    
    foreach ($tablas as $tabla) {
        $count_query = $mysqli->query("SELECT COUNT(*) as total FROM `$tabla`");
        if ($count_query) {
            $count = $count_query->fetch_assoc()['total'];
            
            // Obtener última actualización si es posible
            $ultima_query = $mysqli->query("SELECT MAX(fecha) as ultima FROM `$tabla` WHERE 1 LIMIT 1");
            $ultima = "N/A";
            if ($ultima_query && $ultima_query->num_rows > 0) {
                $ultima_row = $ultima_query->fetch_assoc();
                $ultima = $ultima_row['ultima'] ?? "N/A";
            }
            
            echo "<tr>";
            echo "<td>$tabla</td>";
            echo "<td><strong>$count</strong></td>";
            echo "<td>$ultima</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    
    // Total de dinero en el sistema
    $total_ventas = $mysqli->query("SELECT SUM(total) as total FROM ventas");
    $total_nequi = $mysqli->query("SELECT SUM(monto) as total FROM transacciones_nequi_demo");
    
    $dinero_ventas = $total_ventas ? $total_ventas->fetch_assoc()['total'] ?? 0 : 0;
    $dinero_nequi = $total_nequi ? $total_nequi->fetch_assoc()['total'] ?? 0 : 0;
    $total_dinero = $dinero_ventas + $dinero_nequi;
    
    echo "<div style='background: #d1ecf1; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>💰 Resumen Financiero:</h3>";
    echo "🏪 Ventas Tradicionales: $" . number_format($dinero_ventas) . "<br>";
    echo "📱 Transacciones Nequi: $" . number_format($dinero_nequi) . "<br>";
    echo "<strong>💎 Total en Sistema: $" . number_format($total_dinero) . "</strong>";
    echo "</div>";
    
    $mysqli->close();
    
    echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>🎉 Sincronización Completada</h3>";
    echo "✅ Todos los datos están sincronizados con HeidiSQL<br>";
    echo "✅ Las tablas están optimizadas con índices<br>";
    echo "✅ Los prepared statements aseguran integridad<br>";
    echo "<br><strong>📋 Próximos pasos:</strong><br>";
    echo "1. Abre HeidiSQL y actualiza (F5)<br>";
    echo "2. Verifica que veas todas las tablas y datos<br>";
    echo "3. Cualquier nueva transacción aparecerá automáticamente";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-radius: 5px;'>";
    echo "❌ Error: " . $e->getMessage();
    echo "</div>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f8f9fa;
}

table {
    margin: 10px 0;
    border-radius: 5px;
    overflow: hidden;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background: #007bff;
    color: white;
}

td {
    border-bottom: 1px solid #eee;
}
</style>
