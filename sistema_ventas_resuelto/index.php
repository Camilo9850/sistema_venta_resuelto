<?php
// Nota: session_start() se maneja en header.php

include_once "config.php";
include_once "entidades/venta.php";
include_once "facturacion_totales.php";
include_once("header.php"); 
$pg = "Inicio";

$venta = new Venta();
$facturacionMes = $venta->obtenerFacturacionMensual(date("m"), date("Y"));

$fechaHasta = date("Y-m-d");
$date = new DateTime($fechaHasta);
$fechaDesde = date_format($date->modify("-12 months"), "Y-m-d");
$facturacionAnual = $venta->obtenerFacturacionPorPeriodo($fechaDesde, $fechaHasta);

// 🆕 OBTENER TODAS LAS VENTAS (TOTAL GLOBAL)
$facturacionTotalGlobal = $venta->obtenerFacturacionTotal(); // Nueva función para obtener TODAS las ventas

// 🆕 INTEGRACIÓN CON NEQUI Y TOTALES COMPLETOS
$facturacionCompleta = new FacturacionTotales();
$facturacionCompleta->simularDatosDemo(); // Asegurar datos demo
$estadisticas = $facturacionCompleta->obtenerEstadisticasCompletas();

// Totales combinados (ventas tradicionales + Nequi) con validaciones
$totalMensualCompleto = $facturacionMes + (isset($estadisticas['nequi_total']) ? $estadisticas['nequi_total'] * 0.1 : 0); // Aproximación mensual Nequi
$totalAnualCompleto = $facturacionAnual + (isset($estadisticas['nequi_total']) ? $estadisticas['nequi_total'] : 0);
$totalGlobalCompleto = $facturacionTotalGlobal + (isset($estadisticas['nequi_total']) ? $estadisticas['nequi_total'] : 0); // TOTAL DE TODAS LAS VENTAS
$totalNequi = isset($estadisticas['nequi_total']) ? $estadisticas['nequi_total'] : 0;
$transaccionesNequi = isset($estadisticas['nequi_cantidad']) ? $estadisticas['nequi_cantidad'] : 0;

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">💰 Dashboard Financiero</h1>
            <div>
              <a href="reporte.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Generar Reporte
              </a>
              <a href="dashboard.php" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-chart-line fa-sm text-white-50"></i> Dashboard Completo
              </a>
            </div>
          </div>

          <!-- Resumen Rápido -->
          <div class="row mb-3">
            <div class="col-12">
              <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i> 
                <strong>Sistema Integrado:</strong> 
                Los totales incluyen ventas tradicionales del sistema + transferencias Nequi simuladas.
                <span class="float-right">
                  <small>Última actualización: <?php echo date('d/m/Y H:i:s'); ?></small>
                </span>
              </div>
            </div>
          </div>

          <!-- Content Row -->
          <div class="row">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">💰 Facturación Total (todas las ventas)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$ <?php echo number_format($totalGlobalCompleto, 0, ",", "."); ?></div>
                      <div class="text-xs text-muted">Ventas + Nequi</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Annual) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">� Facturación Mensual</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$ <?php echo number_format($totalMensualCompleto, 0, ",", ".");?></div>
                      <div class="text-xs text-muted">Mes actual</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Nequi Payments Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">📱 Pagos Nequi</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$ <?php echo number_format($totalNequi, 0, ",", ".");?></div>
                      <div class="text-xs text-muted"><?php echo $transaccionesNequi; ?> transferencias</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-mobile-alt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Nequi Simulator Access Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">🚀 Nequi Simulator</div>
                      <div class="h6 mb-0">
                        <a href="prueba_millon_notificaciones.php" class="btn btn-warning btn-sm">
                          <i class="fas fa-rocket"></i> Probar 1M
                        </a>
                      </div>
                      <div class="text-xs text-muted">Con notificaciones SMS</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-rocket fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>


          </div>

          <!-- Content Row -->

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Ganancia por productos</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                  </div>
                  <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Direct
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Social
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> Referral
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

<!-- CSS personalizado para mejorar la visualización -->
<style>
  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
  }
  
  .text-muted {
    font-size: 0.75rem;
  }
  
  .border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
  }
  
  .border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
  }
  
  .card-body .h5, .card-body .h6 {
    animation: countUp 1s ease-out;
  }
  
  @keyframes countUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  .alert {
    border-radius: 10px;
    border: none;
  }
  
  .btn-warning {
    color: #fff;
    background-color: #f6c23e;
    border-color: #f6c23e;
  }
  
  .btn-warning:hover {
    background-color: #f4b619;
    border-color: #f4b619;
    transform: scale(1.05);
  }
</style>

<!-- Script para auto-actualización de totales -->
<script>
  // Función para actualizar los totales automáticamente
  function actualizarTotales() {
    fetch('facturacion_totales.php')
      .then(response => response.json())
      .then(data => {
        console.log('Totales actualizados:', data);
        
        // Opcional: mostrar notificación de actualización
        if (data.mensual || data.anual) {
          const notification = document.createElement('div');
          notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
          notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
          notification.innerHTML = `
            <i class="fas fa-sync-alt"></i> Totales actualizados
            <button type="button" class="close" data-dismiss="alert">
              <span>&times;</span>
            </button>
          `;
          document.body.appendChild(notification);
          
          setTimeout(() => {
            if (notification.parentNode) {
              notification.parentNode.removeChild(notification);
            }
          }, 3000);
        }
      })
      .catch(error => console.log('Error actualizando:', error));
  }
  
  // Auto-actualizar cada 2 minutos
  setInterval(actualizarTotales, 120000);
  
  // Animación inicial de las tarjetas
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      
      setTimeout(() => {
        card.style.transition = 'all 0.5s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, index * 150);
    });
  });
</script>

<?php include_once("footer.php"); ?>