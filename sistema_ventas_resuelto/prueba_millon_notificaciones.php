<?php
// 🎯 PRUEBA ESPECÍFICA - ENVÍO DE 1 MILLÓN CON NOTIFICACIONES
require_once 'config.php';
require_once 'notificaciones_nequi.php';

header('Content-Type: text/html; charset=utf-8');

// Datos predefinidos para la prueba del millón
$datos_prueba = [
    'telefono_origen' => '3219264943',
    'telefono_destino' => '3219264943', // El mismo número para que "te llegue"
    'monto' => 1000000,
    'concepto' => 'Prueba de 1 millón - Sistema Educativo',
    'pin' => '1234'
];

$mostrar_simulacion = false;

// Si se envía el formulario, procesar
if ($_POST && isset($_POST['enviar_millon'])) {
    $mostrar_simulacion = true;
    ob_start();
}

include_once "header.php";
?>

<style>
/* Estilos heredados del archivo de notificaciones */
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

.boton-millon {
    background: linear-gradient(45deg, #4ecdc4, #44a08d);
    border: none;
    color: white;
    padding: 20px 40px;
    border-radius: 15px;
    font-size: 20px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.boton-millon:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    background: linear-gradient(45deg, #5ddeaf, #4ecdc4);
}

.proceso-container {
    background: rgba(0,0,0,0.7);
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    max-height: 400px;
    overflow-y: auto;
}
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">💸 Prueba Específica: Envío de 1 Millón</h1>
    
    <div class="advertencia-critica">
        <h2>⚠️ SIMULADOR EDUCATIVO - NO ES DINERO REAL ⚠️</h2>
        <p><strong>IMPORTANTE:</strong> Esta prueba simula el envío de $1,000,000 pesos colombianos</p>
        <p><strong>NO SE TRANSFIERE DINERO REAL</strong> - Solo notificaciones simuladas</p>
        <p><strong>El mensaje llegará a tu "teléfono simulado"</strong></p>
    </div>

    <?php if (!$mostrar_simulacion): ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>🎯 Configuración de la Prueba</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <input type="hidden" name="enviar_millon" value="1">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5>📱 Datos de la Transacción</h5>
                                <p><strong>Número origen:</strong> <?php echo $datos_prueba['telefono_origen']; ?></p>
                                <p><strong>Número destino:</strong> <?php echo $datos_prueba['telefono_destino']; ?></p>
                                <p><strong>Monto:</strong> $<?php echo number_format($datos_prueba['monto']); ?> COP</p>
                                <p><strong>Concepto:</strong> <?php echo $datos_prueba['concepto']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>📲 Notificaciones que se enviarán</h5>
                                <ul>
                                    <li>✅ SMS al destinatario</li>
                                    <li>✅ SMS de confirmación al remitente</li>
                                    <li>✅ Push notification</li>
                                    <li>✅ Email de confirmación</li>
                                    <li>✅ Simulación visual del teléfono</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="submit" class="boton-millon">
                                💰 ENVIAR 1 MILLÓN (SIMULADO) 💰
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>📋 Información de la Prueba</h5>
                    <p><strong>Objetivo:</strong> Demostrar las notificaciones completas cuando llega dinero.</p>
                    
                    <p><strong>Proceso simulado:</strong></p>
                    <ol>
                        <li>Autenticación con PIN</li>
                        <li>Validación de fondos</li>
                        <li>Procesamiento de transferencia</li>
                        <li>Envío de notificaciones</li>
                        <li>Simulación visual del teléfono</li>
                    </ol>
                    
                    <div class="alert alert-info">
                        <strong>💡 Tip:</strong> Verás en tiempo real cómo el teléfono recibe el mensaje de Nequi.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    
    <div class="row">
        <div class="col-md-8">
            <div class="proceso-container">
                <h3>🔄 Procesando Envío de 1 Millón...</h3>
                
                <?php
                // Crear el simulador y procesar la transacción
                class NequiSimuladorMillon extends NequiSimuladorAvanzado {
                    public function procesarMillonCompleto($datos) {
                        $this->mostrarAdvertencia();
                        
                        // Simular todo el proceso
                        $resultado = $this->enviarDineroNequi($datos);
                        
                        return $resultado;
                    }
                    
                    private function mostrarAdvertencia() {
                        echo "<div style='color: #ffc107; padding: 10px; border: 2px solid #ffc107; border-radius: 5px; margin: 10px 0;'>";
                        echo "<strong>⚠️ RECORDATORIO:</strong> Este es un simulador educativo. NO se transfiere dinero real.";
                        echo "</div>";
                        flush();
                    }
                }
                
                // Incluir la clase del simulador principal
                include_once 'nequi_simulador_realista.php';
                
                $simulador = new NequiSimuladorMillon();
                $resultado = $simulador->procesarMillonCompleto([
                    'telefono_origen' => $datos_prueba['telefono_origen'],
                    'telefono_destino' => $datos_prueba['telefono_destino'],
                    'monto' => $datos_prueba['monto'],
                    'concepto' => $datos_prueba['concepto'],
                    'pin' => $datos_prueba['pin'],
                    'accion' => 'enviar_dinero'
                ]);
                ?>
                
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Aquí aparecerá la simulación del teléfono -->
            <h4>📱 Tu Teléfono Recibiendo el Mensaje</h4>
            <div id="simulacion-telefono">
                <!-- La simulación del teléfono se carga automáticamente desde notificaciones_nequi.php -->
            </div>
        </div>
    </div>
    
    <?php if (isset($resultado) && $resultado['status'] === 'success'): ?>
    <div class="alert alert-success mt-4">
        <h4>🎉 ¡Simulación Completada Exitosamente!</h4>
        <div class="row">
            <div class="col-md-6">
                <p><strong>💰 Monto enviado:</strong> $<?php echo number_format($resultado['monto']); ?></p>
                <p><strong>📱 Al número:</strong> <?php echo $resultado['telefono_destino']; ?></p>
                <p><strong>🆔 ID Transacción:</strong> <?php echo $resultado['transaccion_id']; ?></p>
                <p><strong>📅 Fecha:</strong> <?php echo $resultado['fecha']; ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>💰 Comisión:</strong> $<?php echo number_format($resultado['comision']); ?></p>
                <p><strong>💳 Total debitado:</strong> $<?php echo number_format($resultado['monto_total']); ?></p>
                <p><strong>💼 Nuevo saldo:</strong> $<?php echo number_format($resultado['nuevo_saldo']); ?></p>
                <p><strong>📝 Concepto:</strong> <?php echo $resultado['concepto']; ?></p>
            </div>
        </div>
        
        <div class="alert alert-warning mt-3">
            <h5>📲 Notificaciones Enviadas:</h5>
            <ul class="mb-0">
                <li>✅ SMS enviado al destinatario (<?php echo $resultado['telefono_destino']; ?>)</li>
                <li>✅ SMS de confirmación al remitente</li>
                <li>✅ Push notification en la app Nequi</li>
                <li>✅ Email de confirmación</li>
                <li>✅ Registro en base de datos local</li>
            </ul>
        </div>
    </div>
    
    <div class="text-center">
        <a href="?" class="btn btn-primary">🔄 Hacer Otra Prueba</a>
        <a href="nequi_simulador_realista.php" class="btn btn-success">🚀 Simulador Completo</a>
        <a href="index.php" class="btn btn-secondary">🏠 Dashboard</a>
    </div>
    
    <?php endif; ?>
    
    <?php endif; ?>

</div>

<?php 
if ($mostrar_simulacion) {
    $contenido = ob_get_clean();
    echo $contenido;
}

include_once "footer.php"; 
?>

<script>
// Agregar efectos de sonido simulados (opcional)
<?php if ($mostrar_simulacion): ?>
setTimeout(function() {
    // Simular sonido de notificación
    if (typeof Audio !== 'undefined') {
        // En un sistema real, aquí se reproduciría un sonido de notificación
        console.log('🔔 Notificación de Nequi recibida (sonido simulado)');
    }
    
    // Hacer vibrar la página (si es compatible)
    if (navigator.vibrate) {
        navigator.vibrate([200, 100, 200]);
    }
    
    // Mostrar notificación del navegador (si es compatible)
    if ('Notification' in window) {
        Notification.requestPermission().then(function(permission) {
            if (permission === 'granted') {
                new Notification('💜 Nequi Simulado', {
                    body: '¡Has recibido $1,000,000! (Simulación educativa)',
                    icon: 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y=".9em" font-size="90">💜</text></svg>'
                });
            }
        });
    }
}, 3000);
<?php endif; ?>
</script>
