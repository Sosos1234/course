@echo off
chcp 65001 >nul
title ТРЦ Европа 27
cd /d "%~dp0"

set "PHPEXE="
if exist "%~dp0php\php.exe" set "PHPEXE=%~dp0php\php.exe"
if "%PHPEXE%"=="" if exist "C:\xampp\php\php.exe" set "PHPEXE=C:\xampp\php\php.exe"
if "%PHPEXE%"=="" if exist "C:\OpenServer\modules\php\php.exe" set "PHPEXE=C:\OpenServer\modules\php\php.exe"
if "%PHPEXE%"=="" (
    where php >nul 2>&1
    if not errorlevel 1 set "PHPEXE=php"
)

if "%PHPEXE%"=="" (
    echo.
    echo   [ОШИБКА] PHP не найден.
    echo.
    echo   Положите папку php с php.exe рядом с этим файлом
    echo   или установите XAMPP.
    echo.
    echo   Скачать PHP: https://windows.php.net/download/
    echo.
    pause
    exit /b 1
)

if not exist "portable.flag" echo. > portable.flag
if not exist "data\europa27.sqlite" (
    echo Создание базы...
    "%PHPEXE%" -d display_errors=0 -f install_portable.php 2>nul
)

echo.
echo   ТРЦ Европа 27
echo   http://localhost:8000
echo.
timeout /t 2 /nobreak >nul
start "" "http://localhost:8000"

"%PHPEXE%" -S localhost:8000
echo.
echo   Сервер остановлен.
pause
