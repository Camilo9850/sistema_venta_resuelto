<?php
include_once "config.php";
include_once "entidades/producto.php";

$pg = "Generar Imágenes";
$mensaje = "";
$error = "";

if ($_POST) {
    try {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        // Imágenes de ejemplo para usar
        $imagenes_ejemplo = [
            '2020081708082052.jpg',
            '2020081708082555.jpeg', 
            '2020081708085956.png',
            '2020081808083521.jpg',
            '2020122607121152.PNG',
            '2021022605021631.jpg',
            '2021040205041138.png',
            '2021040205044637.png',
            '2021051310055753.png',
            '2025071810074136.png'
        ];
        
        // Buscar productos sin imagen
        $sql = "SELECT idproducto, nombre FROM productos WHERE imagen IS NULL OR imagen = '' OR imagen = 'default.jpg'";
        $resultado = $mysqli->query($sql);
        
        $contador = 0;
        while ($fila = $resultado->fetch_assoc()) {
            $imagen_asignada = $imagenes_ejemplo[$contador % count($imagenes_ejemplo)];
            $sql_update = "UPDATE productos SET imagen = '$imagen_asignada' WHERE idproducto = " . $fila['idproducto'];
            $mysqli->query($sql_update);
            $contador++;
        }
        
        $mensaje = "¡Se asignaron imágenes a $contador productos!";
        $mysqli->close();
        
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

include_once("header.php"); 
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">🖼️ Asignar Imágenes a Productos</h1>
    
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Asignar Imágenes Existentes</h6>
        </div>
        <div class="card-body">
            <p>Este proceso asignará automáticamente las imágenes existentes en la carpeta <code>files/</code> a los productos que no tienen imagen.</p>
            
            <form method="POST">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-images"></i> Asignar Imágenes
                </button>
                <a href="producto-listado.php" class="btn btn-secondary ml-2">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
            </form>
        </div>
    </div>
</div>

<?php include_once("footer.php"); ?>
