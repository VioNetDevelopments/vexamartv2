<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'user', 'items']);

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by invoice or customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_code', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate(20);

        // Stats calculation based on filtered query
        $statsQuery = clone $query;
        $totalTransactions = $statsQuery->count();
        $totalRevenue = $statsQuery->sum('grand_total');
        $averageRevenue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Get top payment method
        $popularMethodData = clone $query;
        $popularMethod = $popularMethodData->select('payment_method', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->first();

        $topMethod = $popularMethod ? $popularMethod->payment_method : '-';

        // Get all unique payment methods from DB for filter dropdown
        $paymentMethods = \Illuminate\Support\Facades\DB::table('transactions')
            ->whereNotNull('payment_method')
            ->distinct()
            ->pluck('payment_method')
            ->toArray();

        $allTimeTotal = \App\Models\Transaction::count();

        $stats = [
            'total_count' => $totalTransactions,
            'total_revenue' => $totalRevenue,
            'average_revenue' => $averageRevenue,
            'top_method' => $topMethod,
            'all_time_total' => $allTimeTotal,
        ];

        if ($request->ajax()) {
            return response()->json([
                'transactions' => $transactions,
                'stats' => $stats,
                'html' => view('admin.transactions._table', compact('transactions'))->render()
            ]);
        }

        return view('admin.transactions.index', compact('transactions', 'stats', 'paymentMethods', 'allTimeTotal'));
    }

    public function liveStats(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_code', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $totalTransactions = $query->count();
        $totalRevenue = $query->sum('grand_total');
        $averageRevenue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        $popularMethod = (clone $query)->select('payment_method', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->first();

        return response()->json([
            'total_count' => $totalTransactions,
            'total_revenue' => $totalRevenue,
            'average_revenue' => $averageRevenue,
            'top_method' => $popularMethod ? ucfirst($popularMethod->payment_method) : '-',
        ]);
    }

    public function exportCsv(Request $request)
    {
        $query = Transaction::with(['customer', 'user']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_code', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->get();
        $filename = "laporan-transaksi-" . now()->format('Y-m-d-His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No. Invoice', 'Tanggal', 'Waktu', 'Customer', 'Metode Bayar', 'Pajak', 'Total Bayar'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Force Semicolon separator for Indonesian Excel
            fputs($file, "sep=;\n");
            
            // Use Semicolon (;) as delimiter
            fputcsv($file, $columns, ';');

            foreach ($transactions as $trx) {
                fputcsv($file, [
                    $trx->invoice_code,
                    $trx->created_at->format('d/m/Y'),
                    $trx->created_at->format('H:i'),
                    $trx->customer->name ?? 'Umum',
                    ucfirst($trx->payment_method),
                    round($trx->tax),
                    round($trx->grand_total)
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['customer', 'user', 'items.product']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['items.product', 'customer', 'user']);
        return view('admin.transactions.print', compact('transaction'));
    }
}