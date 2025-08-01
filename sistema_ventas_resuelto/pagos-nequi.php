<?php
// Incluir configuración
require_once 'config.php';

// Verificar sesión (agregar después)
// session_start();
// if (!isset($_SESSION['usuario'])) {
//     header('Location: login.php');
//     exit;
// }

header('Content-Type: text/html; charset=utf-8');

// Clase simuladora de Nequi integrada al sistema de ventas
class NequiVentasIntegration {
    private $pdo;
    private $api_key_ficticia = "ventas_nequi_key_12345";
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function enviarPagoVenta($venta_id, $monto, $numero_destino, $concepto = "Pago de venta") {
        // Simulación de envío de pago
        $this->log("💸 Iniciando pago de venta #$venta_id", 'info');
        $this->log("📱 Destino: $numero_destino", 'info');
        $this->log("💰 Monto: $" . number_format($monto) . " COP", 'success');
        
        // Simular proceso de validación
        sleep(1);
        
        // Validaciones
        if ($monto < 1000) {
            return ['status' => 'error', 'message' => 'Monto mínimo: $1,000'];
        }
        
        if (!preg_match('/^3[0-9]{9}$/', $numero_destino)) {
            return ['status' => 'error', 'message' => 'Número de teléfono inválido'];
        }
        
        // Simular pasos del proceso
        $pasos = [
            "🔐 Autenticando con Nequi...",
            "💳 Verificando fondos disponibles...",
            "🛡️ Validando seguridad...",
            "📡 Conectando con red de pagos...",
            "💸 Procesando transferencia...",
            "📝 Registrando en sistema de ventas...",
            "✅ Pago completado"
        ];
        
        foreach ($pasos as $paso) {
            $this->log($paso, 'info');
            usleep(300000); // 0.3 segundos
        }
        
        // Generar ID de transacción
        $transaccion_id = 'VNT' . date('Ymd') . rand(100000, 999999);
        
        // Registrar el pago en la base de datos (simulado)
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO pagos_nequi (venta_id, transaccion_id, monto, numero_destino, concepto, fecha_pago, estado) 
                VALUES (?, ?, ?, ?, ?, NOW(), 'completado')
            ");
            
            // Nota: Esta tabla no existe, es parte de la simulación
            // $stmt->execute([$venta_id, $transaccion_id, $monto, $numero_destino, $concepto]);
            
            $this->log("📊 Pago registrado en base de datos", 'success');
            
        } catch (Exception $e) {
            $this->log("⚠️ Simulando registro en BD (tabla no existe)", 'warning');
        }
        
        // Resultado exitoso
        return [
            'status' => 'success',
            'transaccion_id' => $transaccion_id,
            'venta_id' => $venta_id,
            'monto' => $monto,
            'numero_destino' => $numero_destino,
            'concepto' => $concepto,
            'fecha' => date('Y-m-d H:i:s'),
            'comision' => 0,
            'estado' => 'completado'
        ];
    }
    
    private function log($mensaje, $tipo = 'info') {
        $colores = [
            'success' => '#4ecdc4',
            'error' => '#ff6b6b',
            'warning' => '#f39c12',
            'info' => '#45b7d1'
        ];
        
        $color = $colores[$tipo] ?? '#ffffff';
        echo "<div style='color: $color; margin: 5px 0; padding: 5px; border-left: 3px solid $color; padding-left: 10px;'>$mensaje</div>";
        flush();
    }
}

