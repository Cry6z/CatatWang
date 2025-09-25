@echo off
echo ========================================
echo   PREPARING CATATWANG FOR PRESENTATION
echo ========================================

echo.
echo [1/6] Clearing all caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo [2/6] Optimizing application...
php artisan config:cache
php artisan route:cache

echo.
echo [3/6] Running migrations with demo data...
php artisan migrate:fresh --seed

echo.
echo [4/6] Creating storage link...
php artisan storage:link

echo.
echo [5/6] Setting proper permissions...
icacls storage /grant Everyone:(OI)(CI)F /T
icacls bootstrap\cache /grant Everyone:(OI)(CI)F /T

echo.
echo [6/6] Starting development server...
echo.
echo ========================================
echo   CATATWANG IS READY FOR PRESENTATION!
echo ========================================
echo.
echo Demo Accounts:
echo - Admin: admin@catatwang.com / admin123
echo - Bendahara: bendahara@catatwang.com / admin123  
echo - Anggota: anggota1@catatwang.com / admin123
echo.
echo Server will start at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo ========================================
echo.

php artisan serve
