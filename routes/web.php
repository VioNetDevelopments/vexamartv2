<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProductController; // User snippet said Admin\ProductController but file is top-level
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CustomerController as AdminCustomerController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\ChatController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Default Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'cashier') return redirect()->route('cashier.pos');
        if ($role === 'customer') return redirect()->route('customer.home');
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('customer.home');
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
| API Routes for Settings
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

    // Logout - Always handle with POST
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:owner,admin')->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');
        Route::get('/dashboard/payment-data', [DashboardController::class, 'paymentData'])->name('dashboard.payment-data');
        Route::get('/dashboard/top-products', [DashboardController::class, 'topProducts'])->name('dashboard.top-products');
        Route::get('/dashboard/recent-transactions', [DashboardController::class, 'recentTransactions'])->name('dashboard.recent-transactions');
        Route::get('/dashboard/live-stats', [DashboardController::class, 'liveStats'])->name('dashboard.live-stats');
        Route::get('/search', [SearchController::class, 'globalSearch'])->name('search');

        // Products Management
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        
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
            Route::get('/stock-in', [StockController::class, 'stockIn'])->name('stock-in');
            Route::post('/stock-in', [StockController::class, 'processStockIn'])->name('stock-in.process');
            Route::get('/{product}/adjust', [StockController::class, 'adjust'])->name('adjust');
            Route::post('/{product}/adjust', [StockController::class, 'processAdjustment'])->name('adjust.process');
            Route::get('/history', [StockController::class, 'history'])->name('history');
            Route::get('/history/{product}', [StockController::class, 'history'])->name('history.product');
            Route::get('/api/low-stock', [StockController::class, 'getLowStock'])->name('api.low-stock');
        });

        // Customer Management
        Route::resource('customers', AdminCustomerController::class)->names('customers');

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

        // Activity Logs
        Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs');

        // Transactions Management
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [AdminTransactionController::class, 'index'])->name('index');
            Route::get('/{transaction}', [AdminTransactionController::class, 'show'])->name('show');
            Route::get('/{transaction}/print', [AdminTransactionController::class, 'print'])->name('print');
        });

        // Settings Management
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
            Route::post('/reset-logo', [SettingController::class, 'resetLogo'])->name('reset-logo');
        });

        // Payment Providers Management
        Route::prefix('payment-providers')->name('payment-providers.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PaymentProviderController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\PaymentProviderController::class, 'store'])->name('store');
            Route::put('/{provider}', [\App\Http\Controllers\PaymentProviderController::class, 'update'])->name('update');
            Route::post('/{provider}/toggle', [\App\Http\Controllers\PaymentProviderController::class, 'toggleStatus'])->name('toggle');
            Route::delete('/{provider}', [\App\Http\Controllers\PaymentProviderController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Cashier Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:cashier,admin,owner')->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos');
        Route::get('/products', [PosController::class, 'getProducts'])->name('products.index');
        Route::get('/products/by-barcode', [PosController::class, 'scanBarcode'])->name('products.by-barcode');
        Route::get('/daily-sales', [PosController::class, 'dailySales'])->name('daily-sales');
        Route::post('/transaction', [PosController::class, 'processTransaction'])->name('transaction.store');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        
        // Notifications
        Route::get('/notifications', [PosController::class, 'getNotifications'])->name('notifications');
        Route::post('/notifications/read', [PosController::class, 'markNotificationAsRead'])->name('notifications.read');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/switch-role', [ProfileController::class, 'switchRole'])->name('profile.switch-role');
    Route::post('/profile/restore-role', [ProfileController::class, 'restoreRole'])->name('profile.restore-role');
});

/*
|--------------------------------------------------------------------------
| Customer Routes (Shop - No Auth Required)
|--------------------------------------------------------------------------
*/
Route::prefix('shop')->name('customer.')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('home');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{product}', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/{id}', [CustomerController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{id}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/cart/clear', [CustomerController::class, 'clearCart'])->name('cart.clear');
    Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CustomerController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/product/{product}', [CustomerController::class, 'show'])->name('product.show');
    Route::get('/receipt/{invoiceCode}', [CustomerController::class, 'receipt'])->name('receipt');
    Route::get('/credits', [CustomerController::class, 'credits'])->name('credits');
    Route::post('/chat', [ChatController::class, 'chat'])->name('chat');
});