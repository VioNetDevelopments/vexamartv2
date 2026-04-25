<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Category;
use App\Http\Requests\StockAdjustmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display stock overview
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('sku', 'LIKE', "%{$request->search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Stock status filter
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'available':
                    $query->whereColumn('stock', '>', 'min_stock');
                    break;
                case 'low':
                    $query->whereColumn('stock', '<=', 'min_stock')
                          ->where('stock', '>', 0);
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
            }
        }
        
        $products = $query->latest()->paginate(5);
        $categories = Category::where('is_active', true)->get();
        
        $summary = [
            'total_products' => Product::count(),
            'low_stock' => Product::whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'total_value' => Product::selectRaw('SUM(stock * buy_price) as total')->value('total') ?? 0,
        ];
        
        if ($request->ajax()) {
            return view('admin.stock.partials.table', compact('products'));
        }
        
        return view('admin.stock.index', compact('products', 'categories', 'summary'));
    }

    /**
     * Show stock adjustment form
     */
    public function adjust(Product $product)
    {
        return view('admin.stock.adjust', compact('product'));
    }

    /**
     * Process stock adjustment
     */
    public function processAdjustment(StockAdjustmentRequest $request, Product $product)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $oldStock = $product->stock;
            $newStock = $oldStock + $validated['quantity'];

            if ($newStock < 0) {
                throw new \Exception('Stok tidak boleh negatif!');
            }

            // Update product stock
            $product->update(['stock' => $newStock]);

            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'adjustment',
                'qty' => $validated['quantity'],
                'reason' => $validated['reason'],
                'stock_before' => $oldStock,
            ]);

            // Trigger Notification
            \App\Models\CashierNotification::createProductNotification(Auth::id(), $product, 'updated');

            DB::commit();

            return redirect()->route('admin.stock.index')
                ->with('success', 'Sip! Stoknya udah kita update sesuai penyesuaian tadi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Stock In (Restock)
     */
    public function stockIn()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.stock.stock-in', compact('products'));
    }

    /**
     * Process Stock In
     */
    public function processStockIn(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'buy_price' => 'nullable|numeric|min:0',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::lockForUpdate()->find($validated['product_id']);
            $oldStock = $product->stock;
            $newStock = $oldStock + $validated['quantity'];

            // Update price if provided
            $updateData = ['stock' => $newStock];
            if ($validated['buy_price']) {
                $updateData['buy_price'] = $validated['buy_price'];
            }
            $product->update($updateData);

            // Create stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'qty' => $validated['quantity'],
                'reason' => $validated['reason'],
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
            ]);

            // Trigger Notification
            \App\Models\CashierNotification::createProductNotification(Auth::id(), $product, 'updated');

            DB::commit();
            return redirect()->route('admin.stock.index')->with('success', 'Beres! Stok masuk udah berhasil kita catat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat stok masuk: ' . $e->getMessage());
        }
    }

    /**
     * Stock History
     */
    public function history(Request $request, Product $product = null)
    {
        $query = StockMovement::with(['product', 'user']);

        if ($product) {
            $query->where('product_id', $product->id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $movements = $query->latest()->paginate(20);
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.stock.history', compact('movements', 'products', 'product'));
    }

    /**
     * API: Get low stock products
     */
    public function getLowStock()
    {
        $products = Product::whereColumn('stock', '<=', 'min_stock')
            ->where('is_active', true)
            ->with('category')
            ->orderBy('stock')
            ->get();

        return response()->json(['products' => $products]);
    }
}