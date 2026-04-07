<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        // Default filter: bulan ini
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->endOfMonth()->format('Y-m-d'));
        $groupBy = $request->input('group_by', 'daily');

        // Query dasar
        $query = Transaction::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->where('payment_status', 'paid');

        // Filter tambahan
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Summary Stats
        $stats = [
            'total_sales' => $query->clone()->sum('grand_total'),
            'total_transactions' => $query->clone()->count(),
            'total_items' => $query->clone()->sum('total_item'),
            'avg_transaction' => $query->clone()->avg('grand_total'),
            'total_profit' => $this->calculateProfit($dateFrom, $dateTo),
        ];

        // Chart Data
        $chartData = $this->getChartData($dateFrom, $dateTo, $groupBy);

        // Top Products
        $topProducts = $this->getTopProducts($dateFrom, $dateTo);

        // Payment Distribution
        $paymentDistribution = $this->getPaymentDistribution($dateFrom, $dateTo);

        // Category Performance
        $categoryPerformance = $this->getCategoryPerformance($dateFrom, $dateTo);

        // Transactions for table
        $transactions = $query->with(['user', 'customer'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Users for filter
        $users = \App\Models\User::where('is_active', true)->get();

        return view('admin.reports.index', compact(
            'stats',
            'chartData',
            'topProducts',
            'paymentDistribution',
            'categoryPerformance',
            'transactions',
            'users',
            'dateFrom',
            'dateTo',
            'groupBy'
        ));
    }

    /**
     * Calculate profit
     */
    private function calculateProfit($from, $to)
    {
        return DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$from, $to . ' 23:59:59'])
            ->where('transactions.payment_status', 'paid')
            ->selectRaw('SUM((products.sell_price - products.buy_price) * transaction_items.qty) as profit')
            ->value('profit') ?? 0;
    }

    /**
     * Get chart data
     */
    private function getChartData($from, $to, $groupBy)
    {
        $format = match ($groupBy) {
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            default => '%Y-%m-%d',
        };

        $labelFormat = match ($groupBy) {
            'weekly' => 'W',
            'monthly' => 'M Y',
            default => 'd M',
        };

        $data = Transaction::selectRaw("DATE_FORMAT(created_at, '{$format}') as period, SUM(grand_total) as total, COUNT(*) as count")
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels' => $data->map(fn($d) => date($labelFormat, strtotime($d->period))),
            'sales' => $data->pluck('total'),
            'transactions' => $data->pluck('count'),
        ];
    }

    /**
     * Get top products
     */
    private function getTopProducts($from, $to, $limit = 10)
    {
        return DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$from, $to . ' 23:59:59'])
            ->where('transactions.payment_status', 'paid')
            ->selectRaw('products.id, products.name, products.image, SUM(transaction_items.qty) as total_sold, SUM(transaction_items.subtotal) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    /**
     * Get payment distribution
     */
    private function getPaymentDistribution($from, $to)
    {
        return Transaction::selectRaw('payment_method, COUNT(*) as count, SUM(grand_total) as total')
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get()
            ->map(fn($d) => [
                'method' => ucfirst($d->payment_method),
                'count' => $d->count,
                'total' => $d->total,
            ]);
    }

    /**
     * Get category performance
     */
    private function getCategoryPerformance($from, $to)
    {
        return DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$from, $to . ' 23:59:59'])
            ->where('transactions.payment_status', 'paid')
            ->selectRaw('categories.name, SUM(transaction_items.qty) as total_sold, SUM(transaction_items.subtotal) as total_revenue')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth());
        $dateTo = $request->input('date_to', now()->endOfMonth());

        return Excel::download(
            new SalesExport($dateFrom, $dateTo),
            'laporan-penjualan-' . $dateFrom . '-to-' . $dateTo . '.xlsx'
        );
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth());
        $dateTo = $request->input('date_to', now()->endOfMonth());

        $stats = [
            'total_sales' => Transaction::whereBetween('created_at', [$dateFrom, $dateTo])->sum('grand_total'),
            'total_transactions' => Transaction::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
        ];

        $transactions = Transaction::with(['user', 'items.product'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('stats', 'transactions', 'dateFrom', 'dateTo'));

        return $pdf->download('laporan-penjualan.pdf');
    }
}