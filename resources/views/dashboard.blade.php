@extends('layouts.app')

@section('title', 'Dashboard - CatatWang')
@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan keuangan kelas')

@section('page-actions')
@can('manage-transactions')
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
@endcan
@endsection

@section('content')
<div class="space-y-8">
    <!-- Welcome Message -->
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat datang, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-primary-100">Kelola keuangan kelas dengan mudah dan efisien</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Balance Warning -->
    @if($showLowBalanceWarning)
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 shadow-sm">
        <div class="flex items-start">
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-1">⚠️ Peringatan Saldo Rendah</h3>
                <p class="text-yellow-700 dark:text-yellow-300 mb-3">Saldo kas kelas saat ini di bawah Rp 100.000. Segera lakukan penambahan pemasukan untuk menjaga stabilitas keuangan kelas.</p>
                @can('manage-transactions')
                <a href="{{ route('transactions.create', ['type' => 'income']) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Pemasukan Sekarang
                </a>
                @endcan
            </div>
        </div>
    </div>
    @endif

    <!-- Monthly & Yearly Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- This Month -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                    <div class="text-center">
                        <i class="fas fa-calendar-alt text-white text-lg mb-1"></i>
                        <div class="text-white text-xs font-bold">{{ now()->format('M') }}</div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-calendar-check text-green-600 dark:text-green-400 mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Bulan Ini</h3>
                    </div>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                        Rp {{ number_format(\App\Models\Transaction::whereMonth('transaction_date', now()->month)->whereYear('transaction_date', now()->year)->where('type', 'income')->sum('amount') - \App\Models\Transaction::whereMonth('transaction_date', now()->month)->whereYear('transaction_date', now()->year)->where('type', 'expense')->sum('amount'), 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <i class="fas fa-chart-line mr-1"></i>
                        📅 Saldo {{ now()->format('F Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- This Year -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                    <div class="text-center">
                        <i class="fas fa-calendar text-white text-lg mb-1"></i>
                        <div class="text-white text-xs font-bold">{{ now()->format('Y') }}</div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <i class="fas fa-calendar-week text-blue-600 dark:text-blue-400 mr-2"></i>
                        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Tahun Ini</h3>
                    </div>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                        Rp {{ number_format(\App\Models\Transaction::whereYear('transaction_date', now()->year)->where('type', 'income')->sum('amount') - \App\Models\Transaction::whereYear('transaction_date', now()->year)->where('type', 'expense')->sum('amount'), 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <i class="fas fa-trending-up mr-1"></i>
                        📆 Saldo {{ now()->format('Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Income -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-arrow-up text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</p>
                            <p class="text-xs text-green-600 dark:text-green-400 font-medium">↗ Semua waktu</p>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mb-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                    <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                        <i class="fas fa-chart-line mr-1"></i>
                        <span>Akumulasi dana masuk</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Expense -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-arrow-down text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                            <p class="text-xs text-red-600 dark:text-red-400 font-medium">↘ Semua waktu</p>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mb-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                    <div class="flex items-center text-sm text-red-600 dark:text-red-400">
                        <i class="fas fa-chart-line-down mr-1"></i>
                        <span>Akumulasi dana keluar</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Balance -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 {{ $currentBalance < 0 ? 'ring-2 ring-red-200 dark:ring-red-800' : '' }}">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br {{ $currentBalance >= 0 ? 'from-blue-400 to-blue-600' : 'from-red-400 to-red-600' }} rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas {{ $currentBalance >= 0 ? 'fa-wallet' : 'fa-exclamation-triangle' }} text-white text-lg"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Saat Ini</p>
                            <p class="text-xs {{ $currentBalance >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }} font-medium">
                                {{ $currentBalance >= 0 ? '💰 Sehat' : '⚠️ Defisit' }}
                            </p>
                        </div>
                    </div>
                    <p class="text-3xl font-bold {{ $currentBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mb-1">
                        Rp {{ number_format($currentBalance, 0, ',', '.') }}
                    </p>
                    <div class="flex items-center text-sm {{ $currentBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        <i class="fas {{ $currentBalance >= 0 ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1"></i>
                        <span>{{ $currentBalance >= 0 ? 'Kondisi baik' : 'Perlu perhatian' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Monthly Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">📊 Tren Keuangan {{ date('Y') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Perbandingan pemasukan dan pengeluaran bulanan</p>
                    </div>
                </div>
                <div class="flex flex-col space-y-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        Pemasukan
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200">
                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                        Pengeluaran
                    </span>
                </div>
            </div>
            <div class="relative">
                <canvas id="monthlyChart" height="300"></canvas>
            </div>
        </div>

        <!-- Expense by Category -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            <div class="flex items-center mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                    <i class="fas fa-chart-pie text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">🎯 Breakdown Pengeluaran</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Distribusi pengeluaran berdasarkan kategori</p>
                </div>
            </div>
            @if($expenseByCategory->count() > 0)
                <div class="relative">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            @else
                <div class="flex items-center justify-center h-64 text-gray-500 dark:text-gray-400">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-pie text-3xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Ada Data</h4>
                        <p class="text-sm">Mulai catat pengeluaran untuk melihat breakdown kategori</p>
                        @can('manage-transactions')
                        <a href="{{ route('transactions.create', ['type' => 'expense']) }}" 
                           class="inline-flex items-center px-4 py-2 mt-4 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Pengeluaran
                        </a>
                        @endcan
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-history text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">📋 Aktivitas Terbaru</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">10 transaksi terakhir yang dicatat</p>
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 hover:bg-primary-100 dark:hover:bg-primary-900/30 text-sm font-medium rounded-lg transition-colors">
                    <span>Lihat Semua</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        <div class="p-8">
            @if($recentTransactions->count() > 0)
                <div class="space-y-4">
                    @foreach($recentTransactions as $transaction)
                    <div class="group flex items-center justify-between p-5 bg-gray-50 dark:bg-gray-700/30 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-all duration-200 hover:shadow-md">
                        <div class="flex items-center flex-1">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $transaction->type === 'income' ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-red-400 to-red-600' }} shadow-lg">
                                <i class="fas {{ $transaction->type === 'income' ? 'fa-arrow-up' : 'fa-arrow-down' }} text-white"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ $transaction->description }}</p>
                                    <p class="text-lg font-bold {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full font-medium" 
                                          style="background-color: {{ $transaction->category->color }}20; color: {{ $transaction->category->color }}">
                                        <div class="w-1.5 h-1.5 rounded-full mr-1" style="background-color: {{ $transaction->category->color }}"></div>
                                        {{ $transaction->category->name }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $transaction->transaction_date->format('d M Y') }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $transaction->user->name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- View All Button -->
                <div class="mt-6 text-center">
                    <a href="{{ route('transactions.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Semua Transaksi
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-receipt text-3xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Belum Ada Transaksi</h4>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai catat pemasukan dan pengeluaran kelas Anda</p>
                    @can('manage-transactions')
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('transactions.create', ['type' => 'income']) }}" 
                           class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Pemasukan
                        </a>
                        <a href="{{ route('transactions.create', ['type' => 'expense']) }}" 
                           class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                            <i class="fas fa-minus mr-2"></i>
                            Tambah Pengeluaran
                        </a>
                    </div>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-2xl p-8">
        <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">🎯 Ringkasan Keuangan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-alt text-2xl text-primary-600 dark:text-primary-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-2">{{ $recentTransactions->count() }}</div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">📅 Transaksi Bulan Ini</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">
                        {{ number_format((($totalIncome > 0) ? ($currentBalance / $totalIncome) * 100 : 0), 1) }}%
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">📈 Efisiensi Keuangan</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tags text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                        {{ \App\Models\Category::count() }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">🏷️ Kategori Aktif</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded');
        return;
    }

    // Set Chart.js defaults
    Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
    Chart.defaults.color = '#6B7280';

    // Monthly Chart
    const monthlyChartElement = document.getElementById('monthlyChart');
    if (monthlyChartElement) {
        const monthlyCtx = monthlyChartElement.getContext('2d');
        
        // Prepare data
        const monthlyIncomeData = @json(array_values($monthlyData['income'] ?? []));
        const monthlyExpenseData = @json(array_values($monthlyData['expense'] ?? []));
        
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pemasukan',
                    data: monthlyIncomeData,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }, {
                    label: 'Pengeluaran',
                    data: monthlyExpenseData,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#EF4444',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#374151',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(107, 114, 128, 0.1)'
                        },
                        ticks: {
                            color: '#6B7280',
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 8
                    }
                }
            }
        });
    } else {
        console.error('Monthly chart element not found');
    }

    // Category Chart
    const categoryChartElement = document.getElementById('categoryChart');
    if (categoryChartElement) {
        @if($expenseByCategory->count() > 0)
        const categoryCtx = categoryChartElement.getContext('2d');
        
        // Prepare category data
        const categoryLabels = @json($expenseByCategory->pluck('name'));
        const categoryData = @json($expenseByCategory->pluck('amount'));
        const categoryColors = @json($expenseByCategory->pluck('color'));
        
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryData,
                    backgroundColor: categoryColors,
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 12
                            },
                            color: '#6B7280'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#374151',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID') + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true
                }
            }
        });
        @else
        // Show message if no data
        categoryChartElement.style.display = 'none';
        @endif
    }
});
</script>
@endpush
