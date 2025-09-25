@extends('layouts.app')

@section('title')
Tambah @if($type === 'income') Pemasukan @else Pengeluaran @endif - CatatWang
@endsection

@section('page-title')
Tambah @if($type === 'income') Pemasukan @else Pengeluaran @endif
@endsection

@section('page-description')
Tambah data @if($type === 'income') pemasukan @else pengeluaran @endif baru
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Form Transaksi</h3>
        </div>

        <form method="POST" action="{{ route('transactions.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Transaction Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Jenis Transaksi</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative">
                        <input type="radio" 
                               name="type" 
                               value="income" 
                               {{ old('type', $type ?? 'income') == 'income' ? 'checked' : '' }}
                               class="sr-only peer"
                               onchange="updateCategories()">
                        <div class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-arrow-up text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Pemasukan</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Uang masuk ke kas</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="relative">
                        <input type="radio" 
                               name="type" 
                               value="expense" 
                               {{ old('type', $type ?? 'income') == 'expense' ? 'checked' : '' }}
                               class="sr-only peer"
                               onchange="updateCategories()">
                        <div class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 hover:border-red-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-arrow-down text-red-600 dark:text-red-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Pengeluaran</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Uang keluar dari kas</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                <select id="category_id" 
                        name="category_id" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white @error('category_id') border-red-500 @enderror">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                data-type="{{ $category->type }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jumlah (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400">Rp</span>
                    </div>
                    <input type="number" 
                           id="amount" 
                           name="amount" 
                           value="{{ old('amount') }}"
                           min="0" 
                           step="1000"
                           required
                           placeholder="0"
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white @error('amount') border-red-500 @enderror">
                </div>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                <textarea id="description" 
                          name="description" 
                          rows="3" 
                          required
                          placeholder="Jelaskan detail transaksi..."
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Transaction Date -->
            <div>
                <label for="transaction_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Transaksi</label>
                <input type="date" 
                       id="transaction_date" 
                       name="transaction_date" 
                       value="{{ old('transaction_date', date('Y-m-d')) }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white @error('transaction_date') border-red-500 @enderror">
                @error('transaction_date')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ url()->previous() }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                
                <button type="submit" 
                        class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateCategories() {
    const selectedType = document.querySelector('input[name="type"]:checked')?.value;
    const categorySelect = document.getElementById('category_id');
    const options = categorySelect.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === '') {
            option.style.display = 'block';
            return;
        }
        
        const optionType = option.getAttribute('data-type');
        if (selectedType && optionType !== selectedType) {
            option.style.display = 'none';
            if (option.selected) {
                option.selected = false;
            }
        } else {
            option.style.display = 'block';
        }
    });
    
    // Reset category selection if current selection doesn't match type
    const currentSelection = categorySelect.querySelector('option:checked');
    if (currentSelection && currentSelection.getAttribute('data-type') !== selectedType) {
        categorySelect.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCategories();
    
    // Format amount input
    const amountInput = document.getElementById('amount');
    amountInput.addEventListener('input', function() {
        // Remove non-numeric characters except decimal point
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>
@endpush
