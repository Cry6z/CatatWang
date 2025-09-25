@extends('layouts.app')

@section('title', 'Detail Kategori - CatatWang')
@section('page-title', 'Detail Kategori: ' . $category->name)
@section('page-description', 'Informasi lengkap kategori dan transaksi terkait')

@section('page-actions')
<div class="flex flex-col sm:flex-row gap-3">
    <a href="{{ route('categories.edit', $category) }}" 
       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
        <i class="fas fa-edit mr-2"></i>
        Edit Kategori
    </a>
    <a href="{{ route('categories.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Category Info -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Kategori</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Kategori</label>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $category->name }}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tipe</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $category->type === 'income' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                        <i class="fas fa-{{ $category->type === 'income' ? 'arrow-up' : 'arrow-down' }} mr-2"></i>
                        {{ $category->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Warna</label>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-lg border-2 border-gray-300 dark:border-gray-600 mr-3" style="background-color: {{ $category->color }}"></div>
                        <span class="text-gray-900 dark:text-white font-mono">{{ $category->color }}</span>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Transaksi</label>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $category->transactions->count() }} transaksi</span>
                </div>
                
                @if($category->description)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Deskripsi</label>
                    <p class="text-gray-900 dark:text-white">{{ $category->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaksi Terkait</h3>
        </div>
        
        @if($category->transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($category->transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $transaction->transaction_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ Str::limit($transaction->description, 50) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium
                            {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $transaction->user->name }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <a href="{{ route('transactions.show', $transaction) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-lg transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center">
            <div class="text-gray-500 dark:text-gray-400">
                <i class="fas fa-receipt text-4xl mb-4"></i>
                <p>Belum ada transaksi untuk kategori ini</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Statistics -->
    @if($category->transactions->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Statistik</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->transactions->count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Transaksi</div>
                </div>
                
                <div class="text-center">
                    <div class="text-2xl font-bold {{ $category->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        Rp {{ number_format($category->transactions->sum('amount'), 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Nominal</div>
                </div>
                
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($category->transactions->avg('amount'), 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Rata-rata</div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
