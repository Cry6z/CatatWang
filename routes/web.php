<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication routes (manual definition)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard - All authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Only Routes
    Route::group(['middleware' => function ($request, $next) {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }], function () {
        Route::resource('users', UserController::class);
        Route::get('/admin/users/statistics', [UserController::class, 'statistics'])->name('users.statistics');
    });
    
    // Admin & Bendahara Routes - Transaction Management
    Route::group(['middleware' => function ($request, $next) {
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }], function () {
        Route::resource('transactions', TransactionController::class);
    });
    
    // Admin & Bendahara Routes - Category Management
    Route::group(['middleware' => function ($request, $next) {
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }], function () {
        Route::resource('categories', CategoryController::class);
    });
    
    // View-only transaction routes for all users
    Route::get('/pemasukan', [TransactionController::class, 'income'])->name('transactions.income');
    Route::get('/pengeluaran', [TransactionController::class, 'expense'])->name('transactions.expense');
    
    // Reports - All authenticated users can view
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    // Export - Admin & Bendahara only
    Route::group(['middleware' => function ($request, $next) {
        if (!in_array(auth()->user()->role, ['admin', 'bendahara'])) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }], function () {
        Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    });
});
