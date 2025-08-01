<?php
session_start();
include_once "config.php";
include_once "entidades/usuario.php";

$mensaje = "";
$error = "";

if ($_POST) {
    $email = trim($_POST["txtEmail"]);
    
    if (!empty($email)) {
        // Verificar si el email existe en la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        $sql = "SELECT idusuario, usuario, nombre, apellido, correo FROM usuarios WHERE correo = '$email'";
        $resultado = $mysqli->query($sql);
        
        if ($resultado && $fila = $resultado->fetch_assoc()) {
            // Usuario encontrado - generar nueva contraseña temporal
            $nueva_password = "temp" . rand(1000, 9999); // Contraseña temporal
            $nueva_password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
            
            // Actualizar contraseña en la base de datos
            $sql_update = "UPDATE usuarios SET clave = '$nueva_password_hash' WHERE correo = '$email'";
            
            if ($mysqli->query($sql_update)) {
                $mensaje = "¡Contraseña restablecida! Tu nueva contraseña temporal es: <strong>$nueva_password</strong><br>
                          Usuario: <strong>" . $fila['usuario'] . "</strong><br>
                          Por favor, inicia sesión y cambia tu contraseña.";
            } else {
                $error = "Error al actualizar la contraseña. Intenta de nuevo.";
            }
        } else {
            $error = "El email ingresado no está registrado en el sistema.";
        }
        
        $mysqli->close();
    } else {
        $error = "Por favor, ingresa tu dirección de email.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Sistema de Ventas</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card-forgot {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .form-control-custom {
            border: 2px solid #e3e6f0;
            border-radius: 50px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .form-control-custom:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .alert-custom {
            border-radius: 15px;
            border: none;
            font-weight: 500;
        }
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9">
                <div class="card card-forgot o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <div class="icon-circle">
                                            <i class="fas fa-key fa-2x text-white"></i>
                                        </div>
                                        <h1 class="h3 text-gradient mb-2">¿Olvidaste tu contraseña?</h1>
                                        <p class="mb-4 text-gray-600">
                                            No te preocupes, ¡pasan cosas! Simplemente ingresa tu dirección de correo electrónico 
                                            y te generaremos una contraseña temporal.
                                        </p>
                                    </div>

                                    <?php if (!empty($mensaje)): ?>
                                        <div class="alert alert-success alert-custom" role="alert">
                                            <i class="fas fa-check-circle me-2"></i>
                                            <?php echo $mensaje; ?>
                                        </div>
                                        <div class="text-center mt-4">
                                            <a href="login.php" class="btn btn-primary-custom">
                                                <i class="fas fa-sign-in-alt me-2"></i>Ir al Login
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <?php if (!empty($error)): ?>
                                            <div class="alert alert-danger alert-custom" role="alert">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <?php echo $error; ?>
                                            </div>
                                        <?php endif; ?>

                                        <form method="POST" action="">
                                            <div class="form-group mb-4">
                                                <input type="email" 
                                                       class="form-control form-control-custom" 
                                                       name="txtEmail" 
                                                       placeholder="Ingresa tu dirección de correo electrónico..."
                                                       required>
                                            </div>
                                            <button type="submit" class="btn btn-primary-custom btn-block w-100">
                                                <i class="fas fa-paper-plane me-2"></i>Restablecer Contraseña
                                            </button>
                                        </form>

                                        <hr class="my-4">

                                        <div class="text-center">
                                            <a href="register.html" class="small text-decoration-none">
                                                <i class="fas fa-user-plus me-1"></i>¡Crea una cuenta!
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <a href="login.php" class="small text-decoration-none">
                                                <i class="fas fa-arrow-left me-1"></i>¿Ya tienes una cuenta? ¡Inicia sesión!
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
