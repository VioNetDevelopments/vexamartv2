<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class StockCheckController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                    ->orWhere('sku', 'LIKE', "%{$request->search}%")
                    ->orWhere('barcode', 'LIKE', "%{$request->search}%");
            });
        }

        // Category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Stock Status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'available') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock', 0);
            }
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::all();

        $summary = [
            'total_products' => Product::count(),
            'low_stock' => Product::whereColumn('stock', '<=', 'min_stock')->where('stock', '>', 0)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'total_value' => Product::selectRaw('SUM(buy_price * stock) as total')->value('total') ?? 0,
        ];

        return view('cashier.stock.index', compact('products', 'categories', 'summary'));
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('cashier.stock.show', compact('product'));
    }
}