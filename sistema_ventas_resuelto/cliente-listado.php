<?php

include_once "config.php";
include_once "entidades/cliente.php";
$pg = "Listado de clientes";

$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

include_once("header.php"); 
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Listado de clientes</h1>
          <div class="row">
                <div class="col-12 mb-3">
                    <a href="cliente-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                </div>
            </div>
          <table class="table table-hover border">
            <tr>
                <th>Documento</th>
                <th>Nombre Completo</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($aClientes as $cliente): ?>
              <tr>
                  <td><?php echo $cliente->documento; ?></td>
                  <td><?php echo $cliente->nombre . ' ' . $cliente->apellido; ?></td>
                  <td><?php echo $cliente->telefono; ?></td>
                  <td><?php echo $cliente->email; ?></td>
                  <td><?php echo substr($cliente->direccion, 0, 50) . (strlen($cliente->direccion) > 50 ? '...' : ''); ?></td>
                  <td style="width: 110px;">
                      <a href="cliente-formulario.php?id=<?php echo $cliente->idcliente; ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>   
                  </td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
<?php include_once("footer.php"); ?>