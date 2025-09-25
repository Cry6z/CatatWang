@extends('layouts.app')

@section('title', 'Edit Kategori - CatatWang')
@section('page-title', 'Edit Kategori')
@section('page-description', 'Ubah data kategori')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Kategori</h3>
        </div>

        <form method="POST" action="{{ route('categories.update', $category) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Category Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Kategori</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $category->name) }}"
                       required
                       placeholder="Masukkan nama kategori..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Jenis Kategori</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative">
                        <input type="radio" 
                               name="type" 
                               value="income" 
                               {{ old('type', $category->type) == 'income' ? 'checked' : '' }}
                               class="sr-only peer"
                               required>
                        <div class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-arrow-up text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Pemasukan</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kategori untuk pemasukan</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    <label class="relative">
                        <input type="radio" 
                               name="type" 
                               value="expense" 
                               {{ old('type', $category->type) == 'expense' ? 'checked' : '' }}
                               class="sr-only peer"
                               required>
                        <div class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 hover:border-red-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-arrow-down text-red-600 dark:text-red-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Pengeluaran</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kategori untuk pengeluaran</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color Picker -->
            <div>
                <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Warna Kategori</label>
                <div class="flex items-center space-x-4">
                    <input type="color" 
                           id="color" 
                           name="color" 
                           value="{{ old('color', $category->color) }}"
                           class="w-12 h-12 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer @error('color') border-red-500 @enderror">
                    <div class="flex-1">
                        <input type="text" 
                               id="color-text" 
                               value="{{ old('color', $category->color) }}"
                               readonly
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>
                @error('color')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi (Opsional)</label>
                <textarea id="description" 
                          name="description" 
                          rows="3" 
                          placeholder="Jelaskan kategori ini..."
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preview -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview</label>
                <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                    <div class="flex items-center">
                        <div id="preview-color" class="w-4 h-4 rounded-full mr-3" style="background-color: {{ old('color', $category->color) }}"></div>
                        <span id="preview-name" class="font-medium text-gray-900 dark:text-white">
                            {{ old('name', $category->name) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('categories.index') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                
                <button type="submit" 
                        class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('color-text');
    const previewColor = document.getElementById('preview-color');
    const nameInput = document.getElementById('name');
    const previewName = document.getElementById('preview-name');

    // Update color preview
    colorInput.addEventListener('input', function() {
        const color = this.value;
        colorText.value = color;
        previewColor.style.backgroundColor = color;
    });

    // Update name preview
    nameInput.addEventListener('input', function() {
        const name = this.value || '{{ $category->name }}';
        previewName.textContent = name;
    });
});
</script>
@endpush
