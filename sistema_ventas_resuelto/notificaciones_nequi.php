<?php
// 📱 SIMULADOR DE NOTIFICACIONES SMS/PUSH - NEQUI EDUCATIVO
require_once 'config.php';

class NotificacionesNequiSimulador {
    private $pdo;
    
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
    
    // Simular envío de SMS al destinatario
    public function enviarSMSDestinatario($telefono_destino, $monto, $remitente, $transaccion_id) {
        $this->mostrarProceso("📱 Preparando SMS para destinatario...");
        sleep(1);
        
        // Simular diferentes operadores
        $operador = $this->detectarOperador($telefono_destino);
        
        $this->mostrarProceso("📡 Conectando con $operador...");
        sleep(1);
        
        // Crear mensaje realista
        $mensaje = $this->generarMensajeDestinatario($monto, $remitente, $transaccion_id);
        
        $this->mostrarProceso("💬 Enviando SMS al $telefono_destino...");
        sleep(2);
        
        // Simular respuesta del operador
        $this->simularRespuestaOperador($telefono_destino, $mensaje);
        
        return [
            'status' => 'enviado',
            'telefono' => $telefono_destino,
            'operador' => $operador,
            'mensaje' => $mensaje,
            'fecha_envio' => date('Y-m-d H:i:s'),
            'id_sms' => 'SMS' . rand(100000, 999999)
        ];
    }
    
    // Simular envío de SMS al remitente
    public function enviarSMSRemitente($telefono_origen, $monto, $destinatario, $transaccion_id) {
        $this->mostrarProceso("📱 Enviando confirmación al remitente...");
        sleep(1);
        
        $operador = $this->detectarOperador($telefono_origen);
        $mensaje = $this->generarMensajeRemitente($monto, $destinatario, $transaccion_id);
        
        $this->mostrarProceso("💬 SMS confirmación enviado a $telefono_origen...");
        sleep(1);
        
        return [
            'status' => 'enviado',
            'telefono' => $telefono_origen,
            'operador' => $operador,
            'mensaje' => $mensaje,
            'fecha_envio' => date('Y-m-d H:i:s'),
            'id_sms' => 'SMS' . rand(100000, 999999)
        ];
    }
    
    // Simular notificación push en la app
    public function enviarPushNotification($telefono, $monto, $tipo = 'recibido') {
        $this->mostrarProceso("📲 Enviando notificación push...");
        sleep(1);
        
        $titulo = $tipo === 'recibido' ? "💰 ¡Dinero recibido!" : "✅ Envío exitoso";
        $mensaje = $tipo === 'recibido' 
            ? "Has recibido $" . number_format($monto) . " en tu Nequi"
            : "Enviaste $" . number_format($monto) . " exitosamente";
            
        $this->mostrarProceso("🔔 Push notification enviada a $telefono");
        
        return [
            'status' => 'enviado',
            'telefono' => $telefono,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'fecha_envio' => date('Y-m-d H:i:s'),
            'id_push' => 'PUSH' . rand(100000, 999999)
        ];
    }
    
    // Simular notificación por email
    public function enviarEmailNotificacion($telefono, $monto, $transaccion_id, $tipo = 'recibido') {
        $this->mostrarProceso("📧 Preparando notificación por email...");
        sleep(1);
        
        // Simular email asociado al teléfono
        $email = $this->generarEmailSimulado($telefono);
        
        $this->mostrarProceso("📮 Enviando email a $email...");
        sleep(1);
        
        $asunto = $tipo === 'recibido' 
            ? "Nequi - Dinero recibido: $" . number_format($monto)
            : "Nequi - Envío exitoso: $" . number_format($monto);
            
        return [
            'status' => 'enviado',
            'email' => $email,
            'asunto' => $asunto,
            'transaccion_id' => $transaccion_id,
            'fecha_envio' => date('Y-m-d H:i:s'),
            'id_email' => 'EMAIL' . rand(100000, 999999)
        ];
    }
    
    private function detectarOperador($telefono) {
        $prefijos = [
            '300' => 'Tigo',
            '301' => 'Claro',
            '302' => 'Movistar',
            '303' => 'Tigo',
            '310' => 'Movistar',
            '311' => 'Movistar',
            '312' => 'Movistar',
            '313' => 'Movistar',
            '314' => 'Movistar',
            '315' => 'Movistar',
            '316' => 'Movistar',
            '317' => 'Movistar',
            '318' => 'Movistar',
            '319' => 'Movistar',
            '320' => 'Tigo',
            '321' => 'Tigo',
            '322' => 'Movistar',
            '323' => 'Movistar',
            '324' => 'ETB',
            '325' => 'ETB',
            '350' => 'Avantel',
            '351' => 'Avantel'
        ];
        
        $prefijo = substr($telefono, 0, 3);
        return $prefijos[$prefijo] ?? 'Claro';
    }
    
    private function generarMensajeDestinatario($monto, $remitente, $transaccion_id) {
        $remitente_oculto = substr($remitente, 0, 3) . '***' . substr($remitente, -4);
        
        return "🎉 NEQUI: ¡Recibiste $" . number_format($monto) . "! " .
               "De: $remitente_oculto. " .
               "ID: $transaccion_id. " .
               "Disponible ahora en tu Nequi. " .
               "¡Disfrútalo! 💰";
    }
    
