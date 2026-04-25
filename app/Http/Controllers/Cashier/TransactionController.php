<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'customer', 'items.product'])
            ->where('user_id', Auth::id())
            ->latest();

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_code', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('name', 'LIKE', "%{$request->search}%");
                  });
            });
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(15);

        return view('cashier.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        // Only allow cashier to view their own transactions
        if ($transaction->user_id !== Auth::id() && Auth::user()->role !== 'admin' && Auth::user()->role !== 'owner') {
            abort(403);
        }

        $transaction->load(['items.product', 'customer', 'user']);

        return view('cashier.transactions.show', compact('transaction'));
    }
}