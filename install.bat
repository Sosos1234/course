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

if exist "%~dp0php\php.exe" if not exist "%~dp0php\php.ini" (
    echo Run setup_php.bat first.
    pause
    exit /b 1
)

echo Creating database...
if exist "%~dp0php\php.exe" (
    "%~dp0php\php.exe" -d display_errors=1 -f install_portable.php
) else (
    "%PHPEXE%" -d display_errors=1 -f install_portable.php
)

echo.
if exist "data\europa27.sqlite" (
    echo OK. Now run start.bat
) else (
    echo Failed. Enable pdo_sqlite in php\php.ini
)
echo.
pause
