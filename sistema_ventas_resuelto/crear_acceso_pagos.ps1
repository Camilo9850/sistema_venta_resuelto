$WshShell = New-Object -comObject WScript.Shell

# Crear acceso directo para el nuevo módulo de pagos Nequi integrado
$pagosShortcut = $WshShell.CreateShortcut("$env:USERPROFILE\OneDrive\Escritorio\Pagos Nequi Integrado.lnk")
$pagosShortcut.TargetPath = "http://localhost/mi_proyecto/php-1/sistema_ventas_resuelto/pagos-nequi.php"
$pagosShortcut.Description = "Sistema de Pagos Nequi Integrado al Sistema de Ventas"
$pagosShortcut.Save()

Write-Host "✅ Acceso directo creado: Pagos Nequi Integrado.lnk" -ForegroundColor Green
Write-Host "🎯 Accede directamente al módulo de pagos integrado desde tu escritorio" -ForegroundColor Cyan
