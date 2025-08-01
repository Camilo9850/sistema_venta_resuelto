<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM Sistema de Ventas</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .welcome-card {
            max-width: 600px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin: 0 auto 15px;
        }
        .countdown {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
        }
        .btn-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 15px 30px;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
            color: white;
        }
    </style>
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="welcome-card">
            <div class="card-body p-5 text-center">
                <!-- Logo/Icon -->
                <div class="mb-4">
                    <div class="feature-icon mx-auto mb-3">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h1 class="h2 text-gray-900 mb-2">🚀 ABM Sistema de Ventas</h1>
                    <p class="text-muted">Plataforma completa de gestión empresarial</p>
                </div>

                <!-- Características principales -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h6>Gestión de Clientes</h6>
                        <small class="text-muted">Control completo de tu cartera</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="feature-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <h6>Inventario</h6>
                        <small class="text-muted">Productos y stock en tiempo real</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h6>Reportes</h6>
                        <small class="text-muted">Análisis y estadísticas avanzadas</small>
                    </div>
                </div>

                <!-- Mensaje de redirección -->
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i> 
                    Accediendo al dashboard en <span class="countdown" id="countdown">3</span> segundos...
                </div>

                <!-- Botones de acción -->
                <div class="d-grid gap-2 d-md-flex justify-content-center">
                    <a href="index.php" class="btn btn-dashboard me-md-2">
                        <i class="fas fa-tachometer-alt"></i> Ir al Dashboard
                    </a>
                    <a href="login.php" class="btn btn-outline-secondary">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                </div>

                <!-- Enlaces adicionales -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row">
                        <div class="col-6">
                            <a href="crear-cuenta.html" class="text-decoration-none">
                                <i class="fas fa-user-plus text-primary"></i> Crear Cuenta
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="dashboard_help.php" class="text-decoration-none">
                                <i class="fas fa-question-circle text-info"></i> Ayuda
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer info -->
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt"></i> Sistema seguro y confiable<br>
                        Versión 2.0 - Actualizado <?php echo date('d/m/Y'); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Countdown y redirección automática
        let timeLeft = 3;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(function() {
            timeLeft--;
            countdownElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = 'index.php';
            }
        }, 1000);

        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.welcome-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(function() {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });

        // Efectos hover para los iconos
        document.querySelectorAll('.feature-icon').forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(5deg)';
                this.style.transition = 'all 0.3s ease';
            });
            
            icon.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });
        });
    </script>
</body>
</html>
