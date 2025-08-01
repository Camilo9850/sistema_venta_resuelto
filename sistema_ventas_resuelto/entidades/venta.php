<?php

class Venta {
    private $idventa;
    private $fk_idcliente;
    private $fk_idproducto;
    private $fecha;
    private $cantidad;
    private $preciounitario;
    private $total;

    private $nombre_cliente;
    private $nombre_producto;

    public function __construct(){
        $this->cantidad = 0;
        $this->preciounitario = 0.0;
        $this->total = 0.0;
        $this->fecha = date('Y-m-d H:i:s'); // Asignar fecha actual por defecto
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }


    public function cargarFormulario($request){
        $this->idventa = isset($request["id"])? $request["id"] : "";
        $this->fk_idcliente = isset($request["lstCliente"])? $request["lstCliente"] : "";
        $this->fk_idproducto = isset($request["lstProducto"])? $request["lstProducto"]: "";
        
        // Manejar la fecha de forma más robusta
        if(isset($request["txtAnio"]) && isset($request["txtMes"]) && isset($request["txtDia"])){
            $anio = $request["txtAnio"];
            $mes = str_pad($request["txtMes"], 2, '0', STR_PAD_LEFT);
            $dia = str_pad($request["txtDia"], 2, '0', STR_PAD_LEFT);
            $hora = isset($request["txtHora"]) ? $request["txtHora"] : "00:00";
            
            // Validar que los valores no estén vacíos
            if (!empty($anio) && !empty($mes) && !empty($dia)) {
                $this->fecha = $anio . "-" . $mes . "-" . $dia . " " . $hora . ":00";
            } else {
                $this->fecha = date('Y-m-d H:i:s'); // Usar fecha actual si los campos están vacíos
            }
        } else {
            $this->fecha = date('Y-m-d H:i:s'); // Usar fecha actual si no se enviaron los campos
        }
        
        $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"] : 0;
        $this->preciounitario = isset($request["txtPrecioUni"])? $request["txtPrecioUni"] : 0.0;
        $this->total = $this->preciounitario * $this->cantidad;
    }

