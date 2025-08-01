# Script para crear acceso directo de XAMPP en el escritorio
$WshShell = New-Object -comObject WScript.Shell

# Crear acceso directo para XAMPP en WSL
Write-Host "Creando accesos directos para XAMPP..." -ForegroundColor Cyan

# Acceso directo para comandos XAMPP WSL
$xamppWSLCmdShortcut = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\XAMPP WSL.lnk")
$xamppWSLCmdShortcut.TargetPath = "wt.exe"
$xamppWSLCmdShortcut.Arguments = "wsl"
$xamppWSLCmdShortcut.WorkingDirectory = "$env:USERPROFILE"
$xamppWSLCmdShortcut.Description = "Terminal WSL para comandos XAMPP"
$xamppWSLCmdShortcut.Save()
Write-Host "Acceso directo creado: XAMPP WSL.lnk" -ForegroundColor Green

# Crear acceso directo para tu proyecto
$projectShortcut = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\Sistema de Ventas.lnk")
$projectShortcut.TargetPath = "http://localhost/mi_proyecto/php-1/sistema_ventas_resuelto/"
$projectShortcut.Description = "Sistema de Ventas - Proyecto PHP"
$projectShortcut.Save()
Write-Host "Acceso directo creado: Sistema de Ventas.lnk" -ForegroundColor Green

Write-Host "Todos los accesos directos han sido creados en el escritorio!" -ForegroundColor Cyan
