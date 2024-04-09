<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



if($_POST){
    header("Location: https://google.com.ar");

}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <main class="container">
        <div class="row">
            <div class="col-12">
                <h1>Login</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="" method="POST">
                    <div>
                        <label for="">Usuario:</label>
                        <input type="text" id="txtUsuario" name="txtUsuario" class="form-control">
                    </div>
                    <div>
                        <label for="">Clave:</label>
                        <input type="password" id="txtClave" name="txtClave" class="form-control">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">INGRESAR</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>