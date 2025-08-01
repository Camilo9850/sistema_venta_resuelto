
<?php

include_once "config.php";
include_once "entidades/usuario.php";

$pg = "Gestión de Usuario";

// Debug: Mostrar información de la URL si hay parámetros de debug
if (isset($_GET['debug']) || isset($_REQUEST['debug'])) {
    echo "<div style='background: #f8f9fa; padding: 10px; margin: 10px; border: 1px solid #ccc;'>";
    echo "<h4>Debug Info:</h4>";
    echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
    echo "QUERY_STRING: " . $_SERVER['QUERY_STRING'] . "<br>";
    echo "GET ID: " . (isset($_GET['id']) ? $_GET['id'] : 'No definido') . "<br>";
    echo "REQUEST ID: " . (isset($_REQUEST['id']) ? $_REQUEST['id'] : 'No definido') . "<br>";
    echo "</div>";
}

$usuario = new Usuario();
$usuario->cargarFormulario($_REQUEST);
$contraseña_generada = ""; // Para mostrar la contraseña cuando se crea un usuario

if ($_POST) {
    $usuarioAux = new Usuario();
    if ($usuarioAux->obtenerPorUsuario($usuario->usuario, $usuario->idusuario)) {
        //Ya existe un usuario con ese nombre de usuario mostrar mensaje
        $msg = "El usuario ya existe";
    } else if ($usuarioAux->obtenerPorCorreo($usuario->correo, $usuario->idusuario)) {
        //Ya existe un usuario con ese correo mostrar mensaje
        $msg = "El correo ya existe";
    } else {

        if (isset($_POST["btnGuardar"])) {
            if (isset($_GET["id"]) && $_GET["id"] > 0) {
                //Actualizo un usuario existente
                $usuario->actualizar();
                header("Location: /mi_proyecto/php-1/sistema_ventas_resuelto/usuario-listado.php");
            } else {
                //Es nuevo - generar contraseña aleatoria si no se especifica
                if (empty($_POST["txtClave"])) {
                    $contraseña_generada = generarContraseñaAleatoria();
                    $_POST["txtClave"] = $contraseña_generada;
                    $usuario->cargarFormulario($_POST); // Recargar con la nueva contraseña
                } else {
                    $contraseña_generada = $_POST["txtClave"];
                }
                
                $usuario->insertar();
                $msg_success = "Usuario creado exitosamente";
                $mostrar_contraseña = true;
            }
        } else if (isset($_POST["btnBorrar"])) {
            $usuario->eliminar();
            header("Location: /mi_proyecto/php-1/sistema_ventas_resuelto/usuario-listado.php");
        }
    }
}

if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $usuario->idusuario = $_GET["id"];
    $usuario->obtenerPorId();
} else if (isset($_REQUEST["id"]) && $_REQUEST["id"] > 0) {
    // Manejar también $_REQUEST por si viene de una URL malformada
    $usuario->idusuario = $_REQUEST["id"];
    $usuario->obtenerPorId();
}

// Función para generar contraseña aleatoria
function generarContraseñaAleatoria($longitud = 8) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $contraseña = '';
    for ($i = 0; $i < $longitud; $i++) {
        $contraseña .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $contraseña;
}

