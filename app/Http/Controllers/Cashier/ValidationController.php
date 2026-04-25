<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\GuestCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidationController extends Controller
{
    public function index(Request $request)
    {
        // Get pending validations (customer orders waiting for approval)
        $query = Transaction::with(['customer', 'user', 'items.product'])
            ->where('payment_status', 'pending')
            ->latest();

        if ($request->filled('search')) {
            $query->where('invoice_code', 'LIKE', "%{$request->search}%");
        }

        $validations = $query->paginate(15);

        $stats = [
            'pending' => Transaction::where('payment_status', 'pending')->count(),
            'approved_today' => Transaction::where('payment_status', 'approved')->whereDate('updated_at', today())->count(),
            'rejected_today' => Transaction::where('payment_status', 'rejected')->whereDate('updated_at', today())->count(),
        ];

        return view('cashier.validations.index', compact('validations', 'stats'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'customer', 'user']);
        return view('cashier.validations.show', compact('transaction'));
    }

    public function approve(Transaction $transaction)
    {
        DB::beginTransaction();

        try {
            // Validate stock availability
            foreach ($transaction->items as $item) {
                if ($item->product->stock < $item->qty) {
                    return back()->with('error', "Stok {$item->product->name} tidak mencukupi!");
                }
            }

            // Update transaction status
            $transaction->update([
                'payment_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Reduce stock
            foreach ($transaction->items as $item) {
                $item->product->decrement('stock', $item->qty);
            }

            // Create notification
            \App\Helpers\NotificationHelper::create(
                $transaction->customer_id,
                'order',
                'Pesanan Disetujui!',
                'Pesanan #' . $transaction->invoice_code . ' telah disetujui oleh kasir',
                'check-circle',
                'green',
                ['transaction_id' => $transaction->id, 'url' => '/shop/receipt/' . $transaction->invoice_code]
            );

            DB::commit();

            return back()->with('success', 'Pesanan berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Transaction $transaction, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $transaction->update([
            'payment_status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Create notification
        \App\Helpers\NotificationHelper::create(
            $transaction->customer_id,
            'order',
            'Pesanan Ditolak',
            'Pesanan #' . $transaction->invoice_code . ' ditolak: ' . $request->rejection_reason,
            'x-circle',
            'red',
            ['transaction_id' => $transaction->id]
        );

        return back()->with('success', 'Pesanan ditolak!');
    }
}