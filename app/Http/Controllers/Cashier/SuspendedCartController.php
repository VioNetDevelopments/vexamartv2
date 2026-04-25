<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\SuspendedCart;
use Illuminate\Http\Request;

class SuspendedCartController extends Controller
{
    public function index()
    {
        $suspendedCarts = SuspendedCart::where('user_id', auth()->id())
            ->where('expires_at', '>', now())
            ->latest()
            ->get();

        // Update session so the sidebar dot disappears
        session(['suspended_last_checked' => now()]);

        return view('cashier.suspended.index', compact('suspendedCarts'));
    }

    public function suspend(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'subtotal' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'total' => 'required|numeric',
            'notes' => 'nullable|string|max:500',
        ]);

        SuspendedCart::create([
            'user_id' => auth()->id(),
            'customer_id' => $request->customer_id ?? null,
            'items' => $request->items,
            'subtotal' => $request->subtotal,
            'discount' => $request->discount ?? 0,
            'tax' => $request->tax ?? 0,
            'total' => $request->total,
            'notes' => $request->notes,
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil disimpan']);
    }

    public function resume(SuspendedCart $cart)
    {
        if ($cart->isExpired()) {
            $cart->delete();
            return back()->with('error', 'Transaksi yang di-hold telah kadaluarsa!');
        }

        return response()->json([
            'success' => true,
            'cart' => [
                'items' => $cart->items,
                'subtotal' => $cart->subtotal,
                'discount' => $cart->discount,
                'tax' => $cart->tax,
                'total' => $cart->total,
                'customer_id' => $cart->customer_id,
            ]
        ]);
    }

    public function destroy(SuspendedCart $cart)
    {
        $cart->delete();
        return back()->with('success', 'Transaksi yang di-hold berhasil dihapus!');
    }

    public function cleanup()
    {
        // Delete expired suspended carts
        $deleted = SuspendedCart::where('expires_at', '<', now())->delete();
        return response()->json(['deleted' => $deleted]);
    }
}