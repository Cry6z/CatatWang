# 💰 CatatWang - Sistem Manajemen Keuangan Kelas

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-cyan?style=for-the-badge&logo=tailwindcss" alt="Tailwind">
</p>

<p align="center">
  <strong>Sistem manajemen keuangan kelas yang modern, responsif, dan mudah digunakan</strong>
</p>

---

## 📋 Deskripsi

**CatatWang** adalah aplikasi web untuk mengelola keuangan kelas dengan fitur lengkap dan antarmuka yang modern. Aplikasi ini mendukung multi-role authentication dan menyediakan dashboard interaktif untuk monitoring keuangan secara real-time.

## ✨ Fitur Utama

### 🔐 **Multi-Role Authentication**
- **Admin**: Akses penuh ke semua fitur
- **Bendahara**: Mengelola transaksi dan laporan
- **Anggota**: Melihat laporan keuangan

### 📊 **Dashboard Interaktif**
- Ringkasan keuangan real-time
- Grafik pemasukan vs pengeluaran (Chart.js)
- Statistik bulanan dan tahunan
- Widget informasi penting

### 💸 **Manajemen Transaksi**
- CRUD transaksi pemasukan & pengeluaran
- Kategorisasi dengan sistem warna
- Upload bukti transaksi
- Pencarian dan filter advanced

### 📈 **Laporan Keuangan**
- Laporan bulanan dan keseluruhan
- Export ke PDF dan Excel
- Grafik trend keuangan
- Analisis kategori pengeluaran

### 🎨 **UI/UX Modern**
- Design responsif (Mobile-first)
- Dark mode toggle
- Tailwind CSS styling
- SweetAlert2 notifications
- Loading states dan animasi

## 🛠️ Teknologi yang Digunakan

| Kategori | Teknologi |
|----------|-----------|
| **Backend** | Laravel 11.x, PHP 8.2+ |
| **Frontend** | Blade Templates, Tailwind CSS, JavaScript |
| **Database** | MySQL 8.0+ |
| **Charts** | Chart.js |
| **Icons** | Font Awesome 6 |
| **Notifications** | SweetAlert2 |
| **Export** | Laravel Excel, DomPDF |

## 🚀 Quick Start

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL 8.0+
- Node.js & NPM (opsional)

### Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/Cry6z/catatwang.git
   cd catatwang
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi database** (edit `.env`)
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=catatwang
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Buat database dan migrate**
   ```bash
   # Buat database 'catatwang' di MySQL
   php artisan migrate --seed
   ```

6. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

   Akses: `http://localhost:8000`

## 👥 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@catatwang.com | admin123 |
| **Bendahara** | bendahara@catatwang.com | admin123 |
| **Anggota** | anggota1@catatwang.com | admin123 |

## 📱 Screenshots

### Dashboard
![Dashboard](screenshots/dashboard.png)

### Transaksi
![Transaksi](screenshots/transactions.png)

### Laporan
![Laporan](screenshots/reports.png)

## 🏗️ Struktur Project

```
catatwang/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/
│   │   ├── DashboardController.php
│   │   ├── TransactionController.php
│   │   └── ...
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── transactions/
│   │   └── layouts/
│   └── css/
├── routes/
│   └── web.php
└── public/
```

## 🔧 Konfigurasi

### Environment Variables
```env
APP_NAME=CatatWang
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=catatwang
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
```

## 📚 API Documentation

### Authentication Endpoints
- `GET /login` - Halaman login
- `POST /login` - Proses login
- `POST /logout` - Logout

### Transaction Endpoints
- `GET /transactions` - List transaksi
- `POST /transactions` - Tambah transaksi
- `PUT /transactions/{id}` - Update transaksi
- `DELETE /transactions/{id}` - Hapus transaksi

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter TransactionTest
```

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` di `.env`
2. Set `APP_DEBUG=false`
3. Jalankan optimizations:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Server Requirements
- PHP 8.2+
- MySQL 8.0+
- Nginx/Apache
- SSL Certificate (recommended)
