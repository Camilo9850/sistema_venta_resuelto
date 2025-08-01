<?php
include_once "config.php";
include_once "entidades/usuario.php";

echo "<h2>URLs de edición para cada usuario</h2>";

$entidadUsuario = new Usuario();
$aUsuarios = $entidadUsuario->obtenerTodos();

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f8f9fa;'>";
echo "<th style='padding: 10px;'>ID</th>";
echo "<th style='padding: 10px;'>Usuario</th>";
echo "<th style='padding: 10px;'>Nombre</th>";
echo "<th style='padding: 10px;'>URL de Edición</th>";
echo "<th style='padding: 10px;'>Enlace Directo</th>";
echo "</tr>";

foreach ($aUsuarios as $usuario) {
    $url_edicion = "/mi_proyecto/php-1/sistema_ventas_resuelto/usuario-formulario.php?id=" . $usuario->idusuario;
    $url_completa = "http://localhost" . $url_edicion;
    
    echo "<tr>";
    echo "<td style='padding: 8px;'>" . $usuario->idusuario . "</td>";
    echo "<td style='padding: 8px;'>" . $usuario->usuario . "</td>";
    echo "<td style='padding: 8px;'>" . $usuario->nombre . " " . $usuario->apellido . "</td>";
    echo "<td style='padding: 8px; font-family: monospace; font-size: 12px;'>" . $url_edicion . "</td>";
    echo "<td style='padding: 8px;'><a href='" . $url_completa . "' target='_blank'>🔗 Probar</a></td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>URL Pattern:</h3>";
echo "<code>/mi_proyecto/php-1/sistema_ventas_resuelto/usuario-formulario.php?id=[ID_DEL_USUARIO]</code>";
?>
