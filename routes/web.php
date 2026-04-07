<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\DashboardController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ActivityLogController;

use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\PosController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);

});


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');


    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:owner,admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            /*
            | Dashboard
            */
            Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])
                ->name('dashboard');

            /*
            | Activity Logs
            */
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])
                ->name('activity-logs');

            /*
            | Products
            */
            Route::resource('products', ProductController::class);

            Route::post('products/{product}/toggle-status',
                [ProductController::class, 'toggleStatus'])
                ->name('products.toggle-status');

            /*
            | Categories
            */
            Route::resource('categories', CategoryController::class)
                ->except(['create', 'show', 'edit']);

            /*
            | Customers
            */
            Route::resource('customers', CustomerController::class);

            Route::post('customers/{customer}/add-points',
                [CustomerController::class, 'addPoints'])
                ->name('customers.add-points');

            /*
            | Transactions
            */
            Route::get('transactions',
                [TransactionController::class, 'index'])
                ->name('transactions.index');

            Route::get('transactions/{transaction}',
                [TransactionController::class, 'show'])
                ->name('transactions.show');

            Route::get('transactions/{transaction}/print',
                [TransactionController::class, 'print'])
                ->name('transactions.print');

            /*
            | Reports
            */
            Route::get('reports',
                [ReportController::class, 'index'])
                ->name('reports.index');

            Route::get('reports/export/excel',
                [ReportController::class, 'exportExcel'])
                ->name('reports.export.excel');

            Route::get('reports/export/pdf',
                [ReportController::class, 'exportPdf'])
                ->name('reports.export.pdf');

            /*
            | Stock Management
            */
            Route::get('stock',
                [StockController::class, 'index'])
                ->name('stock.index');

            Route::get('stock/history',
                [StockController::class, 'history'])
                ->name('stock.history');

            Route::get('stock/history/{product}',
                [StockController::class, 'history'])
                ->name('stock.history.product');

            Route::get('stock/stock-in',
                [StockController::class, 'stockIn'])
                ->name('stock.stock-in');

            Route::post('stock/stock-in',
                [StockController::class, 'processStockIn'])
                ->name('stock.stock-in.process');

            Route::get('stock/{product}/adjust',
                [StockController::class, 'adjust'])
                ->name('stock.adjust');

            Route::post('stock/{product}/adjust',
                [StockController::class, 'processAdjustment'])
                ->name('stock.adjust.process');

            /*
            | Settings
            */
            Route::get('settings',
                [SettingController::class, 'index'])
                ->name('settings.index');

            Route::post('settings',
                [SettingController::class, 'update'])
                ->name('settings.update');

            Route::get('settings/backup',
                [SettingController::class, 'backup'])
                ->name('settings.backup');

        });


    /*
    |--------------------------------------------------------------------------
    | CASHIER ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:cashier,admin,owner')
        ->prefix('cashier')
        ->name('cashier.')
        ->group(function () {

            Route::get('/dashboard',
                [DashboardController::class, 'cashierDashboard'])
                ->name('dashboard');

            /*
            | POS
            */
            Route::get('/pos',
                [PosController::class, 'index'])
                ->name('pos');

            Route::get('/products',
                [PosController::class, 'getProducts'])
                ->name('products.search');

            Route::get('/products/by-barcode',
                [PosController::class, 'scanBarcode'])
                ->name('products.by-barcode');

            /*
            | TRANSACTIONS
            */
            Route::post('/transaction',
                [PosController::class, 'processTransaction'])
                ->name('transaction.store');

            Route::get('/transaction/{id}',
                [PosController::class, 'getTransaction'])
                ->name('transaction.show');

            /*
            | QRIS PAYMENT
            */
            Route::post('/qris/generate',
                [PosController::class, 'generateQris'])
                ->name('qris.generate');

            Route::get('/qris/status/{invoice}',
                [PosController::class, 'checkQrisStatus'])
                ->name('qris.status');

        });


    /*
    |--------------------------------------------------------------------------
    | DEFAULT REDIRECT
    |--------------------------------------------------------------------------
    */

    Route::get('/', function () {

        if (Auth::user()->role === 'cashier') {
    return redirect()->route('cashier.pos');
}

        return redirect()->route('admin.dashboard');

    });

});