include_once "header.php";
?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Usuario</h1>
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="/mi_proyecto/php-1/sistema_ventas_resuelto/usuario-listado.php" class="btn btn-primary mr-2">📋 Listado</a>
                    <a href="/mi_proyecto/php-1/sistema_ventas_resuelto/usuario-formulario.php" class="btn btn-success mr-2">➕ Nuevo</a>
                </div>
            </div>
            
            <?php if (isset($msg)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $msg; ?>
                        </div>
                    </div>
                </div>
			<?php endif;?>
			
			<?php if (isset($msg_success) && isset($mostrar_contraseña)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success" role="alert">
                            <h4><i class="fas fa-check-circle"></i> <?php echo $msg_success; ?></h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>📝 Datos del usuario creado:</strong><br>
                                    <strong>Usuario:</strong> <?php echo $usuario->usuario; ?><br>
                                    <strong>Nombre:</strong> <?php echo $usuario->nombre . " " . $usuario->apellido; ?><br>
                                    <strong>Email:</strong> <?php echo $usuario->correo; ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-warning text-dark">
                                        <div class="card-body">
                                            <h5><i class="fas fa-key"></i> CONTRASEÑA GENERADA:</h5>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-lg font-weight-bold" 
                                                       value="<?php echo $contraseña_generada; ?>" 
                                                       id="contraseñaMostrar" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-dark" type="button" 
                                                            onclick="copiarContraseña()" title="Copiar contraseña">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small><strong>⚠️ Importante:</strong> Guarda esta contraseña, no se mostrará nuevamente.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endif;?>
			
            <form method="post">
            <?php if (isset($_GET["id"]) && $_GET["id"] > 0): ?>
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
            <?php endif; ?>
            
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtNombre">Usuario:</label>
                    <input type="text" required class="form-control" name="txtUsuario" id="txtUsuario" value="<?php echo $usuario->usuario ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtCuit">Nombre:</label>
                    <input type="text" required class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $usuario->nombre ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtCuit">Apellido:</label>
                    <input type="text" required class="form-control" name="txtApellido" id="txtApellido" value="<?php echo $usuario->apellido ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtCorreo">Correo:</label>
                    <input type="email" class="form-control" name="txtCorreo" id="txtCorreo" required value="<?php echo $usuario->correo ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtClave">Clave:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="txtClave" id="txtClave" value="">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                            <button class="btn btn-outline-primary" type="button" onclick="generarContraseña()">
                                <i class="fas fa-random"></i> Generar
                            </button>
                        </div>
                    </div>
                    <?php if (isset($_GET["id"]) && $_GET["id"] > 0): ?>
                        <small class="text-muted">Completar únicamente para cambiar la clave</small>
                    <?php else: ?>
                        <small class="text-muted">Dejar vacío para generar automáticamente</small>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <?php if (isset($_GET["id"]) && $_GET["id"] > 0): ?>
                        <button type="submit" class="btn btn-danger mr-2" id="btnBorrar" name="btnBorrar" 
                                onclick="return confirm('¿Está seguro que desea eliminar este usuario?')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    <?php endif; ?>
                    <a href="/mi_proyecto/php-1/sistema_ventas_resuelto/usuario-listado.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </a>
                </div>
            </div>
            </form>
            
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
      
      <script>
      function togglePassword() {
          const passwordField = document.getElementById('txtClave');
          const toggleIcon = document.getElementById('toggleIcon');
          
          if (passwordField.type === 'password') {
              passwordField.type = 'text';
              toggleIcon.className = 'fas fa-eye-slash';
          } else {
              passwordField.type = 'password';
              toggleIcon.className = 'fas fa-eye';
          }
      }
      
      function generarContraseña() {
          const caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
          let contraseña = '';
          for (let i = 0; i < 8; i++) {
              contraseña += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
          }
          document.getElementById('txtClave').value = contraseña;
          document.getElementById('txtClave').type = 'text';
          document.getElementById('toggleIcon').className = 'fas fa-eye-slash';
      }
      
      function copiarContraseña() {
          const contraseñaField = document.getElementById('contraseñaMostrar');
          contraseñaField.select();
          contraseñaField.setSelectionRange(0, 99999);
          document.execCommand('copy');
          
          // Mostrar mensaje temporal
          const button = event.target.closest('button');
          const originalHTML = button.innerHTML;
          button.innerHTML = '<i class="fas fa-check"></i> Copiado';
          button.classList.remove('btn-outline-dark');
          button.classList.add('btn-success');
          
          setTimeout(() => {
              button.innerHTML = originalHTML;
              button.classList.remove('btn-success');
              button.classList.add('btn-outline-dark');
          }, 2000);
      }
      </script>
<?php include_once "footer.php";?>