    private function generarMensajeRemitente($monto, $destinatario, $transaccion_id) {
        $destinatario_oculto = substr($destinatario, 0, 3) . '***' . substr($destinatario, -4);
        
        return "✅ NEQUI: Enviaste $" . number_format($monto) . " " .
               "a $destinatario_oculto exitosamente. " .
               "ID: $transaccion_id. " .
               "¡Transacción completada! 👍";
    }
    
    private function generarEmailSimulado($telefono) {
        $dominios = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com'];
        $usuario = 'usuario' . substr($telefono, -4);
        $dominio = $dominios[array_rand($dominios)];
        return "$usuario@$dominio";
    }
    
    private function simularRespuestaOperador($telefono, $mensaje) {
        $this->mostrarProceso("📱 SMS entregado exitosamente");
        $this->mostrarProceso("🔔 El usuario será notificado inmediatamente");
        
        // Simular la pantalla del teléfono recibiendo el mensaje
        $this->mostrarNotificacionTelefono($telefono, $mensaje);
    }
    
    private function mostrarNotificacionTelefono($telefono, $mensaje) {
        echo "<div class='telefono-simulacion'>";
        echo "<div class='pantalla-telefono'>";
        echo "<div class='status-bar'>📶 " . date('H:i') . " 🔋</div>";
        echo "<div class='notificacion-sms'>";
        echo "<div class='icono-nequi'>💜</div>";
        echo "<div class='contenido-sms'>";
        echo "<strong>Nequi</strong> <span class='tiempo'>ahora</span><br>";
        echo "<span class='mensaje-preview'>" . substr($mensaje, 0, 50) . "...</span>";
        echo "</div>";
        echo "</div>";
        echo "<div class='mensaje-completo'>";
        echo "<strong>💜 Nequi</strong><br>";
        echo "<span class='mensaje-texto'>$mensaje</span><br>";
        echo "<small class='timestamp'>" . date('H:i') . "</small>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    
    private function mostrarProceso($mensaje) {
        echo "<div class='proceso-notificacion'>$mensaje</div>";
        echo str_repeat(' ', 1024);
        flush();
        ob_flush();
    }
    
    // Guardar notificaciones en base de datos
    public function guardarNotificacion($datos) {
        if (!$this->pdo) return false;
        
        try {
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS notificaciones_nequi_demo (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    telefono VARCHAR(15),
                    tipo VARCHAR(20),
                    mensaje TEXT,
                    estado VARCHAR(20) DEFAULT 'enviado',
                    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            $stmt = $this->pdo->prepare("
                INSERT INTO notificaciones_nequi_demo (telefono, tipo, mensaje, estado) 
                VALUES (?, ?, ?, 'enviado')
            ");
            
            $stmt->execute([
                $datos['telefono'],
                $datos['tipo'] ?? 'sms',
                $datos['mensaje']
            ]);
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }
}

// CSS para la simulación del teléfono
?>
<style>
.telefono-simulacion {
    background: #000;
    border-radius: 25px;
    padding: 20px;
    margin: 20px auto;
    max-width: 350px;
    border: 8px solid #333;
    box-shadow: 0 0 30px rgba(0,0,0,0.5);
    animation: phoneGlow 2s infinite alternate;
}

@keyframes phoneGlow {
    0% { box-shadow: 0 0 30px rgba(76, 205, 196, 0.3); }
    100% { box-shadow: 0 0 50px rgba(76, 205, 196, 0.6); }
}

.pantalla-telefono {
    background: linear-gradient(180deg, #1a1a1a 0%, #000 100%);
    border-radius: 15px;
    padding: 10px;
    color: white;
    min-height: 400px;
}

.status-bar {
    text-align: center;
    font-size: 14px;
    padding: 5px 0;
    border-bottom: 1px solid #333;
    margin-bottom: 10px;
}

.notificacion-sms {
    background: rgba(76, 205, 196, 0.1);
    border: 1px solid #4ecdc4;
    border-radius: 10px;
    padding: 15px;
    margin: 10px 0;
    display: flex;
    align-items: center;
    animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.icono-nequi {
    font-size: 24px;
    margin-right: 10px;
}

.contenido-sms {
    flex: 1;
}

.tiempo {
    float: right;
    font-size: 12px;
    color: #999;
}

.mensaje-preview {
    color: #ccc;
    font-size: 14px;
}

.mensaje-completo {
    background: #1a1a1a;
    border-radius: 10px;
    padding: 15px;
    margin: 15px 0;
    border-left: 4px solid #4ecdc4;
}

.mensaje-texto {
    color: white;
    line-height: 1.4;
    display: block;
    margin: 10px 0;
}

.timestamp {
    color: #999;
    font-size: 11px;
}

.proceso-notificacion {
    background: rgba(76, 205, 196, 0.1);
    border-left: 4px solid #4ecdc4;
    padding: 10px;
    margin: 5px 0;
    border-radius: 4px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
}
</style>
