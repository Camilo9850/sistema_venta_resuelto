<?php
// 🔐 DEMOSTRACIÓN - CREACIÓN DE USUARIOS CON CONTRASEÑAS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
require_once 'entidades/usuario.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔐 Demo - Creación de Usuarios</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .container { max-width: 1000px; margin: 0 auto; background: rgba(0,0,0,0.8); padding: 30px; border-radius: 15px; }
        .success { background: #28a745; color: white; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .error { background: #dc3545; color: white; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .info { background: #17a2b8; color: white; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .warning { background: #ffc107; color: #212529; padding: 15px; border-radius: 8px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; background: rgba(255,255,255,0.1); }
        th, td { border: 1px solid rgba(255,255,255,0.3); padding: 12px; text-align: left; }
        th { background: rgba(255,255,255,0.2); font-weight: bold; }
        .btn { display: inline-block; padding: 12px 24px; margin: 10px 5px; background: linear-gradient(45deg, #667eea, #764ba2); color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }
        .btn:hover { transform: translateY(-2px); color: white; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin: 20px 0; }
        .card { background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2); }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Sistema de Usuarios - Demostración</h1>
        
        <?php
        try {
            $usuario = new Usuario();
            $aUsuarios = $usuario->obtenerTodos();
            
            echo "<div class='info'>";
            echo "<h2>📊 Estado Actual del Sistema</h2>";
            echo "<p><strong>Total de usuarios registrados:</strong> " . count($aUsuarios) . "</p>";
            echo "</div>";
            
            if (count($aUsuarios) > 0) {
                echo "<h2>👥 Usuarios Existentes</h2>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Usuario</th><th>Nombre Completo</th><th>Email</th><th>Acciones</th></tr>";
                
                foreach ($aUsuarios as $usr) {
                    echo "<tr>";
                    echo "<td>{$usr->idusuario}</td>";
                    echo "<td><strong>{$usr->usuario}</strong></td>";
                    echo "<td>{$usr->nombre} {$usr->apellido}</td>";
                    echo "<td>{$usr->correo}</td>";
                    echo "<td><a href='usuario-formulario.php?id={$usr->idusuario}' class='btn' style='padding: 5px 10px; margin: 0; font-size: 12px;'>✏️ Editar</a></td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            }
            
            echo "<h2>🆕 Funcionalidades Mejoradas</h2>";
            echo "<div class='grid'>";
            
            echo "<div class='card'>";
            echo "<h3>🔑 Generación Automática de Contraseñas</h3>";
            echo "<ul>";
            echo "<li>Si no especificas contraseña, se genera automáticamente</li>";
            echo "<li>La contraseña se muestra claramente después de crear el usuario</li>";
            echo "<li>Botón para copiar la contraseña al portapapeles</li>";
            echo "<li>Generador manual de contraseñas en el formulario</li>";
            echo "</ul>";
            echo "</div>";
            
            echo "<div class='card'>";
            echo "<h3>👁️ Visualización de Contraseñas</h3>";
            echo "<ul>";
            echo "<li>Botón para mostrar/ocultar contraseña</li>";
            echo "<li>Alerta destacada con los datos del usuario creado</li>";
            echo "<li>Mensaje de advertencia para guardar la contraseña</li>";
            echo "<li>Interfaz mejorada con iconos y colores</li>";
            echo "</ul>";
            echo "</div>";
            
            echo "<div class='card'>";
            echo "<h3>🛡️ Seguridad</h3>";
            echo "<ul>";
            echo "<li>Contraseñas hasheadas con password_hash()</li>";
            echo "<li>Validación de usuarios y emails únicos</li>";
            echo "<li>Confirmación antes de eliminar usuarios</li>";
            echo "<li>Campos obligatorios validados</li>";
            echo "</ul>";
            echo "</div>";
            
            echo "<div class='card'>";
            echo "<h3>🎨 Mejoras de UX</h3>";
            echo "<ul>";
            echo "<li>Formulario reorganizado y más intuitivo</li>";
            echo "<li>Botones con iconos descriptivos</li>";
            echo "<li>Mensajes de éxito y error más claros</li>";
            echo "<li>Listado mejorado con más información</li>";
            echo "</ul>";
            echo "</div>";
            
            echo "</div>";
            
            echo "<div class='warning'>";
            echo "<h3>🧪 Cómo Probar la Nueva Funcionalidad:</h3>";
            echo "<ol>";
            echo "<li><strong>Crear usuario sin contraseña:</strong> Ve al formulario, llena solo nombre, usuario y email. Deja el campo contraseña vacío.</li>";
            echo "<li><strong>Ver contraseña generada:</strong> Después de guardar, verás una alerta verde con la contraseña generada.</li>";
            echo "<li><strong>Copiar contraseña:</strong> Usa el botón de copiar para guardar la contraseña.</li>";
            echo "<li><strong>Generar contraseña manual:</strong> En el formulario, usa el botón 'Generar' para crear una contraseña.</li>";
            echo "<li><strong>Mostrar/ocultar:</strong> Usa el botón del ojo para ver la contraseña mientras escribes.</li>";
            echo "</ol>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ <strong>ERROR:</strong> " . $e->getMessage() . "</div>";
        }
        ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="usuario-formulario.php" class="btn">👤 Crear Nuevo Usuario</a>
            <a href="usuario-listado.php" class="btn">📋 Ver Todos los Usuarios</a>
            <a href="index.php" class="btn">🏠 Dashboard Principal</a>
        </div>
        
        <div class="success" style="margin-top: 30px;">
            <h2>✅ Funcionalidad Implementada</h2>
            <p><strong>Cuando se crea un usuario, ahora se muestra claramente cuál es la contraseña generada.</strong></p>
            <p>El usuario puede ver, copiar y guardar la contraseña antes de continuar.</p>
            <p><strong>Fecha de implementación:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
</body>
</html>
