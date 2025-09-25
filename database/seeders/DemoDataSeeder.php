<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Create demo users
        $admin = User::create([
            'name' => 'Admin CatatWang',
            'email' => 'admin@catatwang.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        $bendahara = User::create([
            'name' => 'Bendahara Kelas',
            'email' => 'bendahara@catatwang.com',
            'password' => Hash::make('bendahara123'),
            'role' => 'bendahara'
        ]);

        $anggota = User::create([
            'name' => 'Anggota Kelas',
            'email' => 'anggota@catatwang.com',
            'password' => Hash::make('anggota123'),
            'role' => 'anggota'
        ]);

        // Create demo categories
        $categories = [
            // Income categories
            ['name' => 'Iuran Bulanan', 'type' => 'income', 'color' => '#10B981', 'description' => 'Iuran rutin anggota kelas'],
            ['name' => 'Donasi', 'type' => 'income', 'color' => '#059669', 'description' => 'Donasi dari alumni atau pihak lain'],
            ['name' => 'Kegiatan Fundraising', 'type' => 'income', 'color' => '#047857', 'description' => 'Hasil kegiatan penggalangan dana'],
            
            // Expense categories
            ['name' => 'Konsumsi', 'type' => 'expense', 'color' => '#EF4444', 'description' => 'Pembelian makanan dan minuman'],
            ['name' => 'Alat Tulis', 'type' => 'expense', 'color' => '#DC2626', 'description' => 'Pembelian alat tulis dan perlengkapan'],
            ['name' => 'Dekorasi', 'type' => 'expense', 'color' => '#B91C1C', 'description' => 'Dekorasi untuk acara kelas'],
            ['name' => 'Transport', 'type' => 'expense', 'color' => '#991B1B', 'description' => 'Biaya transportasi kegiatan'],
            ['name' => 'Lain-lain', 'type' => 'expense', 'color' => '#7F1D1D', 'description' => 'Pengeluaran lainnya']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create demo transactions for the last 6 months
        $incomeCategories = Category::where('type', 'income')->get();
        $expenseCategories = Category::where('type', 'expense')->get();

        // Generate transactions for last 6 months
        for ($month = 5; $month >= 0; $month--) {
            $date = Carbon::now()->subMonths($month);
            
            // Income transactions (2-4 per month)
            for ($i = 0; $i < rand(2, 4); $i++) {
                Transaction::create([
                    'category_id' => $incomeCategories->random()->id,
                    'user_id' => [$admin->id, $bendahara->id][rand(0, 1)],
                    'type' => 'income',
                    'amount' => rand(50000, 500000),
                    'description' => $this->getRandomIncomeDescription(),
                    'transaction_date' => $date->copy()->addDays(rand(1, 28))
                ]);
            }

            // Expense transactions (3-8 per month)
            for ($i = 0; $i < rand(3, 8); $i++) {
                Transaction::create([
                    'category_id' => $expenseCategories->random()->id,
                    'user_id' => [$admin->id, $bendahara->id][rand(0, 1)],
                    'type' => 'expense',
                    'amount' => rand(10000, 200000),
                    'description' => $this->getRandomExpenseDescription(),
                    'transaction_date' => $date->copy()->addDays(rand(1, 28))
                ]);
            }
        }
    }

    private function getRandomIncomeDescription()
    {
        $descriptions = [
            'Iuran bulan ini dari seluruh anggota kelas',
            'Donasi dari alumni angkatan sebelumnya',
            'Hasil penjualan makanan di kantin',
            'Donasi dari wali murid untuk kegiatan kelas',
            'Hasil kegiatan bazaar kelas',
            'Iuran tambahan untuk acara perpisahan',
            'Donasi dari guru wali kelas',
            'Hasil penjualan merchandise kelas'
        ];

        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomExpenseDescription()
    {
        $descriptions = [
            'Pembelian snack untuk rapat kelas',
            'Beli spidol dan kertas untuk presentasi',
            'Dekorasi untuk acara ulang tahun kelas',
            'Ongkos angkot untuk survey lapangan',
            'Beli kue untuk perayaan kelulusan teman',
            'Pembelian hadiah untuk guru',
            'Biaya fotokopi materi pelajaran',
            'Beli minuman untuk gotong royong kelas',
            'Pembelian bunga untuk acara wisuda',
            'Ongkos untuk kunjungan ke museum',
            'Beli alat kebersihan kelas',
            'Pembelian trophy untuk lomba kelas'
        ];

        return $descriptions[array_rand($descriptions)];
    }
}
