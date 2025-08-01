<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario']) && !isset($_SESSION['nombre'])) {
    header('Location: login.php');
    exit;
}

$pg = "Guía de Integración";
require_once 'header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">🔗 Guía de Integración del Dashboard</h1>
        <a href="dashboard.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Volver al Dashboard
        </a>
    </div>

    <div class="row">
        <!-- Documentación -->
        <div class="col-lg-8">
            
            <!-- Introducción -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">📋 Introducción</h6>
                </div>
                <div class="card-body">
                    <p>Esta guía le ayudará a integrar el Dashboard de Ventas en otras páginas web o aplicaciones. Tenemos varias opciones disponibles según sus necesidades:</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border-left-primary p-3 mb-3" style="border-left: 4px solid #4e73df;">
                                <h6><i class="fas fa-link text-primary"></i> Link Directo</h6>
                                <p class="mb-0">Para redirigir usuarios al dashboard completo</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-left-info p-3 mb-3" style="border-left: 4px solid #36b9cc;">
                                <h6><i class="fas fa-code text-info"></i> Iframe Embed</h6>
                                <p class="mb-0">Para mostrar el dashboard dentro de otra página</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-left-success p-3 mb-3" style="border-left: 4px solid #1cc88a;">
                                <h6><i class="fas fa-database text-success"></i> API JSON</h6>
                                <p class="mb-0">Para obtener datos programáticamente</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-left-warning p-3 mb-3" style="border-left: 4px solid #f6c23e;">
                                <h6><i class="fas fa-chart-bar text-warning"></i> Widget</h6>
                                <p class="mb-0">Para mostrar estadísticas básicas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Método 1: Iframe -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">💻 Método 1: Iframe (Embebido)</h6>
                </div>
                <div class="card-body">
                    <p><strong>Mejor para:</strong> Mostrar el dashboard completo dentro de otra página web.</p>
                    
                    <h6>Código básico:</h6>
                    <pre class="bg-light p-3 rounded"><code>&lt;iframe 
    src="http://<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/dashboard.php'; ?>" 
    width="100%" 
    height="600" 
    frameborder="0" 
    scrolling="yes"&gt;
&lt;/iframe&gt;</code></pre>

                    <h6 class="mt-3">Opciones de personalización:</h6>
                    <ul>
                        <li><code>width</code>: Ajuste el ancho (ej: "800px", "100%")</li>
                        <li><code>height</code>: Ajuste la altura (ej: "400px", "800px")</li>
                        <li><code>scrolling</code>: "yes" para permitir scroll, "no" para ocultarlo</li>
                    </ul>
                </div>
            </div>

            <!-- Método 2: API JSON -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">📡 Método 2: API JSON</h6>
                </div>
                <div class="card-body">
                    <p><strong>Mejor para:</strong> Obtener datos para crear sus propios gráficos o integraciones.</p>
                    
                    <h6>URL del API:</h6>
                    <div class="bg-light p-2 rounded mb-3">
                        <code>http://<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/api_dashboard.php'; ?></code>
                    </div>

                    <h6>Ejemplo con JavaScript (Fetch):</h6>
                    <pre class="bg-light p-3 rounded"><code>fetch('http://<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/api_dashboard.php'; ?>')
  .then(response => response.json())
  .then(data => {
    console.log('Ventas del mes:', data.resumen.facturacion_mensual);
    console.log('Total clientes:', data.resumen.clientes_activos);
    // Usar los datos en su aplicación
  })
  .catch(error => console.error('Error:', error));</code></pre>

                    <h6 class="mt-3">Estructura de respuesta JSON:</h6>
                    <pre class="bg-light p-3 rounded small"><code>{
  "exito": true,
  "timestamp": "2025-08-01 14:30:00",
  "resumen": {
    "facturacion_mensual": 29600000,
    "facturacion_anual": 54070000,
    "total_ventas": 48920000,
    "total_nequi": 5150000,
    "clientes_activos": 5,
    "productos_disponibles": 12
  },
  "resumen_formateado": {
    "facturacion_mensual": "$29.600.000",
    ...
  }
}</code></pre>
                </div>
            </div>

            <!-- Método 3: Widget -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">🎯 Método 3: Widget Compacto</h6>
                </div>
                <div class="card-body">
                    <p><strong>Mejor para:</strong> Mostrar estadísticas básicas en espacios pequeños.</p>
                    
                    <h6>Código del widget:</h6>
                    <pre class="bg-light p-3 rounded"><code>&lt;div style="border:1px solid #ddd; border-radius:8px; padding:15px; max-width:300px;"&gt;
    &lt;h4&gt;💰 Ventas Hoy&lt;/h4&gt;
    &lt;iframe 
        src="http://<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/widget_ventas.php'; ?>" 
        width="100%" 
        height="120" 
        frameborder="0" 
        scrolling="no"&gt;
    &lt;/iframe&gt;
    &lt;small&gt;Powered by ABM Ventas&lt;/small&gt;
&lt;/div&gt;</code></pre>

                    <h6 class="mt-3">Vista previa del widget:</h6>
                    <div style="border:1px solid #ddd; border-radius:8px; padding:15px; max-width:300px; font-family:Arial,sans-serif;">
                        <h6 style="margin:0 0 10px; color:#333;">💰 Ventas Hoy</h6>
                        <iframe src="widget_ventas.php" width="100%" height="120" frameborder="0" scrolling="no"></iframe>
                        <small style="color:#666;">Powered by ABM Ventas</small>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            
            <!-- Enlaces Rápidos -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">🚀 Enlaces Rápidos</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="dashboard.php?showLinks=true" class="list-group-item list-group-item-action">
                            <i class="fas fa-link text-primary"></i> Abrir Modal de Enlaces
                        </a>
                        <a href="api_dashboard.php" class="list-group-item list-group-item-action" target="_blank">
                            <i class="fas fa-database text-success"></i> Ver API en acción
                        </a>
                        <a href="widget_ventas.php" class="list-group-item list-group-item-action" target="_blank">
                            <i class="fas fa-chart-bar text-warning"></i> Ver Widget
                        </a>
                    </div>
                </div>
            </div>

            <!-- Consideraciones de Seguridad -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">🔒 Seguridad</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Importante:</h6>
                        <ul class="mb-0">
                            <li>El acceso requiere autenticación</li>
                            <li>Los datos son sensibles - úselos responsablemente</li>
                            <li>No comparta enlaces públicamente</li>
                            <li>Configure CORS apropiadamente en producción</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Soporte -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">🛟 Soporte</h6>
                </div>
                <div class="card-body">
                    <p>¿Necesita ayuda con la integración?</p>
                    <ul>
                        <li>Revise la consola del navegador para errores</li>
                        <li>Verifique que las URLs sean correctas</li>
                        <li>Asegúrese de estar autenticado</li>
                    </ul>
                    <a href="mailto:soporte@abmventas.com" class="btn btn-info btn-sm">
                        <i class="fas fa-envelope"></i> Contactar Soporte
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php require_once 'footer.php'; ?>
