<?php
// 🎓 SIMULADOR AVANZADO DE API NEQUI - SOLO EDUCATIVO
// ⚠️ ESTE CÓDIGO ES COMPLETAMENTE FICTICIO Y NO SE CONECTA A NEQUI REAL
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

// Clase simuladora de conexión Nequi (FICTICIA)
class NequiAPISimulator {
    private $api_key_ficticia = "nequi_test_key_12345_FICTICIA";
    private $base_url_ficticia = "https://api-sandbox.nequi.com.co/ficticia";
    private $usuario_ficticio = "usuario_demo";
    
    public function __construct() {
        // Simulación de inicialización
        $this->log("🔧 Inicializando cliente Nequi ficticio...");
    }
    
    public function autenticar($usuario, $pin) {
        // Simulación de autenticación
        $this->log("🔐 Simulando autenticación para usuario: $usuario");
        sleep(1); // Simular delay de red
        
        if ($usuario === "demo" && $pin === "1234") {
            $this->log("✅ Autenticación ficticia exitosa");
            return [
                'status' => 'success',
                'token' => 'token_ficticio_' . time(),
                'expires_in' => 3600
            ];
        } else {
            $this->log("❌ Credenciales ficticias incorrectas");
            return ['status' => 'error', 'message' => 'Credenciales inválidas'];
        }
    }
    
    public function verificarSaldo() {
        $this->log("💰 Consultando saldo ficticio...");
        sleep(1);
        
        return [
            'status' => 'success',
            'saldo_disponible' => rand(500000, 5000000),
            'moneda' => 'COP'
        ];
    }
    
    public function enviarDinero($destino, $monto, $mensaje = "") {
        $this->log("💸 Iniciando transferencia ficticia...");
        $this->log("📱 Destino: $destino");
        $this->log("💰 Monto: $" . number_format($monto) . " COP");
        
        // Simulación de validaciones
        if (!$this->validarNumero($destino)) {
            return ['status' => 'error', 'message' => 'Número de teléfono inválido'];
        }
        
        if ($monto < 1000) {
            return ['status' => 'error', 'message' => 'Monto mínimo: $1,000 COP'];
        }
        
        if ($monto > 2000000) {
            return ['status' => 'error', 'message' => 'Monto máximo excedido'];
        }
        
        // Simulación de proceso de envío
        $pasos = [
            "🔍 Validando destinatario...",
            "💳 Verificando saldo...",
            "🛡️ Aplicando medidas de seguridad...",
            "📡 Conectando con red Nequi...",
            "💸 Procesando transferencia...",
            "📝 Generando comprobante...",
            "✅ Transacción completada"
        ];
        
        foreach ($pasos as $paso) {
            $this->log($paso);
            usleep(500000); // 0.5 segundos
        }
        
        // Resultado ficticio exitoso
        return [
            'status' => 'success',
            'transaccion_id' => 'NEQ' . date('Ymd') . rand(100000, 999999),
            'monto' => $monto,
            'destino' => $destino,
            'fecha' => date('Y-m-d H:i:s'),
            'mensaje' => $mensaje,
            'comision' => 0, // Nequi no cobra comisión entre usuarios
            'saldo_restante' => rand(100000, 1000000)
        ];
    }
    
    private function validarNumero($numero) {
        // Simulación de validación de número colombiano
        return preg_match('/^3[0-9]{9}$/', $numero);
    }
    