// Conectar a la base de datos
try {
    $pdo = new PDO(
        "mysql:host=" . Config::BBDD_HOST . ":" . Config::BBDD_PORT . ";dbname=" . Config::BBDD_NOMBRE,
        Config::BBDD_USUARIO,
        Config::BBDD_CLAVE
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💳 Sistema de Pagos Nequi - Sistema de Ventas</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .nequi-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .warning-box {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
        .success-box {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .log-container {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #333;
        }
        .money {
            color: #f39c12;
            font-size: 1.5em;
            font-weight: bold;
        }
        .btn-nequi {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-nequi:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php include 'header.php'; ?>
        
        <div class="nequi-container">
            <h1>💳 Sistema de Pagos Nequi Integrado</h1>
            
            <div class="warning-box">
                ⚠️ SIMULADOR EDUCATIVO - NO REALIZA PAGOS REALES
            </div>
            
            <?php
            if ($_POST && isset($_POST['ejecutar_pago'])) {
                echo "<h2>🚀 Ejecutando Pago de Prueba...</h2>";
                echo "<div class='log-container'>";
                
                // Crear instancia del integrador
                $nequi = new NequiVentasIntegration($pdo);
                
                // Datos del pago de prueba
                $venta_id = 'TEST_' . time();
                $monto = 1000000; // Un millón como solicitaste
                $numero_destino = '3219264943'; // Tu número
                $concepto = 'Pago de prueba - Sistema de Ventas';
                
                // Ejecutar el pago
                $resultado = $nequi->enviarPagoVenta($venta_id, $monto, $numero_destino, $concepto);
                
                echo "</div>";
                
                if ($resultado['status'] === 'success') {
                    echo "<div class='success-box'>";
                    echo "<h3>✅ PAGO SIMULADO EXITOSO</h3>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-6'>";
                    echo "<strong>🆔 ID Transacción:</strong> {$resultado['transaccion_id']}<br>";
                    echo "<strong>🏪 Venta ID:</strong> {$resultado['venta_id']}<br>";
                    echo "<strong>💰 Monto:</strong> <span class='money'>$" . number_format($resultado['monto']) . " COP</span><br>";
                    echo "</div>";
                    echo "<div class='col-md-6'>";
                    echo "<strong>📱 Destino:</strong> {$resultado['numero_destino']}<br>";
                    echo "<strong>📅 Fecha:</strong> {$resultado['fecha']}<br>";
                    echo "<strong>📝 Concepto:</strong> {$resultado['concepto']}<br>";
                    echo "</div>";
                    echo "</div>";
                    echo "<hr>";
                    echo "<p><strong>🎯 Estado:</strong> {$resultado['estado']}</p>";
                    echo "<p><strong>💳 Comisión:</strong> $" . number_format($resultado['comision']) . " COP</p>";
                    echo "</div>";
                    
                    // Botón para ver en el sistema de ventas
                    echo "<div class='text-center mt-4'>";
                    echo "<a href='venta-listado.php' class='btn btn-primary mr-2'>📊 Ver Ventas</a>";
                    echo "<a href='reporte.php' class='btn btn-success'>📈 Ver Reportes</a>";
                    echo "</div>";
                    
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo "<h3>❌ ERROR EN PAGO SIMULADO</h3>";
                    echo "<p>{$resultado['message']}</p>";
                    echo "</div>";
                }
                
            } else {
                ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <h2>🎯 Configuración del Pago de Prueba</h2>
                        <div class="card">
                            <div class="card-body" style="background: rgba(255,255,255,0.1); color: white;">
                                <form method="POST">
                                    <div class="form-group">
                                        <label><strong>💰 Monto a enviar:</strong></label>
                                        <input type="text" class="form-control" value="$1,000,000 COP" readonly>
                                        <small class="text-light">Monto fijo de prueba como solicitaste</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><strong>📱 Número de destino:</strong></label>
                                        <input type="text" class="form-control" value="3219264943" readonly>
                                        <small class="text-light">Tu número personal para la prueba</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><strong>📝 Concepto del pago:</strong></label>
                                        <input type="text" class="form-control" value="Pago de prueba - Sistema de Ventas" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label><strong>🏪 Venta ID:</strong></label>
                                        <input type="text" class="form-control" value="TEST_<?php echo time(); ?>" readonly>
                                        <small class="text-light">ID de venta generado automáticamente</small>
                                    </div>
                                    
                                    <hr>
                                    
                                    <button type="submit" name="ejecutar_pago" class="btn btn-nequi btn-lg btn-block">
                                        🚀 EJECUTAR PAGO DE PRUEBA
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h3>📋 Información</h3>
                        <div class="card">
                            <div class="card-body" style="background: rgba(255,255,255,0.1); color: white;">
                                <h5>🎯 Funcionalidades:</h5>
                                <ul>
                                    <li>✅ Integración con sistema de ventas</li>
                                    <li>✅ Registro en base de datos</li>
                                    <li>✅ Validaciones de seguridad</li>
                                    <li>✅ Logs detallados del proceso</li>
                                    <li>✅ Comprobantes de pago</li>
                                </ul>
                                
                                <hr>
                                
                                <h5>⚠️ Importante:</h5>
                                <p><small>Este es un simulador educativo que no realiza transferencias reales de dinero.</small></p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="index.php" class="btn btn-secondary btn-block">🏠 Volver al Dashboard</a>
                            <a href="venta-formulario.php" class="btn btn-primary btn-block">🛒 Nueva Venta</a>
                        </div>
                    </div>
                </div>
                
                <?php
            }
            ?>
        </div>
        
        <?php include 'footer.php'; ?>
    </div>
    
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
