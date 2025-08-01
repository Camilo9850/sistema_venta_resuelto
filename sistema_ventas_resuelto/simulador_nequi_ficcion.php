<?php
// 🎭 SCRIPT DE FICCIÓN - SIMULADOR DE TRANSACCIONES NEQUI
// ⚠️ SOLO PARA PROPÓSITOS EDUCATIVOS - NO ES REAL
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎭 Simulador Nequi - FICCIÓN</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(0,0,0,0.8);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .warning {
            background: #ff6b6b;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .success {
            color: #4ecdc4;
            font-weight: bold;
        }
        .error {
            color: #ff6b6b;
        }
        .info {
            color: #45b7d1;
        }
        .money {
            color: #f39c12;
            font-size: 1.2em;
            font-weight: bold;
        }
        .loading {
            display: inline-block;
            animation: pulse 1.5s ease-in-out infinite alternate;
        }
        @keyframes pulse {
            from { opacity: 0.6; }
            to { opacity: 1.0; }
        }
        .fake-api {
            background: #2c3e50;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            border-left: 4px solid #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💸 Simulador de Transacciones Nequi - FICCIÓN</h1>
        
        <div class="warning">
            ⚠️ ESTO ES COMPLETAMENTE FICTICIO - NO ENVÍA DINERO REAL ⚠️
        </div>

        <?php
        // Simulación de parámetros
        $monto_ficticio = 1000000; // Un millón
        $numero_destino = "3001234567"; // Número ficticio
        $nombre_destinatario = "Juan Pérez"; // Nombre ficticio
        
        echo "<h2>🎯 Datos de la Transacción Ficticia:</h2>";
        echo "<div class='fake-api'>";
        echo "<strong>💰 Monto:</strong> <span class='money'>$" . number_format($monto_ficticio) . " COP</span><br>";
        echo "<strong>📱 Número destino:</strong> <span class='info'>$numero_destino</span><br>";
        echo "<strong>👤 Destinatario:</strong> <span class='info'>$nombre_destinatario</span><br>";
        echo "<strong>🕐 Fecha:</strong> <span class='info'>" . date('Y-m-d H:i:s') . "</span>";
        echo "</div>";

        echo "<h2>🔄 Simulando proceso...</h2>";
        
        // Simulación de pasos del proceso
        $pasos = [
            "🔐 Validando credenciales...",
            "💳 Verificando saldo disponible...",
            "🛡️ Aplicando medidas de seguridad...",
            "📡 Conectando con servidor Nequi...",
            "💸 Procesando transferencia...",
            "✅ Enviando confirmación..."
        ];

        foreach ($pasos as $index => $paso) {
            echo "<div class='loading'>$paso</div><br>";
            // Simular delay para efecto visual
            if (isset($_GET['demo'])) {
                sleep(1); // Solo si se pasa ?demo=1
            }
        }

        echo "<hr>";
        
        // Resultado ficticio
        $transaccion_id = "NEQ" . rand(100000, 999999);
        
        echo "<h2>📊 Resultado de la Simulación:</h2>";
        echo "<div class='fake-api'>";
        echo "<div class='success'>✅ TRANSACCIÓN FICTICIA COMPLETADA</div><br>";
        echo "<strong>🆔 ID Transacción:</strong> <span class='info'>$transaccion_id</span><br>";
        echo "<strong>💰 Monto enviado:</strong> <span class='money'>$" . number_format($monto_ficticio) . " COP</span><br>";
        echo "<strong>📱 A:</strong> <span class='info'>$numero_destino ($nombre_destinatario)</span><br>";
        echo "<strong>📈 Estado:</strong> <span class='success'>EXITOSA (FICTICIA)</span><br>";
        echo "<strong>💳 Saldo restante:</strong> <span class='money'>$" . number_format(rand(500000, 2000000)) . " COP</span>";
        echo "</div>";

        echo "<h2>⚠️ Disclaimer Importante:</h2>";
        echo "<div class='warning'>";
        echo "🚫 Este script es completamente FICTICIO<br>";
        echo "💡 Creado solo para fines educativos<br>";
        echo "🔒 NO se conecta a APIs reales de Nequi<br>";
        echo "💸 NO transfiere dinero real<br>";
        echo "⚖️ Para uso real, contacta a Nequi oficialmente";
        echo "</div>";

        // Código simulado de API
        echo "<h2>📝 Código de Ejemplo (Ficticio):</h2>";
        echo "<div class='fake-api'>";
        echo "<pre>";
        echo "// EJEMPLO FICTICIO - NO FUNCIONAL\n";
        echo "class NequiSimulator {\n";
        echo "    public function sendMoney(\$amount, \$phone) {\n";
        echo "        // Esto es solo un ejemplo educativo\n";
        echo "        return [\n";
        echo "            'status' => 'success_ficticio',\n";
        echo "            'transaction_id' => 'NEQ' . rand(100000, 999999),\n";
        echo "            'amount' => \$amount,\n";
        echo "            'destination' => \$phone\n";
        echo "        ];\n";
        echo "    }\n";
        echo "}\n";
        echo "</pre>";
        echo "</div>";

        ?>

        <div style="text-align: center; margin-top: 30px;">
            <h3>🎓 Para aprender sobre APIs reales:</h3>
            <p>📖 Revisa la documentación oficial de Nequi para desarrolladores</p>
            <p>🏦 Contacta al equipo de Nequi para acceso a APIs sandbox</p>
            <p>🔐 Siempre usa entornos de prueba para desarrollo</p>
        </div>
    </div>
</body>
</html>
