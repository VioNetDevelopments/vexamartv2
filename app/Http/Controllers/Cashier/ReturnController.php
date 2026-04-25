<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ProductReturn::with(['transaction', 'product', 'user'])
            ->latest()
            ->paginate(15);

        return view('cashier.returns.index', compact('returns'));
    }

    public function create(Transaction $transaction)
    {
        $transaction->load(['items.product', 'customer']);
        return view('cashier.returns.create', compact('transaction'));
    }

    public function store(Request $request, Transaction $transaction)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
        ]);

        $product = $transaction->items()
            ->where('product_id', $request->product_id)
            ->first();

        if (!$product || $product->qty < $request->quantity) {
            return back()->with('error', 'Jumlah retur melebihi yang dibeli!');
        }

        DB::beginTransaction();

        try {
            $return = ProductReturn::create([
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'total' => $product->price * $request->quantity,
                'reason' => $request->reason,
                'status' => 'approved',
            ]);

            // Update transaction total
            $transaction->decrement('grand_total', (float) $return->total);

            DB::commit();

            return redirect()->route('cashier.returns.print', $return)
                ->with('success', 'Retur berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function print(ProductReturn $return)
    {
        $return->load(['transaction', 'product', 'user']);
        return view('cashier.returns.print', compact('return'));
    }
}