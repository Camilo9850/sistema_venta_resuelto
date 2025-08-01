<?php
require_once "config.php";
require_once "entidades/usuario.php";

// Función para generar respuesta JSON
function jsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Función para validar email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Función para validar usuario
function validarUsuario($usuario) {
    if (strlen($usuario) < 3 || strlen($usuario) > 30) {
        return false;
    }
    return preg_match('/^[a-zA-Z0-9_]+$/', $usuario);
}

// Función para validar contraseña
function validarPassword($password) {
    if (strlen($password) < 4) {
        return false;
    }
    return true;
}

// Función para verificar si email ya existe
function emailExiste($email, $pdo = null) {
    try {
        $usuario = new Usuario();
        return $usuario->obtenerPorCorreo($email);
    } catch (Exception $e) {
        return false;
    }
}

// Función para verificar si usuario ya existe
function usuarioExiste($usuario, $pdo = null) {
    try {
        $usuarioObj = new Usuario();
        return $usuarioObj->obtenerPorUsuario($usuario);
    } catch (Exception $e) {
        return false;
    }
}

// Solo procesar si es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: crear-cuenta.html');
    exit;
}

try {
    // Obtener datos del formulario
    $nombre = trim($_POST['txtNombre'] ?? '');
    $apellido = trim($_POST['txtApellido'] ?? '');
    $correo = trim($_POST['txtCorreo'] ?? '');
    $usuario = trim($_POST['txtUsuario'] ?? '');
    $clave = $_POST['txtClave'] ?? '';
    $claveConfirm = $_POST['txtClaveConfirm'] ?? '';
    $terminos = isset($_POST['terminos']);

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($usuario) || empty($clave)) {
        throw new Exception('Todos los campos son obligatorios');
    }

    if (!$terminos) {
        throw new Exception('Debe aceptar los términos y condiciones');
    }

    // Validar email
    if (!validarEmail($correo)) {
        throw new Exception('El formato del email es inválido');
    }

    // Validar usuario
    if (!validarUsuario($usuario)) {
        throw new Exception('El usuario debe tener entre 3-30 caracteres y solo contener letras, números y guión bajo');
    }

    // Validar contraseña
    if (!validarPassword($clave)) {
        throw new Exception('La contraseña debe tener al menos 4 caracteres');
    }

    // Verificar que las contraseñas coincidan
    if ($clave !== $claveConfirm) {
        throw new Exception('Las contraseñas no coinciden');
    }

    // Conectar a la base de datos (aunque no la usemos directamente, para mantener compatibilidad)
    // $pdo = new PDO($dsn, $username, $password, $options);

    // Verificar si el email ya existe
    if (emailExiste($correo)) {
        throw new Exception('Este email ya está registrado');
    }

    // Verificar si el usuario ya existe
    if (usuarioExiste($usuario)) {
        throw new Exception('Este nombre de usuario ya está en uso');
    }

    // Crear instancia del usuario
    $usuarioObj = new Usuario();
    
    // Simular el request para usar cargarFormulario
    $request = [
        'txtNombre' => $nombre,
        'txtApellido' => $apellido,
        'txtCorreo' => $correo,
        'txtUsuario' => $usuario,
        'txtClave' => $clave
    ];
    
    $usuarioObj->cargarFormulario($request);
    
    // Insertar usuario
    $usuarioObj->insertar();
    
    // Simular resultado exitoso
    $resultado = ['correcto' => true, 'mensaje' => 'Usuario creado exitosamente'];
    
    if ($resultado && isset($resultado['correcto']) && $resultado['correcto']) {
        // Éxito - mostrar página de éxito
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>Cuenta Creada - ABM Ventas</title>
            <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
            <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
            <link href="css/sb-admin-2.min.css" rel="stylesheet">
            <style>
                .bg-gradient-success {
                    background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
                }
                .success-animation {
                    animation: bounceIn 1s;
                }
                @keyframes bounceIn {
                    0% { transform: scale(0.3); opacity: 0; }
                    50% { transform: scale(1.05); }
                    70% { transform: scale(0.9); }
                    100% { transform: scale(1); opacity: 1; }
                }
            </style>
        </head>
        <body class="bg-gradient-success">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-8 col-md-9">
                        <div class="card o-hidden border-0 shadow-lg my-5 success-animation">
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-5 text-center">
                                            <div class="mb-4">
                                                <i class="fas fa-check-circle fa-5x text-success"></i>
                                            </div>
                                            <h1 class="h4 text-gray-900 mb-4">🎉 ¡Cuenta Creada Exitosamente!</h1>
                                            <div class="alert alert-success" role="alert">
                                                <h6><strong>¡Bienvenido/a <?php echo htmlspecialchars($nombre . ' ' . $apellido); ?>!</strong></h6>
                                                <p class="mb-0">Tu cuenta ha sido creada correctamente con el usuario: <strong><?php echo htmlspecialchars($usuario); ?></strong></p>
                                            </div>
                                            <div class="card border-left-success shadow h-100 py-2 mb-4">
                                                <div class="card-body">
                                                    <div class="row no-gutters align-items-center">
                                                        <div class="col mr-2">
                                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                                Información de tu cuenta</div>
                                                            <div class="text-sm">
                                                                <p><i class="fas fa-user"></i> <strong>Usuario:</strong> <?php echo htmlspecialchars($usuario); ?></p>
                                                                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($correo); ?></p>
                                                                <p><i class="fas fa-calendar"></i> <strong>Fecha de registro:</strong> <?php echo date('d/m/Y H:i'); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <h6 class="text-gray-800">¿Qué puedes hacer ahora?</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <div class="card border-left-primary h-100 py-2">
                                                            <div class="card-body text-center">
                                                                <i class="fas fa-sign-in-alt text-primary"></i>
                                                                <small class="d-block">Iniciar Sesión</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <div class="card border-left-info h-100 py-2">
                                                            <div class="card-body text-center">
                                                                <i class="fas fa-tachometer-alt text-info"></i>
                                                                <small class="d-block">Acceder al Dashboard</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="login.php" class="btn btn-success btn-user btn-block">
                                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión Ahora
                                            </a>
                                            
                                            <hr>
                                            
                                            <div class="text-center">
                                                <a class="small text-muted" href="crear-cuenta.html">← Crear otra cuenta</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
            <script src="js/sb-admin-2.min.js"></script>
            
            <script>
                // Auto-redirect después de 10 segundos
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 10000);
                
                // Mostrar countdown
                let countdown = 10;
                const countdownElement = $('<p class="text-muted small mt-3">Redirigiendo al login en <span id="countdown">10</span> segundos...</p>');
                $('.card-body').append(countdownElement);
                
                const timer = setInterval(function() {
                    countdown--;
                    $('#countdown').text(countdown);
                    if (countdown <= 0) {
                        clearInterval(timer);
                    }
                }, 1000);
            </script>
        </body>
        </html>
        <?php
    } else {
        throw new Exception($resultado['mensaje'] ?? 'Error al crear la cuenta');
    }

} catch (Exception $e) {
    // Error - mostrar página de error
    $errorMessage = $e->getMessage();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Error - ABM Ventas</title>
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <style>
            .bg-gradient-danger {
                background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
            }
        </style>
    </head>
    <body class="bg-gradient-danger">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5 text-center">
                                        <div class="mb-4">
                                            <i class="fas fa-exclamation-triangle fa-5x text-warning"></i>
                                        </div>
                                        <h1 class="h4 text-gray-900 mb-4">❌ Error al Crear Cuenta</h1>
                                        <div class="alert alert-danger" role="alert">
                                            <h6><strong>¡Oops! Algo salió mal</strong></h6>
                                            <p class="mb-0"><?php echo htmlspecialchars($errorMessage); ?></p>
                                        </div>
                                        
                                        <a href="crear-cuenta.html" class="btn btn-primary btn-user btn-block">
                                            <i class="fas fa-redo"></i> Intentar Nuevamente
                                        </a>
                                        
                                        <hr>
                                        
                                        <div class="text-center">
                                            <a class="small" href="login.php">¿Ya tienes cuenta? Inicia Sesión</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
