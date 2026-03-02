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

if not exist "data\europa27.sqlite" (
    echo.
    echo Database not found. Run install.bat first.
    echo.
    pause
    exit /b 1
)

echo.
echo Starting server...
start /b "" "%PHPEXE%" -S 127.0.0.1:8000
timeout /t 3 /nobreak >nul

echo Open: http://127.0.0.1:8000
start http://127.0.0.1:8000

echo.
echo Server running. Close this window to stop.
pause
