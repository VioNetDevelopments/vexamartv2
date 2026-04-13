<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\HeldTransaction;
use App\Models\CashDrawer;
use App\Models\CashLog;
use App\Models\Category;
use App\Models\Customer;
use App\Models\CashierNotification;
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
            $query->where(function($q) use ($search) {
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
            // Generate unique invoice code
            $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            $randomCode = '';
            for ($i = 0; $i < 8; $i++) {
                $randomCode .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $invoiceCode = 'VMS-' . $randomCode;
            
            // Calculate totals
            $subtotal = collect($validated['items'])->sum(function($item) {
                return $item['price'] * $item['qty'];
            });
            
            $discount = $validated['discount'] ?? 0;
            $tax = 0;
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

            // Create notification for digital payments
            if (in_array($validated['payment_method'], ['qris', 'debit', 'ewallet'])) {
                CashierNotification::createPaymentNotification(Auth::id(), $transaction);
            }

            // ✅ UPDATE CUSTOMER POINTS & TOTAL SPENT
            if ($validated['customer_id']) {
                $customer = Customer::find($validated['customer_id']);
                
                if ($customer) {
                    // Calculate points (1 point per Rp 1000)
                    $points = floor($grandTotal / 1000);
                    
                    // Add points
                    $customer->increment('loyalty_points', $points);
                    
                    // Update total spent
                    $customer->increment('total_spent', $grandTotal);
                    
                    // Update last transaction
                    $customer->update(['last_transaction_at' => now()]);
                    
                    // Auto-update membership
                    $customer->updateMembership();
                    
                    // Log for debugging
                    \Log::info("Customer points updated", [
                        'customer_id' => $customer->id,
                        'points_added' => $points,
                        'new_points' => $customer->loyalty_points,
                        'total_spent' => $customer->total_spent,
                        'membership' => $customer->membership,
                    ]);
                }
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
            \Log::error("Transaction error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stock Check (Read Only for Cashier)
     */
    public function stockCheck()
    {
        return view('cashier.pages.stock-check');
    }

    /**
     * Get product stock info
     */
    public function getProductStock($productId)
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
     * Stock Check Data API (AJAX)
     */
    public function stockCheckData(Request $request)
    {
        $perPage = $request->get('per_page', 6);
        
        $query = Product::with('category')
            ->where('is_active', true)
            ->orderBy('name');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }
        
        $products = $query->paginate($perPage);
        
        return response()->json($products);
    }

    /**
     * Stock Stats API
     */
    public function stockStats()
    {
        $available = Product::where('is_active', true)
            ->whereColumn('stock', '>', 'min_stock')
            ->count();
        
        $low = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0)
            ->count();
        
        $out = Product::where('is_active', true)
            ->where('stock', 0)
            ->count();
        
        return response()->json([
            'available' => $available,
            'low' => $low,
            'out' => $out
        ]);
    }

    /**
     * Held Transactions Page
     */
    public function heldTransactionsPage()
    {
        $heldTransactions = HeldTransaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);
        
        return view('cashier.pages.held-transactions', compact('heldTransactions'));
    }

    /**
     * Daily Sales Page
     */
    public function dailySalesPage()
    {
        $today = now();
        
        $salesByMethod = Transaction::whereDate('created_at', $today)
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
        
        $hourlySales = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->selectRaw('HOUR(created_at) as hour, SUM(grand_total) as total, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        
        // Fill missing hours with 0
        $completeHourlySales = collect(range(0, 23))->map(function($hour) use ($hourlySales) {
            $existing = $hourlySales->firstWhere('hour', $hour);
            return [
                'hour' => $hour,
                'total' => $existing->total ?? 0,
                'count' => $existing->count ?? 0
            ];
        });
        
        return view('cashier.pages.daily-sales', compact(
            'salesByMethod', 'totalSales', 'totalTransactions', 'completeHourlySales'
        ));
    }

    /**
     * Daily Sales API
     */
    public function dailySales()
    {
        $today = now();
        
        $totalSales = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('grand_total');
        
        $totalTransactions = Transaction::whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->count();
        
        return response()->json([
            'success' => true,
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
        ]);
    }

    /**
     * Recent Transactions Page
     */
    public function recentTransactionsPage()
    {
        $transactions = Transaction::with(['customer', 'user', 'items.product'])
            ->whereDate('created_at', today())
            ->latest()
            ->paginate(30);
        
        return view('cashier.pages.recent-transactions', compact('transactions'));
    }

    /**
     * Get Notifications (API)
     */
    public function getNotifications()
    {
        $notifications = CashierNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->latest()
            ->take(10)
            ->get();
        
        $unreadCount = CashierNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark Notification as Read (API)
     */
    public function markNotificationAsRead(Request $request)
    {
        $notification = CashierNotification::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Hold current transaction
     */
    public function holdTransaction(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'subtotal' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $heldTransaction = HeldTransaction::create([
            'user_id' => Auth::id(),
            'transaction_code' => 'HOLD-' . date('YmdHis') . '-' . Auth::id(),
            'items' => $validated['items'],
            'subtotal' => $validated['subtotal'],
            'discount' => $validated['discount'] ?? 0,
            'customer_id' => $validated['customer_id'] ?? null,
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
     * Delete held transaction
     */
    public function deleteHeldTransaction($id)
    {
        HeldTransaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['success' => true]);
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

    /**
     * Print Receipt
     */
    public function printReceipt($id)
    {
        $transaction = Transaction::with(['customer', 'user', 'items.product'])->findOrFail($id);
        
        // Get store settings from database
        $storeName = \App\Models\Setting::get('store_name', config('app.name'));
        $storeAddress = \App\Models\Setting::get('store_address', '');
        $storePhone = \App\Models\Setting::get('store_phone', '');
        $receiptFooter = \App\Models\Setting::get('receipt_footer', 'Terima kasih!');
        
        return view('cashier.receipt', compact('transaction', 'storeName', 'storeAddress', 'storePhone', 'receiptFooter'));
    }
}