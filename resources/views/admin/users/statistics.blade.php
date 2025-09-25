@extends('layouts.app')

@section('title', 'Statistik User - CatatWang')
@section('page-title', 'Statistik User')
@section('page-description', 'Overview data user sistem')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total User</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <!-- Admin Count -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-crown text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Admin</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['admin_count'] }}</p>
                </div>
            </div>
        </div>

        <!-- Bendahara Count -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-briefcase text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bendahara</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['bendahara_count'] }}</p>
                </div>
            </div>
        </div>

        <!-- Anggota Count -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Anggota</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['anggota_count'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Terbaru</h3>
        </div>
        
        <div class="p-6">
            @if($stats['recent_users']->count() > 0)
            <div class="space-y-4">
                @foreach($stats['recent_users'] as $user)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->role === 'admin') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300
                            @elseif($user->role === 'bendahara') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300 @endif">
                            @if($user->role === 'admin') 👑 Admin
                            @elseif($user->role === 'bendahara') 💼 Bendahara
                            @else 👤 Anggota @endif
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="fas fa-users text-4xl mb-4"></i>
                <p>Belum ada user terbaru</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
