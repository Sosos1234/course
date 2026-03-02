@echo off
title PHP Setup
cd /d "%~dp0"

if not exist "php\php.exe" (
    echo Put the php folder here. Download from windows.php.net
    pause
    exit /b 1
)

if not exist "php\ext" (
    echo ERROR: php\ext folder not found.
    echo Extract the FULL PHP zip - it contains ext folder with DLLs.
    pause
    exit /b 1
)

if not exist "php\ext\php_pdo_sqlite.dll" (
    echo WARNING: php_pdo_sqlite.dll not found in php\ext
)

echo Creating php\php.ini...
(
echo [PHP]
echo extension_dir="%~dp0php\ext"
echo extension=pdo_sqlite
) > "php\php.ini"

echo Done. Run install.bat
pause
