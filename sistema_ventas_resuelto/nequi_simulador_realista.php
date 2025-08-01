<?php
// ⚠️ SIMULADOR EDUCATIVO AVANZADO DE NEQUI - NO ES REAL
require_once 'config.php';
require_once 'notificaciones_nequi.php';

header('Content-Type: text/html; charset=utf-8');

// Clase simuladora de Nequi más realista
class NequiSimuladorAvanzado {
    private $pdo;
    private $api_endpoint = "https://api.nequi.com.co/v2/"; // SIMULADO - NO REAL
    private $api_key = "DEMO_KEY_" . date('Ymd');
    
    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=" . Config::BBDD_HOST . ":" . Config::BBDD_PORT . ";dbname=" . Config::BBDD_NOMBRE,
                Config::BBDD_USUARIO,
                Config::BBDD_CLAVE,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (Exception $e) {
            $this->pdo = null;
        }
    }
    
    // Simular autenticación con Nequi
    public function autenticarConNequi($telefono_origen, $pin) {
        $this->mostrarProceso("🔐 Autenticando con Nequi...");
        sleep(1);
        
        // Simular validación de PIN
        if (strlen($pin) !== 4 || !is_numeric($pin)) {
            return ['status' => false, 'error' => 'PIN debe tener 4 dígitos'];
        }
        
        // Simular verificación de cuenta
        $this->mostrarProceso("📱 Verificando cuenta activa...");
        sleep(1);
        
        // Simular respuesta de Nequi
        return [
            'status' => true,
            'usuario' => 'Usuario Demo',
            'saldo_disponible' => 1500000, // $1.500.000 simulados
            'session_token' => 'DEMO_TOKEN_' . md5($telefono_origen . time())
        ];
    }
    
    // Simular consulta de saldo
    public function consultarSaldo($telefono) {
        $this->mostrarProceso("💰 Consultando saldo en Nequi...");
        sleep(1);
        
        // Simular diferentes saldos según el número
        $ultimo_digito = substr($telefono, -1);
        $saldos_simulados = [
            0 => 850000,   // $850.000
            1 => 1200000,  // $1.200.000
            2 => 750000,   // $750.000
            3 => 2000000,  // $2.000.000
            4 => 500000,   // $500.000
            5 => 1500000,  // $1.500.000
            6 => 300000,   // $300.000
            7 => 1800000,  // $1.800.000
            8 => 650000,   // $650.000
            9 => 950000    // $950.000
        ];
        
        $saldo = $saldos_simulados[$ultimo_digito];
        
        return [
            'status' => 'success',
            'saldo' => $saldo,
            'moneda' => 'COP',
            'fecha_consulta' => date('Y-m-d H:i:s')
        ];
    }
    
    // Simular envío de dinero a Nequi
    public function enviarDineroNequi($datos) {
        $telefono_origen = $datos['telefono_origen'];
        $telefono_destino = $datos['telefono_destino'];
        $monto = $datos['monto'];
        $concepto = $datos['concepto'];
        $pin = $datos['pin'];
        
        // Advertencias de seguridad
        $this->mostrarAdvertencia();
        
        // Paso 1: Autenticación
        $auth = $this->autenticarConNequi($telefono_origen, $pin);
        if (!$auth['status']) {
            return ['status' => 'error', 'message' => $auth['error']];
        }
        
        // Paso 2: Verificar saldo
        $this->mostrarProceso("💳 Verificando saldo disponible...");
        $saldo_info = $this->consultarSaldo($telefono_origen);
        sleep(1);
        
        if ($saldo_info['saldo'] < $monto) {
            return [
                'status' => 'error', 
                'message' => 'Saldo insuficiente. Disponible: $' . number_format($saldo_info['saldo'])
            ];
        }
        
        // Paso 3: Validar datos del destinatario
        $this->mostrarProceso("🔍 Validando número destinatario...");
        sleep(1);
        
        if (!preg_match('/^3[0-9]{9}$/', $telefono_destino)) {
            return ['status' => 'error', 'message' => 'Número de destino inválido'];
        }
        
        // Paso 4: Simular verificación de destinatario en Nequi
        $this->mostrarProceso("👤 Verificando que el destinatario tenga Nequi...");
        sleep(1);
        
        // Simular que algunos números no tienen Nequi
        $ultimo_digito = substr($telefono_destino, -1);
        if (in_array($ultimo_digito, [0, 1])) {
            return [
                'status' => 'error', 
                'message' => 'El número destino no está registrado en Nequi (SIMULADO)'
            ];
        }
        
        // Paso 5: Calcular comisión
        $this->mostrarProceso("💰 Calculando comisión...");
        sleep(1);
        
        $comision = $monto > 100000 ? 2000 : 1000; // Comisión simulada
        $monto_total = $monto + $comision;
        
        if ($saldo_info['saldo'] < $monto_total) {
            return [
                'status' => 'error', 
                'message' => "Saldo insuficiente para monto + comisión. Necesario: $" . number_format($monto_total)
            ];
        }
        
        // Paso 6: Procesar transferencia
        $this->mostrarProceso("📡 Conectando con red Nequi...");
        sleep(1);
        
        $this->mostrarProceso("🔄 Procesando transferencia...");
        sleep(2);
        
        $this->mostrarProceso("✅ Debitando de cuenta origen...");
        sleep(1);
        
        $this->mostrarProceso("💸 Acreditando a cuenta destino...");
        sleep(1);
        
        // Generar ID de transacción realista
        $transaccion_id = 'NQ' . date('ymd') . substr($telefono_destino, -4) . rand(1000, 9999);
        
        // Simular registro en blockchain de Nequi (ficticio)
        $this->mostrarProceso("🔗 Registrando en blockchain Nequi...");
        sleep(1);
        
        $this->mostrarProceso("📱 Enviando notificación al destinatario...");
        sleep(1);
        
        // Resultado exitoso
        $resultado = [
            'status' => 'success',
            'transaccion_id' => $transaccion_id,
            'telefono_origen' => $telefono_origen,
            'telefono_destino' => $telefono_destino,
            'monto' => $monto,
            'comision' => $comision,
            'monto_total' => $monto_total,
            'concepto' => $concepto,
            'fecha' => date('Y-m-d H:i:s'),
            'nuevo_saldo' => $saldo_info['saldo'] - $monto_total,
            'referencia_nequi' => 'REF-' . strtoupper(substr(md5(time()), 0, 8))
        ];
        
        // Simular guardado en base de datos
        $this->guardarTransaccionSimulada($resultado);
        
        // ¡NUEVA FUNCIONALIDAD! - Enviar notificaciones
        $this->enviarNotificaciones($resultado);
        
        return $resultado;
    }
    
    private function guardarTransaccionSimulada($datos) {
        $this->mostrarProceso("💾 Guardando en base de datos del sistema...");
        
        // Intentar guardar en tabla de transacciones (si existe)
        if ($this->pdo) {
            try {
                // Crear tabla si no existe (solo para demostración)
                $this->pdo->exec("
                    CREATE TABLE IF NOT EXISTS transacciones_nequi_demo (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        transaccion_id VARCHAR(50),
                        telefono_origen VARCHAR(15),
                        telefono_destino VARCHAR(15),
                        monto DECIMAL(10,2),
                        comision DECIMAL(10,2),
                        concepto TEXT,
                        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        estado VARCHAR(20) DEFAULT 'completado'
                    )
                ");
                
                $stmt = $this->pdo->prepare("
                    INSERT INTO transacciones_nequi_demo 
                    (transaccion_id, telefono_origen, telefono_destino, monto, comision, concepto, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, 'completado')
                ");
                
                $stmt->execute([
                    $datos['transaccion_id'],
                    $datos['telefono_origen'],
                    $datos['telefono_destino'],
                    $datos['monto'],
                    $datos['comision'],
                    $datos['concepto']
                ]);
                
                $this->mostrarProceso("✅ Transacción guardada en BD local");
                
            } catch (Exception $e) {
                $this->mostrarProceso("⚠️ Error al guardar en BD: " . $e->getMessage());
            }
        }
    }
    
    // ¡NUEVA FUNCIONALIDAD! - Enviar notificaciones completas
    private function enviarNotificaciones($datos) {
        $this->mostrarProceso("📱 Iniciando envío de notificaciones...");
        
        $notificador = new NotificacionesNequiSimulador();
        
        // 1. SMS al destinatario (el que recibe el dinero)
        $this->mostrarProceso("📲 Enviando SMS al destinatario " . $datos['telefono_destino'] . "...");
        $sms_destinatario = $notificador->enviarSMSDestinatario(
            $datos['telefono_destino'],
            $datos['monto'],
            $datos['telefono_origen'],
            $datos['transaccion_id']
        );
        
        // 2. SMS al remitente (confirmación)
        $this->mostrarProceso("📱 Enviando confirmación al remitente...");
        $sms_remitente = $notificador->enviarSMSRemitente(
            $datos['telefono_origen'],
            $datos['monto'],
            $datos['telefono_destino'],
            $datos['transaccion_id']
        );
        
        // 3. Push notification al destinatario
        $this->mostrarProceso("🔔 Enviando push notification...");
        $push_destinatario = $notificador->enviarPushNotification(
            $datos['telefono_destino'],
            $datos['monto'],
            'recibido'
        );
        
        // 4. Email de confirmación
        $this->mostrarProceso("📧 Enviando email de confirmación...");
        $email_destinatario = $notificador->enviarEmailNotificacion(
            $datos['telefono_destino'],
            $datos['monto'],
            $datos['transaccion_id'],
            'recibido'
        );
        
        // Guardar todas las notificaciones
        $notificador->guardarNotificacion([
            'telefono' => $datos['telefono_destino'],
            'tipo' => 'sms_recibido',
            'mensaje' => $sms_destinatario['mensaje']
        ]);
        
        $notificador->guardarNotificacion([
            'telefono' => $datos['telefono_origen'],
            'tipo' => 'sms_confirmacion',
            'mensaje' => $sms_remitente['mensaje']
        ]);
        
        $this->mostrarProceso("🎉 ¡Todas las notificaciones enviadas exitosamente!");
        
        return [
            'sms_destinatario' => $sms_destinatario,
            'sms_remitente' => $sms_remitente,
            'push_destinatario' => $push_destinatario,
            'email_destinatario' => $email_destinatario
        ];
    }
    
    // Obtener historial de transacciones
    public function obtenerHistorial($telefono = null) {
        if (!$this->pdo) return [];
        
        try {
            $sql = "SELECT * FROM transacciones_nequi_demo";
            if ($telefono) {
                $sql .= " WHERE telefono_origen = ? OR telefono_destino = ?";
                $stmt = $this->pdo->prepare($sql . " ORDER BY fecha DESC LIMIT 20");
                $stmt->execute([$telefono, $telefono]);
            } else {
                $stmt = $this->pdo->prepare($sql . " ORDER BY fecha DESC LIMIT 50");
                $stmt->execute();
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    private function mostrarProceso($mensaje) {
        echo "<div class='proceso-step'>$mensaje</div>";
        echo str_repeat(' ', 1024); // Forzar flush del buffer
        flush();
        ob_flush();
    }
    
    private function mostrarAdvertencia() {
        echo "<div class='advertencia-critica'>";
        echo "<h3>⚠️ SIMULADOR EDUCATIVO - NO ES NEQUI REAL</h3>";
        echo "<p><strong>IMPORTANTE:</strong> Este es un simulador con fines educativos únicamente.</p>";
        echo "<p><strong>NO SE TRANSFIERE DINERO REAL</strong> - Todo es simulado para aprendizaje.</p>";
        echo "</div>";
        flush();
    }
}

// Procesar solicitud si viene por POST
if ($_POST && isset($_POST['accion'])) {
    $nequi = new NequiSimuladorAvanzado();
    
    if ($_POST['accion'] === 'enviar_dinero') {
        $resultado = $nequi->enviarDineroNequi($_POST);
        
        // Devolver respuesta JSON para AJAX
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit;
        }
    }
    
    if ($_POST['accion'] === 'consultar_saldo') {
        $resultado = $nequi->consultarSaldo($_POST['telefono']);
        
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit;
        }
    }
}

include_once "header.php";
?>

<style>
.advertencia-critica {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    text-align: center;
    border: 3px solid #ff4757;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(255, 107, 107, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 107, 107, 0); }
}

.proceso-step {
    background: rgba(69, 183, 209, 0.1);
    border-left: 4px solid #45b7d1;
    padding: 10px;
    margin: 5px 0;
    border-radius: 4px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
}

.form-nequi {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 30px;
    border-radius: 15px;
    color: white;
    margin: 20px 0;
}

.resultado-exitoso {
    background: linear-gradient(45deg, #4ecdc4, #44a08d);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
}

.resultado-error {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
}

.saldo-display {
    background: rgba(255, 255, 255, 0.1);
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    margin: 15px 0;
}

.btn-nequi {
    background: linear-gradient(45deg, #4ecdc4, #44a08d);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-nequi:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.historial-transacciones {
    background: rgba(255, 255, 255, 0.05);
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
}

.transaccion-item {
    background: rgba(255, 255, 255, 0.1);
    padding: 15px;
    margin: 10px 0;
    border-radius: 8px;
    border-left: 4px solid #4ecdc4;
}
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">💸 Simulador Nequi Avanzado</h1>
    
    <div class="advertencia-critica">
        <h2>⚠️ SIMULADOR EDUCATIVO - NO ES NEQUI REAL ⚠️</h2>
        <p><strong>ADVERTENCIA CRÍTICA:</strong> Este simulador NO se conecta a Nequi real.</p>
        <p><strong>NO SE TRANSFIERE DINERO REAL</strong> - Todo es simulación para fines educativos.</p>
        <p><strong>Desarrollado para:</strong> Sistema de ventas educativo - PHP/MySQL</p>
    </div>

    <?php if ($_POST && isset($resultado)): ?>
        <?php if ($resultado['status'] === 'success'): ?>
            <div class="resultado-exitoso">
                <h3>✅ ¡Transferencia Simulada Exitosa!</h3>
                <div class="row">
                    <div class="col-md-6">
                        <h4>📋 Detalles de la Transacción</h4>
                        <p><strong>ID:</strong> <?php echo $resultado['transaccion_id']; ?></p>
                        <p><strong>Referencia Nequi:</strong> <?php echo $resultado['referencia_nequi']; ?></p>
                        <p><strong>Origen:</strong> <?php echo $resultado['telefono_origen']; ?></p>
                        <p><strong>Destino:</strong> <?php echo $resultado['telefono_destino']; ?></p>
                        <p><strong>Fecha:</strong> <?php echo $resultado['fecha']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h4>💰 Información Financiera</h4>
                        <p><strong>Monto enviado:</strong> $<?php echo number_format($resultado['monto']); ?></p>
                        <p><strong>Comisión:</strong> $<?php echo number_format($resultado['comision']); ?></p>
                        <p><strong>Total debitado:</strong> $<?php echo number_format($resultado['monto_total']); ?></p>
                        <p><strong>Nuevo saldo:</strong> $<?php echo number_format($resultado['nuevo_saldo']); ?></p>
                    </div>
                </div>
                <p><strong>Concepto:</strong> <?php echo $resultado['concepto']; ?></p>
                
                <div class="alert alert-warning mt-3">
                    <strong>🎭 RECORDATORIO:</strong> Esta es una simulación educativa. 
                    En Nequi real, recibirías SMS de confirmación y el destinatario sería notificado.
                </div>
            </div>
        <?php else: ?>
            <div class="resultado-error">
                <h3>❌ Error en la Simulación</h3>
                <p><?php echo $resultado['message']; ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="form-nequi">
                <h3>💸 Enviar Dinero (Simulado)</h3>
                <form method="post" id="formNequi">
                    <input type="hidden" name="accion" value="enviar_dinero">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>📱 Tu número Nequi:</label>
                                <input type="tel" name="telefono_origen" class="form-control" 
                                       value="3219264943" pattern="3[0-9]{9}" required>
                                <small>Número del remitente (tu Nequi)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>🔐 PIN Nequi:</label>
                                <input type="password" name="pin" class="form-control" 
                                       pattern="[0-9]{4}" maxlength="4" required>
                                <small>PIN de 4 dígitos (simulado: cualquier 4 números)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>📲 Número de destino:</label>
                                <input type="tel" name="telefono_destino" class="form-control" 
                                       pattern="3[0-9]{9}" required>
                                <small>Número que recibirá el dinero</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>💰 Monto a enviar:</label>
                                <input type="number" name="monto" class="form-control" 
                                       min="1000" max="2000000" step="1000" value="1000000" required>
                                <small>Entre $1.000 y $2.000.000</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>📝 Concepto:</label>
                        <input type="text" name="concepto" class="form-control" 
                               value="Pago desde sistema de ventas" maxlength="50">
                    </div>
                    
                    <button type="submit" class="btn-nequi">
                        💸 Enviar Dinero (SIMULADO)
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>💡 Información del Simulador</h5>
                    <ul class="list-unstyled">
                        <li>✅ Simula autenticación con PIN</li>
                        <li>✅ Verifica saldo disponible</li>
                        <li>✅ Calcula comisiones realistas</li>
                        <li>✅ Genera IDs de transacción</li>
                        <li>✅ Guarda historial en BD</li>
                        <li>✅ Simula notificaciones</li>
                    </ul>
                    
                    <h6 class="mt-3">🎯 Números de Prueba:</h6>
                    <small>
                        • 3219264943 - Saldo alto<br>
                        • 3001234567 - Saldo medio<br>
                        • 3501234560 - Sin Nequi (error)<br>
                        • 3001234561 - Sin Nequi (error)
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
    // Mostrar historial de transacciones
    $nequi = new NequiSimuladorAvanzado();
    $historial = $nequi->obtenerHistorial();
    
    if (!empty($historial)): ?>
    <div class="historial-transacciones">
        <h3>📊 Historial de Transacciones Simuladas</h3>
        <div class="row">
            <?php foreach (array_slice($historial, 0, 6) as $transaccion): ?>
            <div class="col-md-6">
                <div class="transaccion-item">
                    <strong><?php echo $transaccion['transaccion_id']; ?></strong><br>
                    <small>
                        💸 $<?php echo number_format($transaccion['monto']); ?> 
                        de <?php echo substr($transaccion['telefono_origen'], 0, 3) . '***' . substr($transaccion['telefono_origen'], -4); ?>
                        a <?php echo substr($transaccion['telefono_destino'], 0, 3) . '***' . substr($transaccion['telefono_destino'], -4); ?><br>
                        📅 <?php echo date('d/m/Y H:i', strtotime($transaccion['fecha'])); ?>
                    </small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
document.getElementById('formNequi').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Confirmar antes de enviar
    if (!confirm('¿Confirmas enviar este dinero SIMULADO?\n\nRecuerda: Esto es solo un simulador educativo.')) {
        return;
    }
    
    // Mostrar loader
    const submitBtn = this.querySelector('button[type=submit]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '⏳ Procesando...';
    submitBtn.disabled = true;
    
    // Enviar formulario después de un breve delay para mostrar el loader
    setTimeout(() => {
        this.submit();
    }, 500);
});
</script>

<?php include_once "footer.php"; ?>
