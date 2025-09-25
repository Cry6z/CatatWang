# 🚀 Panduan Instalasi CatatWang

## Langkah-langkah Setup

### 1. Persiapan Environment
```bash
# Copy file environment
cp .env.example .env
```

### 2. Konfigurasi Database (.env)
```env
APP_NAME=CatatWang
APP_URL=http://localhost/catatwang/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=catatwang
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Buat Database MySQL
```sql
CREATE DATABASE catatwang;
```

### 4. Install Dependencies & Setup
```bash
# Install composer packages
composer install

# Generate app key
php artisan key:generate

# Install Laravel UI for auth
php artisan ui bootstrap --auth

# Run migrations
php artisan migrate

# Seed database with demo data
php artisan db:seed
```

### 5. Jalankan Aplikasi
```bash
# Option 1: Laravel built-in server
php artisan serve

# Option 2: XAMPP (akses via browser)
# http://localhost/catatwang/public
```

## 👥 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@catatwang.com | admin123 |
| Bendahara | bendahara@catatwang.com | admin123 |
| Anggota | anggota1@catatwang.com | admin123 |

## ✅ Fitur yang Sudah Selesai

- ✅ Multi-role authentication (Admin/Bendahara/Anggota)
- ✅ Dashboard dengan ringkasan keuangan
- ✅ Grafik interaktif (Chart.js)
- ✅ CRUD transaksi pemasukan & pengeluaran
- ✅ Manajemen kategori dengan warna
- ✅ Laporan keuangan bulanan & keseluruhan
- ✅ Export PDF & Excel
- ✅ Pencarian & filter transaksi
- ✅ Dark mode toggle
- ✅ Responsive design (Tailwind CSS)
- ✅ Notifikasi SweetAlert2
- ✅ Validasi form lengkap

## 🎯 Cara Penggunaan

1. **Login** dengan salah satu akun demo
2. **Dashboard** - Lihat ringkasan keuangan dan grafik
3. **Pemasukan/Pengeluaran** - Tambah, edit, hapus transaksi
4. **Laporan** - Lihat laporan bulanan, export PDF/Excel
5. **Kategori** - Kelola kategori (Admin/Bendahara only)

## 🔧 Troubleshooting

**Error 500**: Pastikan .env sudah dikonfigurasi dan `php artisan key:generate` sudah dijalankan

**Database Error**: Pastikan MySQL berjalan dan database `catatwang` sudah dibuat

**Permission Error**: Set permission folder `storage` dan `bootstrap/cache` ke 755

## 📱 Responsive Design

CatatWang mendukung:
- Desktop (1024px+)
- Tablet (768px-1023px) 
- Mobile (320px-767px)

Selamat menggunakan CatatWang! 💰✨
