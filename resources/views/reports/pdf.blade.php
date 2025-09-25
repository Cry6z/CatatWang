<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan {{ $monthName }} - CatatWang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #3B82F6;
            margin: 0;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #666;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
        }
        .summary-item.income {
            background-color: #f0fdf4;
            border-color: #10b981;
        }
        .summary-item.expense {
            background-color: #fef2f2;
            border-color: #ef4444;
        }
        .summary-item.balance {
            background-color: #eff6ff;
            border-color: #3b82f6;
        }
        .summary-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .summary-amount {
            font-size: 16px;
            font-weight: bold;
        }
        .summary-amount.positive {
            color: #10b981;
        }
        .summary-amount.negative {
            color: #ef4444;
        }
        .summary-amount.neutral {
            color: #3b82f6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8fafc;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            font-size: 11px;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .amount.income {
            color: #10b981;
        }
        .amount.expense {
            color: #ef4444;
        }
        .category-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .type-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .type-badge.income {
            background-color: #10b981;
        }
        .type-badge.expense {
            background-color: #ef4444;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CatatWang</h1>
        <h2>Laporan Keuangan {{ $monthName }}</h2>
        <p>Sistem Manajemen Keuangan Kelas</p>
    </div>

    <div class="summary">
        <h3>Ringkasan Keuangan</h3>
        <div class="summary-grid">
            <div class="summary-item income">
                <div class="summary-label">Total Pemasukan</div>
                <div class="summary-amount positive">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item expense">
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-amount negative">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item balance">
                <div class="summary-label">Saldo Bersih</div>
                <div class="summary-amount {{ $monthlyBalance >= 0 ? 'positive' : 'negative' }}">
                    Rp {{ number_format($monthlyBalance, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="transactions">
        <h3>Detail Transaksi</h3>
        
        @if($transactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Dicatat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                    <td>
                        <span class="type-badge {{ $transaction->type }}">
                            {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </td>
                    <td>
                        <span class="category-badge" style="background-color: {{ $transaction->category->color }}">
                            {{ $transaction->category->name }}
                        </span>
                    </td>
                    <td>{{ $transaction->description }}</td>
                    <td class="amount {{ $transaction->type }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </td>
                    <td>{{ $transaction->user->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">
            Tidak ada transaksi pada periode ini
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem CatatWang pada {{ now()->format('d F Y, H:i') }} WIB</p>
        <p>© {{ date('Y') }} CatatWang - Sistem Manajemen Keuangan Kelas</p>
    </div>
</body>
</html>
