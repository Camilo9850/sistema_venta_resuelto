$WshShell = New-Object -comObject WScript.Shell

# phpMyAdmin
$shortcut1 = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\phpMyAdmin.lnk")
$shortcut1.TargetPath = "http://localhost/phpMyAdmin/"
$shortcut1.Save()

# XAMPP htdocs
$shortcut2 = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\XAMPP htdocs.lnk")
$shortcut2.TargetPath = "C:\xampp\htdocs"
$shortcut2.Save()

# Localhost
$shortcut3 = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\Localhost.lnk")
$shortcut3.TargetPath = "http://localhost/"
$shortcut3.Save()

# XAMPP WSL Control
$shortcut4 = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\XAMPP WSL Control.lnk")
$shortcut4.TargetPath = "wt.exe"
$shortcut4.Arguments = "wsl"
$shortcut4.Save()

# Mi Sistema de Ventas
$shortcut5 = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\Mi Sistema de Ventas.lnk")
$shortcut5.TargetPath = "http://localhost/mi_proyecto/php-1/sistema_ventas_resuelto/"
$shortcut5.Save()

# Restaurar Base de Datos
$shortcut6 = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\Restaurar BD.lnk")
$shortcut6.TargetPath = "http://localhost/mi_proyecto/php-1/sistema_ventas_resuelto/restaurar_bd.php"
$shortcut6.Save()

Write-Host "Accesos directos creados exitosamente!"
