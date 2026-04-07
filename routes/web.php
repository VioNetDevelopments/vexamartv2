<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::middleware('role:owner,admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/search', [SearchController::class, 'globalSearch'])->name('search');
        
        // Route untuk chart data dinamis
        Route::get('/dashboard/chart-data', function(Request $request) {
            $period = $request->get('period', '7');
            $days = (int)$period;
            
            $sales = DB::table('transactions')
                ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
                ->where('created_at', '>=', now()->subDays($days))
                ->where('payment_status', 'paid')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Fill missing dates
            $allDates = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $allDates[$date] = 0;
            }
            
            foreach ($sales as $sale) {
                $allDates[$sale->date] = (float) $sale->total;
            }
            
            return response()->json([
                'labels' => collect($allDates)->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')),
                'data' => array_values($allDates),
            ]);
        })->name('dashboard.chart-data');
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs');
        Route::get('/verification', fn() => view('admin.verification'))->name('verification');
        
        // Products
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
        Route::get('products/by-barcode', [ProductController::class, 'getByBarcode'])->name('products.by-barcode');
        
        // Categories
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
        
        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
        });
        
        // Stock Management
        Route::prefix('stock')->name('stock.')->group(function () {
            Route::get('/', [StockController::class, 'index'])->name('index');
            Route::get('/{product}/adjust', [StockController::class, 'adjust'])->name('adjust');
            Route::post('/{product}/adjust', [StockController::class, 'processAdjustment'])->name('adjust.process');
            Route::get('/stock-in', [StockController::class, 'stockIn'])->name('stock-in');
            Route::post('/stock-in', [StockController::class, 'processStockIn'])->name('stock-in.process');
            Route::get('/history', [StockController::class, 'history'])->name('history');
            Route::get('/history/{product}', [StockController::class, 'history'])->name('history.product');
            Route::get('/api/low-stock', [StockController::class, 'getLowStock'])->name('api.low-stock');
        });
        
        // Customers
        Route::resource('customers', CustomerController::class);
        Route::post('customers/{customer}/add-points', [CustomerController::class, 'addPoints'])->name('customers.add-points');
        
        // Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
            Route::get('/{transaction}/print', [TransactionController::class, 'print'])->name('print');
        });
        
        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::post('/', [SettingController::class, 'update'])->name('update');
            Route::get('/backup', [SettingController::class, 'backup'])->name('backup');
        });
    });

    // Cashier Routes
    Route::middleware('role:cashier,admin,owner')->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'cashierDashboard'])->name('dashboard');
        Route::get('/pos', [PosController::class, 'index'])->name('pos');
        Route::get('/products', [PosController::class, 'getProducts'])->name('products.search');
        Route::get('/products/by-barcode', [PosController::class, 'scanBarcode'])->name('products.by-barcode');
        Route::post('/transaction', [PosController::class, 'processTransaction'])->name('transaction.store');
        Route::get('/transaction/{id}', [PosController::class, 'getTransaction'])->name('transaction.show');
    });

    // Default redirect
    Route::get('/', function() {
        if (Auth::check()) { // ← Gunakan Auth::check() bukan auth()->check()
            return Auth::user()->role === 'cashier' 
                ? redirect()->route('cashier.pos')
                : redirect()->route('admin.dashboard');
        }
        return redirect()->route('login');
    });
});