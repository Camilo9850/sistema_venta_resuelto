<?php
// 📊 API DEL DASHBOARD - DATOS EN FORMATO JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario']) && !isset($_SESSION['nombre'])) {
    http_response_code(401);
    echo json_encode([
        'error' => true,
        'mensaje' => 'Acceso no autorizado. Debe iniciar sesión.',
        'codigo' => 401
    ]);
    exit;
}

require_once 'facturacion_totales.php';

try {
    $facturacion = new FacturacionTotales();
    $facturacion->simularDatosDemo();
    $estadisticas = $facturacion->obtenerEstadisticasCompletas();
    
    // Agregar información adicional
    $datos = [
        'exito' => true,
        'timestamp' => date('Y-m-d H:i:s'),
        'servidor' => $_SERVER['HTTP_HOST'],
        'version' => '1.0.0',
        'estadisticas' => $estadisticas,
        'resumen' => [
            'facturacion_mensual' => $estadisticas['mensual'] ?? 0,
            'facturacion_anual' => $estadisticas['anual'] ?? 0,
            'total_ventas' => $estadisticas['ventas_total'] ?? 0,
            'total_nequi' => $estadisticas['nequi_total'] ?? 0,
            'clientes_activos' => $estadisticas['clientes_activos'] ?? 0,
            'productos_disponibles' => $estadisticas['productos_cantidad'] ?? 0
        ],
        'enlaces' => [
            'dashboard_completo' => 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/dashboard.php',
            'api_datos' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'widget_ventas' => 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/widget_ventas.php'
        ]
    ];
    
    // Formatear números para mejor legibilidad
    $datos['resumen_formateado'] = [
        'facturacion_mensual' => '$' . number_format($datos['resumen']['facturacion_mensual'], 0, ',', '.'),
        'facturacion_anual' => '$' . number_format($datos['resumen']['facturacion_anual'], 0, ',', '.'),
        'total_ventas' => '$' . number_format($datos['resumen']['total_ventas'], 0, ',', '.'),
        'total_nequi' => '$' . number_format($datos['resumen']['total_nequi'], 0, ',', '.')
    ];
    
    echo json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error interno del servidor: ' . $e->getMessage(),
        'codigo' => 500,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
}
?>
