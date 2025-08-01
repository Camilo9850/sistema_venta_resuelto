<?php
include_once "config.php";
include_once "entidades/usuario.php";

echo "<h2>Prueba de obtenerPorId</h2>";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo "ID recibido: " . $id . "<br>";
    
    $usuario = new Usuario();
    $usuario->idusuario = $id;
    $usuario->obtenerPorId();
    
    echo "<h3>Datos obtenidos:</h3>";
    echo "ID: " . $usuario->idusuario . "<br>";
    echo "Usuario: " . $usuario->usuario . "<br>";
    echo "Nombre: " . $usuario->nombre . "<br>";
    echo "Apellido: " . $usuario->apellido . "<br>";
    echo "Correo: " . $usuario->correo . "<br>";
    echo "Clave (hash): " . substr($usuario->clave, 0, 20) . "...<br>";
    
    if (!empty($usuario->nombre)) {
        echo "<span style='color: green;'>✓ Usuario cargado correctamente</span><br>";
    } else {
        echo "<span style='color: red;'>✗ No se pudo cargar el usuario</span><br>";
    }
} else {
    echo "No se proporcionó ID<br>";
}

echo "<h3>Enlaces de prueba:</h3>";
echo '<a href="test_usuario.php?id=1">Probar con ID 1</a><br>';
echo '<a href="test_usuario.php?id=2">Probar con ID 2</a><br>';
echo '<a href="test_usuario.php?id=9">Probar con ID 9 (miguel)</a><br>';
?>
