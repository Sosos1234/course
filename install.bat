@echo off
title Europa27 - Install
cd /d "%~dp0"

set PHPEXE=
if exist "%~dp0php\php.exe" set PHPEXE=%~dp0php\php.exe
if "%PHPEXE%"=="" if exist "C:\xampp\php\php.exe" set PHPEXE=C:\xampp\php\php.exe
if "%PHPEXE%"=="" if exist "C:\OpenServer\modules\php\php.exe" set PHPEXE=C:\OpenServer\modules\php\php.exe
if "%PHPEXE%"=="" where php >nul 2>&1
if "%PHPEXE%"=="" if not errorlevel 1 set PHPEXE=php

if "%PHPEXE%"=="" (
    echo PHP not found.
    pause
    exit /b 1
)

echo [PHP] > "%~dp0php.ini"
echo extension=pdo_sqlite >> "%~dp0php.ini"
if exist "%~dp0php\ext" (
    set "EXTP=%~dp0php\ext"
    set "EXTP=%EXTP:\=/%"
    echo extension_dir="%EXTP%" >> "%~dp0php.ini"
) else if exist "C:\xampp\php\ext" (
    echo extension_dir="C:/xampp/php/ext" >> "%~dp0php.ini"
)

echo Creating database...
"%PHPEXE%" -c "%~dp0" -d display_errors=1 -f install_portable.php

echo.
if exist "data\europa27.sqlite" (
    echo OK. Now run start.bat
) else (
    echo Failed. Enable pdo_sqlite in php\php.ini
)
echo.
pause
