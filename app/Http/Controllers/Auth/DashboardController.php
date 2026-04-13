<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Stats
        $stats = [
            'today_sales' => Transaction::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->sum('grand_total'),
            'today_transactions' => Transaction::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->count(),
            'low_stock_products' => Product::whereColumn('stock', '<=', 'min_stock')
                ->where('is_active', true)
                ->count(),
            'total_products' => Product::where('is_active', true)->count(),
        ];

        // Top Products - Limit 5
        $topProducts = Product::with('category')
            ->where('is_active', true)
            ->withSum('transactionItems as total_sold', 'qty')
            ->withSum('transactionItems as total_revenue', 'subtotal')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Recent Transactions - Limit 5
        $recentTransactions = Transaction::with(['customer', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'topProducts', 'recentTransactions'));
    }

    public function cashierDashboard()
    {
        $today = now()->startOfDay();
        
        $stats = [
            'today_sales' => Transaction::whereDate('created_at', $today)->where('user_id', auth()->id())->sum('grand_total'),
            'today_transactions' => Transaction::whereDate('created_at', $today)->where('user_id', auth()->id())->count(),
        ];

        return view('cashier.dashboard', compact('stats'));
    }

    public function getChartData(Request $request)
    {
        $period = (int) $request->input('period', 7);
        
        // Get exact number of days from database
        $sales = DB::table('transactions')
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->where('created_at', '>=', now()->subDays($period - 1)->startOfDay())
            ->where('payment_status', 'paid')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Fill in missing dates with 0
        $allDates = [];
        for ($i = $period - 1; $i >= 0; $i--) {
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
    }

    public function getPaymentData()
    {
        $today = now();
        
        $paymentMethods = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get()
            ->pluck('count', 'payment_method');
        
        return response()->json([
            'cash' => $paymentMethods['cash'] ?? 0,
            'qris' => $paymentMethods['qris'] ?? 0,
            'debit' => $paymentMethods['debit'] ?? 0,
            'ewallet' => $paymentMethods['ewallet'] ?? 0,
            'total' => ($paymentMethods['cash'] ?? 0) + 
                       ($paymentMethods['qris'] ?? 0) + 
                       ($paymentMethods['debit'] ?? 0) + 
                       ($paymentMethods['ewallet'] ?? 0)
        ]);
    }

    private function getSalesChartData(int $days = 7)
    {
        $sales = Transaction::selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
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

        return [
            'labels' => collect($allDates)->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')),
            'data' => array_values($allDates),
        ];
    }

    private function getPaymentChartData()
    {
        $data = Transaction::selectRaw('payment_method, COUNT(*) as count, SUM(grand_total) as total')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('payment_method')
            ->get();
            
        $totalCount = $data->sum('count');

        return [
            'labels' => $data->map(fn($d) => ucfirst($d->payment_method ?? 'Unknown')),
            'counts' => $data->pluck('count'),
            'totals' => $data->pluck('total'),
            'percentages' => $data->map(fn($d) => $totalCount > 0 ? round(($d->count / $totalCount) * 100) : 0),
        ];
    }

    // API for Top Products with Pagination
    public function getTopProducts(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 5;
        
        $products = Product::with('category')
            ->where('is_active', true)
            ->withSum('transactionItems as total_sold', 'qty')
            ->withSum('transactionItems as total_revenue', 'subtotal')
            ->orderByDesc('total_sold')
            ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json($products);
    }

    // API for Recent Transactions with Pagination
    public function getRecentTransactions(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 5;
        
        $transactions = Transaction::with(['customer', 'user'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json($transactions);
    }
}