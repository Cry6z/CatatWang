-- CatatWang Database Schema
-- Financial Management System for Class

CREATE DATABASE IF NOT EXISTS catatwang;
USE catatwang;

-- Users table for authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'bendahara', 'anggota') DEFAULT 'anggota',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories table for transaction types
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    description TEXT,
    color VARCHAR(7) DEFAULT '#3B82F6',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Transactions table for income and expenses
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    user_id INT NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    description TEXT NOT NULL,
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Settings table for application configuration
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sessions table for Laravel session management
CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);

-- Cache table for Laravel caching
CREATE TABLE cache (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL
);

-- Cache locks table
CREATE TABLE cache_locks (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL
);

-- Jobs table for Laravel queue system
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX jobs_queue_index (queue)
);

-- Job batches table
CREATE TABLE job_batches (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
);

-- Failed jobs table
CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Administrator', 'admin@catatwang.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('Bendahara Kelas', 'bendahara@catatwang.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bendahara', NOW(), NOW()),
('Anggota Kelas 1', 'anggota1@catatwang.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'anggota', NOW(), NOW());

-- Insert default categories
INSERT INTO categories (name, type, description, color, created_at, updated_at) VALUES
('Iuran Kelas', 'income', 'Iuran bulanan anggota kelas', '#10B981', NOW(), NOW()),
('Donasi', 'income', 'Donasi dari pihak luar', '#059669', NOW(), NOW()),
('Kegiatan Kelas', 'expense', 'Pengeluaran untuk kegiatan kelas', '#EF4444', NOW(), NOW()),
('Perlengkapan', 'expense', 'Pembelian perlengkapan kelas', '#F59E0B', NOW(), NOW()),
('Konsumsi', 'expense', 'Konsumsi untuk kegiatan', '#8B5CF6', NOW(), NOW()),
('Lain-lain', 'income', 'Pemasukan lainnya', '#6B7280', NOW(), NOW()),
('Operasional', 'expense', 'Biaya operasional', '#DC2626', NOW(), NOW());

-- Insert dummy transactions
INSERT INTO transactions (category_id, user_id, type, amount, description, transaction_date, created_at, updated_at) VALUES
(1, 2, 'income', 50000, 'Iuran bulan Januari - Andi', '2024-01-15', NOW(), NOW()),
(1, 2, 'income', 50000, 'Iuran bulan Januari - Budi', '2024-01-16', NOW(), NOW()),
(1, 2, 'income', 50000, 'Iuran bulan Januari - Citra', '2024-01-17', NOW(), NOW()),
(4, 2, 'expense', 75000, 'Pembelian spidol dan penghapus papan tulis', '2024-01-20', NOW(), NOW()),
(3, 2, 'expense', 200000, 'Biaya kegiatan class meeting', '2024-01-25', NOW(), NOW()),
(5, 2, 'expense', 150000, 'Konsumsi rapat kelas', '2024-02-01', NOW(), NOW()),
(1, 2, 'income', 50000, 'Iuran bulan Februari - Andi', '2024-02-15', NOW(), NOW()),
(1, 2, 'income', 50000, 'Iuran bulan Februari - Budi', '2024-02-16', NOW(), NOW()),
(2, 2, 'income', 100000, 'Donasi dari alumni', '2024-02-20', NOW(), NOW()),
(4, 2, 'expense', 50000, 'Pembelian kertas dan alat tulis', '2024-02-25', NOW(), NOW());

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('app_name', 'CatatWang', 'Nama aplikasi'),
('currency', 'Rp', 'Mata uang yang digunakan'),
('low_balance_threshold', '100000', 'Batas minimum saldo untuk notifikasi'),
('items_per_page', '10', 'Jumlah item per halaman');
