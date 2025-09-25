@extends('layouts.app')

@section('title', 'Tambah User - CatatWang')
@section('page-title', 'Tambah User Baru')
@section('page-description', 'Buat akun user baru untuk sistem')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf
            
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nama Lengkap
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors @error('name') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap"
                       required>
                @error('name')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Email
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors @error('email') border-red-500 @enderror"
                       placeholder="user@example.com"
                       required>
                @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Role
                </label>
                <select name="role" 
                        id="role"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors @error('role') border-red-500 @enderror"
                        required>
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>👑 Admin</option>
                    <option value="bendahara" {{ old('role') === 'bendahara' ? 'selected' : '' }}>💼 Bendahara</option>
                    <option value="anggota" {{ old('role') === 'anggota' ? 'selected' : '' }}>👤 Anggota</option>
                </select>
                @error('role')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Password
                </label>
                <input type="password" 
                       name="password" 
                       id="password"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors @error('password') border-red-500 @enderror"
                       placeholder="Minimal 8 karakter"
                       required>
                @error('password')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Konfirmasi Password
                </label>
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
                       placeholder="Ulangi password"
                       required>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                    <i class="fas fa-save mr-2"></i>
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
