@echo off
chcp 65001 >nul
title ТРЦ Европа 27 — Запуск сайта

cd /d "%~dp0"

set PHPEXE=php
where php >nul 2>&1
if %errorlevel% neq 0 (
    if exist "C:\xampp\php\php.exe" set PHPEXE=C:\xampp\php\php.exe
    if exist "C:\OpenServer\modules\php\php.exe" set PHPEXE=C:\OpenServer\modules\php\php.exe
)

"%PHPEXE%" -v >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo   [ОШИБКА] PHP не найден.
    echo.
    echo   Установите XAMPP: https://www.apachefriends.org/download.html
    echo   Или PHP: https://windows.php.net/download/
    echo.
    echo   Не забудьте запустить MySQL в XAMPP.
    echo.
    pause
    exit /b 1
)

echo.
echo   ========================================
echo   ТРЦ Европа 27 — Запуск сайта
echo   ========================================
echo.

echo   Запуск сервера на http://localhost:8000
echo   Чтобы остановить — закройте это окно.
echo.
echo   ========================================
echo.

start "" "http://localhost:8000"
"%PHPEXE%" -S localhost:8000

pause
