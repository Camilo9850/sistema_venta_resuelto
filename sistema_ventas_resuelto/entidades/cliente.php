<?php

class Cliente
{
    private $idcliente;
    private $nombre;
    private $apellido;
    private $documento;
    private $email;
    private $telefono;
    private $direccion;
    private $fk_idprovincia;
    private $fk_idlocalidad;

    public function __construct()
    {

    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarFormulario($request)
    {
        $this->idcliente = isset($request["id"]) ? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
        $this->apellido = isset($request["txtApellido"]) ? $request["txtApellido"] : "";
        $this->documento = isset($request["txtDocumento"]) ? $request["txtDocumento"] : "";
        $this->email = isset($request["txtEmail"]) ? $request["txtEmail"] : "";
        $this->telefono = isset($request["txtTelefono"]) ? $request["txtTelefono"] : "";
        $this->direccion = isset($request["txtDireccion"]) ? $request["txtDireccion"] : "";
        $this->fk_idprovincia = isset($request["lstProvincia"]) ? $request["lstProvincia"] : "";
        $this->fk_idlocalidad = isset($request["lstLocalidad"]) ? $request["lstLocalidad"] : "";
    }

    public function insertar()
    {
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        //Arma la query
        $sql = "INSERT INTO clientes (
                    nombre,
                    apellido,
                    documento,
                    email,
                    telefono,
                    direccion,
                    fk_idprovincia,
                    fk_idlocalidad
                ) VALUES (
                    '$this->nombre',
                    '$this->apellido',
                    '$this->documento',
                    '$this->email',
                    '$this->telefono',
                    '$this->direccion',
                    $this->fk_idprovincia,
                    $this->fk_idlocalidad
                );";
        // print_r($sql);exit;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        //Obtiene el id generado por la inserción
        $this->idcliente = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }

    public function actualizar()
    {

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "UPDATE clientes SET
                nombre = '" . $this->nombre . "',
                apellido = '" . $this->apellido . "',
                documento = '" . $this->documento . "',
                email = '" . $this->email . "',
                telefono = '" . $this->telefono . "',
                direccion = '" . $this->direccion . "',
                fk_idprovincia = '" . $this->fk_idprovincia . "',
                fk_idlocalidad = '" . $this->fk_idlocalidad . "'
                WHERE idcliente = " . $this->idcliente;

        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "DELETE FROM clientes WHERE idcliente = " . $this->idcliente;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT idcliente,
                        nombre,
                        apellido,
                        documento,
                        email,
                        telefono,
                        direccion,
                        fk_idprovincia,
                        fk_idlocalidad
                FROM clientes
                WHERE idcliente = $this->idcliente";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $this->idcliente = $fila["idcliente"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->documento = $fila["documento"];
            $this->email = $fila["email"];
            $this->telefono = $fila["telefono"];
            $this->direccion = $fila["direccion"];
            $this->fk_idprovincia = $fila["fk_idprovincia"];
            $this->fk_idlocalidad = $fila["fk_idlocalidad"];
        }
        $mysqli->close();

    }

     public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT 
                    idcliente,
                    nombre,
                    apellido,
                    documento,
                    email,
                    telefono,
                    direccion,
                    fk_idprovincia,
                    fk_idlocalidad
                FROM clientes";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo

            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Cliente();
                $entidadAux->idcliente = $fila["idcliente"];
                $entidadAux->nombre = $fila["nombre"];
                $entidadAux->apellido = $fila["apellido"];
                $entidadAux->documento = $fila["documento"];
                $entidadAux->email = $fila["email"];
                $entidadAux->telefono = $fila["telefono"];
                $entidadAux->direccion = $fila["direccion"];
                $entidadAux->fk_idprovincia = $fila["fk_idprovincia"];
                $entidadAux->fk_idlocalidad = $fila["fk_idlocalidad"];
                $aResultado[] = $entidadAux;
            }
        }
        return $aResultado;
    }

}
?>