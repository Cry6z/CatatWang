<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate financial summary
        $totalIncome = Transaction::income()->sum('amount');
        $totalExpense = Transaction::expense()->sum('amount');
        $currentBalance = $totalIncome - $totalExpense;
        
        // Monthly data for current year
        $monthlyIncome = Transaction::income()
            ->thisYear()
            ->select(DB::raw('MONTH(transaction_date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
            
        $monthlyExpense = Transaction::expense()
            ->thisYear()
            ->select(DB::raw('MONTH(transaction_date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData['income'][] = $monthlyIncome[$i] ?? 0;
            $monthlyData['expense'][] = $monthlyExpense[$i] ?? 0;
        }

        // Recent transactions
        $recentTransactions = Transaction::with(['category', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Category breakdown for expenses
        $expenseByCategory = Transaction::expense()
            ->with('category')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category ? $item->category->name : 'Tidak Berkategori',
                    'amount' => $item->total,
                    'color' => $item->category ? $item->category->color : '#6B7280'
                ];
            })
            ->filter(function ($item) {
                return $item['amount'] > 0;
            });

        // Low balance warning
        $lowBalanceThreshold = 100000; // Rp 100,000
        $showLowBalanceWarning = $currentBalance < $lowBalanceThreshold;

        return view('dashboard', compact(
            'totalIncome',
            'totalExpense', 
            'currentBalance',
            'monthlyData',
            'recentTransactions',
            'expenseByCategory',
            'showLowBalanceWarning'
        ));
    }
}
