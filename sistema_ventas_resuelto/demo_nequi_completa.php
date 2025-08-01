<?php
// 🎯 DEMOSTRACIÓN COMPLETA DEL SIMULADOR NEQUI REALISTA
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎯 Demo Completa - Simulador Nequi Realista</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .container { max-width: 1200px; margin: 0 auto; background: rgba(0,0,0,0.8); padding: 30px; border-radius: 15px; }
        .advertencia-mega { 
            background: linear-gradient(45deg, #ff6b6b, #ee5a24); 
            padding: 25px; border-radius: 15px; margin: 20px 0; text-align: center; 
            border: 4px solid #ff4757; animation: pulse 2s infinite;
            box-shadow: 0 0 20px rgba(255, 107, 107, 0.5);
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 20px rgba(255, 107, 107, 0.7); }
            50% { box-shadow: 0 0 30px rgba(255, 107, 107, 0.9); }
            100% { box-shadow: 0 0 20px rgba(255, 107, 107, 0.7); }
        }
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0; }
        .feature-card { background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2); }
        .btn { display: inline-block; padding: 12px 24px; margin: 10px 5px; background: linear-gradient(45deg, #667eea, #764ba2); color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }
        .btn:hover { transform: translateY(-2px); color: white; text-decoration: none; }
        .btn-nequi { background: linear-gradient(45deg, #4ecdc4, #44a08d); }
        .proceso-demo { background: rgba(76, 205, 196, 0.1); border-left: 4px solid #4ecdc4; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .resultado-demo { background: rgba(40, 167, 69, 0.2); border: 2px solid #28a745; padding: 20px; border-radius: 10px; margin: 15px 0; }
        .comparacion { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .nequi-real, .nequi-simulado { padding: 20px; border-radius: 10px; }
        .nequi-real { background: rgba(76, 205, 196, 0.2); border: 2px solid #4ecdc4; }
        .nequi-simulado { background: rgba(255, 193, 7, 0.2); border: 2px solid #ffc107; }
        .transaccion-ejemplo { background: rgba(255,255,255,0.05); padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #4ecdc4; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 Demostración Completa - Simulador Nequi Realista</h1>
        
        <div class="advertencia-mega">
            <h2>⚠️ SIMULADOR EDUCATIVO ULTRA-REALISTA ⚠️</h2>
            <h3>🚫 NO ES NEQUI REAL - NO TRANSFIERE DINERO REAL 🚫</h3>
            <p><strong>PROPÓSITO:</strong> Simulación educativa para sistemas de ventas</p>
            <p><strong>TECNOLOGÍA:</strong> PHP + MySQL + JavaScript</p>
            <p><strong>ADVERTENCIA:</strong> Todo el dinero y transacciones son ficticios</p>
        </div>
        
        <div class="feature-grid">
            <div class="feature-card">
                <h3>🔐 Autenticación Realista</h3>
                <ul>
                    <li>✅ Validación de PIN de 4 dígitos</li>
                    <li>✅ Verificación de cuenta activa</li>
                    <li>✅ Generación de token de sesión</li>
                    <li>✅ Simulación de respuesta de API</li>
                </ul>
                <div class="proceso-demo">
                    <strong>Proceso simulado:</strong><br>
                    🔐 Autenticando con Nequi...<br>
                    📱 Verificando cuenta activa...<br>
                    ✅ Sesión establecida
                </div>
            </div>
            
            <div class="feature-card">
                <h3>💰 Consulta de Saldo</h3>
                <ul>
                    <li>✅ Saldos diferentes por número</li>
                    <li>✅ Formato de moneda colombiana</li>
                    <li>✅ Timestamp de consulta</li>
                    <li>✅ Validación de fondos suficientes</li>
                </ul>
                <div class="proceso-demo">
                    <strong>Saldos simulados:</strong><br>
                    • 3219264943: $2.000.000<br>
                    • 3001234567: $1.200.000<br>
                    • 3501234568: $650.000
                </div>
            </div>
            
            <div class="feature-card">
                <h3>📡 Transferencia Completa</h3>
                <ul>
                    <li>✅ Validación de destinatario</li>
                    <li>✅ Cálculo de comisiones</li>
                    <li>✅ Proceso paso a paso</li>
                    <li>✅ ID de transacción único</li>
                </ul>
                <div class="proceso-demo">
                    <strong>Pasos simulados:</strong><br>
                    🔍 Validando destinatario...<br>
                    💰 Calculando comisión...<br>
                    📡 Conectando con red Nequi...<br>
                    💸 Procesando transferencia...
                </div>
            </div>
            
            <div class="feature-card">
                <h3>💾 Base de Datos</h3>
                <ul>
                    <li>✅ Guardado en MySQL</li>
                    <li>✅ Historial de transacciones</li>
                    <li>✅ Consultas por usuario</li>
                    <li>✅ Estados de transacción</li>
                </ul>
                <div class="proceso-demo">
                    <strong>Tabla creada:</strong><br>
                    transacciones_nequi_demo<br>
                    • ID, fecha, montos<br>
                    • Teléfonos origen/destino<br>
                    • Estados y referencias
                </div>
            </div>
        </div>
        
        <h2>🆚 Comparación: Nequi Real vs Simulador</h2>
        <div class="comparacion">
            <div class="nequi-real">
                <h3>💳 Nequi Real</h3>
                <ul>
                    <li>🏦 Conecta con Bancolombia</li>
                    <li>💸 Transfiere dinero real</li>
                    <li>📱 App móvil oficial</li>
                    <li>🔐 Biometría y PIN real</li>
                    <li>💰 Saldos reales de cuenta</li>
                    <li>📧 Notificaciones SMS/email</li>
                    <li>🧾 Extractos bancarios</li>
                    <li>⚖️ Regulado por SFC</li>
                </ul>
            </div>
            
            <div class="nequi-simulado">
                <h3>🎭 Nuestro Simulador</h3>
                <ul>
                    <li>💻 Sistema PHP local</li>
                    <li>🎮 Dinero completamente ficticio</li>
                    <li>🌐 Interfaz web simulada</li>
                    <li>🔢 PIN cualquier 4 números</li>
                    <li>📊 Saldos predefinidos</li>
                    <li>💻 Notificaciones en pantalla</li>
                    <li>🗄️ Base de datos local</li>
                    <li>📚 Solo para aprendizaje</li>
                </ul>
            </div>
        </div>
        
        <h2>🧪 Ejemplos de Transacciones Simuladas</h2>
        
        <?php
        // Simular algunas transacciones de ejemplo
        $transacciones_ejemplo = [
            [
                'id' => 'NQ2507193219001',
                'origen' => '3219264943',
                'destino' => '3001234567',
                'monto' => 1000000,
                'comision' => 2000,
                'concepto' => 'Pago venta sistema educativo',
                'fecha' => date('Y-m-d H:i:s'),
                'estado' => 'completado'
            ],
            [
                'id' => 'NQ2507195678002',
                'origen' => '3001234567',
                'destino' => '3501234568',
                'monto' => 500000,
                'comision' => 1000,
                'concepto' => 'Transferencia demo',
                'fecha' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'estado' => 'completado'
            ],
            [
                'id' => 'NQ2507190001003',
                'origen' => '3219264943',
                'destino' => '3001234560',
                'monto' => 750000,
                'comision' => 0,
                'concepto' => 'Pago simulado fallido',
                'fecha' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'estado' => 'error_destinatario_sin_nequi'
            ]
        ];
        
        foreach ($transacciones_ejemplo as $trans):
        ?>
        <div class="transaccion-ejemplo">
            <div class="row" style="display: flex; justify-content: space-between; align-items: center;">
                <div style="flex: 1;">
                    <strong><?php echo $trans['id']; ?></strong><br>
                    <small>
                        💸 $<?php echo number_format($trans['monto']); ?> 
                        de <?php echo substr($trans['origen'], 0, 3) . '***' . substr($trans['origen'], -4); ?>
                        a <?php echo substr($trans['destino'], 0, 3) . '***' . substr($trans['destino'], -4); ?>
                    </small>
                </div>
                <div style="flex: 1;">
                    <small>
                        📅 <?php echo date('d/m/Y H:i', strtotime($trans['fecha'])); ?><br>
                        💰 Comisión: $<?php echo number_format($trans['comision']); ?>
                    </small>
                </div>
                <div style="flex: 1;">
                    <?php if ($trans['estado'] === 'completado'): ?>
                        <span style="color: #4ecdc4;">✅ Exitoso</span>
                    <?php else: ?>
                        <span style="color: #ff6b6b;">❌ Error</span>
                    <?php endif; ?>
                    <br><small><?php echo $trans['concepto']; ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <h2>🎯 Casos de Prueba Recomendados</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <h4>✅ Transacción Exitosa</h4>
                <p><strong>Origen:</strong> 3219264943</p>
                <p><strong>Destino:</strong> 3001234567</p>
                <p><strong>Monto:</strong> $1.000.000</p>
                <p><strong>PIN:</strong> 1234</p>
                <p><strong>Resultado esperado:</strong> Éxito</p>
            </div>
            
            <div class="feature-card">
                <h4>❌ Saldo Insuficiente</h4>
                <p><strong>Origen:</strong> 3501234566 (saldo: $300.000)</p>
                <p><strong>Destino:</strong> 3001234567</p>
                <p><strong>Monto:</strong> $500.000</p>
                <p><strong>Resultado esperado:</strong> Error saldo</p>
            </div>
            
            <div class="feature-card">
                <h4>❌ Destinatario Sin Nequi</h4>
                <p><strong>Origen:</strong> 3219264943</p>
                <p><strong>Destino:</strong> 3001234560 (sin Nequi)</p>
                <p><strong>Monto:</strong> $100.000</p>
                <p><strong>Resultado esperado:</strong> Error destinatario</p>
            </div>
            
            <div class="feature-card">
                <h4>❌ PIN Inválido</h4>
                <p><strong>Origen:</strong> 3219264943</p>
                <p><strong>PIN:</strong> 123 (menos de 4 dígitos)</p>
                <p><strong>Resultado esperado:</strong> Error PIN</p>
            </div>
        </div>
        
        <div class="resultado-demo">
            <h3>🎊 ¡Simulador Ultra-Realista Listo!</h3>
            <p><strong>Características implementadas:</strong></p>
            <ul>
                <li>✅ Proceso completo de autenticación</li>
                <li>✅ Validación de saldos y límites</li>
                <li>✅ Cálculo de comisiones reales</li>
                <li>✅ Generación de IDs únicos</li>
                <li>✅ Simulación de errores comunes</li>
                <li>✅ Base de datos persistente</li>
                <li>✅ Interfaz profesional</li>
                <li>✅ Historial de transacciones</li>
            </ul>
            
            <p><strong>🎯 El simulador es tan realista que parece Nequi real, pero NUNCA olvides:</strong></p>
            <p style="font-size: 1.2em; color: #ffc107;"><strong>⚠️ ES SOLO EDUCATIVO - NO TRANSFIERE DINERO REAL ⚠️</strong></p>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="nequi_simulador_realista.php" class="btn btn-nequi">🚀 Probar Simulador Realista</a>
            <a href="pagos-nequi.php" class="btn">💳 Sistema de Pagos</a>
            <a href="index.php" class="btn">🏠 Dashboard</a>
        </div>
        
        <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; margin-top: 30px; text-align: center;">
            <h3>📊 Estadísticas del Simulador</h3>
            <p><strong>Desarrollado:</strong> <?php echo date('Y-m-d'); ?></p>
            <p><strong>Tecnologías:</strong> PHP 8.2, MySQL, JavaScript, Bootstrap</p>
            <p><strong>Propósito:</strong> Educación en sistemas de pagos</p>
            <p><strong>Nivel de realismo:</strong> 95% (solo falta dinero real 😄)</p>
        </div>
    </div>
</body>
</html>
