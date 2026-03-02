@echo off
title Europa27
cd /d "%~dp0"

set PHPEXE=
if exist "%~dp0php\php.exe" set PHPEXE=%~dp0php\php.exe
if "%PHPEXE%"=="" if exist "C:\xampp\php\php.exe" set PHPEXE=C:\xampp\php\php.exe
if "%PHPEXE%"=="" if exist "C:\OpenServer\modules\php\php.exe" set PHPEXE=C:\OpenServer\modules\php\php.exe
if "%PHPEXE%"=="" where php >nul 2>&1
if "%PHPEXE%"=="" if not errorlevel 1 set PHPEXE=php

if "%PHPEXE%"=="" (
    echo.
    echo PHP not found. Put php folder here or install XAMPP.
    echo Download: https://windows.php.net/download/
    echo.
    pause
    exit /b 1
)

if not exist portable.flag echo. > portable.flag

echo [PHP] > "%~dp0php.ini"
echo extension=pdo_sqlite >> "%~dp0php.ini"

if not exist "data\europa27.sqlite" (
    echo Creating database...
    "%PHPEXE%" -c "%~dp0" -d display_errors=0 -f install_portable.php
    if not exist "data\europa27.sqlite" (
        echo.
        echo ERROR: Database not created. Enable pdo_sqlite in php.ini
        echo.
        pause
        exit /b 1
    )
)

echo.
echo Europa27 - http://localhost:8000
echo Close window to stop.
echo.

timeout /t 2 /nobreak >nul
start http://localhost:8000

"%PHPEXE%" -c "%~dp0" -S localhost:8000

echo.
pause
