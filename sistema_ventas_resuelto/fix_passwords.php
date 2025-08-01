<?php
include_once "config.php";

// Conectar a la base de datos
$mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);

if ($mysqli->connect_error) {
    die('Error de conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

echo "<h2>Corrección de contraseñas en la base de datos</h2>";

// Obtener todos los usuarios
$sql = "SELECT idusuario, usuario, clave FROM usuarios";
$resultado = $mysqli->query($sql);

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $id = $fila['idusuario'];
        $usuario = $fila['usuario'];
        $clave_actual = $fila['clave'];
        
        // Verificar si la contraseña ya está hasheada
        if (strlen($clave_actual) < 60 || !str_starts_with($clave_actual, '$2y$')) {
            // La contraseña no está hasheada, necesita ser convertida
            $nueva_clave_hash = password_hash($clave_actual, PASSWORD_DEFAULT);
            
            $sql_update = "UPDATE usuarios SET clave = '$nueva_clave_hash' WHERE idusuario = $id";
            
            if ($mysqli->query($sql_update)) {
                echo "✓ Usuario '$usuario': Contraseña actualizada de '$clave_actual' a hash<br>";
            } else {
                echo "✗ Error actualizando usuario '$usuario': " . $mysqli->error . "<br>";
            }
        } else {
            echo "○ Usuario '$usuario': Ya tiene hash válido<br>";
        }
    }
    
    echo "<h3>Proceso completado!</h3>";
    echo "<a href='login.php'>Ir al login</a><br>";
    echo "<a href='debug_login.php'>Volver a debug</a>";
    
} else {
    echo "Error en la consulta: " . $mysqli->error;
}

$mysqli->close();
?>
