@echo off
echo ==========================================
echo Running PHPUnit Tests with Coverage HTML...
echo ==========================================

call php artisan test --coverage-html coverage-report --log-junit junit.xml
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [WARNING] Tests failed! SonarQube analysis might be incomplete.
    timeout /t 5
)

echo.
echo ==========================================
echo Running SonarQube Scanner...
echo ==========================================

set "TOKEN="

:: Priority 1: Argument passed to script
if not "%1"=="" set "TOKEN=%1"

:: Priority 2: Read from .sonar_token file if argument not provided
if "%TOKEN%"=="" (
    if exist .sonar_token (
        set /p TOKEN=<.sonar_token
    )
)

if "%TOKEN%"=="" (
    echo [INFO] No token found. Running scanner anonymously...
    echo (If authentication fails, put your token in a file named .sonar_token)
    echo.
    call sonar-scanner
) else (
    echo [INFO] Using Token Authentication
    call sonar-scanner -Dsonar.login=%TOKEN%
)

echo.
echo ==========================================
echo Done! Report should be available at http://localhost:9000
echo ==========================================
pause
