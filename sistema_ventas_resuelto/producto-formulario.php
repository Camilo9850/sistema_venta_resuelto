<?php

include_once "config.php";
include_once "entidades/producto.php";
include_once "entidades/tipoproducto.php";

$pg = "Edición de producto";

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);

// Variables para mensajes
$msg = [];

if ($_POST) {
    if (isset($_POST["btnGuardar"])) {
        $nombreImagen = "";
        //Almacenamos la imagen en el servidor
        if ($_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
            $nombreRandom = date("Ymdhmsi");
            $archivoTmp = $_FILES["imagen"]["tmp_name"];
            $nombreArchivo = $_FILES["imagen"]["name"];
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nombreImagen = "$nombreRandom.$extension";
            move_uploaded_file($archivoTmp, "files/$nombreImagen");
        }

        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            $productoAnt = new Producto();
            $productoAnt->idproducto = $_GET["id"];
            $productoAnt->obtenerPorId();
            $imagenAnterior = $productoAnt->imagen;

            //Si es una actualizacion y se sube una imagen, elimina la anterior
            if ($_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
                if (!$imagenAnterior != "") {
                    if(file_exists("files/$imagenAnterior"))
                        unlink("files/$imagenAnterior");
                }
            } else {
                //Si no viene ninguna imagen, setea como imagen la que habia previamente
                $nombreImagen = $imagenAnterior;
            }

            $producto->imagen = $nombreImagen;
            //Actualizo un producto existente
            $resultado = $producto->actualizar();
            if ($resultado['exito']) {
                $msg["texto"] = $resultado['mensaje'];
                $msg["codigo"] = "alert-success";
            } else {
                $msg["texto"] = "Error al actualizar el producto";
                $msg["codigo"] = "alert-danger";
            }
        } else {
            //Es nuevo
            $producto->imagen = $nombreImagen;
            $resultado = $producto->insertar();
            if ($resultado['exito']) {
                $msg["texto"] = $resultado['mensaje'];
                $msg["codigo"] = "alert-success";
                // Actualizar el ID para que se muestre como "editar" después de guardar
                $producto->idproducto = $resultado['id'];
                $_GET["id"] = $resultado['id'];
            } else {
                $msg["texto"] = "Error al guardar el producto";
                $msg["codigo"] = "alert-danger";
            }
        }
    } else if (isset($_POST["btnBorrar"])) {
        // Asegurarse de que tenemos el ID del producto
        $id = isset($_POST["id"]) ? $_POST["id"] : (isset($_GET["id"]) ? $_GET["id"] : null);
        if ($id && $id > 0) {
            $producto->idproducto = $id;
            $resultado = $producto->eliminar();
            
            if ($resultado['exito']) {
                // Eliminación exitosa, redirigir a la lista
                header("Location: producto-listado.php?msg=" . urlencode($resultado['mensaje']) . "&tipo=success");
                exit;
            } else {
                // Error en la eliminación, mostrar mensaje
                $msg["texto"] = $resultado['mensaje'];
                $msg["codigo"] = "alert-danger";
            }
        } else {
            $msg["texto"] = "ID de producto no válido";
            $msg["codigo"] = "alert-danger";
        }
    }
}
if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $producto->obtenerPorId();
}

$tipoProducto = new Tipoproducto();
$aTipoProductos = $tipoProducto->obtenerTodos();

include_once "header.php";
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Productos</h1>
          
          <?php if(isset($msg) && !empty($msg)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert <?php echo $msg["codigo"]; ?> alert-dismissible fade show" role="alert">
                        <strong>
                            <?php if($msg["codigo"] == "alert-success"): ?>
                                ✅ Éxito:
                            <?php else: ?>
                                ❌ Error:
                            <?php endif; ?>
                        </strong>
                        <?php echo $msg["texto"]; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
          <?php endif; ?>
          
           <div class="row">
                <div class="col-12 mb-3">
                    <a href="producto-listado.php" class="btn btn-primary mr-2">📋 Listado</a>
                    <a href="producto-formulario.php" class="btn btn-success mr-2">➕ Nuevo</a>
                </div>
            </div>
            
            <form method="post" enctype="multipart/form-data">
            <?php if (isset($_GET["id"]) && $_GET["id"] > 0): ?>
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
            <?php endif; ?>
            <div class="row">
                <div class="col-6 form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text" required="" class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $producto->nombre; ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtNombre">Tipo de producto:</label>
                    <select name="lstTipoProducto" id="lstTipoProducto" class="form-control selectpicker" data-live-search="true" required>
                        <option value="" disabled selected>Seleccionar</option>
                        <?php foreach ($aTipoProductos as $tipo): ?>
                            <?php if ($tipo->idtipoproducto == $producto->fk_idtipoproducto): ?>
                                <option selected value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                            <?php else: ?>
                                <option value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
                            <?php endif;?>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="col-6 form-group">
                    <label for="txtCantidad">Cantidad:</label>
                    <input type="number" required="" class="form-control" name="txtCantidad" id="txtCantidad" value="<?php echo $producto->cantidad; ?>">
                </div>
                <div class="col-6 form-group">
                    <label for="txtPrecio">Precio:</label>
                    <input type="text" class="form-control" name="txtPrecio" id="txtPrecio" value="<?php echo $producto->precio; ?>">
                </div>
                <div class="col-12 form-group">
                    <label for="txtCorreo">Descripción:</label>
                    <textarea type="text" name="txtDescripcion" id="txtDescripcion"><?php echo $producto->descripcion; ?></textarea>
                </div>
                <div class="col-6 form-group">
                    <label for="fileImagen">Imagen:</label>
                    <input type="file" class="form-control-file" name="imagen" id="imagen" accept="image/*">
                    <?php if ($producto->imagen && file_exists("files/" . $producto->imagen)): ?>
                        <div class="mt-2">
                            <img src="files/<?php echo $producto->imagen; ?>" class="img-thumbnail" style="max-width: 200px;">
                            <p class="text-muted">Imagen actual: <?php echo $producto->imagen; ?></p>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mt-2">No hay imagen asignada</p>
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
                                onclick="return confirmarEliminacion()">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    <?php endif; ?>
                    <a href="producto-listado.php" class="btn btn-secondary">
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
        ClassicEditor
            .create( document.querySelector( '#txtDescripcion' ) )
            .catch( error => {
            console.error( error );
            } );
            
        // Función mejorada para confirmar eliminación
        function confirmarEliminacion() {
            const nombreProducto = document.getElementById('txtNombre').value || 'este producto';
            
            return confirm(
                `⚠️ ATENCIÓN: ¿Está seguro que desea eliminar "${nombreProducto}"?\n\n` +
                `IMPORTANTE: Si este producto tiene ventas asociadas, NO podrá ser eliminado.\n` +
                `Primero deberá eliminar todas las ventas relacionadas con este producto.\n\n` +
                `¿Desea continuar?`
            );
        }

        // Mejorar la visualización de la imagen
        document.addEventListener('DOMContentLoaded', function() {
            const inputImagen = document.getElementById('txtImagen');
            const previewImagen = document.getElementById('preview-imagen');
            
            if (inputImagen && previewImagen) {
                inputImagen.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImagen.src = e.target.result;
                            previewImagen.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
        </script>
<?php include_once "footer.php";?>