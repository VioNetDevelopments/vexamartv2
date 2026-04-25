<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['customer', 'user'])
            ->where('user_id', auth()->id())
            ->latest();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_code', 'LIKE', "%{$request->search}%")
                    ->orWhereHas('customer', function ($q) use ($request) {
                        $q->where('name', 'LIKE', "%{$request->search}%");
                    });
            });
        }

        // Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Payment Method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $transactions = $query->paginate(15);

        $stats = [
            'total' => Transaction::where('user_id', auth()->id())->count(),
            'today' => Transaction::where('user_id', auth()->id())->whereDate('created_at', today())->count(),
            'total_sales' => Transaction::where('user_id', auth()->id())->sum('grand_total'),
            'today_sales' => Transaction::where('user_id', auth()->id())->whereDate('created_at', today())->sum('grand_total'),
        ];

        return view('cashier.transactions.history', compact('transactions', 'stats'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'customer', 'user']);
        return view('cashier.transactions.show', compact('transaction'));
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['items.product', 'customer', 'user']);
        return view('cashier.transactions.print', compact('transaction'));
    }
}