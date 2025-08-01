<?php
// 📊 DASHBOARD PRINCIPAL - SISTEMA DE VENTAS CON TOTALES
session_start();

// Verificar si el usuario está logueado ANTES de incluir archivos que generan HTML
if (!isset($_SESSION['usuario']) && !isset($_SESSION['nombre'])) {
    header('Location: login.php');
    exit;
}

require_once 'header.php';
require_once 'facturacion_totales.php';

$facturacion = new FacturacionTotales();
$facturacion->simularDatosDemo(); // Asegurar que hay datos demo
$estadisticas = $facturacion->obtenerEstadisticasCompletas();
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Dashboard Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">💰 Dashboard Principal</h1>
        <div class="text-right">
            <small class="text-muted">Sistema de Ventas y Pagos Nequi - Resumen Ejecutivo</small><br>
            <small class="text-muted">Última actualización: <?php echo date('d/m/Y H:i:s'); ?></small>
            <br>
            <!-- 🔗 Enlaces de Integración -->
            <div class="mt-2">
                <button class="btn btn-sm btn-outline-primary" onclick="copiarLink('completo')">
                    <i class="fas fa-link"></i> Link Completo
                </button>
                <button class="btn btn-sm btn-outline-info" onclick="copiarLink('embebido')">
                    <i class="fas fa-code"></i> Código Embed
                </button>
                <button class="btn btn-sm btn-outline-success" onclick="copiarLink('api')">
                    <i class="fas fa-database"></i> API Datos
                </button>
            </div>
        </div>
    </div>

    <!-- 📋 Modal de Enlaces de Integración -->
    <div class="modal fade" id="modalIntegracion" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🔗 Enlaces de Integración del Dashboard</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <!-- Link Completo -->
                        <div class="mb-4">
                            <h6><i class="fas fa-link text-primary"></i> Link Directo al Dashboard</h6>
                            <div class="input-group">
                                <input type="text" class="form-control" id="linkCompleto" 
                                       value="<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/dashboard.php'; ?>" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" onclick="copiarTexto('linkCompleto')">
                                        <i class="fas fa-copy"></i> Copiar
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">Use este enlace para acceder directamente al dashboard</small>
                        </div>

                        <!-- Código Embed -->
                        <div class="mb-4">
                            <h6><i class="fas fa-code text-info"></i> Código para Embeber (iframe)</h6>
                            <textarea class="form-control" id="codigoEmbed" rows="4" readonly><iframe src="http://<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/dashboard.php'; ?>" width="100%" height="600" frameborder="0" scrolling="yes" title="Dashboard de Ventas">
    <p>Su navegador no soporta iframes. <a href="http://<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/dashboard.php'; ?>">Acceder al Dashboard</a></p>
</iframe></textarea>
                            <div class="mt-2">
                                <button class="btn btn-info" onclick="copiarTexto('codigoEmbed')">
                                    <i class="fas fa-copy"></i> Copiar Código
                                </button>
                            </div>
                            <small class="text-muted">Pegue este código en cualquier página HTML para mostrar el dashboard</small>
                        </div>

                        <!-- API de Datos -->
                        <div class="mb-4">
                            <h6><i class="fas fa-database text-success"></i> API de Datos del Dashboard</h6>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="apiUrl" 
                                       value="<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/api_dashboard.php'; ?>" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-success" onclick="copiarTexto('apiUrl')">
                                        <i class="fas fa-copy"></i> Copiar URL
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">Endpoint JSON para obtener los datos del dashboard programáticamente</small>
                        </div>

                        <!-- Widget Compacto -->
                        <div class="mb-4">
                            <h6><i class="fas fa-chart-bar text-warning"></i> Widget Compacto</h6>
                            <textarea class="form-control" id="widgetCompacto" rows="5" readonly><div style="border:1px solid #ddd; border-radius:8px; padding:15px; max-width:300px; font-family:Arial,sans-serif;">
    <h4 style="margin:0 0 10px; color:#333;">💰 Ventas Hoy</h4>
    <iframe src="http://<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/widget_ventas.php'; ?>" 
            width="100%" height="120" frameborder="0" scrolling="no"></iframe>
    <small style="color:#666;">Powered by ABM Ventas</small>
