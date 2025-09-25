@extends('layouts.app')

@section('title', 'Laporan Keuangan - CatatWang')
@section('page-title', 'Laporan Keuangan')
@section('page-description', 'Laporan dan analisis keuangan kelas')

@section('page-actions')
@if(in_array(auth()->user()->role, ['admin', 'bendahara']))
<div class="flex space-x-3">
    <a href="{{ route('reports.export.pdf', ['month' => $month, 'year' => $year]) }}" 
       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-file-pdf mr-2"></i>
        Export PDF
    </a>
    <a href="{{ route('reports.export.excel', ['month' => $month, 'year' => $year]) }}" 
       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="fas fa-file-excel mr-2"></i>
        Export Excel
    </a>
</div>
@endif
@endsection

@section('content')
<div class="space-y-6">
    <!-- Period Selection -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pilih Periode Laporan</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan</label>
                <select name="month" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                <select name="year" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                    @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Monthly Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-up text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pemasukan {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-down text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pengeluaran {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-balance-scale text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Saldo Bulan Ini</p>
                    <p class="text-2xl font-bold {{ $monthlyBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        Rp {{ number_format($monthlyBalance, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Summary -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Ringkasan Keseluruhan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Pemasukan</p>
                <p class="text-xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
            <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Pengeluaran</p>
                <p class="text-xl font-bold text-red-600 dark:text-red-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
            </div>
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Saldo Keseluruhan</p>
                <p class="text-xl font-bold {{ $overallBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    Rp {{ number_format($overallBalance, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Income by Category -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Pemasukan per Kategori</h3>
            @if($incomeByCategory->count() > 0)
                <div class="space-y-4">
                    @foreach($incomeByCategory as $item)
                    <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $item->category->color }}"></div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->category->name }}</span>
                        </div>
                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <i class="fas fa-chart-pie text-3xl mb-2"></i>
                    <p>Tidak ada data pemasukan</p>
                </div>
            @endif
        </div>

        <!-- Expense by Category -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Pengeluaran per Kategori</h3>
            @if($expenseByCategory->count() > 0)
                <div class="space-y-4">
                    @foreach($expenseByCategory as $item)
                    <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $item->category->color }}"></div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->category->name }}</span>
                        </div>
                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <i class="fas fa-chart-pie text-3xl mb-2"></i>
                    <p>Tidak ada data pengeluaran</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Detailed Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Detail Transaksi {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
            </h3>
        </div>

        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dicatat oleh</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            {{ $transaction->transaction_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-200' }}">
                                <i class="fas {{ $transaction->type === 'income' ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                                {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $transaction->user->name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada transaksi</h3>
            <p class="text-gray-500 dark:text-gray-400">Tidak ada transaksi pada periode yang dipilih</p>
        </div>
        @endif
    </div>
</div>
@endsection
