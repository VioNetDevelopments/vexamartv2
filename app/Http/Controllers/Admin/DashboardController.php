<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get settings from database
        $settings = Setting::pluck('value', 'key')->toArray();

        // Today's stats
        $todaySales = Transaction::whereDate('created_at', today())
            ->sum('grand_total');
        $todayTransactions = Transaction::whereDate('created_at', today())->count();

        // Yesterday's stats for comparison
        $yesterdaySales = Transaction::whereDate('created_at', today()->subDay())
            ->sum('grand_total');
        $yesterdayTransactions = Transaction::whereDate('created_at', today()->subDay())->count();

        // Calculate growth percentages
        $salesGrowth = 0;
        if ($yesterdaySales > 0) {
            $salesGrowth = (($todaySales - $yesterdaySales) / $yesterdaySales) * 100;
        } elseif ($todaySales > 0) {
            $salesGrowth = 100;
        }

        $transactionsGrowth = 0;
        if ($yesterdayTransactions > 0) {
            $transactionsGrowth = (($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions) * 100;
        } elseif ($todayTransactions > 0) {
            $transactionsGrowth = 100;
        }

        // Low stock products
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->count();

        // Total products
        $totalProducts = Product::count();

        $stats = [
            'today_sales' => $todaySales,
            'sales_growth' => $salesGrowth,
            'today_transactions' => $todayTransactions,
            'transactions_growth' => $transactionsGrowth,
            'low_stock_products' => $lowStockProducts,
            'total_products' => $totalProducts,
        ];

        // Chart data (7 days)
        $chartData = $this->getChartData(7);

        // Payment distribution
        $paymentDistribution = DB::table('transactions')
            ->select('payment_method', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('payment_method')
            ->get();

        // Top products
        $topProducts = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                'products.image',
                DB::raw('SUM(transaction_items.qty) as total_sold'),
                DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Recent transactions
        $recentTransactions = Transaction::with(['user', 'customer'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'settings',
            'stats',
            'chartData',
            'paymentDistribution',
            'topProducts',
            'recentTransactions'
        ));
    }

    private function getChartData($days = 7)
    {
        $sales = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(grand_total) as total')
        )
            ->whereBetween('created_at', [now()->subDays($days), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->parse($date)->format('d M');

            $found = $sales->firstWhere('date', $date);
            $data[] = $found ? (float) $found->total : 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function chartData(Request $request)
    {
        $period = $request->input('period', 7);
        $data = $this->getChartData($period);

        return response()->json($data);
    }

    public function paymentData()
    {
        $paymentData = DB::table('transactions')
            ->select('payment_method', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('payment_method')
            ->get();

        $total = $paymentData->sum('total');

        $result = [
            'total' => $total,
            'cash' => $paymentData->firstWhere('payment_method', 'cash')->total ?? 0,
            'qris' => $paymentData->firstWhere('payment_method', 'qris')->total ?? 0,
            'debit' => $paymentData->firstWhere('payment_method', 'debit')->total ?? 0,
            'ewallet' => $paymentData->firstWhere('payment_method', 'ewallet')->total ?? 0,
            'card' => $paymentData->firstWhere('payment_method', 'card')->total ?? 0,
            'bank' => $paymentData->firstWhere('payment_method', 'bank')->total ?? 0,
        ];

        return response()->json($result);
    }

    public function topProducts(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 5;

        $products = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                DB::raw('SUM(transaction_items.qty) as total_sold'),
                DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($products);
    }

    public function recentTransactions(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 5;

        $transactions = Transaction::with(['user', 'customer'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($transactions);
    }

    public function liveStats()
    {
        $todaySales = Transaction::whereDate('created_at', today())->sum('grand_total');
        $todayTransactions = Transaction::whereDate('created_at', today())->count();

        $yesterdaySales = Transaction::whereDate('created_at', today()->subDay())->sum('grand_total');
        $yesterdayTransactions = Transaction::whereDate('created_at', today()->subDay())->count();

        $salesGrowth = 0;
        if ($yesterdaySales > 0) {
            $salesGrowth = (($todaySales - $yesterdaySales) / $yesterdaySales) * 100;
        } elseif ($todaySales > 0) {
            $salesGrowth = 100;
        }

        $transactionsGrowth = 0;
        if ($yesterdayTransactions > 0) {
            $transactionsGrowth = (($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions) * 100;
        } elseif ($todayTransactions > 0) {
            $transactionsGrowth = 100;
        }

        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->count();
        $totalProducts = Product::count();

        return response()->json([
            'today_sales' => $todaySales,
            'sales_growth' => round($salesGrowth, 1),
            'today_transactions' => $todayTransactions,
            'transactions_growth' => round($transactionsGrowth, 1),
            'low_stock_products' => $lowStockProducts,
            'total_products' => $totalProducts,
        ]);
    }
}