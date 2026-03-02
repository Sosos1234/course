@echo off
chcp 65001 >nul
title ТРЦ Европа 27
cd /d "%~dp0"

set PHPEXE=
if exist "php\php.exe" set PHPEXE=php\php.exe
if exist "php\php-cgi.exe" set PHPEXE=php\php-cgi.exe
where php >nul 2>&1
if "%PHPEXE%"=="" if %errorlevel% equ 0 set PHPEXE=php
if "%PHPEXE%"=="" if exist "C:\xampp\php\php.exe" set PHPEXE=C:\xampp\php\php.exe
if "%PHPEXE%"=="" if exist "C:\OpenServer\modules\php\php.exe" set PHPEXE=C:\OpenServer\modules\php\php.exe

if "%PHPEXE%"=="" (
    echo.
    echo   PHP не найден на флешке и в системе.
    echo.
    echo   Скачайте portable PHP: https://windows.php.net/download/
    echo   Распакуйте в папку php\ рядом с этим файлом.
    echo   Или установите XAMPP на компьютер.
    echo.
    pause
    exit /b 1
)

if not exist "portable.flag" (
    echo. > portable.flag
)
if not exist "data\europa27.sqlite" (
    echo Создание базы данных...
    "%PHPEXE%" -d display_errors=0 -f install_portable.php > nul 2>&1
)

echo.
echo   ТРЦ Европа 27
echo   Сайт: http://localhost:8000
echo   Закройте окно для остановки.
echo.

start "" "http://localhost:8000"
"%PHPEXE%" -S localhost:8000 -t "%cd%"

pause
