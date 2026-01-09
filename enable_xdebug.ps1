$phpIniPath = "C:\laragon\bin\php\php-8.5.0-nts-Win32-vs17-x64\php.ini"
$xdebugPath = "C:\laragon\bin\php\php-8.5.0-nts-Win32-vs17-x64\ext\php_xdebug.dll"

$content = Get-Content $phpIniPath
$xdebugLine = "zend_extension=""$xdebugPath"""
$modeLine = "xdebug.mode=coverage"

if ($content -notcontains $xdebugLine) {
    Add-Content $phpIniPath ""
    Add-Content $phpIniPath "[Xdebug]"
    Add-Content $phpIniPath $xdebugLine
    Add-Content $phpIniPath $modeLine
    Write-Host "Xdebug added to php.ini"
} else {
    Write-Host "Xdebug is already present in php.ini"
}
