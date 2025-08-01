<?php
// =====================================================
// 🔧 VERIFICAR VENTAS Y FACTURACIÓN
// =====================================================

include_once "config.php";

echo "<h2>🔍 VERIFICACIÓN DE VENTAS Y FACTURACIÓN</h2>";

try {
    $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
    
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }
    
    echo "<h3>📊 Resumen de ventas:</h3>";
    
    // Verificar total de ventas
    $sql = "SELECT COUNT(*) AS total_ventas, SUM(total) AS suma_total FROM ventas";
    $resultado = $mysqli->query($sql);
    if ($fila = $resultado->fetch_assoc()) {
        echo "<p><strong>Total de ventas:</strong> " . $fila['total_ventas'] . "</p>";
        echo "<p><strong>Suma total de todas las ventas:</strong> $" . number_format($fila['suma_total'], 2) . "</p>";
    }
    
    // Verificar ventas del mes actual
    $mesActual = date('m');
    $anioActual = date('Y');
    echo "<h3>📅 Ventas del mes actual ($mesActual/$anioActual):</h3>";
    
    $sql = "SELECT COUNT(*) AS ventas_mes, SUM(total) AS suma_mes FROM ventas WHERE MONTH(fecha) = '$mesActual' AND YEAR(fecha) = '$anioActual'";
    $resultado = $mysqli->query($sql);
    if ($fila = $resultado->fetch_assoc()) {
        echo "<p><strong>Ventas del mes:</strong> " . $fila['ventas_mes'] . "</p>";
        echo "<p><strong>Facturación del mes:</strong> $" . number_format($fila['suma_mes'], 2) . "</p>";
    }
    
    // Mostrar algunas ventas de ejemplo
    echo "<h3>📋 Últimas 5 ventas:</h3>";
    $sql = "SELECT v.idventa, v.fecha, v.total, c.nombre as cliente, p.nombre as producto 
            FROM ventas v 
            LEFT JOIN clientes c ON v.fk_idcliente = c.idcliente 
            LEFT JOIN productos p ON v.fk_idproducto = p.idproducto 
            ORDER BY v.fecha DESC LIMIT 5";
    
    $resultado = $mysqli->query($sql);
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Producto</th><th>Total</th></tr>";
    
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $fila['idventa'] . "</td>";
        echo "<td>" . $fila['fecha'] . "</td>";
        echo "<td>" . ($fila['cliente'] ?? 'Sin nombre') . "</td>";
        echo "<td>" . ($fila['producto'] ?? 'Sin nombre') . "</td>";
        echo "<td>$" . number_format($fila['total'], 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Verificar ventas por mes
    echo "<h3>📈 Ventas por mes (último año):</h3>";
    $sql = "SELECT YEAR(fecha) as anio, MONTH(fecha) as mes, COUNT(*) as cantidad, SUM(total) as total 
            FROM ventas 
            WHERE fecha >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY YEAR(fecha), MONTH(fecha) 
            ORDER BY anio DESC, mes DESC";
    
    $resultado = $mysqli->query($sql);
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Año</th><th>Mes</th><th>Cantidad</th><th>Total</th></tr>";
    
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $fila['anio'] . "</td>";
        echo "<td>" . $fila['mes'] . "</td>";
        echo "<td>" . $fila['cantidad'] . "</td>";
        echo "<td>$" . number_format($fila['total'], 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<br><p><a href='index.php'>← Volver al Dashboard</a></p>";
?>
