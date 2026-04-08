<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Default Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'cashier'
            ? redirect()->route('cashier.pos')
            : redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

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
| API Routes for Settings (Accessible for POS without full auth group)
|--------------------------------------------------------------------------
*/
Route::get('/api/settings', [SettingController::class, 'getSettings'])->name('api.settings');
Route::post('/api/settings/upload-logo', [SettingController::class, 'uploadLogo'])
    ->name('api.settings.upload-logo')
    ->middleware(['auth']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Activity Logs (Owner & Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:owner,admin')
        ->get('/admin/activity-logs', [ActivityLogController::class, 'index'])
        ->name('admin.activity-logs');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (Owner & Admin Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:owner,admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/search', [SearchController::class, 'globalSearch'])->name('search');
        Route::get('/verification', fn() => view('admin.verification'))->name('verification');

        // Chart Data for Dashboard
        Route::get('/dashboard/chart-data', function (Request $request) {
            $period = $request->input('period', '7');
            $days = (int) $period;

            $sales = DB::table('transactions')
                ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
                ->where('created_at', '>=', now()->subDays($days))
                ->where('payment_status', 'paid')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $allDates = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $allDates[$date] = 0;
            }

            foreach ($sales as $sale) {
                $allDates[$sale->date] = (float) $sale->total;
            }

            return response()->json([
                'labels' => collect($allDates)->keys()->map(fn($d) => Carbon::parse($d)->format('d M')),
                'data' => array_values($allDates),
            ]);
        })->name('dashboard.chart-data');

        // Products Management
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
        Route::get('products/by-barcode', [ProductController::class, 'getByBarcode'])->name('products.by-barcode');

        // Categories Management
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

        // Reports Management
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

        // Customers Management
        Route::resource('customers', CustomerController::class)->names('customers');
        Route::post('customers/{customer}/add-points', [CustomerController::class, 'addPoints'])->name('customers.add-points');

        // Transactions Management
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'index'])->name('index');
            Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
            Route::get('/{transaction}/print', [TransactionController::class, 'print'])->name('print');
        });

        // Settings Management
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::delete('/reset-logo', [SettingController::class, 'resetLogo'])->name('reset-logo');
            Route::get('/backup', [SettingController::class, 'backup'])->name('backup');
        });

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Cashier Routes (Cashier, Admin & Owner)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:cashier,admin,owner')->prefix('cashier')->name('cashier.')->group(function () {

        // Dashboard & POS
        Route::get('/dashboard', [DashboardController::class, 'cashierDashboard'])->name('dashboard');
        Route::get('/pos', [PosController::class, 'index'])->name('pos');

        // Products Search & Barcode
        Route::get('/products', [PosController::class, 'getProducts'])->name('products.search');
        Route::get('/products/by-barcode', [PosController::class, 'scanBarcode'])->name('products.by-barcode');

        // Transaction Processing
        Route::post('/transaction', [PosController::class, 'processTransaction'])->name('transaction.store');
        Route::get('/transaction/{id}', [PosController::class, 'getTransaction'])->name('transaction.show');

        // Hold/Recall Transaction
        Route::post('/hold-transaction', [PosController::class, 'holdTransaction'])->name('hold-transaction');
        Route::get('/held-transactions', [PosController::class, 'getHeldTransactions'])->name('held-transactions');
        Route::post('/recall-transaction/{id}', [PosController::class, 'recallTransaction'])->name('recall-transaction');

        // Void Transaction
        Route::post('/void-transaction', [PosController::class, 'voidTransaction'])->name('void-transaction');

        // Price Override (Admin/Owner Only - handled in controller)
        Route::post('/price-override', [PosController::class, 'priceOverride'])->name('price-override');

        // Stock Check
        Route::get('/stock-check/{productId}', [PosController::class, 'stockCheck'])->name('stock-check');

        // Cash Management
        Route::post('/cash-in-out', [PosController::class, 'cashInOut'])->name('cash-in-out');

        // Shift Management
        Route::post('/open-shift', [PosController::class, 'openShift'])->name('open-shift');
        Route::post('/close-shift', [PosController::class, 'closeShift'])->name('close-shift');
        Route::get('/shift-summary', [PosController::class, 'shiftSummary'])->name('shift-summary');

        // Daily Sales Summary
        Route::get('/daily-sales', [PosController::class, 'dailySales'])->name('daily-sales');
    });
});