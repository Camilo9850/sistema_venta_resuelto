<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION["listadoClientes"])) {
    $aClientes = $_SESSION["listadoClientes"];
} else {
    $aClientes = array();
}

if ($_POST) {
    if (isset($_POST["btnEnviar"])) {
        //Capturamos los datos del formulario y lo almacenamos
        $nombre = $_POST["txtNombre"];
        $dni = $_POST["txtDni"];
        $telefono = $_POST["txtTelefono"];
        $edad = $_POST["txtEdad"];

        //Agregamos los datos del cliente en la primera posición vacía
        $aClientes[] = array(
            "nombre" => $nombre,
            "dni" => $dni,
            "telefono" => $telefono,
            "edad" => $edad,
        );
        //Para que persista el cliente actualizamos la variable de session
        $_SESSION["listadoClientes"] = $aClientes;
    } else {
        session_destroy();
        $aClientes = array();
    }
}

if(isset($_GET["pos"]) && $_GET["pos"] >= 0){
    $pos = $_GET["pos"];
    //Eliminar del array aClientes la posición seleccionada
    unset($aClientes[$pos]);
    //Actualizar la variable de session con el aClientes actualizado
    $_SESSION["listadoClientes"] = $aClientes;
    header("Location: prueba.php");
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de clientes</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center py-5">
                <h1> Listado de clientes </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-3 offset-1 me-5">
                <form action="" method="POST" class="form d-inline">
                    <label for="txtNombre"> Nombre:</label>
                    <input type="text" name="txtNombre" required class="form-control my-2 " placeholder="Ingrese el nombre y apellido">

                    <label for="txtDni">DNI:</label>
                    <input type="text" name="txtDni" required class="form-control my-2">

                    <label for="txtTelefono">Telefono:</label>
                    <input type="tel" name="txtTelefono" class="form-control my-2">

                    <label for="txtEdad">Edad:</label>
                    <input type="text" name="txtEdad" class="form-control my-2">

                    <button type="submit" name="btnEnviar" class="btn bg-primary text-white">Enviar</button>
                </form>
                <form action="" method="POST" class="d-inline">
                    <button type="submit" name="btnEliminar" class="btn bg-danger text-white">Eliminar</button>
                </form>
            </div>
            <div class="col-6 ms-5">
                <table class="table table-hover shadow border">
                    <thead>
                        <th>Nombre:</th>
                        <th>DNI:</th>
                        <th>Telefono:</th>
                        <th>Edad:</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        <?php foreach ($aClientes as $pos => $cliente) { ?>
                            <tr>
                                <td><?php echo $cliente["nombre"]; ?></td>
                                <td><?php echo $cliente["dni"]; ?></td>
                                <td><?php echo $cliente["telefono"]; ?></td>
                                <td><?php echo $cliente["edad"]; ?></td>
                                <td><a href="?pos=<?php echo $pos; ?>">Eliminar</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>