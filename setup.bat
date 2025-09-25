@echo off
echo ========================================
echo    CatatWang Setup Script
echo    Sistem Manajemen Keuangan Kelas
echo ========================================
echo.

echo [1/6] Copying environment file...
if not exist .env (
    copy .env.example .env
    echo .env file created successfully!
) else (
    echo .env file already exists, skipping...
)
echo.

echo [2/6] Installing Composer dependencies...
composer install --no-dev --optimize-autoloader
echo.

echo [3/6] Generating application key...
php artisan key:generate
echo.

echo [4/6] Running database migrations...
php artisan migrate
echo.

echo [5/6] Seeding database with demo data...
php artisan db:seed
echo.

echo [6/6] Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo.

echo ========================================
echo    Setup completed successfully!
echo ========================================
echo.
echo Demo accounts:
echo - Admin: admin@catatwang.com / admin123
echo - Bendahara: bendahara@catatwang.com / admin123  
echo - Anggota: anggota1@catatwang.com / admin123
echo.
echo To start the application:
echo php artisan serve
echo.
echo Then visit: http://localhost:8000
echo ========================================

pause
