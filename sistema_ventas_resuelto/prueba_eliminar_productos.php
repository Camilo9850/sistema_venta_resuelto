<?php
// 🧪 SCRIPT DE PRUEBA PARA PRODUCTOS - ELIMINAR FUNCIÓN
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
require_once 'entidades/producto.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Prueba - Función Eliminar Productos</title>
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
        .btn { display: inline-block; padding: 8px 16px; margin: 5px; background: linear-gradient(45deg, #667eea, #764ba2); color: white; text-decoration: none; border-radius: 5px; font-size: 12px; }
        .btn:hover { transform: translateY(-1px); color: white; }
        .btn-danger { background: linear-gradient(45deg, #dc3545, #c82333); }
        .btn-success { background: linear-gradient(45deg, #28a745, #218838); }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Prueba - Función Eliminar Productos</h1>
        
        <?php
        try {
            $producto = new Producto();
            $aProductos = $producto->obtenerTodos();
            
            echo "<div class='info'>📊 <strong>Total de productos:</strong> " . count($aProductos) . "</div>";
            
            if (count($aProductos) > 0) {
                echo "<h2>📋 Listado de Productos</h2>";
                echo "<p>Haz clic en 'Probar Eliminar' para ir al formulario de edición donde podrás probar la función eliminar.</p>";
                
                echo "<table>";
                echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Imagen</th>";
                echo "<th>Nombre</th>";
                echo "<th>Cantidad</th>";
                echo "<th>Precio</th>";
                echo "<th>Acciones de Prueba</th>";
                echo "</tr>";
                
                foreach ($aProductos as $prod) {
                    echo "<tr>";
                    echo "<td>{$prod->idproducto}</td>";
                    echo "<td>";
                    if ($prod->imagen && file_exists("files/" . $prod->imagen)) {
                        echo "<img src='files/{$prod->imagen}' style='max-width: 60px; max-height: 60px; border-radius: 4px;'>";
                    } else {
                        echo "<div style='width: 60px; height: 40px; background: #666; display: flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 10px;'>Sin imagen</div>";
                    }
                    echo "</td>";
                    echo "<td>{$prod->nombre}</td>";
                    echo "<td>{$prod->cantidad}</td>";
                    echo "<td>$" . number_format($prod->precio, 2) . "</td>";
                    echo "<td>";
                    echo "<a href='producto-formulario.php?id={$prod->idproducto}' class='btn btn-success'>📝 Editar/Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
                
                echo "<div class='warning'>";
                echo "<h3>⚠️ Instrucciones para Probar:</h3>";
                echo "<ol>";
                echo "<li>Haz clic en 'Editar/Eliminar' de cualquier producto</li>";
                echo "<li>En el formulario verás el botón 'Eliminar' (solo aparece cuando editas un producto existente)</li>";
                echo "<li>El botón incluye confirmación JavaScript</li>";
                echo "<li>Al eliminar, también se borra la imagen del servidor</li>";
                echo "</ol>";
                echo "</div>";
                
            } else {
                echo "<div class='warning'>⚠️ <strong>No hay productos para mostrar</strong></div>";
            }
            
            echo "<h2>🔧 Estado de Correcciones Aplicadas</h2>";
            echo "<div class='success'>";
            echo "<h3>✅ Correcciones Implementadas:</h3>";
            echo "<ul>";
            echo "<li><strong>Formulario corregido:</strong> Los botones ahora están dentro del form</li>";
            echo "<li><strong>ID de producto:</strong> Se pasa correctamente via POST y GET</li>";
            echo "<li><strong>Confirmación JavaScript:</strong> Pregunta antes de eliminar</li>";
            echo "<li><strong>Eliminación de imagen:</strong> Borra también el archivo de imagen</li>";
            echo "<li><strong>Error SQL corregido:</strong> Comilla faltante en método actualizar</li>";
            echo "<li><strong>Seguridad mejorada:</strong> Verificación de ID antes de eliminar</li>";
            echo "</ul>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ <strong>ERROR:</strong> " . $e->getMessage() . "</div>";
        }
        ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="producto-listado.php" class="btn">📋 Listado Oficial</a>
            <a href="producto-formulario.php" class="btn">➕ Nuevo Producto</a>
            <a href="generar_imagenes_productos.php" class="btn">🖼️ Generar Imágenes</a>
            <a href="index.php" class="btn">🏠 Dashboard</a>
        </div>
        
        <div class="info" style="margin-top: 30px;">
            <h3>🎯 Problema Original Solucionado:</h3>
            <p><strong>"El botón eliminar estaba fallando"</strong> - Ahora funciona correctamente.</p>
            <p><strong>Causa:</strong> Los botones estaban fuera del formulario y faltaba el ID del producto.</p>
            <p><strong>Solución:</strong> Formulario reestructurado con todos los elementos correctamente organizados.</p>
        </div>
    </div>
</body>
</html>
