<?php
session_start(); // ¡ESTA LÍNEA ES CRÍTICA Y DEBE SER LA PRIMERA!

include_once "config.php";
include_once "entidades/usuario.php";

if($_POST){
    $usuario = trim($_POST["txtUsuario"]);
    $clave = trim($_POST["txtClave"]);

    $entidadUsuario = new Usuario();
    $entidadUsuario->obtenerPorUsuarioOCorreo($usuario);

    if($entidadUsuario->nombre != "" && $entidadUsuario->verificarClave($clave, $entidadUsuario->clave)){
        $_SESSION["nombre"] = $entidadUsuario->nombre; // Ahora se guardará correctamente
        header("location:index.php");
        exit(); // Detiene el script inmediatamente después de redirigir
    } else {
        $msg = "Usuario o clave incorrecto";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title> Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    .bg-login-image {
      background: linear-gradient(135deg, rgba(30, 136, 229, 0.9) 0%, rgba(142, 68, 173, 0.9) 30%, rgba(74, 144, 226, 0.9) 100%), 
                  url('img/funciones.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: white;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    
    .bg-login-image::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
      background-size: 50px 50px;
      animation: floating 20s infinite linear;
      pointer-events: none;
    }
    
    @keyframes floating {
      0% { transform: translate(-50px, -50px) rotate(0deg); }
      100% { transform: translate(-50px, -50px) rotate(360deg); }
    }
    
    .login-content {
      z-index: 2;
      padding: 2rem;
      animation: slideInLeft 1s ease-out;
    }
    
    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    .login-icon {
      font-size: 4rem;
      margin-bottom: 1rem;
      opacity: 0.9;
      animation: pulse 2s infinite;
      background: linear-gradient(45deg, #fff, #e3f2fd);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }
    
    .login-title {
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 1rem;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
      background: linear-gradient(45deg, #fff, #bbdefb);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: glow 3s ease-in-out infinite alternate;
    }
    
    @keyframes glow {
      from { text-shadow: 0 0 5px rgba(255,255,255,0.5), 0 0 10px rgba(255,255,255,0.3); }
      to { text-shadow: 0 0 10px rgba(255,255,255,0.8), 0 0 20px rgba(255,255,255,0.5); }
    }
    
    .login-subtitle {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 2rem;
      line-height: 1.5;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }
    
    .feature-list {
      text-align: left;
      max-width: 300px;
    }
    
    .feature-item {
      display: flex;
      align-items: center;
      margin-bottom: 0.8rem;
      font-size: 0.95rem;
      animation: fadeInUp 0.8s ease-out;
      animation-fill-mode: both;
      background: rgba(255, 255, 255, 0.1);
      padding: 0.5rem;
      border-radius: 20px;
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
    }
    
    .feature-item:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateX(5px);
    }
    
    .feature-item:nth-child(1) { animation-delay: 0.1s; }
    .feature-item:nth-child(2) { animation-delay: 0.2s; }
    .feature-item:nth-child(3) { animation-delay: 0.3s; }
    .feature-item:nth-child(4) { animation-delay: 0.4s; }
    .feature-item:nth-child(5) { animation-delay: 0.5s; }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .feature-item i {
      margin-right: 0.5rem;
      width: 20px;
    }
    
    .bg-gradient-primary {
      background: linear-gradient(135deg, #1e88e5 0%, #3f51b5 100%);
      position: relative;
    }
    
    .bg-gradient-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      pointer-events: none;
    }
    
    .input-group-text {
      background-color: #f8f9fc;
      border-color: #d1d3e2;
      color: #5a5c69;
    }
    
    .form-control-user {
      border-left: none;
    }
    
    .input-group .form-control:not(:last-child) {
      border-top-right-radius: 10rem;
      border-bottom-right-radius: 10rem;
    }
    
    .btn-user {
      font-size: 0.8rem;
      border-radius: 10rem;
      padding: 0.75rem 1rem;
      background: linear-gradient(135deg, #1e88e5 0%, #3f51b5 100%);
      border: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(30, 136, 229, 0.3);
      position: relative;
      overflow: hidden;
    }
    
    .btn-user::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    
    .btn-user:hover::before {
      left: 100%;
    }
    
    .btn-user:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(30, 136, 229, 0.5);
    }
    
    .developer-credit {
      background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
      padding: 8px 15px;
      border-radius: 20px;
      display: inline-block;
      margin-top: 10px;
      border: 1px solid #90caf9;
      color: #1976d2 !important;
      font-weight: 500;
      animation: creditGlow 3s ease-in-out infinite alternate;
    }
    
    @keyframes creditGlow {
      from { box-shadow: 0 2px 5px rgba(25, 118, 210, 0.2); }
      to { box-shadow: 0 4px 15px rgba(25, 118, 210, 0.4); }
    }
    
    .developer-credit strong {
      background: linear-gradient(45deg, #1976d2, #42a5f5);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .developer-credit .fa-heart {
      animation: heartbeat 1.5s ease-in-out infinite;
    }
    
    @keyframes heartbeat {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.2); }
    }
    
    .developer-credit-left {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      padding: 10px 20px;
      border-radius: 25px;
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: white;
      font-size: 0.85rem;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
      animation: fadeInUp 2s ease-out 1s both;
    }
    
    .developer-credit-left strong {
      background: linear-gradient(45deg, #fff, #e1f5fe);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 600;
    }
    
    .developer-credit-left i {
      margin-right: 5px;
      color: #81d4fa;
    }
  </style>

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image">
                <div class="login-content">
                  <div class="login-icon">
                    <i class="fas fa-rocket"></i>
                  </div>
                  <h2 class="login-title">ABM Ventas ⚡</h2>
                  <p class="login-subtitle">Sistema completo de gestión empresarial 🌟</p>
                  
                  <div class="feature-list">
                    <div class="feature-item">
                      <i class="fas fa-users"></i>
                      <span>Gestión de clientes 👥</span>
                    </div>
                    <div class="feature-item">
                      <i class="fas fa-box"></i>
                      <span>Control de inventario 📦</span>
                    </div>
                    <div class="feature-item">
                      <i class="fas fa-chart-bar"></i>
                      <span>Reportes en tiempo real 📊</span>
                    </div>
                    <div class="feature-item">
                      <i class="fas fa-mobile-alt"></i>
                      <span>Pagos Nequi integrados 💳</span>
                    </div>
                    <div class="feature-item">
                      <i class="fas fa-shield-alt"></i>
                      <span>Sistema súper seguro 🛡️</span>
                    </div>
                  </div>
                  
                  <!-- Crédito del desarrollador -->
                  <div class="developer-credit-left">
                    <i class="fas fa-laptop-code"></i> Desarrollado por <strong>Camilo Rincón</strong>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <div class="mb-4">
                      <i class="fas fa-user-circle fa-3x text-primary"></i>
                    </div>
                    <h1 class="h4 text-gray-900 mb-2">¡Bienvenido de vuelta!</h1>
                    <p class="text-muted mb-4">Ingresa tus credenciales para continuar</p>
                  </div>
                  <form action="" method="POST" class="user">
				  <?php if(isset($msg)): ?>
				  	<div class="alert alert-danger" role="alert">
						<?php echo $msg; ?>
					</div>
				  <?php endif; ?>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control form-control-user" id="txtUsuario" name="txtUsuario" placeholder="Nombre de usuario" value="ntarche" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control form-control-user" id="txtClave" name="txtClave" placeholder="Contraseña" value="password" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Recordarme</label>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.php">
                      <i class="fas fa-question-circle"></i> ¿Olvidaste tu contraseña?
                    </a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="crear-cuenta.html">
                      <i class="fas fa-user-plus"></i> ¡Crear una cuenta nueva!
                    </a>
                  </div>
                  
                  <!-- Información adicional -->
                  <div class="text-center mt-4">
                    <small class="text-muted">
                      <i class="fas fa-shield-alt"></i> Conexión segura SSL<br>
                      Sistema ABM Ventas v2.0<br>
                      <div class="mt-2 developer-credit">
                        <i class="fas fa-code"></i> Creado por <strong>Camilo Rincón</strong> 
                        <i class="fas fa-heart text-danger"></i>
                      </div>
                    </small>
                  </div>
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

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
