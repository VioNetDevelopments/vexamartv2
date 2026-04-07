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
        $today = now()->startOfDay();
        
        $stats = [
            'today_sales' => Transaction::whereDate('created_at', $today)->sum('grand_total'),
            'today_transactions' => Transaction::whereDate('created_at', $today)->count(),
            'low_stock_products' => Product::whereColumn('stock', '<=', 'min_stock')->where('is_active', true)->count(),
            'total_products' => Product::where('is_active', true)->count(),
        ];

        $recentTransactions = Transaction::with(['user', 'customer'])
            ->latest()
            ->take(20)
            ->get();

        $topProducts = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select('products.name', 'products.image', 
                     DB::raw('SUM(transaction_items.qty) as total_sold'),
                     DB::raw('SUM(transaction_items.subtotal) as total_revenue'))
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('total_sold', 'desc')
            ->take(20)
            ->get();

        // Data untuk chart (7 hari terakhir)
        $salesData = $this->getSalesChartData(7);
        
        return view('admin.dashboard', compact('stats', 'recentTransactions', 'topProducts', 'salesData'));
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

    private function getSalesChartData($days = 7)
    {
        $sales = Transaction::selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
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

        return [
            'labels' => collect($allDates)->keys()->map(fn($d) => date('d M', strtotime($d))),
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

        return [
            'labels' => $data->map(fn($d) => ucfirst($d->payment_method)),
            'counts' => $data->pluck('count'),
            'totals' => $data->pluck('total'),
        ];
    }
}