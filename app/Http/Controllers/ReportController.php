<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // Monthly report data
        $monthlyIncome = Transaction::income()
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        $monthlyExpense = Transaction::expense()
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->sum('amount');

        $monthlyBalance = $monthlyIncome - $monthlyExpense;

        // Transactions for the selected month
        $transactions = Transaction::with(['category', 'user'])
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Category breakdown
        $incomeByCategory = Transaction::income()
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get();

        $expenseByCategory = Transaction::expense()
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get();

        // Overall summary
        $totalIncome = Transaction::income()->sum('amount');
        $totalExpense = Transaction::expense()->sum('amount');
        $overallBalance = $totalIncome - $totalExpense;

        return view('reports.index', compact(
            'month',
            'year',
            'monthlyIncome',
            'monthlyExpense',
            'monthlyBalance',
            'transactions',
            'incomeByCategory',
            'expenseByCategory',
            'totalIncome',
            'totalExpense',
            'overallBalance'
        ));
    }

    public function exportPdf(Request $request)
    {
        try {
            // Check permission
            if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
                abort(403, 'Unauthorized');
            }

            $month = $request->get('month', Carbon::now()->month);
            $year = $request->get('year', Carbon::now()->year);

            $monthName = Carbon::createFromDate($year, $month, 1)->format('F Y');

            $transactions = Transaction::with(['category', 'user'])
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->orderBy('transaction_date', 'desc')
                ->get();

            $monthlyIncome = $transactions->where('type', 'income')->sum('amount');
            $monthlyExpense = $transactions->where('type', 'expense')->sum('amount');
            $monthlyBalance = $monthlyIncome - $monthlyExpense;

            $pdf = Pdf::loadView('reports.pdf', compact(
                'transactions',
                'monthName',
                'monthlyIncome',
                'monthlyExpense',
                'monthlyBalance'
            ));

            return $pdf->download("laporan-keuangan-{$month}-{$year}.pdf");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengexport PDF: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            // Check permission
            if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
                abort(403, 'Unauthorized');
            }

            $month = $request->get('month', Carbon::now()->month);
            $year = $request->get('year', Carbon::now()->year);

            return Excel::download(
                new TransactionsExport($month, $year),
                "laporan-keuangan-{$month}-{$year}.xlsx"
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengexport Excel: ' . $e->getMessage());
        }
    }
}
