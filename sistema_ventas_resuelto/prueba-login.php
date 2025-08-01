<?php
include_once "config.php";
include_once "entidades/usuario.php";

echo "<h2>🔐 Prueba de Login</h2>";

// Datos de prueba
$usuarioPrueba = "admin";
$clavePrueba = "1234";

echo "<p>Probando login con:</p>";
echo "<ul>";
echo "<li><strong>Usuario:</strong> $usuarioPrueba</li>";
echo "<li><strong>Contraseña:</strong> $clavePrueba</li>";
echo "</ul>";

try {
    // Crear instancia de usuario
    $entidadUsuario = new Usuario();
    
    // Probar obtener usuario
    echo "<h3>📋 Paso 1: Buscar usuario en la base de datos</h3>";
    $resultado = $entidadUsuario->obtenerPorUsuario($usuarioPrueba);
    
    if ($resultado) {
        echo "<p>✅ Usuario encontrado en la base de datos</p>";
        echo "<ul>";
        echo "<li><strong>ID:</strong> " . $entidadUsuario->idusuario . "</li>";
        echo "<li><strong>Usuario:</strong> " . $entidadUsuario->usuario . "</li>";
        echo "<li><strong>Nombre:</strong> " . $entidadUsuario->nombre . "</li>";
        echo "<li><strong>Apellido:</strong> " . $entidadUsuario->apellido . "</li>";
        echo "<li><strong>Email:</strong> " . $entidadUsuario->correo . "</li>";
        echo "<li><strong>Clave (hash):</strong> " . substr($entidadUsuario->clave, 0, 20) . "...</li>";
        echo "</ul>";
        
        // Probar verificación de contraseña
        echo "<h3>🔑 Paso 2: Verificar contraseña</h3>";
        $claveValida = $entidadUsuario->verificarClave($clavePrueba, $entidadUsuario->clave);
        
        if ($claveValida) {
            echo "<p>✅ Contraseña correcta - ¡Login exitoso!</p>";
            echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; border-radius: 5px;'>";
            echo "<strong>🎉 LOGIN EXITOSO</strong><br>";
            echo "El usuario <strong>$usuarioPrueba</strong> puede iniciar sesión correctamente.";
            echo "</div>";
        } else {
            echo "<p>❌ Contraseña incorrecta</p>";
            echo "<p>💡 Esto significa que la contraseña almacenada no coincide con la proporcionada.</p>";
            
            // Intentar crear una nueva contraseña hash para comparar
            echo "<h4>🔧 Diagnóstico de contraseña:</h4>";
            $hashNuevo = password_hash($clavePrueba, PASSWORD_DEFAULT);
            echo "<p>Hash actual en BD: " . $entidadUsuario->clave . "</p>";
            echo "<p>Hash nuevo generado: " . $hashNuevo . "</p>";
            echo "<p>Verificación con nuevo hash: " . (password_verify($clavePrueba, $hashNuevo) ? "✅ Válida" : "❌ Inválida") . "</p>";
        }
        
    } else {
        echo "<p>❌ Usuario no encontrado en la base de datos</p>";
        echo "<p>💡 El usuario '$usuarioPrueba' no existe. Necesitas crear este usuario primero.</p>";
        
        // Intentar crear el usuario
        echo "<h3>🚀 Creando usuario de prueba...</h3>";
        $nuevoUsuario = new Usuario();
        $request = [
            'txtUsuario' => $usuarioPrueba,
            'txtClave' => $clavePrueba,
            'txtNombre' => 'Administrador',
            'txtApellido' => 'Sistema',
            'txtCorreo' => 'admin@sistema.com'
        ];
        
        $nuevoUsuario->cargarFormulario($request);
        $nuevoUsuario->insertar();
        
        echo "<p>✅ Usuario creado exitosamente. Ahora puedes usar:</p>";
        echo "<ul>";
        echo "<li><strong>Usuario:</strong> $usuarioPrueba</li>";
        echo "<li><strong>Contraseña:</strong> $clavePrueba</li>";
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error durante la prueba: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>🎯 Usuarios disponibles para probar:</h3>";

try {
    $usuario = new Usuario();
    $usuarios = $usuario->obtenerTodos();
    
    if (count($usuarios) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background-color: #f2f2f2;'><th>Usuario</th><th>Nombre Completo</th><th>Email</th><th>Acción</th></tr>";
        
        foreach ($usuarios as $u) {
            echo "<tr>";
            echo "<td><strong>" . $u->usuario . "</strong></td>";
            echo "<td>" . $u->nombre . " " . $u->apellido . "</td>";
            echo "<td>" . $u->correo . "</td>";
            echo "<td><a href='login.php' style='color: blue;'>Probar Login</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>⚠️ No hay usuarios registrados en el sistema.</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error al obtener usuarios: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='login.php'>🔑 Ir al Login</a> | <a href='crear-cuenta.html'>📝 Crear Cuenta</a> | <a href='diagnostico.php'>🔍 Diagnóstico</a></p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { margin: 10px 0; }
th, td { padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
ul { margin: 10px 0; }
</style>
