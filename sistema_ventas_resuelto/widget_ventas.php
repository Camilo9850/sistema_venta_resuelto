<?php
// 🎯 WIDGET COMPACTO DE VENTAS
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario']) && !isset($_SESSION['nombre'])) {
    // Mostrar widget básico sin datos sensibles para usuarios no autenticados
    $mostrarDatos = false;
} else {
    $mostrarDatos = true;
}

if ($mostrarDatos) {
    require_once 'facturacion_totales.php';
    
    try {
        $facturacion = new FacturacionTotales();
        $facturacion->simularDatosDemo();
        $estadisticas = $facturacion->obtenerEstadisticasCompletas();
        
        $ventasHoy = $estadisticas['mensual'] ?? 0;
        $transaccionesHoy = ($estadisticas['ventas_cantidad'] ?? 0) + ($estadisticas['nequi_cantidad'] ?? 0);
        $estado = "Activo";
        
    } catch (Exception $e) {
        $ventasHoy = 0;
        $transaccionesHoy = 0;
        $estado = "Error";
    }
} else {
    $ventasHoy = 0;
    $transaccionesHoy = 0;
    $estado = "No autenticado";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Widget Ventas</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 12px;
        }
        .widget-container {
            text-align: center;
            padding: 10px;
        }
        .valor-principal {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }
        .subtitulo {
            font-size: 10px;
            opacity: 0.8;
            margin: 2px 0;
        }
        .estado {
            font-size: 9px;
            margin-top: 8px;
            padding: 2px 6px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: inline-block;
        }
        .icono {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .no-auth {
            background: linear-gradient(135deg, #666 0%, #999 100%);
        }
    </style>
</head>
<body class="<?php echo !$mostrarDatos ? 'no-auth' : ''; ?>">
    <div class="widget-container">
        <?php if ($mostrarDatos): ?>
            <div class="icono">💰</div>
            <div class="valor-principal">$<?php echo number_format($ventasHoy, 0, ',', '.'); ?></div>
            <div class="subtitulo">Ventas del mes</div>
            <div class="subtitulo"><?php echo $transaccionesHoy; ?> transacciones</div>
            <div class="estado">🟢 <?php echo $estado; ?></div>
        <?php else: ?>
            <div class="icono">🔒</div>
            <div class="valor-principal">ABM Ventas</div>
            <div class="subtitulo">Inicie sesión para ver datos</div>
            <div class="estado">🔴 <?php echo $estado; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
