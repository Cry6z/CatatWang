<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return Transaction::with(['category', 'user'])
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date', $this->year)
            ->orderBy('transaction_date', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis',
            'Kategori',
            'Deskripsi',
            'Jumlah',
            'Dicatat Oleh',
            'Dibuat Pada'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
            $transaction->category->name,
            $transaction->description,
            $transaction->amount,
            $transaction->user->name,
            $transaction->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        $monthName = \DateTime::createFromFormat('!m', $this->month)->format('F');
        return "Laporan {$monthName} {$this->year}";
    }
}
