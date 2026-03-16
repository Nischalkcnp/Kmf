@echo off
REM Run KMF website with PHP built-in server
REM Ensure PHP is installed and in PATH, or set PHP path below.

set PHP_BIN=C:\xampp\php\php.exe
REM If you move XAMPP, update the path above.
REM For Laragon, you could use: set PHP_BIN=C:\laragon\bin\php\php.exe

cd /d "%~dp0"
echo Starting PHP server at http://localhost:8080
echo Press Ctrl+C to stop.
echo.
%PHP_BIN% -S localhost:8080
pause
