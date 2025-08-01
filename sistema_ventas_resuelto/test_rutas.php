<?php
echo "<h2>Prueba de rutas</h2>";
echo "Directorio actual: " . __DIR__ . "<br>";
echo "Archivo actual: " . __FILE__ . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";

echo "<h3>Archivos en el directorio:</h3>";
$files = scandir(__DIR__);
foreach($files as $file) {
    if(strpos($file, 'usuario') !== false) {
        echo "- " . $file . "<br>";
    }
}

echo "<h3>Enlaces de prueba:</h3>";
echo '<a href="usuario-formulario.php">usuario-formulario.php</a><br>';
echo '<a href="usuario-formulario.php?id=1">usuario-formulario.php?id=1</a><br>';
echo '<a href="usuario-listado.php">usuario-listado.php</a><br>';
?>
