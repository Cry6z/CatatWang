@extends('layouts.app')

@section('title', 'Detail Transaksi - CatatWang')
@section('page-title', 'Detail Transaksi')
@section('page-description', 'Informasi lengkap transaksi')

@section('page-actions')
@if(in_array(auth()->user()->role, ['admin', 'bendahara']))
<div class="flex space-x-3">
    <a href="{{ route('transactions.edit', $transaction) }}" 
       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-edit mr-2"></i>
        Edit Transaksi
    </a>
    <button onclick="deleteTransaction({{ $transaction->id }})" 
            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-trash mr-2"></i>
        Hapus Transaksi
    </button>
</div>
@endif
<a href="{{ route('transactions.index') }}" 
   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
    <i class="fas fa-arrow-left mr-2"></i>
    Kembali
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Transaction Header -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20' }}">
                    <i class="fas {{ $transaction->type === 'income' ? 'fa-arrow-up text-green-600 dark:text-green-400' : 'fa-arrow-down text-red-600 dark:text-red-400' }} text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                    </h1>
                    <p class="text-lg {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-semibold">
                        {{ $transaction->type === 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200' }}">
                    <i class="fas {{ $transaction->type === 'income' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                    {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Transaction Details -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Deskripsi</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $transaction->description }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Kategori</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                          style="background-color: {{ $transaction->category->color }}20; color: {{ $transaction->category->color }}">
                        <div class="w-2 h-2 rounded-full mr-2" style="background-color: {{ $transaction->category->color }}"></div>
                        {{ $transaction->category->name }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal Transaksi</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $transaction->transaction_date->format('d F Y') }}</p>
                </div>
            </div>

            <!-- Meta Information -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dicatat Oleh</label>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-primary-600 dark:text-primary-400">
                                {{ substr($transaction->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $transaction->user->name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ $transaction->user->role }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat Pada</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $transaction->created_at->format('d F Y, H:i') }} WIB</p>
                </div>

                @if($transaction->updated_at != $transaction->created_at)
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Terakhir Diubah</label>
                    <p class="text-gray-900 dark:text-white font-medium">{{ $transaction->updated_at->format('d F Y, H:i') }} WIB</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Category Information -->
    @if($transaction->category->description)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Kategori</h3>
        <div class="flex items-start">
            <div class="w-4 h-4 rounded-full mt-1 mr-3" style="background-color: {{ $transaction->category->color }}"></div>
            <div>
                <h4 class="font-medium text-gray-900 dark:text-white mb-1">{{ $transaction->category->name }}</h4>
                <p class="text-gray-600 dark:text-gray-400">{{ $transaction->category->description }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Related Transactions -->
    @php
    $relatedTransactions = \App\Models\Transaction::where('category_id', $transaction->category_id)
        ->where('id', '!=', $transaction->id)
        ->with(['user'])
        ->orderBy('transaction_date', 'desc')
        ->limit(5)
        ->get();
    @endphp

    @if($relatedTransactions->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Transaksi Terkait (Kategori: {{ $transaction->category->name }})</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($relatedTransactions as $related)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $related->type === 'income' ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20' }}">
                            <i class="fas {{ $related->type === 'income' ? 'fa-arrow-up text-green-600 dark:text-green-400' : 'fa-arrow-down text-red-600 dark:text-red-400' }} text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $related->description }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $related->transaction_date->format('d M Y') }} • {{ $related->user->name }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold {{ $related->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $related->type === 'income' ? '+' : '-' }} Rp {{ number_format($related->amount, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('transactions.show', $related) }}" 
                           class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex items-center justify-between">
        <a href="{{ url()->previous() }}" 
           class="inline-flex items-center px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>

        <div class="flex space-x-3">
            <a href="{{ route('transactions.index') }}" 
               class="inline-flex items-center px-4 py-2 text-primary-700 dark:text-primary-300 bg-primary-100 dark:bg-primary-900/20 hover:bg-primary-200 dark:hover:bg-primary-900/30 rounded-lg transition-colors">
                <i class="fas fa-list mr-2"></i>Semua Transaksi
            </a>
            
            @if($transaction->type === 'income')
            <a href="{{ route('transactions.income') }}" 
               class="inline-flex items-center px-4 py-2 text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/20 hover:bg-green-200 dark:hover:bg-green-900/30 rounded-lg transition-colors">
                <i class="fas fa-arrow-up mr-2"></i>Pemasukan Lainnya
            </a>
            @else
            <a href="{{ route('transactions.expense') }}" 
               class="inline-flex items-center px-4 py-2 text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/20 hover:bg-red-200 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                <i class="fas fa-arrow-down mr-2"></i>Pengeluaran Lainnya
            </a>
            @endif
        </div>
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
