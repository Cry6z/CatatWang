<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CatatWang - Manajemen Keuangan Kelas')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 lg:w-72 md:flex-col fixed left-0 top-0 h-full z-40">
            <div class="flex flex-col h-full bg-white dark:bg-gray-800 shadow-xl border-r border-gray-200 dark:border-gray-700 sidebar-scroll">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4 py-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center w-full">
                        <div class="relative">
                            <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                                <i class="fas fa-wallet text-white text-lg"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                        <div class="ml-3 flex-1">
                            <h1 class="text-lg font-black text-gray-900 dark:text-white">CatatWang</h1>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">💰 Finance Manager</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 overflow-y-auto">
                    <!-- Menu Cards -->
                    <div class="space-y-2">
                        <!-- Dashboard Card - All roles -->
                        @can('view-dashboard')
                        <div class="nav-card {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="card-link group">
                                <div class="flex items-center p-3">
                                    <div class="w-9 h-9 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-primary-600 shadow-lg' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-chart-line {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <span class="block font-semibold text-gray-900 dark:text-white text-sm">Dashboard</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">
                                            @if(auth()->user()->role === 'admin') Kontrol Penuh
                                            @elseif(auth()->user()->role === 'bendahara') Kelola Keuangan
                                            @else Lihat Ringkasan @endif
                                        </span>
                                    </div>
                                    @if(request()->routeIs('dashboard'))
                                    <div class="w-2 h-2 bg-primary-600 rounded-full animate-pulse"></div>
                                    @endif
                                </div>
                            </a>
                        </div>
                        @endcan

                        <!-- Admin Only - User Management -->
                        @if(auth()->user()->role === 'admin')
                        <div class="nav-card {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class="card-link group">
                                <div class="flex items-center p-3">
                                    <div class="w-9 h-9 rounded-xl {{ request()->routeIs('users.*') ? 'bg-indigo-600 shadow-lg' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-users {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <span class="block font-semibold text-gray-900 dark:text-white text-sm">Kelola User</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Admin Only</span>
                                    </div>
                                    @if(request()->routeIs('users.*'))
                                    <div class="w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></div>
                                    @endif
                                </div>
                            </a>
                        </div>
                        @endif

                        <!-- Transaction Management - All roles can view -->
                        <div class="nav-card {{ request()->routeIs('transactions.income') ? 'active' : '' }}">
                            <a href="{{ route('transactions.income') }}" class="card-link group">
                                <div class="flex items-center p-3">
                                    <div class="w-9 h-9 rounded-xl {{ request()->routeIs('transactions.income') ? 'bg-green-600 shadow-lg' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-arrow-up {{ request()->routeIs('transactions.income') ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <span class="block font-semibold text-gray-900 dark:text-white text-sm">Pemasukan</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">
                                            @if(auth()->user()->role === 'admin') Kontrol Penuh
                                            @elseif(auth()->user()->role === 'bendahara') Kelola Dana
                                            @else Lihat Data @endif
                                        </span>
                                    </div>
                                    @if(request()->routeIs('transactions.income'))
                                    <div class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></div>
                                    @endif
                                </div>
                            </a>
                        </div>

                        <div class="nav-card {{ request()->routeIs('transactions.expense') ? 'active' : '' }}">
                            <a href="{{ route('transactions.expense') }}" class="card-link group">
                                <div class="flex items-center p-3">
                                    <div class="w-9 h-9 rounded-xl {{ request()->routeIs('transactions.expense') ? 'bg-red-600 shadow-lg' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-arrow-down {{ request()->routeIs('transactions.expense') ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <span class="block font-semibold text-gray-900 dark:text-white text-sm">Pengeluaran</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">
                                            @if(auth()->user()->role === 'admin') Kontrol Penuh
                                            @elseif(auth()->user()->role === 'bendahara') Kelola Dana
                                            @else Lihat Data @endif
                                        </span>
                                    </div>
                                    @if(request()->routeIs('transactions.expense'))
                                    <div class="w-2 h-2 bg-red-600 rounded-full animate-pulse"></div>
                                    @endif
                                </div>
                            </a>
                        </div>

                        <!-- Reports - All roles can view -->
                        <div class="nav-card {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <a href="{{ route('reports.index') }}" class="card-link group">
                                <div class="flex items-center p-3">
                                    <div class="w-9 h-9 rounded-xl {{ request()->routeIs('reports.*') ? 'bg-orange-600 shadow-lg' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-chart-bar {{ request()->routeIs('reports.*') ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <span class="block font-semibold text-gray-900 dark:text-white text-sm">Laporan</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">
                                            @if(auth()->user()->role === 'admin') Analisis & Export
                                            @elseif(auth()->user()->role === 'bendahara') Analisis & Export
                                            @else Lihat Laporan @endif
                                        </span>
                                    </div>
                                    @if(request()->routeIs('reports.*'))
                                    <div class="w-2 h-2 bg-orange-600 rounded-full animate-pulse"></div>
                                    @endif
                                </div>
                            </a>
                        </div>

                        <!-- Admin & Bendahara - Categories -->
                        @if(in_array(auth()->user()->role, ['admin', 'bendahara']))
                        <div class="nav-card {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <a href="{{ route('categories.index') }}" class="card-link group">
                                <div class="flex items-center p-3">
                                    <div class="w-9 h-9 rounded-xl {{ request()->routeIs('categories.*') ? 'bg-purple-600 shadow-lg' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-tags {{ request()->routeIs('categories.*') ? 'text-white' : 'text-gray-600 dark:text-gray-300' }}"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <span class="block font-semibold text-gray-900 dark:text-white text-sm">Kategori</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Kelola Kategori</span>
                                    </div>
                                    @if(request()->routeIs('categories.*'))
                                    <div class="w-2 h-2 bg-purple-600 rounded-full animate-pulse"></div>
                                    @endif
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="pt-4 space-y-2">
                        @if(auth()->user()->role !== 'anggota')
                        <div class="text-center">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                @if(auth()->user()->role === 'admin') Admin Panel
                                @else Aksi Cepat @endif
                            </span>
                        </div>
                        @endif
                        
                        @if(auth()->user()->role === 'admin')
                        <!-- Admin Quick Actions -->
                        <div class="space-y-3">
                            <!-- Primary Admin Actions -->
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('users.create') }}" class="admin-action-card group bg-gradient-to-br from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white shadow-lg hover:shadow-xl">
                                    <div class="flex flex-col items-center p-3 relative z-10">
                                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-user-plus text-white"></i>
                                        </div>
                                        <span class="text-xs font-bold text-center leading-tight">Tambah<br>User</span>
                                    </div>
                                </a>
                                
                                <a href="{{ route('users.statistics') }}" class="admin-action-card group bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white shadow-lg hover:shadow-xl">
                                    <div class="flex flex-col items-center p-3 relative z-10">
                                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-chart-pie text-white"></i>
                                        </div>
                                        <span class="text-xs font-bold text-center leading-tight">Statistik<br>User</span>
                                    </div>
                                </a>
                            </div>
                            
                            <!-- Secondary Admin Actions -->
                            <div class="grid grid-cols-1 gap-2">
                                <a href="{{ route('users.index') }}" class="admin-action-card group bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white shadow-md hover:shadow-lg">
                                    <div class="flex items-center p-2.5 relative z-10">
                                        <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-users text-white text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-xs font-bold">👑 Kelola Semua User</span>
                                            <div class="text-xs opacity-80">Admin Control Panel</div>
                                        </div>
                                        <i class="fas fa-chevron-right text-white/60 group-hover:text-white group-hover:translate-x-1 transition-all duration-300"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @elseif(auth()->user()->role === 'bendahara')
                        <!-- Bendahara Quick Actions -->
                        <div class="space-y-2">
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="bendahara-action-card group bg-gradient-to-br from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white shadow-lg hover:shadow-xl">
                                    <div class="flex flex-col items-center p-3 relative z-10">
                                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-plus text-white"></i>
                                        </div>
                                        <span class="text-xs font-bold text-center leading-tight">Tambah<br>Pemasukan</span>
                                    </div>
                                </a>
                                
                                <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="bendahara-action-card group bg-gradient-to-br from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white shadow-lg hover:shadow-xl">
                                    <div class="flex flex-col items-center p-3 relative z-10">
                                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-minus text-white"></i>
                                        </div>
                                        <span class="text-xs font-bold text-center leading-tight">Tambah<br>Pengeluaran</span>
                                    </div>
                                </a>
                            </div>
                            
                            <!-- Bendahara Management -->
                            <div class="grid grid-cols-1 gap-2">
                                <a href="{{ route('categories.index') }}" class="bendahara-action-card group bg-gradient-to-r from-violet-600 to-purple-700 hover:from-violet-700 hover:to-purple-800 text-white shadow-md hover:shadow-lg">
                                    <div class="flex items-center p-2.5 relative z-10">
                                        <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-300">
                                            <i class="fas fa-tags text-white text-sm"></i>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-xs font-bold">💼 Kelola Kategori</span>
                                            <div class="text-xs opacity-80">Bendahara Panel</div>
                                        </div>
                                        <i class="fas fa-chevron-right text-white/60 group-hover:text-white group-hover:translate-x-1 transition-all duration-300"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </nav>

                <!-- User Profile & Dark Mode -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-3">
                    <!-- Dark Mode Toggle -->
                    <div class="flex justify-center mb-3">
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                                class="group p-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300">
                            <i class="fas fa-moon dark:hidden text-gray-600 group-hover:scale-110 transition-transform"></i>
                            <i class="fas fa-sun hidden dark:block text-yellow-500 group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>
                    
                    <!-- User Profile Card -->
                    <div class="user-profile-card">
                        <div class="flex items-center p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-300">
                            <div class="relative">
                                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <span class="text-sm font-black text-white">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                                    {{ auth()->user()->role === 'admin' ? '👑 Admin' : (auth()->user()->role === 'bendahara' ? '💼 Bendahara' : '👤 Anggota') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logout Button - Fixed at Bottom -->
                <div class="absolute bottom-0 left-0 right-0 p-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmLogout()">
                        @csrf
                        <button type="submit" class="w-full group flex items-center justify-center p-3 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-xl border border-red-200 dark:border-red-800 hover:border-red-300 dark:hover:border-red-700 transition-all duration-300">
                            <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-sign-out-alt text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-red-700 dark:text-red-300 group-hover:text-red-800 dark:group-hover:text-red-200 transition-colors">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-primary-600 to-primary-700 shadow-lg" x-data="{ mobileMenuOpen: false }">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wallet text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-lg font-bold text-white">CatatWang</h1>
                        <p class="text-xs text-primary-100">Manajemen Keuangan</p>
                    </div>
                </div>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors">
                    <i class="fas fa-bars text-white"></i>
                </button>
            </div>
            
            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="bg-primary-700 border-t border-white/10">
                <nav class="px-4 py-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('transactions.income') }}" class="mobile-nav-link {{ request()->routeIs('transactions.income') ? 'active' : '' }}">
                        <i class="fas fa-arrow-up text-green-300"></i>
                        <span>Pemasukan</span>
                    </a>
                    <a href="{{ route('transactions.expense') }}" class="mobile-nav-link {{ request()->routeIs('transactions.expense') ? 'active' : '' }}">
                        <i class="fas fa-arrow-down text-red-300"></i>
                        <span>Pengeluaran</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="mobile-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-file-chart-line"></i>
                        <span>Laporan</span>
                    </a>
                    @can('manage-categories')
                    <a href="{{ route('categories.index') }}" class="mobile-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Kategori</span>
                    </a>
                    @endcan
                </nav>
                
                <!-- Mobile User Info -->
                <div class="px-4 py-3 border-t border-white/10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-white">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-primary-200 capitalize">{{ auth()->user()->role }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden md:ml-64 lg:ml-72">
            <!-- Page header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                @yield('page-title', 'Dashboard')
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                @yield('page-description', 'Kelola keuangan kelas dengan mudah')
                            </p>
                        </div>
                        @yield('page-actions')
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 mt-16 md:mt-0">
                <!-- Flash messages -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <style>
        /* Navigation Cards */
        .nav-card {
            @apply rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-300 overflow-hidden;
        }
        .nav-card.active {
            @apply border-primary-300 dark:border-primary-600 bg-primary-50 dark:bg-primary-900/20 shadow-lg;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.15);
        }
        .nav-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .card-link {
            @apply block w-full transition-all duration-300;
        }
        
        /* Quick Action Cards */
        .quick-card {
            @apply flex flex-col items-center justify-center p-3 rounded-xl transition-all duration-300 text-center;
        }
        .quick-card:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Admin Action Cards */
        .admin-action-card {
            @apply relative rounded-xl transition-all duration-300 text-center overflow-hidden block;
            z-index: 1;
        }
        .admin-action-card:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Bendahara Action Cards */
        .bendahara-action-card {
            @apply relative rounded-xl transition-all duration-300 text-center overflow-hidden block;
            z-index: 1;
        }
        .bendahara-action-card:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        /* Anggota Info Cards */
        .anggota-info-card {
            @apply relative rounded-xl transition-all duration-300 text-center overflow-hidden block;
            z-index: 1;
            text-decoration: none;
        }
        .anggota-info-card:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            text-decoration: none;
        }
        
        /* User Profile Card */
        .user-profile-card {
            @apply transition-all duration-300;
        }
        .user-profile-card:hover {
            transform: translateY(-2px);
        }
        
        /* Mobile navigation */
        .mobile-nav-link {
            @apply flex items-center px-4 py-3 text-sm font-medium rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all duration-300;
        }
        .mobile-nav-link.active {
            @apply text-white bg-white/15;
        }
        .mobile-nav-link i {
            @apply mr-3 w-5 text-center;
        }
        
        /* Scrollbar styling for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(156, 163, 175, 0.1);
            border-radius: 2px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 2px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.5);
        }
        
        /* Dark mode scrollbar */
        .dark .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(75, 85, 99, 0.1);
        }
        .dark .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.3);
        }
        .dark .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(75, 85, 99, 0.5);
        }
        
        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .nav-card {
                @apply mx-1;
            }
        }
        
        @media (max-width: 768px) {
            .nav-card {
                @apply px-2 py-1;
            }
        }
    </style>
    
    <script>
        // Enhanced logout confirmation with security
        function confirmLogout() {
            Swal.fire({
                title: '🚪 Konfirmasi Logout',
                html: `
                    <div class="text-left">
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Apakah Anda yakin ingin keluar dari CatatWang?</p>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3 mb-4">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 mt-1 mr-2"></i>
                                <div class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <strong>Peringatan Keamanan:</strong><br>
                                    • Pastikan Anda telah menyimpan semua pekerjaan<br>
                                    • Jangan logout di komputer umum tanpa menutup browser<br>
                                    • Sesi akan berakhir dan Anda perlu login ulang
                                </div>
                            </div>
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i>Ya, Logout Sekarang',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                reverseButtons: true,
                focusCancel: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'rounded-2xl border border-gray-300 dark:border-gray-600',
                    confirmButton: 'rounded-xl px-6 py-3 font-semibold',
                    cancelButton: 'rounded-xl px-6 py-3 font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: '🔄 Sedang Logout...',
                        text: 'Mohon tunggu, sedang mengamankan sesi Anda',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Perform logout with security measures
                    performSecureLogout();
                }
            });
            return false; // Prevent form submission initially
        }

        function performSecureLogout() {
            // Create form data
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'POST');

            // Send logout request
            fetch('{{ route("logout") }}', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Clear any stored data
                    if (typeof(Storage) !== "undefined") {
                        localStorage.clear();
                        sessionStorage.clear();
                    }
                    
                    // Clear cookies (basic cleanup)
                    document.cookie.split(";").forEach(function(c) { 
                        document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
                    });

                    // Show success message
                    Swal.fire({
                        title: '✅ Logout Berhasil!',
                        html: `
                            <div class="text-center">
                                <p class="text-gray-600 dark:text-gray-300 mb-4">Anda telah berhasil keluar dari CatatWang</p>
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-shield-alt text-green-600 dark:text-green-400 mr-2"></i>
                                        <span class="text-sm text-green-800 dark:text-green-200 font-medium">Sesi Anda telah diamankan</span>
                                    </div>
                                </div>
                            </div>
                        `,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'rounded-2xl border border-gray-300 dark:border-gray-600'
                        }
                    }).then(() => {
                        // Force redirect to login page
                        window.location.href = '{{ route("login") }}';
                        
                        // Additional security: reload page after short delay
                        setTimeout(() => {
                            window.location.reload(true);
                        }, 100);
                    });
                } else {
                    throw new Error('Logout failed');
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
                Swal.fire({
                    title: '❌ Logout Gagal',
                    text: 'Terjadi kesalahan saat logout. Silakan coba lagi atau tutup browser.',
                    icon: 'error',
                    confirmButtonText: 'Coba Lagi',
                    customClass: {
                        popup: 'rounded-2xl border border-gray-300 dark:border-gray-600',
                        confirmButton: 'rounded-xl px-6 py-3 font-semibold'
                    }
                });
            });
        }

        // Prevent back button after logout
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                // Check if user is logged out by making a quick auth check
                fetch('{{ route("dashboard") }}', {
                    method: 'HEAD',
                    credentials: 'same-origin'
                }).then(response => {
                    if (response.status === 401 || response.redirected) {
                        window.location.href = '{{ route("login") }}';
                    }
                }).catch(() => {
                    window.location.href = '{{ route("login") }}';
                });
            }
        });

        // Session timeout warning
        let sessionTimeout;
        let warningTimeout;
        
        function resetSessionTimer() {
            clearTimeout(sessionTimeout);
            clearTimeout(warningTimeout);
            
            // Warning 5 minutes before session expires (25 minutes)
            warningTimeout = setTimeout(() => {
                Swal.fire({
                    title: '⏰ Sesi Akan Berakhir',
                    text: 'Sesi Anda akan berakhir dalam 5 menit. Klik "Perpanjang" untuk melanjutkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Perpanjang Sesi',
                    cancelButtonText: 'Logout Sekarang',
                    timer: 300000, // 5 minutes
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl border border-gray-300 dark:border-gray-600'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Extend session by making a simple request
                        fetch('{{ route("dashboard") }}', {
                            method: 'HEAD',
                            credentials: 'same-origin'
                        }).then(() => {
                            resetSessionTimer();
                            Swal.fire({
                                title: '✅ Sesi Diperpanjang',
                                text: 'Sesi Anda telah diperpanjang',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });
                    } else {
                        performSecureLogout();
                    }
                });
            }, 25 * 60 * 1000); // 25 minutes
            
            // Auto logout after 30 minutes
            sessionTimeout = setTimeout(() => {
                Swal.fire({
                    title: '🔒 Sesi Berakhir',
                    text: 'Sesi Anda telah berakhir karena tidak ada aktivitas',
                    icon: 'info',
                    confirmButtonText: 'Login Ulang',
                    allowOutsideClick: false,
                    customClass: {
                        popup: 'rounded-2xl border border-gray-300 dark:border-gray-600'
                    }
                }).then(() => {
                    performSecureLogout();
                });
            }, 30 * 60 * 1000); // 30 minutes
        }
        
        // Start session timer on page load
        document.addEventListener('DOMContentLoaded', function() {
            resetSessionTimer();
            
            // Reset timer on user activity
            ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(event => {
                document.addEventListener(event, resetSessionTimer, { passive: true });
            });
        });
    </script>
</body>
</html>
