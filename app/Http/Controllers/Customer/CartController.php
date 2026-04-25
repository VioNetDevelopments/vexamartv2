<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\GuestCart;
use App\Models\Product;
use App\Models\Customer as CustomerModel;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\SettingHelper;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        $cartTotal = $cartItems->sum('subtotal');
        $cartCount = $cartItems->sum('quantity');

        return view('customer.cart.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $sessionId = session()->getId();

        $cartItem = GuestCart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            GuestCart::create([
                'session_id' => $sessionId,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->sell_price,
            ]);
        }

        // Return JSON for AJAX requests (no page reload)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cartCount' => $this->getCartCount(),
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = GuestCart::findOrFail($id);

        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Keranjang berhasil diupdate!');
    }

    public function remove($id)
    {
        $cartItem = GuestCart::findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function clear()
    {
        GuestCart::where('session_id', session()->getId())->delete();
        return back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function checkout()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong!');
        }

        $subtotal = $cartItems->sum('subtotal');
        $taxRate = SettingHelper::get('tax_rate', 0);
        $tax = ($subtotal * $taxRate) / 100;
        $total = $subtotal + $tax;

        $cartCount = $cartItems->sum('quantity');

        return view('customer.checkout.index', compact('cartItems', 'subtotal', 'tax', 'total', 'cartCount'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'payment_method' => 'required|in:cash,qris,debit,ewallet',
            'paid_amount' => 'required|numeric|min:' . $request->total,
        ]);

        DB::beginTransaction();

        try {
            $cartItems = $this->getCartItems();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Keranjang kosong!');
            }

            $subtotal = $cartItems->sum('subtotal');
            $taxRate = SettingHelper::get('tax_rate', 0);
            $tax = ($subtotal * $taxRate) / 100;
            $total = $subtotal + $tax;

            // Generate invoice code
            $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            $randomCode = '';
            for ($i = 0; $i < 8; $i++) {
                $randomCode .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $invoiceCode = 'VMS-' . $randomCode;

            // Create or find customer
            $customer = CustomerModel::firstOrCreate(
                ['email' => $request->email ?? $request->phone],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'membership' => 'regular',
                    'loyalty_points' => 0,
                ]
            );

            // Create transaction
            $transaction = Transaction::create([
                'invoice_code' => $invoiceCode,
                'user_id' => auth()->id() ?? null,
                'customer_id' => $customer->id,
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => $tax,
                'grand_total' => $total,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->paid_amount - $total,
                'payment_status' => 'paid',
            ]);

            // Create transaction items and update stock
            foreach ($cartItems as $cartItem) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $cartItem->product_id,
                    'qty' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal,
                ]);

                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Add loyalty points
            $points = floor($total / 1000);
            $customer->increment('loyalty_points', $points);
            $customer->increment('total_spent', $total);
            $customer->updateMembership();

            // Clear cart
            GuestCart::where('session_id', session()->getId())->delete();

            DB::commit();

            return redirect()->route('customer.receipt', $transaction->invoice_code)
                ->with('success', 'Transaksi berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function receipt($invoiceCode)
    {
        $transaction = Transaction::with(['items.product', 'customer', 'user'])
            ->where('invoice_code', $invoiceCode)
            ->firstOrFail();

        return view('customer.receipt', compact('transaction'));
    }

    private function getCartCount()
    {
        return GuestCart::where('session_id', session()->getId())->sum('quantity');
    }

    private function getCartItems()
    {
        return GuestCart::where('session_id', session()->getId())
            ->with('product')
            ->get();
    }
}