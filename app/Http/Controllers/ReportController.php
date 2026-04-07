<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Category;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default filter: bulan ini
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->endOfMonth()->format('Y-m-d'));
        $groupBy = $request->get('group_by', 'daily'); // daily, weekly, monthly

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

        // Payment Method Distribution
        $paymentDistribution = $this->getPaymentDistribution($dateFrom, $dateTo);

        // Transactions for table
        $transactions = $query->with(['user', 'customer'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Users for filter
        $users = \App\Models\User::where('is_active', true)->get();

        return view('admin.reports.index', compact(
            'stats', 'chartData', 'topProducts', 'paymentDistribution',
            'transactions', 'users', 'dateFrom', 'dateTo', 'groupBy'
        ));
    }

    private function calculateProfit($from, $to)
    {
        // Profit = (sell_price - buy_price) * qty
        return DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$from, $to . ' 23:59:59'])
            ->where('transactions.payment_status', 'paid')
            ->selectRaw('SUM((products.sell_price - products.buy_price) * transaction_items.qty) as profit')
            ->value('profit') ?? 0;
    }

    private function getChartData($from, $to, $groupBy)
    {
        $format = match($groupBy) {
            'weekly' => '%Y-%u', // Week number
            'monthly' => '%Y-%m',
            default => '%Y-%m-%d', // Daily
        };

        $labelFormat = match($groupBy) {
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

    public function exportExcel(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth());
        $dateTo = $request->get('date_to', now()->endOfMonth());

        return Excel::download(
            new SalesExport($dateFrom, $dateTo),
            'laporan-penjualan-' . $dateFrom . '-to-' . $dateTo . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth());
        $dateTo = $request->get('date_to', now()->endOfMonth());

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