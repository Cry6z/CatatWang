-- Quick SQL script to add demo users to CatatWang
-- Run this in phpMyAdmin if users don't exist

-- Insert demo users (password: admin123)
INSERT IGNORE INTO users (name, email, password, role, created_at, updated_at) VALUES
('Administrator', 'admin@catatwang.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('Bendahara Kelas', 'bendahara@catatwang.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bendahara', NOW(), NOW()),
('Anggota Kelas 1', 'anggota1@catatwang.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'anggota', NOW(), NOW());

-- Insert demo categories
INSERT IGNORE INTO categories (name, type, description, color, created_at, updated_at) VALUES
('Iuran Kelas', 'income', 'Iuran bulanan anggota kelas', '#10B981', NOW(), NOW()),
('Donasi', 'income', 'Donasi dari pihak luar', '#059669', NOW(), NOW()),
('Kegiatan Kelas', 'expense', 'Pengeluaran untuk kegiatan kelas', '#EF4444', NOW(), NOW()),
('Perlengkapan', 'expense', 'Pembelian perlengkapan kelas', '#F59E0B', NOW(), NOW()),
('Konsumsi', 'expense', 'Konsumsi untuk kegiatan', '#8B5CF6', NOW(), NOW()),
('Lain-lain', 'income', 'Pemasukan lainnya', '#6B7280', NOW(), NOW()),
('Operasional', 'expense', 'Biaya operasional', '#DC2626', NOW(), NOW());

-- Insert demo transactions (using bendahara user ID = 2)
INSERT IGNORE INTO transactions (category_id, user_id, type, amount, description, transaction_date, created_at, updated_at) VALUES
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
