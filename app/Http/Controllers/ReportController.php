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
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));
        $groupBy = $request->input('group_by', 'daily');
        $paymentMethod = $request->input('payment_method');
        
        // Query transactions with date range
        $query = Transaction::with(['user', 'customer', 'items'])
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        
        // Filter by payment method
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        
        // ✅ FIXED: Paginate to 10 items per page
        $transactions = $query->latest()->paginate(10);
        
        // Calculate stats
        $stats = [
            'total_sales' => $query->sum('grand_total'),
            'total_transactions' => $query->count(),
            'total_items' => DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->whereBetween('transactions.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->when($paymentMethod, fn($q) => $q->where('transactions.payment_method', $paymentMethod))
                ->sum('qty'),
            'avg_transaction' => $query->avg('grand_total') ?? 0,
            'total_profit' => $this->calculateProfit($dateFrom, $dateTo, $paymentMethod),
        ];
        
        // Chart data based on group by
        $chartData = $this->getChartData($dateFrom, $dateTo, $groupBy, $paymentMethod);
        
        // Payment distribution
        $paymentDistribution = DB::table('transactions')
            ->select('payment_method as method', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->groupBy('payment_method')
            ->get();
        
        // Top products
        $topProducts = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                DB::raw('SUM(transaction_items.qty) as total_sold'),
                DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->whereBetween('transactions.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->when($paymentMethod, fn($q) => $q->where('transactions.payment_method', $paymentMethod))
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();
        
        // Category performance
        $categoryPerformance = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->select(
                'categories.name',
                DB::raw('SUM(transaction_items.qty) as total_sold'),
                DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->whereBetween('transactions.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->when($paymentMethod, fn($q) => $q->where('transactions.payment_method', $paymentMethod))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();
        
        return view('admin.reports.index', compact(
            'transactions',
            'stats',
            'chartData',
            'paymentDistribution',
            'topProducts',
            'categoryPerformance',
            'dateFrom',
            'dateTo',
            'groupBy'
        ));
    }

    /**
     * Calculate profit
     */
    private function calculateProfit($from, $to, $paymentMethod = null)
    {
        $query = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$from, $to . ' 23:59:59'])
            ->where('transactions.payment_status', 'paid');

        if ($paymentMethod) {
            $query->where('transactions.payment_method', $paymentMethod);
        }

        return $query->selectRaw('SUM((products.sell_price - products.buy_price) * transaction_items.qty) as profit')
            ->value('profit') ?? 0;
    }

    /**
     * Get chart data
     */
    private function getChartData($from, $to, $groupBy, $paymentMethod = null)
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

        $query = Transaction::selectRaw("DATE_FORMAT(created_at, '{$format}') as period, SUM(grand_total) as total, COUNT(*) as count")
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->where('payment_status', 'paid');

        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }

        $data = $query->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels' => $data->map(fn($d) => date($labelFormat, strtotime($d->period))),
            'sales' => $data->pluck('total'),
            'transactions' => $data->pluck('count'),
        ];
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

        $transactions = Transaction::with(['user', 'customer', 'items.product'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('stats', 'transactions', 'dateFrom', 'dateTo'));

        return $pdf->download('laporan-penjualan.pdf');
    }
}