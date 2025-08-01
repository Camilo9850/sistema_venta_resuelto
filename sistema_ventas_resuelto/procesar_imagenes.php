<?php
// 🎨 GENERADOR DE IMÁGENES REALISTAS PARA PRODUCTOS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

// Función para crear imagen con gradiente y mejor diseño
function crearImagenProducto($nombre, $id, $tipo = 'electrodomestico') {
    $width = 400;
    $height = 300;
    
    // Crear imagen con fondo transparente
    $imagen = imagecreatetruecolor($width, $height);
    
    // Configurar transparencia
    imagealphablending($imagen, false);
    imagesavealpha($imagen, true);
    
    // Colores según el tipo de producto
    $esquemas_color = [
        'electrodomestico' => ['#1e3c72', '#2a5298', '#ffffff'],
        'informatica' => ['#134e5e', '#71b280', '#ffffff'],
        'cocina' => ['#fc4a1a', '#f7b733', '#ffffff'],
        'audio' => ['#8360c3', '#2ebf91', '#ffffff'],
        'default' => ['#667eea', '#764ba2', '#ffffff']
    ];
    
    // Detectar tipo de producto
    $tipo_detectado = 'default';
    if (stripos($nombre, 'impresora') !== false || stripos($nombre, 'teclado') !== false || stripos($nombre, 'mouse') !== false) {
        $tipo_detectado = 'informatica';
    } elseif (stripos($nombre, 'heladera') !== false || stripos($nombre, 'licuadora') !== false || stripos($nombre, 'tostadora') !== false) {
        $tipo_detectado = 'electrodomestico';
    } elseif (stripos($nombre, 'cocina') !== false || stripos($nombre, 'horno') !== false) {
        $tipo_detectado = 'cocina';
    }
    
    $colores = $esquemas_color[$tipo_detectado];
    
    // Convertir hex a RGB
    function hexToRgb($hex) {
        return sscanf($hex, "#%02x%02x%02x");
    }
    
    $rgb1 = hexToRgb($colores[0]);
    $rgb2 = hexToRgb($colores[1]);
    $rgb_text = hexToRgb($colores[2]);
    
    // Crear gradiente vertical
    for ($y = 0; $y < $height; $y++) {
        $ratio = $y / $height;
        $r = $rgb1[0] + ($rgb2[0] - $rgb1[0]) * $ratio;
        $g = $rgb1[1] + ($rgb2[1] - $rgb1[1]) * $ratio;
        $b = $rgb1[2] + ($rgb2[2] - $rgb1[2]) * $ratio;
        
        $color = imagecolorallocate($imagen, $r, $g, $b);
        imageline($imagen, 0, $y, $width, $y, $color);
    }
    
    // Colores para texto y elementos
    $color_texto = imagecolorallocate($imagen, $rgb_text[0], $rgb_text[1], $rgb_text[2]);
    $color_borde = imagecolorallocate($imagen, 255, 255, 255);
    $color_sombra = imagecolorallocate($imagen, 0, 0, 0);
    
    // Dibujar borde redondeado (simulado)
    $grosor_borde = 3;
    for ($i = 0; $i < $grosor_borde; $i++) {
        imagerectangle($imagen, $i, $i, $width-1-$i, $height-1-$i, $color_borde);
    }
    
    // Preparar texto
    $texto_principal = strtoupper($nombre);
    $texto_id = "ID: $id";
    
    // Dividir texto en múltiples líneas si es necesario
    $max_chars_por_linea = 18;
    $lineas = [];
    
    if (strlen($texto_principal) > $max_chars_por_linea) {
        $palabras = explode(' ', $texto_principal);
        $linea_actual = '';
        
        foreach ($palabras as $palabra) {
            if (strlen($linea_actual . ' ' . $palabra) > $max_chars_por_linea) {
                if ($linea_actual) $lineas[] = trim($linea_actual);
                $linea_actual = $palabra;
            } else {
                $linea_actual .= ($linea_actual ? ' ' : '') . $palabra;
            }
        }
        if ($linea_actual) $lineas[] = trim($linea_actual);
    } else {
        $lineas[] = $texto_principal;
    }
    
    // Calcular posición centrada del texto
    $font_size = 5;
    $altura_linea = 20;
    $altura_total_texto = count($lineas) * $altura_linea;
    $y_inicio = ($height - $altura_total_texto) / 2;
    
    // Dibujar sombra del texto (efecto 3D)
    foreach ($lineas as $i => $linea) {
        $ancho_texto = strlen($linea) * imagefontwidth($font_size);
        $x = ($width - $ancho_texto) / 2;
        $y = $y_inicio + ($i * $altura_linea);
        
        // Sombra
        imagestring($imagen, $font_size, $x + 2, $y + 2, $linea, $color_sombra);
        // Texto principal
        imagestring($imagen, $font_size, $x, $y, $linea, $color_texto);
    }
    
    // Agregar ID en la esquina inferior derecha
    $ancho_id = strlen($texto_id) * imagefontwidth(3);
    imagestring($imagen, 3, $width - $ancho_id - 12, $height - 25, $texto_id, $color_texto);
    
    // Agregar icono/símbolo según el tipo de producto
    $simbolos = [
        'impresora' => '🖨️',
        'teclado' => '⌨️',
        'heladera' => '❄️',
        'licuadora' => '🥤',
        'tostadora' => '🍞'
    ];
    
    // Agregar decoración según el tipo
    $decoracion_color = imagecolorallocate($imagen, 255, 255, 255);
    
    // Dibujar círculos decorativos
    for ($i = 0; $i < 3; $i++) {
        $x = 30 + ($i * 40);
        $y = 30;
        imageellipse($imagen, $x, $y, 20, 20, $decoracion_color);
    }
    
    return $imagen;
}

try {
    $pdo = new PDO(
        "mysql:host=" . Config::BBDD_HOST . ":" . Config::BBDD_PORT . ";dbname=" . Config::BBDD_NOMBRE,
        Config::BBDD_USUARIO,
        Config::BBDD_CLAVE,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Obtener productos sin imagen
    $stmt = $pdo->query("SELECT idproducto, nombre, imagen FROM productos WHERE imagen = '' OR imagen IS NULL");
    $productos_sin_imagen = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $imagenes_creadas = 0;
    
    foreach ($productos_sin_imagen as $producto) {
        $id = $producto['idproducto'];
        $nombre = $producto['nombre'];
        
        // Generar nombre de archivo único
        $timestamp = date('Ymdhmsi') . rand(10, 99);
        $nombre_archivo = "producto_{$id}_{$timestamp}.png";
        $ruta_archivo = "files/$nombre_archivo";
        
        // Crear imagen mejorada
        $imagen = crearImagenProducto($nombre, $id);
        
        // Guardar imagen
        if (imagepng($imagen, $ruta_archivo)) {
            // Actualizar base de datos
            $stmt_update = $pdo->prepare("UPDATE productos SET imagen = ? WHERE idproducto = ?");
            $stmt_update->execute([$nombre_archivo, $id]);
            $imagenes_creadas++;
        }
        
        imagedestroy($imagen);
    }
    
    // Redireccionar de vuelta al generador principal con mensaje de éxito
    header("Location: generar_imagenes_productos.php?mensaje=creadas_$imagenes_creadas");
    exit;
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
