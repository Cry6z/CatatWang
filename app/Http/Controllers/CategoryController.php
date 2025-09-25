<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        $categories = Category::withCount('transactions')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        return view('categories.create');
    }

    public function store(Request $request)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:categories',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show(Category $category)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        $category->load(['transactions' => function($query) {
            $query->with('user')->orderBy('transaction_date', 'desc');
        }]);
        
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }

        if ($category->transactions()->count() > 0) {
            return redirect()->route('categories.index')
                           ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki transaksi!');
        }

        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil dihapus!');
    }
}
