@extends('layouts.app')

@section('title', 'Kategori - CatatWang')
@section('page-title', 'Kategori Transaksi')
@section('page-description', 'Kelola kategori pemasukan dan pengeluaran')

@section('page-actions')
@if(in_array(auth()->user()->role, ['admin', 'bendahara']))
<div class="flex space-x-3">
    <a href="{{ route('categories.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-plus mr-2"></i>
        Tambah Kategori
    </a>
</div>
@endif
@endsection

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-up text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kategori Pemasukan</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $categories->where('type', 'income')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-down text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kategori Pengeluaran</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $categories->where('type', 'expense')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Income Categories -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-arrow-up text-green-600 dark:text-green-400 mr-2"></i>
                Kategori Pemasukan
            </h3>
        </div>

        @php $incomeCategories = $categories->where('type', 'income') @endphp
        @if($incomeCategories->count() > 0)
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($incomeCategories as $category)
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</h4>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('categories.edit', $category) }}" 
                               class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="deleteCategory({{ $category->id }})" 
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    @if($category->description)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $category->description }}</p>
                    @endif
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">{{ $category->transactions_count }} transaksi</span>
                        <a href="{{ route('categories.show', $category) }}" 
                           class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-arrow-up text-3xl text-gray-400 mb-2"></i>
            <p class="text-gray-500 dark:text-gray-400">Belum ada kategori pemasukan</p>
        </div>
        @endif
    </div>

    <!-- Expense Categories -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-arrow-down text-red-600 dark:text-red-400 mr-2"></i>
                Kategori Pengeluaran
            </h3>
        </div>

        @php $expenseCategories = $categories->where('type', 'expense') @endphp
        @if($expenseCategories->count() > 0)
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($expenseCategories as $category)
                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $category->name }}</h4>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('categories.edit', $category) }}" 
                               class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="deleteCategory({{ $category->id }})" 
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    @if($category->description)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $category->description }}</p>
                    @endif
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">{{ $category->transactions_count }} transaksi</span>
                        <a href="{{ route('categories.show', $category) }}" 
                           class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-arrow-down text-3xl text-gray-400 mb-2"></i>
            <p class="text-gray-500 dark:text-gray-400">Belum ada kategori pengeluaran</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCategory(id) {
    Swal.fire({
        title: 'Hapus Kategori?',
        text: 'Kategori yang memiliki transaksi tidak dapat dihapus!',
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
            form.action = `/categories/${id}`;
            
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
