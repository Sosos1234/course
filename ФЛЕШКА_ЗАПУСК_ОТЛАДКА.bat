@echo off
cd /d "%~dp0"
echo Запуск ФЛЕШКА_ЗАПУСК.bat...
echo Текущая папка: %cd%
echo.
call "%~dp0ФЛЕШКА_ЗАПУСК.bat"
echo.
echo Скрипт завершён. Нажмите любую клавишу.
pause >nul
