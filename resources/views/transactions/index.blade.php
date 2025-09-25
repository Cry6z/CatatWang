@extends('layouts.app')

@section('title', 'Kelola Transaksi - CatatWang')
@section('page-title', 'Kelola Transaksi')
@section('page-description', 'Manajemen semua transaksi keuangan')

@section('page-actions')
@if(in_array(auth()->user()->role, ['admin', 'bendahara']))
<div class="flex flex-col sm:flex-row gap-3">
    <a href="{{ route('transactions.create', ['type' => 'income']) }}" 
       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
        <i class="fas fa-plus mr-2"></i>
        Tambah Pemasukan
    </a>
    <a href="{{ route('transactions.create', ['type' => 'expense']) }}" 
       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
        <i class="fas fa-minus mr-2"></i>
        Tambah Pengeluaran
    </a>
</div>
@endif
@endsection

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 dark:text-green-400 mr-3"></i>
            <span class="text-green-800 dark:text-green-200">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari deskripsi..." 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Tipe</option>
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                <select name="category_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Transaksi</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $transaction->transaction_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300' }}">
                                <i class="fas fa-{{ $transaction->type === 'income' ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                                {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $transaction->category->name ?? 'Tidak ada kategori' }}
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
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <a href="{{ route('transactions.show', $transaction) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-medium rounded-lg transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Lihat
                            </a>
                            @if(in_array(auth()->user()->role, ['admin', 'bendahara']))
                            <a href="{{ route('transactions.edit', $transaction) }}" 
                               class="inline-flex items-center px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-xs font-medium rounded-lg transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-medium rounded-lg transition-colors">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500 dark:text-gray-400">
                                <i class="fas fa-receipt text-4xl mb-4"></i>
                                <p>Belum ada transaksi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