    public function insertar(){
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        // Verificar conexión
        if ($mysqli->connect_error) {
            die("Conexión fallida: " . $mysqli->connect_error);
        }
        
        // Asignar fecha actual si no está definida o está vacía
        if (empty($this->fecha)) {
            $this->fecha = date('Y-m-d H:i:s');
        }
        
        // Usar prepared statement para HeidiSQL
        $sql = "INSERT INTO ventas (
                    fk_idcliente, 
                    fk_idproducto, 
                    fecha, 
                    cantidad,
                    preciounitario,
                    total
                ) VALUES (?, ?, ?, ?, ?, ?)";
                
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            die("Error en prepare: " . $mysqli->error);
        }
        
        $stmt->bind_param("iisidd", 
            $this->fk_idcliente, 
            $this->fk_idproducto,
            $this->fecha, 
            $this->cantidad,
            $this->preciounitario,
            $this->total
        );
        
        if (!$stmt->execute()) {
            die("Error en execute: " . $stmt->error);
        }
        
        //Obtiene el id generado por la inserción
        $this->idventa = $mysqli->insert_id;
        
        $stmt->close();
        $mysqli->close();
        
        return true;
    }

    public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "UPDATE ventas SET
                    fk_idcliente = $this->fk_idcliente,
                    fk_idproducto = $this->fk_idproducto,
                    fecha = '$this->fecha',
                    cantidad = $this->cantidad,
                    preciounitario = $this->preciounitario,
                    total = $this->total
                WHERE idventa = $this->idventa";

        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "DELETE FROM ventas WHERE idventa = " . $this->idventa;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT  idventa, 
                        fk_idcliente, 
                        fk_idproducto, 
                        fecha, 
                        cantidad,
                        preciounitario,
                        total
                FROM ventas 
                WHERE idventa = " . $this->idventa;
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if($fila = $resultado->fetch_assoc()){
            $this->idventa = $fila["idventa"];
            $this->fk_idcliente = $fila["fk_idcliente"];
            $this->fk_idproducto = $fila["fk_idproducto"];
            $this->fecha = $fila["fecha"];
            $this->cantidad = $fila["cantidad"];
            $this->preciounitario = $fila["preciounitario"];
            $this->total = $fila["total"];
        }
        $mysqli->close();

    }
    
    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT idventa, 
                        fk_idcliente, 
                        fk_idproducto, 
                        fecha, 
                        cantidad,
                        preciounitario,
                        total
                FROM ventas";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo
            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Venta();
                $entidadAux->idventa = $fila["idventa"];
                $entidadAux->fk_idcliente = $fila["fk_idcliente"];
                $entidadAux->fk_idproducto = $fila["fk_idproducto"];
                $entidadAux->fecha = $fila["fecha"];
                $entidadAux->cantidad = $fila["cantidad"];
                $entidadAux->preciounitario = $fila["preciounitario"];
                $entidadAux->total = $fila["total"];
                $aResultado[] = $entidadAux;
            }
        }
        $mysqli->close();
        return $aResultado;
    }
    public function obtenerVentasPorCliente($idCliente){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT idventa, 
                        fk_idcliente, 
                        fk_idproducto, 
                        fecha, 
                        cantidad,
                        preciounitario,
                        total
                FROM ventas WHERE fk_idcliente = $idCliente";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo
            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Venta();
                $entidadAux->idventa = $fila["idventa"];
                $entidadAux->fk_idcliente = $fila["fk_idcliente"];
                $entidadAux->fk_idproducto = $fila["fk_idproducto"];
                $entidadAux->fecha = $fila["fecha"];
                $entidadAux->cantidad = $fila["cantidad"];
                $entidadAux->preciounitario = $fila["preciounitario"];
                $entidadAux->total = $fila["total"];
                $aResultado[] = $entidadAux;
            }
        }
        $mysqli->close();
        return $aResultado;
    }

    public function cargarGrilla(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
       
        $sql = "SELECT 
                A.idventa,
                A.fecha,
                A.cantidad,
                A.fk_idcliente,
                B.nombre as nombre_cliente,
                A.fk_idproducto,
                A.total,
                A.preciounitario,
                C.nombre as nombre_producto
            FROM ventas A
            INNER JOIN clientes B ON A.fk_idcliente = B.idcliente
            INNER JOIN productos C ON A.fk_idproducto = C.idproducto
            ORDER BY A.fecha DESC";

        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo
            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Venta();
                $entidadAux->idventa = $fila["idventa"];
                $entidadAux->fk_idcliente = $fila["fk_idcliente"];
                $entidadAux->fk_idproducto = $fila["fk_idproducto"];
                $entidadAux->fecha = $fila["fecha"];
                $entidadAux->cantidad = $fila["cantidad"];
                $entidadAux->preciounitario = $fila["preciounitario"];
                $entidadAux->nombre_cliente = $fila["nombre_cliente"];
                $entidadAux->nombre_producto = $fila["nombre_producto"];
                $entidadAux->total = $fila["total"];
                $aResultado[] = $entidadAux;
            }
        }
        $mysqli->close();
        return $aResultado;
    }

    public function obtenerFacturacionMensual($mesActual, $anioActual){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT SUM(total) AS sumarizacion FROM ventas WHERE MONTH(fecha) = '$mesActual' AND YEAR(fecha) = '$anioActual';";

        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $sumarizacion = 0;
        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $sumarizacion = $fila["sumarizacion"] > 0 ? $fila["sumarizacion"] : 0;

        }
        $mysqli->close();
        return $sumarizacion;
    }

    public function obtenerFacturacionPorPeriodo($fechaDesde, $fechaHasta){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT SUM(total) AS sumarizacion FROM ventas WHERE fecha >= '$fechaDesde' AND fecha <= '$fechaHasta 23:59:59';";

        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $sumarizacion = 0;
        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $sumarizacion = $fila["sumarizacion"] > 0 ? $fila["sumarizacion"] : 0;

        }
        $mysqli->close();
        return $sumarizacion;
    }

    public function obtenerGananciasPorProducto(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        $sql = "SELECT 
                    p.nombre as producto,
                    SUM(v.total) as ganancia,
                    SUM(v.cantidad) as cantidad_vendida
                FROM ventas v 
                INNER JOIN productos p ON v.fk_idproducto = p.idproducto 
                GROUP BY v.fk_idproducto, p.nombre 
                ORDER BY ganancia DESC 
                LIMIT 5";

        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
            return [];
        }
        
        $ganancias = [];
        while ($fila = $resultado->fetch_assoc()) {
            $ganancias[] = [
                'producto' => $fila['producto'],
                'ganancia' => floatval($fila['ganancia']),
                'cantidad' => intval($fila['cantidad_vendida'])
            ];
        }
        
        $mysqli->close();
        return $ganancias;
    }

    public function obtenerFacturacionTotal(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT SUM(total) AS sumarizacion FROM ventas;";

        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $sumarizacion = 0;
        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $sumarizacion = $fila["sumarizacion"] > 0 ? $fila["sumarizacion"] : 0;

        }
        $mysqli->close();
        return $sumarizacion;
    }
}


?>