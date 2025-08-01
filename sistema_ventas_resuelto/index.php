<?php
// Dashboard principal del sistema de ventas
include_once "config.php";
include_once "entidades/venta.php";
include_once "entidades/producto.php";
include_once "entidades/usuario.php";

$pg = "Dashboard - Sistema de Ventas";
include_once("header.php");

// Obtener datos para estadísticas
$venta = new Venta();
$producto = new Producto();
$usuario = new Usuario();

$facturacionMes = $venta->obtenerFacturacionMensual(date("m"), date("Y"));
$fechaHasta = date("Y-m-d");
$date = new DateTime($fechaHasta);
$fechaDesde = date_format($date->modify("-12 months"), "Y-m-d");
$facturacionAnual = $venta->obtenerFacturacionPorPeriodo($fechaDesde, $fechaHasta);

// Contar productos y usuarios
$totalProductos = count($producto->obtenerTodos());
$totalUsuarios = count($usuario->obtenerTodos());
$totalVentas = count($venta->obtenerTodos());

// Obtener ganancias reales por producto
$gananciasProductos = $venta->obtenerGananciasPorProducto();

// Preparar datos para la gráfica
$labelsProductos = [];
$datosGanancias = [];
$colores = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1', '#fd7e14', '#20c997', '#6c757d', '#17a2b8'];
$coloresHover = ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#c74a3b', '#5a35a3', '#e35d04', '#1ba085', '#545b62', '#138496'];

if (!empty($gananciasProductos)) {
    foreach ($gananciasProductos as $index => $item) {
        $labelsProductos[] = $item['producto'];
        $datosGanancias[] = $item['ganancia'];
    }
} else {
    // Datos de ejemplo si no hay ventas
    $labelsProductos = ['Sin ventas registradas'];
    $datosGanancias = [1];
    $colores = ['#e3e6f0'];
}

?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">📊 Dashboard - Sistema de Ventas</h1>
            <div>
              <a href="reporte.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Generar Reporte
              </a>
              <a href="venta-formulario.php" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Nueva Venta
              </a>
            </div>
          </div>

          <!-- Content Row - Tarjetas de estadísticas -->
          <div class="row">

            <!-- Card Ventas del Mes -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ventas del Mes</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($facturacionMes, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Card Ventas del Año -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ventas del Año</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($facturacionAnual, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Card Total Productos -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Productos</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalProductos; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-boxes fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Card Total Usuarios -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Usuarios Activos</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalUsuarios; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Row - Gráficos -->
          <div class="row">

            <!-- Gráfico de Ganancia por productos -->
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Ganancia por productos</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                  </div>
                  <div class="mt-4 small">
                    <?php if (!empty($gananciasProductos)): ?>
                      <?php foreach ($gananciasProductos as $index => $item): ?>
                        <div class="row mx-1 mb-2">
                          <div class="col-8 text-left">
                            <i class="fas fa-circle mr-1" style="color: <?php echo $colores[$index]; ?>"></i> 
                            <?php echo $item['producto']; ?>
                          </div>
                          <div class="col-4 text-right">
                            <strong>$<?php echo number_format($item['ganancia'], 0, '.', ','); ?></strong>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <div class="text-center text-muted">
                        <i class="fas fa-info-circle"></i> No hay ventas registradas
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

            <!-- Accesos Rápidos -->
            <div class="col-xl-6 col-lg-6">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">🚀 Accesos Rápidos</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-6 mb-3">
                      <a href="producto-listado.php" class="btn btn-primary btn-block">
                        <i class="fas fa-boxes"></i><br>
                        <small>Productos</small>
                      </a>
                    </div>
                    <div class="col-6 mb-3">
                      <a href="venta-listado.php" class="btn btn-success btn-block">
                        <i class="fas fa-shopping-cart"></i><br>
                        <small>Ventas</small>
                      </a>
                    </div>
                    <div class="col-6 mb-3">
                      <a href="cliente-listado.php" class="btn btn-info btn-block">
                        <i class="fas fa-users"></i><br>
                        <small>Clientes</small>
                      </a>
                    </div>
                    <div class="col-6 mb-3">
                      <a href="usuario-listado.php" class="btn btn-warning btn-block">
                        <i class="fas fa-user-shield"></i><br>
                        <small>Usuarios</small>
                      </a>
                    </div>
                  </div>
                  
                  <hr>
                  
                  <div class="row">
                    <div class="col-12">
                      <h6 class="font-weight-bold text-gray-800">📈 Resumen del Sistema</h6>
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                          <span>Total de Ventas:</span>
                          <strong class="text-success"><?php echo $totalVentas; ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                          <span>Productos Registrados:</span>
                          <strong class="text-primary"><?php echo $totalProductos; ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                          <span>Usuarios del Sistema:</span>
                          <strong class="text-info"><?php echo $totalUsuarios; ?></strong>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

<!-- Script para el gráfico de pie -->
<script>
// Configuración para Chart.js v3+
Chart.defaults.font.family = 'Nunito, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.color = '#858796';

// Pie Chart
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: [<?php echo '"' . implode('", "', $labelsProductos) . '"'; ?>],
    datasets: [{
      data: [<?php echo implode(', ', $datosGanancias); ?>],
      backgroundColor: [<?php echo '"' . implode('", "', array_slice($colores, 0, count($datosGanancias))) . '"'; ?>],
      hoverBackgroundColor: [<?php echo '"' . implode('", "', array_slice($coloresHover, 0, count($datosGanancias))) . '"'; ?>],
      borderWidth: 3,
      borderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    plugins: {
      tooltip: {
        backgroundColor: "rgb(255,255,255)",
        titleColor: "#858796",
        bodyColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        displayColors: false,
        titleFont: {
          size: 14
        },
        bodyFont: {
          size: 13
        },
        callbacks: {
          label: function(context) {
            var total = context.dataset.data.reduce((a, b) => a + b, 0);
            var percentage = ((context.parsed * 100) / total).toFixed(1);
            return context.label + ': $' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
          }
        }
      },
      legend: {
        display: false
      }
    },
    cutout: '80%',
    responsive: true,
    animation: {
      animateScale: true,
      animateRotate: true
    }
  },
});
</script>

<?php include_once("footer.php"); ?>