    private function log($mensaje) {
        echo "<div style='color: #45b7d1; margin: 5px 0;'>$mensaje</div>";
        flush();
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔗 Simulador de Conexión Nequi - EDUCATIVO</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(0,0,0,0.9);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
        }
        .warning {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(255,107,107,0.3);
        }
        .success {
            color: #4ecdc4;
            font-weight: bold;
        }
        .error {
            color: #ff6b6b;
            font-weight: bold;
        }
        .info {
            color: #45b7d1;
        }
        .money {
            color: #f39c12;
            font-size: 1.3em;
            font-weight: bold;
        }
        .api-box {
            background: linear-gradient(145deg, #2c3e50, #34495e);
            padding: 20px;
            border-radius: 10px;
            margin: 15px 0;
            border-left: 5px solid #3498db;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .log-box {
            background: #1a1a1a;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            border: 1px solid #333;
            font-family: 'Courier New', monospace;
            max-height: 300px;
            overflow-y: auto;
        }
        .form-group {
            margin: 15px 0;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: rgba(255,255,255,0.1);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .btn {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            margin: 10px 5px;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52,152,219,0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔗 Simulador Avanzado de API Nequi</h1>
        
        <div class="warning">
            ⚠️ ADVERTENCIA: ESTO ES UN SIMULADOR EDUCATIVO<br>
            NO SE CONECTA A NEQUI REAL - NO TRANSFIERE DINERO REAL
        </div>

        <?php
        
        if ($_POST) {
            echo "<h2>📡 Iniciando Simulación de Conexión...</h2>";
            echo "<div class='log-box'>";
            
            // Crear instancia del simulador
            $nequi = new NequiAPISimulator();
            
            // Procesar formulario
            $usuario = $_POST['usuario'] ?? '';
            $pin = $_POST['pin'] ?? '';
            $destino = $_POST['destino'] ?? '';
            $monto = (int)($_POST['monto'] ?? 0);
            $mensaje = $_POST['mensaje'] ?? '';
            
            // Paso 1: Autenticación
            $auth = $nequi->autenticar($usuario, $pin);
            
            if ($auth['status'] === 'success') {
                // Paso 2: Verificar saldo
                $saldo = $nequi->verificarSaldo();
                
                if ($saldo['saldo_disponible'] >= $monto) {
                    // Paso 3: Enviar dinero
                    $resultado = $nequi->enviarDinero($destino, $monto, $mensaje);
                    
                    echo "</div>";
                    
                    if ($resultado['status'] === 'success') {
                        echo "<div class='api-box'>";
                        echo "<h3 class='success'>✅ TRANSACCIÓN FICTICIA EXITOSA</h3>";
                        echo "<strong>🆔 ID:</strong> <span class='info'>{$resultado['transaccion_id']}</span><br>";
                        echo "<strong>💰 Monto:</strong> <span class='money'>$" . number_format($resultado['monto']) . " COP</span><br>";
                        echo "<strong>📱 Destino:</strong> <span class='info'>{$resultado['destino']}</span><br>";
                        echo "<strong>📅 Fecha:</strong> <span class='info'>{$resultado['fecha']}</span><br>";
                        echo "<strong>💳 Saldo Restante:</strong> <span class='money'>$" . number_format($resultado['saldo_restante']) . " COP</span><br>";
                        if ($resultado['mensaje']) {
                            echo "<strong>💬 Mensaje:</strong> <span class='info'>{$resultado['mensaje']}</span>";
                        }
                        echo "</div>";
                    } else {
                        echo "<div class='api-box'>";
                        echo "<h3 class='error'>❌ ERROR EN TRANSACCIÓN FICTICIA</h3>";
                        echo "<p class='error'>{$resultado['message']}</p>";
                        echo "</div>";
                    }
                } else {
                    echo "</div>";
                    echo "<div class='api-box'>";
                    echo "<h3 class='error'>❌ SALDO INSUFICIENTE (FICTICIO)</h3>";
                    echo "<p>Saldo disponible: $" . number_format($saldo['saldo_disponible']) . " COP</p>";
                    echo "<p>Monto solicitado: $" . number_format($monto) . " COP</p>";
                    echo "</div>";
                }
            } else {
                echo "</div>";
                echo "<div class='api-box'>";
                echo "<h3 class='error'>❌ ERROR DE AUTENTICACIÓN FICTICIA</h3>";
                echo "<p class='error'>{$auth['message']}</p>";
                echo "</div>";
            }
        } else {
            ?>
            
            <h2>📝 Formulario de Prueba (Ficticio)</h2>
            <form method="POST" action="">
                <div class="api-box">
                    <h3>🔐 Credenciales de Prueba</h3>
                    <div class="form-group">
                        <label>Usuario (usar: "demo"):</label>
                        <input type="text" name="usuario" value="demo" required>
                    </div>
                    <div class="form-group">
                        <label>PIN (usar: "1234"):</label>
                        <input type="password" name="pin" value="1234" required>
                    </div>
                </div>
                
                <div class="api-box">
                    <h3>💸 Datos de Transferencia</h3>
                    <div class="form-group">
                        <label>Número de destino:</label>
                        <input type="text" name="destino" value="3001234567" placeholder="3001234567" required>
                    </div>
                    <div class="form-group">
                        <label>Monto (COP):</label>
                        <input type="number" name="monto" value="50000" min="1000" max="2000000" required>
                    </div>
                    <div class="form-group">
                        <label>Mensaje (opcional):</label>
                        <input type="text" name="mensaje" placeholder="Para ti!" maxlength="50">
                    </div>
                </div>
                
                <button type="submit" class="btn">🚀 Simular Transferencia</button>
            </form>
            
            <?php
        }
        ?>
        
        <div class="api-box" style="margin-top: 30px;">
            <h3>📋 Información del Simulador</h3>
            <p><strong>🎯 Propósito:</strong> Educativo únicamente</p>
            <p><strong>🔒 Seguridad:</strong> No maneja datos reales</p>
            <p><strong>💻 Tecnología:</strong> PHP + Simulación de APIs</p>
            <p><strong>⚠️ Importante:</strong> Para APIs reales, contacta a Bancolombia oficialmente</p>
        </div>
        
        <div class="warning" style="margin-top: 20px;">
            📚 RECUERDA: Este es solo un ejemplo educativo de cómo funcionarían las APIs de Nequi.
            Para desarrollo real, necesitas credenciales oficiales de Bancolombia.
        </div>
    </div>
</body>
</html>
