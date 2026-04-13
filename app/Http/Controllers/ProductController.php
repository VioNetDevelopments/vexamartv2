<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('sku', 'LIKE', "%{$request->search}%")
                  ->orWhere('barcode', 'LIKE', "%{$request->search}%");
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
        
        $products = $query->latest()->paginate(8);
        
        $categories = Category::where('is_active', true)->get();
        
        // Return partial view for AJAX requests
        if ($request->ajax()) {
            return view('admin.products.partials.grid', compact('products'));
        }
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        // Generate SKU if not provided
        if (empty($validated['sku'])) {
            $validated['sku'] = 'SKU-' . strtoupper(\Illuminate\Support\Str::random(8));
        }

        // Generate Barcode if not provided
        if (empty($validated['barcode'])) {
            $validated['barcode'] = '899' . rand(1000000000, 9999999999);
        }

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'transactionItems']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // Track stock change for stock movement log
            $oldStock = $product->stock;
            $newStock = $validated['stock'];
            $stockChanged = ($oldStock != $newStock);

            // Handle Image Upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $validated['image'] = $request->file('image')->store('products', 'public');
            }

            // Update product
            $product->update($validated);

            // Create stock movement record if stock changed
            if ($stockChanged) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'adjustment',
                    'qty' => $newStock - $oldStock,
                    'reason' => 'Penyesuaian stok manual dari halaman edit produk',
                    'stock_before' => $oldStock,
                    'stock_after' => $newStock,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Status produk berhasil diubah!');
    }

    /**
     * Get product by barcode (for POS)
     */
    public function getByBarcode(Request $request)
    {
        $product = Product::where('barcode', $request->barcode)
            ->where('is_active', true)
            ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ], 404);
    }

    /**
     * Search products (for POS)
     */
    public function search(Request $request)
    {
        $products = Product::where('is_active', true)
            ->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('sku', 'LIKE', "%{$request->search}%")
                    ->orWhere('barcode', 'LIKE', "%{$request->search}%");
            })
            ->where('stock', '>', 0)
            ->take(10)
            ->get();

        return response()->json(['products' => $products]);
    }
}