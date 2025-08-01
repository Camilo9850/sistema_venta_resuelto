<?php

include_once "config.php";
include_once "entidades/usuario.php";
$pg = "Listado de usuarios";

// Manejar eliminación de usuario
if (isset($_GET['eliminar']) && isset($_GET['id'])) {
    $usuarioEliminar = new Usuario();
    $usuarioEliminar->idusuario = $_GET['id'];
    $usuarioEliminar->eliminar();
    header("Location: /mi_proyecto/php-1/sistema_ventas_resuelto/usuario-listado.php");
    exit();
}

$entidadUsuario = new Usuario();
$aUsuarios = $entidadUsuario->obtenerTodos();

include_once "header.php";
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Listado de usuarios</h1>
          <div class="row">
                <div class="col-12 mb-3">
                    <a href="/mi_proyecto/php-1/sistema_ventas_resuelto/usuario-formulario.php" class="btn btn-primary mr-2">➕ Nuevo Usuario</a>
                    <a href="/mi_proyecto/php-1/sistema_ventas_resuelto/test_urls_edicion.php" class="btn btn-info mr-2">🔍 Test URLs</a>
                    <span class="text-muted">Total de usuarios: <?php echo count($aUsuarios); ?></span>
                </div>
            </div>
          <table class="table table-hover border">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($aUsuarios as $usuario): ?>
              <tr>
                  <td><?php echo $usuario->idusuario; ?></td>
                  <td>
                      <strong><?php echo $usuario->usuario; ?></strong>
                      <br><small class="text-muted">Login</small>
                  </td>
                  <td><?php echo $usuario->nombre . " " . $usuario->apellido; ?></td>
                  <td>
                      <a href="mailto:<?php echo $usuario->correo; ?>" class="text-decoration-none">
                          <?php echo $usuario->correo; ?>
                      </a>
                  </td>
                  <td style="width: 150px;">
                      <a href="/mi_proyecto/php-1/sistema_ventas_resuelto/usuario-formulario.php?id=<?php echo $usuario->idusuario; ?>" 
                         class="btn btn-sm btn-outline-primary mr-1" title="Editar usuario">
                         <i class="fas fa-edit"></i>
                      </a>
                      <a href="/mi_proyecto/php-1/sistema_ventas_resuelto/usuario-listado.php?eliminar=1&id=<?php echo $usuario->idusuario; ?>" 
                         class="btn btn-sm btn-outline-danger" 
                         title="Eliminar usuario"
                         onclick="return confirm('¿Está seguro de que desea eliminar este usuario?')">
                         <i class="fas fa-trash"></i>
                      </a>
                  </td>
              </tr>
            <?php endforeach;?>
          </table>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
<?php include_once "footer.php";?>