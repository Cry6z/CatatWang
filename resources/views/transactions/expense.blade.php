@extends('layouts.app')

@section('title', 'Pengeluaran - CatatWang')
@section('page-title', 'Pengeluaran')
@section('page-description', 'Kelola data pengeluaran kelas')

@section('page-actions')
@can('manage-transactions')
<div class="flex space-x-3">
    <a href="{{ route('transactions.create', ['type' => 'expense']) }}" 
       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-plus mr-2"></i>
        Tambah Pengeluaran
    </a>
</div>
@endcan
@endsection

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-month text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bulan Ini</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        Rp {{ number_format($transactions->where('transaction_date', '>=', now()->startOfMonth())->sum('amount'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-year text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tahun Ini</p>
                    <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                        Rp {{ number_format($transactions->where('transaction_date', '>=', now()->startOfYear())->sum('amount'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-infinity text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Keseluruhan</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        Rp {{ number_format($transactions->sum('amount'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pencarian</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari deskripsi..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                <select name="category_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dari Tanggal</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sampai Tanggal</label>
                <input type="date" 
                       name="date_to" 
                       value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="md:col-span-4 flex space-x-3">
                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('transactions.expense') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Pengeluaran</h3>
        </div>

        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dicatat oleh</th>
                        @can('manage-transactions')
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $transaction->transaction_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                  style="background-color: {{ $transaction->category->color }}20; color: {{ $transaction->category->color }}">
                                {{ $transaction->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            {{ $transaction->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600 dark:text-red-400">
                            - Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $transaction->user->name }}
                        </td>
                        @can('manage-transactions')
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('transactions.show', $transaction) }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('transactions.edit', $transaction) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteTransaction({{ $transaction->id }})" 
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-arrow-down text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada data pengeluaran</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai catat pengeluaran kelas Anda</p>
            @can('manage-transactions')
            <a href="{{ route('transactions.create', ['type' => 'expense']) }}" 
               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Tambah Pengeluaran Pertama
            </a>
            @endcan
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteTransaction(id) {
    Swal.fire({
        title: 'Hapus Transaksi?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/transactions/${id}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
