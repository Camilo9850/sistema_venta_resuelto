# Script para crear accesos directos de XAMPP para Windows
$WshShell = New-Object -comObject WScript.Shell

Write-Host "🔧 Creando accesos directos para XAMPP..." -ForegroundColor Cyan

# 1. Acceso directo para phpMyAdmin
if (Test-Path "C:\xampp\phpMyAdmin") {
    $phpMyAdminShortcut = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\phpMyAdmin.lnk")
    $phpMyAdminShortcut.TargetPath = "http://localhost/phpMyAdmin/"
    $phpMyAdminShortcut.Description = "phpMyAdmin - Administrador de bases de datos MySQL"
    $phpMyAdminShortcut.Save()
    Write-Host "✅ Acceso directo creado: phpMyAdmin.lnk" -ForegroundColor Green
}

# 2. Acceso directo para la carpeta htdocs
$htdocsShortcut = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\XAMPP htdocs.lnk")
$htdocsShortcut.TargetPath = "C:\xampp\htdocs"
$htdocsShortcut.Description = "Carpeta htdocs de XAMPP - Archivos web"
$htdocsShortcut.Save()
Write-Host "✅ Acceso directo creado: XAMPP htdocs.lnk" -ForegroundColor Green

# 3. Acceso directo para localhost
$localhostShortcut = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\Localhost.lnk")
$localhostShortcut.TargetPath = "http://localhost/"
$localhostShortcut.Description = "Servidor local XAMPP"
$localhostShortcut.Save()
Write-Host "✅ Acceso directo creado: Localhost.lnk" -ForegroundColor Green

# 4. Acceso directo para XAMPP WSL (ya creado anteriormente)
$xamppWSLShortcut = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\XAMPP WSL Control.lnk")
$xamppWSLShortcut.TargetPath = "wt.exe"
$xamppWSLShortcut.Arguments = "wsl sudo /opt/lampp/lampp status"
$xamppWSLShortcut.WorkingDirectory = "$env:USERPROFILE"
$xamppWSLShortcut.Description = "Control de XAMPP en WSL - Ver estado y gestionar servicios"
$xamppWSLShortcut.Save()
Write-Host "✅ Acceso directo creado: XAMPP WSL Control.lnk" -ForegroundColor Green

# 5. Acceso directo para tu proyecto específico
$projectShortcut = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\Mi Sistema de Ventas.lnk")
$projectShortcut.TargetPath = "http://localhost/mi_proyecto/php-1/sistema_ventas_resuelto/"
$projectShortcut.Description = "Sistema de Ventas - Mi Proyecto PHP"
$projectShortcut.Save()
Write-Host "✅ Acceso directo creado: Mi Sistema de Ventas.lnk" -ForegroundColor Green

# 6. Acceso directo para restaurar la base de datos
$restoreShortcut = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\Restaurar Base de Datos.lnk")
$restoreShortcut.TargetPath = "http://localhost/mi_proyecto/php-1/sistema_ventas_resuelto/restaurar_bd.php"
$restoreShortcut.Description = "Restaurar Base de Datos del Sistema de Ventas"
$restoreShortcut.Save()
Write-Host "✅ Acceso directo creado: Restaurar Base de Datos.lnk" -ForegroundColor Green

Write-Host "`n🎉 ¡Todos los accesos directos han sido creados!" -ForegroundColor Cyan
Write-Host "`n📋 Accesos directos disponibles en tu escritorio:" -ForegroundColor White
Write-Host "  • phpMyAdmin.lnk - Para gestionar bases de datos" -ForegroundColor Gray
Write-Host "  • XAMPP htdocs.lnk - Para acceder a la carpeta de archivos web" -ForegroundColor Gray
Write-Host "  • Localhost.lnk - Para ver el servidor local" -ForegroundColor Gray
Write-Host "  • XAMPP WSL Control.lnk - Para gestionar XAMPP en WSL" -ForegroundColor Gray
Write-Host "  • Mi Sistema de Ventas.lnk - Para tu proyecto principal" -ForegroundColor Gray
Write-Host "  • Restaurar Base de Datos.lnk - Para restaurar la BD cuando sea necesario" -ForegroundColor Gray

Write-Host "`n💡 Nota: XAMPP parece estar ejecutándose en WSL." -ForegroundColor Yellow
Write-Host "   Usa los accesos directos de WSL para gestionar los servicios." -ForegroundColor Yellow