</div></textarea>
                            <div class="mt-2">
                                <button class="btn btn-warning" onclick="copiarTexto('widgetCompacto')">
                                    <i class="fas fa-copy"></i> Copiar Widget
                                </button>
                            </div>
                            <small class="text-muted">Widget pequeño para mostrar estadísticas básicas</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <a href="dashboard_help.php" class="btn btn-primary">
                        <i class="fas fa-question-circle"></i> Guía de Integración
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de estadísticas -->
    <div class="row">
        
        <!-- Facturación Mensual -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                💰 Facturación (Mensual)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $ <?php echo number_format($estadisticas['mensual'] ?? 0, 0, ",", "."); ?>
                            </div>
                            <div class="text-xs text-muted">August 2025</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facturación Anual -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                📈 Facturación (Anual)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $ <?php echo number_format($estadisticas['anual'] ?? 0, 0, ",", "."); ?>
                            </div>
                            <div class="text-xs text-muted">2025</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas Sistema -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                🛒 Ventas Sistema
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $ <?php echo number_format($estadisticas['ventas_total'] ?? 0, 0, ",", "."); ?>
                            </div>
                            <div class="text-xs text-muted"><?php echo $estadisticas['ventas_cantidad'] ?? 0; ?> transacciones</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagos Nequi -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                💳 Pagos Nequi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                $ <?php echo number_format($estadisticas['nequi_total'] ?? 0, 0, ",", "."); ?>
                            </div>
                            <div class="text-xs text-muted"><?php echo $estadisticas['nequi_cantidad'] ?? 0; ?> transferencias</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mobile-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Segunda fila de estadísticas -->
    <div class="row">

        <!-- Clientes Activos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                👥 Clientes Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $estadisticas['clientes_activos'] ?? 0; ?>
                            </div>
                            <div class="text-xs text-muted">Total registrados</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                📦 Productos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $estadisticas['productos_cantidad'] ?? 0; ?>
                            </div>
                            <div class="text-xs text-muted"><?php echo $estadisticas['stock_total'] ?? 0; ?> en stock</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Eficiencia -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                ⚡ Eficiencia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">95%</div>
                            <div class="text-xs text-muted">19 transacciones</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nequi Simulator -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                🔄 Nequi Simulator
                            </div>
                            <div class="mb-2">
                                <button class="btn btn-danger btn-sm">🔮 Probar 1M</button>
                            </div>
                            <div class="text-xs text-muted">Simulador activo</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-magic fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Gráficos y actividad -->
    <div class="row">
        <!-- Evolución de facturación -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">📊 Evolución de Facturación (Últimos 6 Meses)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividad reciente -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">🕒 Actividad Reciente</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <div class="btn btn-danger btn-circle btn-sm mr-3">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold">Transferencia Nequi</div>
                                <div class="text-muted small">$1,000,000 - hace 2 horas</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <div class="btn btn-primary btn-circle btn-sm mr-3">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold">Venta Sistema</div>
                                <div class="text-muted small">$500,000 - hace 3 horas</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <div class="btn btn-success btn-circle btn-sm mr-3">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold">Nuevo Cliente</div>
                                <div class="text-muted small">María García - hace 5 horas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script src="vendor/chart.js/Chart.min.js"></script>
<script>
// Datos demo para el gráfico
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Gráfico de área
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["Mar", "Abr", "May", "Jun", "Jul", "Ago"],
    datasets: [{
      label: "Facturación Mensual",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: [10000000, 15000000, 20000000, 18000000, 25000000, 29600000],
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          padding: 10,
          callback: function(value, index, values) {
            return '$' + number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});

// 🔗 Funciones para Enlaces de Integración
function copiarLink(tipo) {
    $('#modalIntegracion').modal('show');
}

function copiarTexto(elementId) {
    const elemento = document.getElementById(elementId);
    elemento.select();
    elemento.setSelectionRange(0, 99999); // Para móviles
    
    try {
        document.execCommand('copy');
        
        // Mostrar notificación de éxito
        const boton = event.target.closest('button');
        const textoOriginal = boton.innerHTML;
        boton.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
        boton.classList.add('btn-success');
        
        setTimeout(() => {
            boton.innerHTML = textoOriginal;
            boton.classList.remove('btn-success');
        }, 2000);
        
    } catch (err) {
        alert('Error al copiar. Seleccione el texto manualmente.');
    }
}

// Auto-abrir modal si se pasa parámetro URL
$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('showLinks') === 'true') {
        $('#modalIntegracion').modal('show');
    }
});
</script>

<?php require_once 'footer.php'; ?>
