<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\StockMovement;
use App\Http\Requests\StoreTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $customers = Customer::where('is_active', true)->get();
        
        return view('cashier.pos', compact('categories', 'customers'));
    }

    public function getProducts(Request $request)
    {
        try {
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

            $products = $query->orderBy('name')->get()->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'sell_price' => (float) $product->sell_price,
                    'buy_price' => (float) $product->buy_price,
                    'stock' => $product->stock,
                    'min_stock' => $product->min_stock,
                    'image' => $product->image,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name
                    ] : null
                ];
            });

            return response()->json([
                'success' => true,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            Log::error('POS Get Products Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function scanBarcode(Request $request)
    {
        try {
            $product = Product::with('category')
                ->where('barcode', $request->barcode)
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->first();

            if ($product) {
                return response()->json([
                    'success' => true,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'barcode' => $product->barcode,
                        'sell_price' => (float) $product->sell_price,
                        'stock' => $product->stock,
                        'image' => $product->image,
                        'category' => $product->category ? ['name' => $product->category->name] : null
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan atau stok habis'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function processTransaction(StoreTransactionRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            $items = $validated['items'];

            $invoiceCode = 'INV-' . date('Ymd') . '-' . str_pad(Transaction::whereDate('created_at', today())->count() + 1, 5, '0', STR_PAD_LEFT);

            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['price'] * $item['qty'];
            }

            $discount = $validated['discount'] ?? 0;
            $taxRate = (float) \App\Models\Setting::get('tax_rate', 0);
            $tax = ($subtotal - $discount) * ($taxRate / 100);
            $grandTotal = $subtotal - $discount + $tax;

            $transaction = Transaction::create([
                'invoice_code' => $invoiceCode,
                'user_id' => Auth::id(),
                'customer_id' => $validated['customer_id'] ?? null,
                'total_item' => count($items),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'paid_amount' => $validated['paid_amount'],
                'change_amount' => $validated['paid_amount'] - $grandTotal,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product || $product->stock < $item['qty']) {
                    throw new \Exception("Stok tidak cukup untuk produk: " . ($product->name ?? 'Unknown'));
                }

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->sell_price,
                    'subtotal' => $product->sell_price * $item['qty'],
                ]);

                $oldStock = $product->stock;
                $product->decrement('stock', $item['qty']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'sale',
                    'qty' => -$item['qty'],
                    'reason' => "Penjualan - {$invoiceCode}",
                    'stock_before' => $oldStock,
                    'stock_after' => $product->stock,
                ]);

                if ($transaction->customer_id) {
                    $points = floor($grandTotal / 10000);
                    $transaction->customer->increment('loyalty_points', $points);
                }
            }

            DB::commit();

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'transaction_created',
                'description' => "Transaksi baru: {$invoiceCode}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => ['transaction_id' => $transaction->id, 'grand_total' => $grandTotal],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'transaction' => $transaction->load(['items.product', 'customer', 'user']),
                'invoice_code' => $invoiceCode
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('POS Transaction Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTransaction($id)
    {
        try {
            $transaction = Transaction::with(['items.product', 'customer', 'user'])->find($id);

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'transaction' => $transaction
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }
}