<?php
include_once "config.php";
include_once "entidades/usuario.php";

echo "<h2>🔍 Diagnóstico del Sistema de Login</h2>";

try {
    // Verificar conexión a la base de datos
    $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
    
    if ($mysqli->connect_error) {
        echo "<p>❌ Error de conexión: " . $mysqli->connect_error . "</p>";
        exit;
    }
    
    echo "<p>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar si la tabla usuarios existe
    $result = $mysqli->query("SHOW TABLES LIKE 'usuarios'");
    if ($result->num_rows > 0) {
        echo "<p>✅ Tabla 'usuarios' existe</p>";
        
        // Mostrar estructura de la tabla
        echo "<h3>📋 Estructura de la tabla usuarios:</h3>";
        $result = $mysqli->query("DESCRIBE usuarios");
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Contar usuarios
        $result = $mysqli->query("SELECT COUNT(*) as total FROM usuarios");
        $row = $result->fetch_assoc();
        echo "<p>👥 Total de usuarios en la base de datos: <strong>" . $row['total'] . "</strong></p>";
        
        // Mostrar algunos usuarios de ejemplo (sin mostrar contraseñas)
        if ($row['total'] > 0) {
            echo "<h3>👥 Usuarios registrados:</h3>";
            $result = $mysqli->query("SELECT idusuario, usuario, nombre, apellido, correo FROM usuarios LIMIT 5");
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Usuario</th><th>Nombre</th><th>Apellido</th><th>Email</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['idusuario'] . "</td>";
                echo "<td>" . $row['usuario'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['apellido'] . "</td>";
                echo "<td>" . $row['correo'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>⚠️ No hay usuarios registrados</p>";
            echo "<p>💡 <strong>Solución:</strong> Crear un usuario de prueba</p>";
            
            // Crear usuario de prueba
            echo "<h3>🚀 Creando usuario de prueba...</h3>";
            
            $usuarioPrueba = new Usuario();
            $request = [
                'txtUsuario' => 'admin',
                'txtClave' => '1234',
                'txtNombre' => 'Administrador',
                'txtApellido' => 'Sistema',
                'txtCorreo' => 'admin@sistema.com'
            ];
            
            $usuarioPrueba->cargarFormulario($request);
            $usuarioPrueba->insertar();
            
            echo "<p>✅ Usuario de prueba creado:</p>";
            echo "<ul>";
            echo "<li><strong>Usuario:</strong> admin</li>";
            echo "<li><strong>Contraseña:</strong> 1234</li>";
            echo "<li><strong>Nombre:</strong> Administrador Sistema</li>";
            echo "</ul>";
        }
        
    } else {
        echo "<p>❌ La tabla 'usuarios' no existe</p>";
        echo "<p>💡 <strong>Solución:</strong> Ejecutar el script de instalación o crear la tabla manualmente</p>";
    }
    
    // Probar la clase Usuario
    echo "<h3>🧪 Prueba de la clase Usuario:</h3>";
    $usuario = new Usuario();
    if (class_exists('Usuario')) {
        echo "<p>✅ Clase Usuario cargada correctamente</p>";
        
        // Verificar métodos disponibles
        $metodos = get_class_methods($usuario);
        echo "<p>📋 Métodos disponibles: " . implode(', ', $metodos) . "</p>";
    } else {
        echo "<p>❌ Error: Clase Usuario no encontrada</p>";
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='login.php'>🔑 Ir al Login</a> | <a href='crear-cuenta.html'>📝 Crear Cuenta</a></p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
</style>
