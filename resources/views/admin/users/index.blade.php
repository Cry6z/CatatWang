@extends('layouts.app')

@section('title', 'Kelola User - CatatWang')
@section('page-title', 'Kelola User')
@section('page-description', 'Manajemen user dan role sistem')

@section('page-actions')
<div class="flex flex-col sm:flex-row gap-3">
    <a href="{{ route('users.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
        <i class="fas fa-user-plus mr-2"></i>
        Tambah User Baru
    </a>
    <a href="{{ route('users.statistics') }}" 
       class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
        <i class="fas fa-chart-pie mr-2"></i>
        Statistik User
    </a>
</div>
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

    @if(session('error'))
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mr-3"></i>
            <span class="text-red-800 dark:text-red-200">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar User</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->role === 'admin') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300
                                @elseif($user->role === 'bendahara') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300 @endif">
                                @if($user->role === 'admin') 👑 Admin
                                @elseif($user->role === 'bendahara') 💼 Bendahara
                                @else 👤 Anggota @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <a href="{{ route('users.edit', $user) }}" 
                               class="inline-flex items-center px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-xs font-medium rounded-lg transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
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
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-gray-500 dark:text-gray-400">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p>Belum ada user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
