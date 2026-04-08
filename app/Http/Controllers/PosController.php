<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\HeldTransaction;
use App\Models\CashDrawer;
use App\Models\CashLog;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PosController extends Controller
{
    /**
     * Display POS page
     */
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $customers = Customer::where('is_active', true)->get();

        // Check if shift is open
        $shift = CashDrawer::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        // Daily sales summary
        $todaySales = Transaction::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $todayTransactions = Transaction::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->count();

        return view('cashier.pos', compact('categories', 'customers', 'shift', 'todaySales', 'todayTransactions'));
    }

    /**
     * Get products for POS
     */
    public function getProducts(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->limit(50)->get();

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    /**
     * Scan barcode
     */
    public function scanBarcode(Request $request)
    {
        $product = Product::where('barcode', $request->barcode)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan atau stok habis'
        ], 404);
    }

    /**
     * Process transaction
     */
    public function processTransaction(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,qris,debit,ewallet',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Generate invoice code
            $invoiceCode = 'INV-' . date('Ymd') . '-' . str_pad(Transaction::whereDate('created_at', today())->count() + 1, 5, '0', STR_PAD_LEFT);

            // Calculate totals
            $subtotal = collect($validated['items'])->sum(function ($item) {
                return $item['price'] * $item['qty'];
            });

            $discount = $validated['discount'] ?? 0;
            $tax = 0; // Add tax calculation if needed
            $grandTotal = $subtotal - $discount + $tax;

            // Create transaction
            $transaction = Transaction::create([
                'invoice_code' => $invoiceCode,
                'user_id' => Auth::id(),
                'customer_id' => $validated['customer_id'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'payment_method' => $validated['payment_method'],
                'paid_amount' => $validated['paid_amount'],
                'change_amount' => $validated['paid_amount'] - $grandTotal,
                'payment_status' => 'paid',
            ]);

            // Create transaction items and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                $transaction->items()->create([
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // Update stock
                $product->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'invoice_code' => $invoiceCode,
                'transaction' => $transaction->load(['items.product', 'customer', 'user'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hold transaction
     */
    public function holdTransaction(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'subtotal' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string',
        ]);

        $heldTransaction = HeldTransaction::create([
            'user_id' => Auth::id(),
            'transaction_code' => 'HOLD-' . date('YmdHis') . '-' . Auth::id(),
            'items' => $validated['items'],
            'subtotal' => $validated['subtotal'],
            'discount' => $validated['discount'] ?? 0,
            'customer_id' => $validated['customer_id'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan sementara',
            'held_transaction' => $heldTransaction
        ]);
    }

    /**
     * Get held transactions
     */
    public function getHeldTransactions()
    {
        $heldTransactions = HeldTransaction::where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'transactions' => $heldTransactions
        ]);
    }

    /**
     * Recall held transaction
     */
    public function recallTransaction($id)
    {
        $heldTransaction = HeldTransaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'transaction' => $heldTransaction
        ]);
    }

    /**
     * Void transaction
     */
    public function voidTransaction(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'reason' => 'required|string|max:255',
        ]);

        $transaction = Transaction::findOrFail($validated['transaction_id']);

        // Restore stock
        foreach ($transaction->items as $item) {
            $item->product->increment('stock', $item->qty);
        }

        // Update transaction status
        $transaction->update([
            'payment_status' => 'void',
            'void_reason' => $validated['reason'],
            'voided_at' => now(),
            'voided_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibatalkan'
        ]);
    }

    /**
     * Price override (requires admin/owner permission)
     */
    public function priceOverride(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'owner'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak memiliki izin untuk mengubah harga'
            ], 403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'new_price' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $oldPrice = $product->sell_price;

        $product->update(['sell_price' => $validated['new_price']]);

        return response()->json([
            'success' => true,
            'message' => 'Harga berhasil diubah',
            'old_price' => $oldPrice,
            'new_price' => $validated['new_price']
        ]);
    }

    /**
     * Stock check
     */
    public function stockCheck($productId)
    {
        $product = Product::with('category')->findOrFail($productId);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'stock' => $product->stock,
                'min_stock' => $product->min_stock,
                'status' => $product->stock <= 0 ? 'out_of_stock' : ($product->stock <= $product->min_stock ? 'low_stock' : 'available')
            ]
        ]);
    }

    /**
     * Cash in/out
     */
    public function cashInOut(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Get open shift
        $cashDrawer = CashDrawer::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        if (!$cashDrawer) {
            return response()->json([
                'success' => false,
                'message' => 'Shift kasir belum dibuka'
            ], 400);
        }

        // Create cash log
        CashLog::create([
            'user_id' => Auth::id(),
            'cash_drawer_id' => $cashDrawer->id,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Update cash drawer
        if ($validated['type'] === 'in') {
            $cashDrawer->increment('cash_in', $validated['amount']);
        } else {
            $cashDrawer->increment('cash_out', $validated['amount']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kas ' . ($validated['type'] === 'in' ? 'masuk' : 'keluar') . ' berhasil dicatat'
        ]);
    }

    /**
     * Open shift
     */
    public function openShift(Request $request)
    {
        $validated = $request->validate([
            'opening_balance' => 'required|numeric|min:0',
        ]);

        // Close any open shifts first
        CashDrawer::where('user_id', Auth::id())
            ->where('status', 'open')
            ->update(['status' => 'closed']);

        $cashDrawer = CashDrawer::create([
            'user_id' => Auth::id(),
            'opened_at' => now(),
            'opening_balance' => $validated['opening_balance'],
            'status' => 'open',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shift berhasil dibuka',
            'shift' => $cashDrawer
        ]);
    }

    /**
     * Close shift
     */
    public function closeShift(Request $request)
    {
        $cashDrawer = CashDrawer::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        if (!$cashDrawer) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada shift yang aktif'
            ], 400);
        }

        $validated = $request->validate([
            'closing_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Calculate expected balance
        $todaySales = Transaction::whereDate('created_at', today())
            ->where('user_id', Auth::id())
            ->where('payment_method', 'cash')
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $expectedBalance = $cashDrawer->opening_balance + $cashDrawer->cash_in - $cashDrawer->cash_out + $todaySales;

        $cashDrawer->update([
            'closed_at' => now(),
            'closing_balance' => $validated['closing_balance'],
            'expected_balance' => $expectedBalance,
            'status' => 'closed',
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shift berhasil ditutup',
            'shift' => $cashDrawer,
            'difference' => $validated['closing_balance'] - $expectedBalance
        ]);
    }

    /**
     * Shift summary
     */
    public function shiftSummary()
    {
        $shift = CashDrawer::where('user_id', Auth::id())
            ->where('status', 'open')
            ->latest()
            ->first();

        if (!$shift) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada shift yang aktif'
            ], 404);
        }

        $todaySales = Transaction::whereDate('created_at', today())
            ->where('user_id', Auth::id())
            ->where('payment_method', 'cash')
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $expectedBalance = $shift->opening_balance + $shift->cash_in - $shift->cash_out + $todaySales;

        return response()->json([
            'success' => true,
            'shift' => $shift,
            'today_sales' => $todaySales,
            'expected_balance' => $expectedBalance,
            'cash_logs' => CashLog::where('cash_drawer_id', $shift->id)
                ->with('user')
                ->latest()
                ->get()
        ]);
    }

    /**
     * Daily sales summary
     */
    public function dailySales()
    {
        $today = Carbon::today();

        $sales = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(grand_total) as total')
            ->groupBy('payment_method')
            ->get();

        $totalSales = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $totalTransactions = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->count();

        return response()->json([
            'success' => true,
            'date' => $today->format('d/m/Y'),
            'sales' => $sales,
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
        ]);
    }

    /**
     * Get transaction by ID
     */
    public function getTransaction($id)
    {
        $transaction = Transaction::with(['items.product', 'customer', 'user'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'transaction' => $transaction
        ]);
    }
}