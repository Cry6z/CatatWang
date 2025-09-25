<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['category', 'user']);

        // Search functionality
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(15);

        $categories = Category::all();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create(Request $request)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        $type = $request->get('type', 'income'); // Default to income
        $categories = Category::where('type', $type)->get();
        
        return view('transactions.create', compact('categories', 'type'));
    }

    public function store(Request $request)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'transaction_date' => 'required|date'
        ]);

        Transaction::create([
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'transaction_date' => $request->transaction_date
        ]);

        return redirect()->route('transactions.index')
                        ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['category', 'user']);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::all();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'transaction_date' => 'required|date'
        ]);

        $transaction->update($request->all());

        return redirect()->route('transactions.index')
                        ->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
                        ->with('success', 'Transaksi berhasil dihapus!');
    }

    public function income()
    {
        $transactions = Transaction::income()
                                  ->with(['category', 'user'])
                                  ->orderBy('transaction_date', 'desc')
                                  ->paginate(15);
        
        $categories = Category::income()->get();
        
        return view('transactions.income', compact('transactions', 'categories'));
    }

    public function expense()
    {
        $transactions = Transaction::expense()
                                  ->with(['category', 'user'])
                                  ->orderBy('transaction_date', 'desc')
                                  ->paginate(15);
        
        $categories = Category::expense()->get();
        
        return view('transactions.expense', compact('transactions', 'categories'));
    }
}
