<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class StockController extends Controller
{
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
            if ($request->stock_status === 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock')
                      ->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock', 0);
            }
        }
        
        $products = $query->latest()->paginate(20);
        $categories = Category::all();
        
        // Stock summary
        $stockSummary = [
            'total' => Product::count(),
            'available' => Product::where('stock', '>', 0)->count(),
            'low' => Product::whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0)->count(),
            'out' => Product::where('stock', 0)->count(),
        ];
        
        return view('cashier.stock.index', compact('products', 'categories', 'stockSummary'));
    }

    public function show(Product $product)
    {
        // Only show product details, no edit capability
        $product->load('category');
        
        return view('cashier.stock.show', compact('product'));
    }
}