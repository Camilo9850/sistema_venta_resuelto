<?php
include_once "config.php";
include_once "entidades/producto.php";

$pg = "Generar Imágenes de Productos";

$mensaje = "";
$error = "";

if ($_POST) {
    try {
        // Conectar a la base de datos
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        
        // Imágenes predefinidas para productos tecnológicos
        $imagenes_productos = [
            'iphone15.jpg' => 'https://picsum.photos/300/200?random=1',
            'samsung_s24.jpg' => 'https://picsum.photos/300/200?random=2',
            'macbook_pro.jpg' => 'https://picsum.photos/300/200?random=3',
            'dell_xps13.jpg' => 'https://picsum.photos/300/200?random=4',
            'sony_headphones.jpg' => 'https://picsum.photos/300/200?random=5',
            'nike_shirt.jpg' => 'https://picsum.photos/300/200?random=6',
            'levis_jeans.jpg' => 'https://picsum.photos/300/200?random=7',
            'sofa_modular.jpg' => 'https://picsum.photos/300/200?random=8',
            'samsung_tv55.jpg' => 'https://picsum.photos/300/200?random=9',
            'trek_bike.jpg' => 'https://picsum.photos/300/200?random=10'
        ];
        
        $productos_actualizados = 0;
        $imagenes_descargadas = 0;
        
        // Descargar y guardar imágenes
        foreach ($imagenes_productos as $nombre_imagen => $url) {
            $ruta_destino = "files/" . $nombre_imagen;
            
            // Solo descargar si no existe
            if (!file_exists($ruta_destino)) {
                $imagen_data = @file_get_contents($url);
                if ($imagen_data !== false) {
                    file_put_contents($ruta_destino, $imagen_data);
                    $imagenes_descargadas++;
                }
            }
        }
        
        // Actualizar productos que no tienen imagen
        $sql = "SELECT idproducto, nombre, imagen FROM productos WHERE imagen IS NULL OR imagen = '' OR imagen = 'default.jpg'";
        $resultado = $mysqli->query($sql);
        
        if ($resultado) {
            $contador = 0;
            $nombres_imagenes = array_keys($imagenes_productos);
            
            while ($fila = $resultado->fetch_assoc()) {
                $idproducto = $fila['idproducto'];
                $nombre_imagen = $nombres_imagenes[$contador % count($nombres_imagenes)];
                
                // Actualizar base de datos
                $sql_update = "UPDATE productos SET imagen = '$nombre_imagen' WHERE idproducto = $idproducto";
                if ($mysqli->query($sql_update)) {
                    $productos_actualizados++;
                }
                $contador++;
            }
        }
        
        $mensaje = "¡Proceso completado! Se descargaron $imagenes_descargadas nuevas imágenes y se actualizaron $productos_actualizados productos.";
        $mysqli->close();
        
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

include_once("header.php");
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">🖼️ Generador de Imágenes de Productos</h1>
    
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $mensaje; ?>
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Descargar Imágenes Automáticamente</h6>
                </div>
                <div class="card-body">
                    <p class="mb-4">
                        Este generador descargará imágenes aleatorias de alta calidad desde Picsum 
                        y las asignará automáticamente a todos los productos que no tengan imagen.
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>¿Qué hace este proceso?</strong>
                        <ul class="mb-0 mt-2">
                            <li>Descarga 10 imágenes aleatorias de 300x200 píxeles</li>
                            <li>Las guarda en la carpeta <code>files/</code></li>
                            <li>Asigna las imágenes a productos sin imagen</li>
                            <li>Actualiza la base de datos automáticamente</li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-download"></i> Descargar y Asignar Imágenes
                        </button>
                        <a href="producto-listado.php" class="btn btn-secondary btn-lg ml-2">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Vista Previa</h6>
                </div>
                <div class="card-body text-center">
                    <img src="https://picsum.photos/200/133?random=demo" 
                         class="img-thumbnail mb-3" style="max-width: 200px;">
                    <p class="text-muted small">
                        Ejemplo de imagen que se descargará
                    </p>
                    <small class="text-muted">
                        Imágenes cortesía de <strong>Picsum Photos</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("footer.php"); ?>
