<?php
/**
 * CatatWang Demo Data Setup Script
 * Run this file to add demo users, categories, and transactions
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Transaction;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🚀 Setting up CatatWang demo data...\n\n";

try {
    // Create demo users
    echo "👥 Creating demo users...\n";
    
    $users = [
        [
            'name' => 'Administrator',
            'email' => 'admin@catatwang.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ],
        [
            'name' => 'Bendahara Kelas',
            'email' => 'bendahara@catatwang.com',
            'password' => Hash::make('admin123'),
            'role' => 'bendahara'
        ],
        [
            'name' => 'Anggota Kelas 1',
            'email' => 'anggota1@catatwang.com',
            'password' => Hash::make('admin123'),
            'role' => 'anggota'
        ]
    ];

    foreach ($users as $userData) {
        $user = User::where('email', $userData['email'])->first();
        if (!$user) {
            User::create($userData);
            echo "✅ Created user: {$userData['email']}\n";
        } else {
            echo "⚠️  User already exists: {$userData['email']}\n";
        }
    }

    // Create demo categories
    echo "\n📂 Creating demo categories...\n";
    
    $categories = [
        ['name' => 'Iuran Kelas', 'type' => 'income', 'description' => 'Iuran bulanan anggota kelas', 'color' => '#10B981'],
        ['name' => 'Donasi', 'type' => 'income', 'description' => 'Donasi dari pihak luar', 'color' => '#059669'],
        ['name' => 'Kegiatan Kelas', 'type' => 'expense', 'description' => 'Pengeluaran untuk kegiatan kelas', 'color' => '#EF4444'],
        ['name' => 'Perlengkapan', 'type' => 'expense', 'description' => 'Pembelian perlengkapan kelas', 'color' => '#F59E0B'],
        ['name' => 'Konsumsi', 'type' => 'expense', 'description' => 'Konsumsi untuk kegiatan', 'color' => '#8B5CF6'],
        ['name' => 'Lain-lain', 'type' => 'income', 'description' => 'Pemasukan lainnya', 'color' => '#6B7280'],
        ['name' => 'Operasional', 'type' => 'expense', 'description' => 'Biaya operasional', 'color' => '#DC2626']
    ];

    foreach ($categories as $categoryData) {
        $category = Category::where('name', $categoryData['name'])->first();
        if (!$category) {
            Category::create($categoryData);
            echo "✅ Created category: {$categoryData['name']}\n";
        } else {
            echo "⚠️  Category already exists: {$categoryData['name']}\n";
        }
    }

    // Create demo transactions
    echo "\n💰 Creating demo transactions...\n";
    
    $bendahara = User::where('email', 'bendahara@catatwang.com')->first();
    if ($bendahara) {
        $transactions = [
            [1, 'income', 50000, 'Iuran bulan Januari - Andi', '2024-01-15'],
            [1, 'income', 50000, 'Iuran bulan Januari - Budi', '2024-01-16'],
            [1, 'income', 50000, 'Iuran bulan Januari - Citra', '2024-01-17'],
            [4, 'expense', 75000, 'Pembelian spidol dan penghapus papan tulis', '2024-01-20'],
            [3, 'expense', 200000, 'Biaya kegiatan class meeting', '2024-01-25'],
            [5, 'expense', 150000, 'Konsumsi rapat kelas', '2024-02-01'],
            [1, 'income', 50000, 'Iuran bulan Februari - Andi', '2024-02-15'],
            [1, 'income', 50000, 'Iuran bulan Februari - Budi', '2024-02-16'],
            [2, 'income', 100000, 'Donasi dari alumni', '2024-02-20'],
            [4, 'expense', 50000, 'Pembelian kertas dan alat tulis', '2024-02-25'],
        ];

        foreach ($transactions as $transactionData) {
            $existing = Transaction::where('description', $transactionData[3])
                                 ->where('amount', $transactionData[2])
                                 ->first();
            
            if (!$existing) {
                Transaction::create([
                    'category_id' => $transactionData[0],
                    'user_id' => $bendahara->id,
                    'type' => $transactionData[1],
                    'amount' => $transactionData[2],
                    'description' => $transactionData[3],
                    'transaction_date' => $transactionData[4]
                ]);
                echo "✅ Created transaction: {$transactionData[3]}\n";
            } else {
                echo "⚠️  Transaction already exists: {$transactionData[3]}\n";
            }
        }
    }

    echo "\n🎉 Demo data setup completed successfully!\n\n";
    echo "📋 Demo Accounts:\n";
    echo "   Admin: admin@catatwang.com / admin123\n";
    echo "   Bendahara: bendahara@catatwang.com / admin123\n";
    echo "   Anggota: anggota1@catatwang.com / admin123\n\n";
    echo "🌐 Access your application at: http://localhost:8000\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Please check your database connection and try again.\n";
}
?>
