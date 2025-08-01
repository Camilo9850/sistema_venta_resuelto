<?php

class Producto {
    private $idproducto;
    private $nombre;
    private $fk_idtipoproducto;
    private $cantidad;
    private $precio;
    private $descripcion;
    private $imagen;

    public function __construct(){
        $this->cantidad = 0;
        $this->precio = 0.0;
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }


    public function cargarFormulario($request){
        $this->idproducto = isset($request["id"])? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"])? $request["txtNombre"] : "";
        $this->fk_idtipoproducto = isset($request["lstTipoProducto"])? $request["lstTipoProducto"] : "";
        $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"]: 0;
        $this->precio = isset($request["txtPrecio"])? $request["txtPrecio"]: 0;
        $this->descripcion = isset($request["txtDescripcion"])? $request["txtDescripcion"] : "";
    }

    public function insertar(){
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        // Verificar conexión
        if ($mysqli->connect_error) {
            die("Conexión fallida: " . $mysqli->connect_error);
        }
        
        // Usar prepared statement para mayor seguridad y compatibilidad
        $sql = "INSERT INTO productos (
                    nombre, 
                    fk_idtipoproducto,
                    cantidad, 
                    precio, 
                    descripcion, 
                    imagen
                ) VALUES (?, ?, ?, ?, ?, ?)";
                
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            die("Error en prepare: " . $mysqli->error);
        }
        
        $stmt->bind_param("siidds", 
            $this->nombre, 
            $this->fk_idtipoproducto,
            $this->cantidad,
            $this->precio, 
            $this->descripcion,
            $this->imagen
        );
        
        if (!$stmt->execute()) {
            die("Error en execute: " . $stmt->error);
        }
        
        //Obtiene el id generado por la inserción
        $this->idproducto = $mysqli->insert_id;
        
        $stmt->close();
        $mysqli->close();
        
        return [
            'exito' => true,
            'mensaje' => 'Producto guardado exitosamente',
            'id' => $this->idproducto
        ];
    }

    public function actualizar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        // Verificar conexión
        if ($mysqli->connect_error) {
            die("Conexión fallida: " . $mysqli->connect_error);
        }
        
        $sql = "UPDATE productos SET
                nombre = ?,
                fk_idtipoproducto = ?,
                cantidad = ?,
                precio = ?,
                descripcion = ?,
                imagen = ?
                WHERE idproducto = ?";
                
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            die("Error en prepare: " . $mysqli->error);
        }
        
        $stmt->bind_param("siidssi", 
            $this->nombre,
            $this->fk_idtipoproducto,
            $this->cantidad,
            $this->precio,
            $this->descripcion,
            $this->imagen,
            $this->idproducto
        );
        
        if (!$stmt->execute()) {
            die("Error en execute: " . $stmt->error);
        }
        
        $stmt->close();
        $mysqli->close();
        
        return [
            'exito' => true,
            'mensaje' => 'Producto actualizado exitosamente'
        ];
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        // Verificar si el producto tiene ventas asociadas
        $sqlCheck = "SELECT COUNT(*) as total FROM ventas WHERE fk_idproducto = " . $this->idproducto;
        $resultado = $mysqli->query($sqlCheck);
        
        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            $ventasAsociadas = $fila['total'];
            
            if ($ventasAsociadas > 0) {
                // No se puede eliminar porque tiene ventas asociadas
                $mysqli->close();
                return [
                    'exito' => false,
                    'mensaje' => "No se puede eliminar el producto porque tiene $ventasAsociadas venta(s) asociada(s). Para eliminarlo, primero debe eliminar todas las ventas de este producto."
                ];
            }
        }
        
        // Si no hay ventas asociadas, proceder con la eliminación
        // Primero obtenemos la información del producto para eliminar la imagen
        $sqlSelect = "SELECT imagen FROM productos WHERE idproducto = " . $this->idproducto;
        if ($resultado = $mysqli->query($sqlSelect)) {
            if ($fila = $resultado->fetch_assoc()) {
                $imagen = $fila['imagen'];
                // Eliminar el archivo de imagen si existe
                if ($imagen && file_exists("files/$imagen")) {
                    unlink("files/$imagen");
                }
            }
        }
        
        // Ahora eliminamos el registro de la base de datos
        $sql = "DELETE FROM productos WHERE idproducto = " . $this->idproducto;
        
        try {
            if (!$mysqli->query($sql)) {
                $mysqli->close();
                return [
                    'exito' => false,
                    'mensaje' => "Error al eliminar el producto: " . $mysqli->error
                ];
            }
            
            $mysqli->close();
            return [
                'exito' => true,
                'mensaje' => "Producto eliminado exitosamente"
            ];
            
        } catch (Exception $e) {
            $mysqli->close();
            return [
                'exito' => false,
                'mensaje' => "Error inesperado: " . $e->getMessage()
            ];
        }
    }

    // Método para verificar ventas asociadas y obtener detalles
    public function verificarVentasAsociadas() {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        $sql = "SELECT 
                    COUNT(*) as total_ventas,
                    SUM(cantidad) as total_cantidad,
                    SUM(total) as total_dinero,
                    MIN(fecha) as primera_venta,
                    MAX(fecha) as ultima_venta
                FROM ventas 
                WHERE fk_idproducto = " . $this->idproducto;
                
        $resultado = $mysqli->query($sql);
        
        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            $mysqli->close();
            return [
                'tiene_ventas' => $fila['total_ventas'] > 0,
                'total_ventas' => $fila['total_ventas'],
                'total_cantidad' => $fila['total_cantidad'],
                'total_dinero' => $fila['total_dinero'],
                'primera_venta' => $fila['primera_venta'],
                'ultima_venta' => $fila['ultima_venta']
            ];
        }
        
        $mysqli->close();
        return [
            'tiene_ventas' => false,
            'total_ventas' => 0,
            'total_cantidad' => 0,
            'total_dinero' => 0,
            'primera_venta' => null,
            'ultima_venta' => null
        ];
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT idproducto, 
                        nombre, 
                        fk_idtipoproducto,
                        cantidad, 
                        precio, 
                        descripcion,
                        imagen
                FROM productos 
                WHERE idproducto = " . $this->idproducto;
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if($fila = $resultado->fetch_assoc()){
            $this->idproducto = $fila["idproducto"];
            $this->nombre = $fila["nombre"];
            $this->fk_idtipoproducto = $fila["fk_idtipoproducto"];
            $this->cantidad = $fila["cantidad"];
            $this->precio = $fila["precio"];
            $this->descripcion = $fila["descripcion"];
            $this->imagen = $fila["imagen"];
        }
        $mysqli->close();
        return $this;
    }

    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT 
                    idproducto,
                    nombre, 
                    fk_idtipoproducto, 
                    cantidad, 
                    precio, 
                    descripcion, 
                    imagen 
                FROM productos ORDER BY idproducto DESC";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo
            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Producto();
                $entidadAux->idproducto = $fila["idproducto"];
                $entidadAux->nombre = $fila["nombre"];
                $entidadAux->fk_idtipoproducto = $fila["fk_idtipoproducto"];
                $entidadAux->cantidad = $fila["cantidad"];
                $entidadAux->precio = $fila["precio"];
                $entidadAux->descripcion = $fila["descripcion"];
                $entidadAux->imagen = $fila["imagen"];
                $aResultado[] = $entidadAux;
            }
        }
        $mysqli->close();
        return $aResultado;
    }

}


?>