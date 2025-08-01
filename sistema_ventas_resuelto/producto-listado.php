<?php

include_once "config.php";
include_once "entidades/producto.php";
$pg = "Listado de productos";

$producto = new Producto();
$aProductos = $producto->obtenerTodos();

include_once("header.php"); 
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Listado de productos</h1>
          
          <!-- Mostrar mensajes si existen -->
          <?php if (isset($_GET['msg']) && !empty($_GET['msg'])): ?>
              <?php 
                  $tipoMsg = isset($_GET['tipo']) ? $_GET['tipo'] : 'info';
                  $claseAlert = 'alert-info';
                  $icono = 'fas fa-info-circle';
                  
                  if ($tipoMsg == 'success') {
                      $claseAlert = 'alert-success';
                      $icono = 'fas fa-check-circle';
                  } elseif ($tipoMsg == 'error') {
                      $claseAlert = 'alert-danger';
                      $icono = 'fas fa-exclamation-circle';
                  }
              ?>
              <div class="alert <?php echo $claseAlert; ?> alert-dismissible fade show" role="alert">
                  <i class="<?php echo $icono; ?>"></i> 
                  <strong>
                      <?php if($tipoMsg == 'success'): ?>
                          ✅ Éxito:
                      <?php elseif($tipoMsg == 'error'): ?>
                          ❌ Error:
                      <?php else: ?>
                          ℹ️ Información:
                      <?php endif; ?>
                  </strong>
                  <?php echo htmlspecialchars($_GET['msg']); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
          <?php endif; ?>
          
          <div class="row">
                <div class="col-12 mb-3">
                    <a href="producto-formulario.php" class="btn btn-primary mr-2">
                        <i class="fas fa-plus"></i> Nuevo Producto
                    </a>
                    <a href="asignar_imagenes.php" class="btn btn-warning mr-2">
                        <i class="fas fa-images"></i> Asignar Imágenes
                    </a>
                    <small class="text-muted">Asigna imágenes automáticamente a productos sin imagen</small>
                </div>
            </div>
          <table class="table table-hover border">
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($aProductos as $producto): ?>
              <tr>
                  <td style="width: 100px;">
                      <?php if ($producto->imagen && file_exists("files/" . $producto->imagen)): ?>
                          <img src="files/<?php echo $producto->imagen; ?>" class="img-thumbnail" style="max-width: 80px; max-height: 80px;">
                      <?php else: ?>
                          <div class="d-flex flex-column align-items-center">
                              <div style="width: 80px; height: 60px; background: #f8f9fa; border: 2px dashed #dee2e6; display: flex; align-items: center; justify-content: center; border-radius: 4px; margin-bottom: 5px;">
                                  <i class="fas fa-image text-muted"></i>
                              </div>
                              <small class="text-muted">Sin imagen</small>
                          </div>
                      <?php endif; ?>
                  </td>
                  <td><?php echo $producto->nombre; ?></td>
                  <td><?php echo $producto->cantidad; ?></td>
                  <td>$ <?php echo number_format($producto->precio, 2, ",", "."); ?></td>
                  <td style="width: 110px;">
                      <a href="producto-formulario.php?id=<?php echo $producto->idproducto; ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="fas fa-edit"></i></a>   
                  </td>
              </tr>
            <?php endforeach; ?>
          </table>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
<?php include_once("footer.php"); ?>