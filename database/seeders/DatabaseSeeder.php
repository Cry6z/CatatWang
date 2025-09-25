<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo users
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@catatwang.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $bendahara = User::create([
            'name' => 'Bendahara Kelas',
            'email' => 'bendahara@catatwang.com',
            'password' => Hash::make('admin123'),
            'role' => 'bendahara'
        ]);

        $anggota = User::create([
            'name' => 'Anggota Kelas 1',
            'email' => 'anggota1@catatwang.com',
            'password' => Hash::make('admin123'),
            'role' => 'anggota'
        ]);

        // Create categories
        $categories = [
            ['name' => 'Iuran Kelas', 'type' => 'income', 'description' => 'Iuran bulanan anggota kelas', 'color' => '#10B981'],
            ['name' => 'Donasi', 'type' => 'income', 'description' => 'Donasi dari pihak luar', 'color' => '#059669'],
            ['name' => 'Kegiatan Kelas', 'type' => 'expense', 'description' => 'Pengeluaran untuk kegiatan kelas', 'color' => '#EF4444'],
            ['name' => 'Perlengkapan', 'type' => 'expense', 'description' => 'Pembelian perlengkapan kelas', 'color' => '#F59E0B'],
            ['name' => 'Konsumsi', 'type' => 'expense', 'description' => 'Konsumsi untuk kegiatan', 'color' => '#8B5CF6'],
            ['name' => 'Lain-lain', 'type' => 'income', 'description' => 'Pemasukan lainnya', 'color' => '#6B7280'],
            ['name' => 'Operasional', 'type' => 'expense', 'description' => 'Biaya operasional', 'color' => '#DC2626']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create demo transactions
        $transactions = [
            ['category_id' => 1, 'user_id' => $bendahara->id, 'type' => 'income', 'amount' => 50000, 'description' => 'Iuran bulan Januari - Andi', 'transaction_date' => '2024-01-15'],
            ['category_id' => 1, 'user_id' => $bendahara->id, 'type' => 'income', 'amount' => 50000, 'description' => 'Iuran bulan Januari - Budi', 'transaction_date' => '2024-01-16'],
            ['category_id' => 1, 'user_id' => $bendahara->id, 'type' => 'income', 'amount' => 50000, 'description' => 'Iuran bulan Januari - Citra', 'transaction_date' => '2024-01-17'],
            ['category_id' => 4, 'user_id' => $bendahara->id, 'type' => 'expense', 'amount' => 75000, 'description' => 'Pembelian spidol dan penghapus papan tulis', 'transaction_date' => '2024-01-20'],
            ['category_id' => 3, 'user_id' => $bendahara->id, 'type' => 'expense', 'amount' => 200000, 'description' => 'Biaya kegiatan class meeting', 'transaction_date' => '2024-01-25'],
            ['category_id' => 5, 'user_id' => $bendahara->id, 'type' => 'expense', 'amount' => 150000, 'description' => 'Konsumsi rapat kelas', 'transaction_date' => '2024-02-01'],
            ['category_id' => 1, 'user_id' => $bendahara->id, 'type' => 'income', 'amount' => 50000, 'description' => 'Iuran bulan Februari - Andi', 'transaction_date' => '2024-02-15'],
            ['category_id' => 1, 'user_id' => $bendahara->id, 'type' => 'income', 'amount' => 50000, 'description' => 'Iuran bulan Februari - Budi', 'transaction_date' => '2024-02-16'],
            ['category_id' => 2, 'user_id' => $bendahara->id, 'type' => 'income', 'amount' => 100000, 'description' => 'Donasi dari alumni', 'transaction_date' => '2024-02-20'],
            ['category_id' => 4, 'user_id' => $bendahara->id, 'type' => 'expense', 'amount' => 50000, 'description' => 'Pembelian kertas dan alat tulis', 'transaction_date' => '2024-02-25'],
            ['category_id' => 1, 'user_id' => $bendahara->id, 'type' => 'income', 'amount' => 50000, 'description' => 'Iuran bulan Maret - Andi', 'transaction_date' => '2024-03-15'],
            ['category_id' => 1, 'user_id' => $bendahara->id, 'type' => 'income', 'amount' => 50000, 'description' => 'Iuran bulan Maret - Budi', 'transaction_date' => '2024-03-16'],
            ['category_id' => 3, 'user_id' => $bendahara->id, 'type' => 'expense', 'amount' => 300000, 'description' => 'Biaya study tour kelas', 'transaction_date' => '2024-03-20'],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}
