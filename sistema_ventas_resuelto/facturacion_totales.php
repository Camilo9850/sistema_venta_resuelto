<?php
// 📊 MÓDULO DE FACTURACIÓN Y TOTALES - SISTEMA DE VENTAS
require_once 'config.php';

class FacturacionTotales {
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=" . Config::BBDD_HOST . ":" . Config::BBDD_PORT . ";dbname=" . Config::BBDD_NOMBRE,
                Config::BBDD_USUARIO,
                Config::BBDD_CLAVE,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Asegurar que existan las tablas necesarias
            $this->crearTablasNecesarias();
            
        } catch (Exception $e) {
            $this->pdo = null;
        }
    }
    
    // Crear tablas necesarias si no existen
    private function crearTablasNecesarias() {
        if (!$this->pdo) return;
        
        try {
            // Crear tabla de transacciones Nequi si no existe
            $this->pdo->exec("
                CREATE TABLE IF NOT EXISTS transacciones_nequi_demo (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    transaccion_id VARCHAR(50) UNIQUE,
                    telefono_origen VARCHAR(15),
                    telefono_destino VARCHAR(15),
                    monto DECIMAL(10,2),
                    comision DECIMAL(10,2) DEFAULT 0,
                    concepto TEXT,
                    estado VARCHAR(20) DEFAULT 'completada',
                    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
        } catch (Exception $e) {
            // Continuar silenciosamente si hay error
        }
    }
    
    // Obtener facturación mensual
    public function obtenerFacturacionMensual($año = null, $mes = null) {
        if (!$this->pdo) return 0;
        
        $año = $año ?? date('Y');
        $mes = $mes ?? date('m');
        
        try {
            // Sumar ventas del mes actual
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(v.total), 0) as total_mensual
                FROM ventas v 
                WHERE YEAR(v.fecha) = ? AND MONTH(v.fecha) = ?
            ");
            $stmt->execute([$año, $mes]);
            $ventasMensual = $stmt->fetchColumn();
            
            // Sumar transacciones Nequi simuladas del mes
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(monto), 0) as total_nequi
                FROM transacciones_nequi_demo 
                WHERE YEAR(fecha) = ? AND MONTH(fecha) = ?
            ");
            $stmt->execute([$año, $mes]);
            $nequiMensual = $stmt->fetchColumn();
            
            return $ventasMensual + $nequiMensual;
            
        } catch (Exception $e) {
            return 0;
        }
    }
    
    // Obtener facturación anual
    public function obtenerFacturacionAnual($año = null) {
        if (!$this->pdo) return 0;
        
        $año = $año ?? date('Y');
        
        try {
            // Sumar ventas del año
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(v.total), 0) as total_anual
                FROM ventas v 
                WHERE YEAR(v.fecha) = ?
            ");
            $stmt->execute([$año]);
            $ventasAnual = $stmt->fetchColumn();
            
            // Sumar transacciones Nequi del año
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(monto), 0) as total_nequi
                FROM transacciones_nequi_demo 
                WHERE YEAR(fecha) = ?
            ");
            $stmt->execute([$año]);
            $nequiAnual = $stmt->fetchColumn();
            
            return $ventasAnual + $nequiAnual;
            
        } catch (Exception $e) {
            return 0;
        }
    }
    
    // Obtener estadísticas detalladas
    public function obtenerEstadisticasCompletas() {
        // Inicializar con valores por defecto
        $estadisticas = [
            'mensual' => 0,
            'anual' => 0,
            'ventas_cantidad' => 0,
            'ventas_total' => 0,
            'nequi_cantidad' => 0,
            'nequi_total' => 0,
            'clientes_activos' => 0,
            'productos_cantidad' => 0,
            'stock_total' => 0,
            'facturacion_mensual' => []
        ];
        
        if (!$this->pdo) return $estadisticas;
        
        try {
            // Totales actuales
            $estadisticas['mensual'] = $this->obtenerFacturacionMensual();
            $estadisticas['anual'] = $this->obtenerFacturacionAnual();
            
            // Ventas tradicionales
            try {
                $stmt = $this->pdo->query("SELECT COUNT(*) as cantidad, COALESCE(SUM(total), 0) as total FROM ventas");
                if ($stmt) {
                    $ventas = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($ventas) {
                        $estadisticas['ventas_cantidad'] = $ventas['cantidad'] ?? 0;
                        $estadisticas['ventas_total'] = $ventas['total'] ?? 0;
                    }
                }
            } catch (Exception $e) {
                // Tabla ventas podría no existir, mantener valores por defecto
            }
            
            // Transacciones Nequi
            try {
                $stmt = $this->pdo->query("SELECT COUNT(*) as cantidad, COALESCE(SUM(monto), 0) as total FROM transacciones_nequi_demo");
                if ($stmt) {
                    $nequi = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($nequi) {
                        $estadisticas['nequi_cantidad'] = $nequi['cantidad'] ?? 0;
                        $estadisticas['nequi_total'] = $nequi['total'] ?? 0;
                    }
                }
            } catch (Exception $e) {
                // Tabla transacciones_nequi_demo podría no existir, mantener valores por defecto
            }
            
            // Clientes activos
            try {
                $stmt = $this->pdo->query("SELECT COUNT(*) FROM clientes");
                if ($stmt) {
                    $estadisticas['clientes_activos'] = $stmt->fetchColumn() ?? 0;
                }
            } catch (Exception $e) {
                // Tabla clientes podría no existir
            }
            
            // Productos en stock
            try {
                $stmt = $this->pdo->query("SELECT COUNT(*) as productos, COALESCE(SUM(cantidad), 0) as stock_total FROM productos");
                if ($stmt) {
                    $productos = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($productos) {
                        $estadisticas['productos_cantidad'] = $productos['productos'] ?? 0;
                        $estadisticas['stock_total'] = $productos['stock_total'] ?? 0;
                    }
                }
            } catch (Exception $e) {
                // Tabla productos podría no existir
            }
            
            // Facturación por mes (últimos 6 meses)
            $estadisticas['facturacion_mensual'] = [];
            for ($i = 5; $i >= 0; $i--) {
                $fecha = date('Y-m', strtotime("-$i months"));
                $año = date('Y', strtotime("-$i months"));
                $mes = date('m', strtotime("-$i months"));
                
                $total = $this->obtenerFacturacionMensual($año, $mes);
                $estadisticas['facturacion_mensual'][] = [
                    'fecha' => $fecha,
                    'mes_nombre' => $this->obtenerNombreMes($mes),
                    'total' => $total
                ];
            }
            
            return $estadisticas;
            
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Simular datos adicionales si no hay suficientes ventas
    public function simularDatosDemo() {
        if (!$this->pdo) return false;
        
        try {
            // Verificar si ya hay datos
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM ventas");
            $ventasExistentes = $stmt->fetchColumn();
            
            if ($ventasExistentes < 5) {
                // Crear algunas ventas de ejemplo para este mes
                $this->crearVentasDemo();
            }
            
            // Verificar transacciones Nequi
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM transacciones_nequi_demo");
            $nequiExistentes = $stmt->fetchColumn();
            
            if ($nequiExistentes < 3) {
                // Crear transacciones Nequi de ejemplo
                $this->crearTransaccionesNequiDemo();
            }
            
            return true;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    private function crearVentasDemo() {
        if (!$this->pdo) return;
        
        try {
            $ventasDemo = [
                ['total' => 250000, 'fecha' => date('Y-m-d', strtotime('-5 days'))],
                ['total' => 180000, 'fecha' => date('Y-m-d', strtotime('-3 days'))],
                ['total' => 95000, 'fecha' => date('Y-m-d', strtotime('-2 days'))],
                ['total' => 320000, 'fecha' => date('Y-m-d', strtotime('-1 day'))],
                ['total' => 150000, 'fecha' => date('Y-m-d')]
            ];
            
            $stmt = $this->pdo->prepare("
                INSERT INTO ventas (total, fecha, fk_idcliente) 
                VALUES (?, ?, 1)
            ");
            
            foreach ($ventasDemo as $venta) {
                $stmt->execute([$venta['total'], $venta['fecha']]);
            }
            
        } catch (Exception $e) {
            // Tabla ventas no existe o error, continuar silenciosamente
        }
    }
    
    private function crearTransaccionesNequiDemo() {
        if (!$this->pdo) return;
        
        try {
            $transaccionesDemo = [
                [
                    'transaccion_id' => 'NQ' . date('ymd') . '001',
                    'telefono_origen' => '3219264943',
                    'telefono_destino' => '3001234567',
                    'monto' => 1000000,
                    'comision' => 2000,
                    'concepto' => 'Pago venta sistema demo'
                ],
                [
                    'transaccion_id' => 'NQ' . date('ymd') . '002',
                    'telefono_origen' => '3001234567',
                    'telefono_destino' => '3219264943',
                    'monto' => 500000,
                    'comision' => 1000,
                    'concepto' => 'Transferencia demo'
                ]
            ];
            
            $stmt = $this->pdo->prepare("
                INSERT INTO transacciones_nequi_demo 
                (transaccion_id, telefono_origen, telefono_destino, monto, comision, concepto) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            foreach ($transaccionesDemo as $trans) {
                $stmt->execute([
                    $trans['transaccion_id'],
                    $trans['telefono_origen'],
                    $trans['telefono_destino'],
                    $trans['monto'],
                    $trans['comision'],
                    $trans['concepto']
                ]);
            }
            
        } catch (Exception $e) {
            // Continuar silenciosamente si hay error
        }
    }
    
    private function obtenerNombreMes($mes) {
        $meses = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo',
            '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
            '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre',
            '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        ];
        return $meses[$mes] ?? 'Mes';
    }
}

// Si se llama directamente, mostrar las estadísticas
if (basename($_SERVER['PHP_SELF']) == 'facturacion_totales.php') {
    $facturacion = new FacturacionTotales();
    
    // Simular datos demo si es necesario
    $facturacion->simularDatosDemo();
    
    // Obtener estadísticas
    $stats = $facturacion->obtenerEstadisticasCompletas();
    
    header('Content-Type: application/json');
    echo json_encode($stats);
    exit;
}
?>
