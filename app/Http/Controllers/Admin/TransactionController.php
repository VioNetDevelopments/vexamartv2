<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'customer', 'items.product']);

        // Filters
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
            $query->where(function($q) use ($request) {
                $q->where('invoice_code', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('customer', function($q2) use ($request) {
                      $q2->where('name', 'LIKE', "%{$request->search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate(20);
        $customers = Customer::all();

        return view('admin.transactions.index', compact('transactions', 'customers'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'customer', 'items.product']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['items.product', 'customer', 'user']);
        return view('admin.transactions.print', compact('transaction'));
    }
}