<?php
include_once "config.php";

// Conectar a la base de datos
$mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

if ($mysqli->connect_error) {
    die('Error de conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

echo "<h2>Usuarios en la base de datos:</h2>";

$sql = "SELECT idusuario, usuario, clave, nombre, apellido, correo FROM usuarios";
$resultado = $mysqli->query($sql);

if ($resultado) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Usuario</th><th>Clave (Hash)</th><th>Nombre</th><th>Apellido</th><th>Correo</th></tr>";
    
    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $fila['idusuario'] . "</td>";
        echo "<td>" . $fila['usuario'] . "</td>";
        echo "<td>" . substr($fila['clave'], 0, 20) . "...</td>";
        echo "<td>" . $fila['nombre'] . "</td>";
        echo "<td>" . $fila['apellido'] . "</td>";
        echo "<td>" . $fila['correo'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error en la consulta: " . $mysqli->error;
}

echo "<h3>Prueba de login:</h3>";
echo "<form method='post'>";
echo "Usuario: <input type='text' name='test_usuario' value='miguel'><br><br>";
echo "Contraseña: <input type='text' name='test_clave' value='1234'><br><br>";
echo "<input type='submit' name='test_login' value='Probar Login'>";
echo "</form>";

echo "<h3>Prueba con email:</h3>";
echo "<form method='post'>";
echo "Usuario/Email: <input type='text' name='test_usuario' value='miguel@gmail.com'><br><br>";
echo "Contraseña: <input type='text' name='test_clave' value='1234'><br><br>";
echo "<input type='submit' name='test_login' value='Probar Login con Email'>";
echo "</form>";

echo "<h3>Usuarios específicos para probar:</h3>";
echo "<ul>";
echo "<li>miguel / 1234 (recién creado)</li>";
echo "<li>miguel@gmail.com / 1234 (por email)</li>";
echo "<li>admin / 1234</li>";
echo "<li>camilo / (contraseña del registro)</li>";
echo "<li>erika / (contraseña del registro)</li>";
echo "</ul>";

if (isset($_POST['test_login'])) {
    include_once "entidades/usuario.php";
    
    $test_usuario = $_POST['test_usuario'];
    $test_clave = $_POST['test_clave'];
    
    echo "<h4>Resultado de la prueba:</h4>";
    echo "Usuario/Email a buscar: " . $test_usuario . "<br>";
    echo "Contraseña ingresada: " . $test_clave . "<br>";
    
    // Probar con la nueva función
    $entidadUsuario = new Usuario();
    $encontrado = $entidadUsuario->obtenerPorUsuarioOCorreo($test_usuario);
    
    if ($encontrado) {
        echo "✓ Usuario encontrado: " . $entidadUsuario->usuario . "<br>";
        echo "Nombre: " . $entidadUsuario->nombre . "<br>";
        echo "Correo: " . $entidadUsuario->correo . "<br>";
        echo "Hash en BD: " . substr($entidadUsuario->clave, 0, 30) . "...<br>";
        echo "Tipo de hash: " . (strlen($entidadUsuario->clave) > 10 ? 'Parece hash válido' : 'Posible texto plano') . "<br>";
        
        // Verificar si la contraseña es correcta
        if ($entidadUsuario->verificarClave($test_clave, $entidadUsuario->clave)) {
            echo "<span style='color: green; font-weight: bold;'>✓ LOGIN EXITOSO - Contraseña CORRECTA</span><br>";
        } else {
            echo "<span style='color: red;'>✗ Contraseña INCORRECTA con password_verify()</span><br>";
            
            // Verificar si la contraseña está sin hash (texto plano)
            if ($test_clave === $entidadUsuario->clave) {
                echo "<span style='color: orange;'>⚠️ La contraseña coincide en texto plano</span><br>";
                echo "<span style='color: blue;'>💡 Necesita actualizar el hash de esta contraseña</span><br>";
            }
        }
    } else {
        echo "<span style='color: red;'>✗ Usuario/Email NO encontrado</span><br>";
    }
}

$mysqli->close();
?>
