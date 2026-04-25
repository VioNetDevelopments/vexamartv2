<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product, $slug = null)
    {
        $product->load([
            'category',
            'reviews' => function ($q) {
                $q->approved()->with('user', 'customer')->latest();
            }
        ]);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->limit(4)
            ->get();

        $averageRating = $product->average_rating ?? 0;
        $reviewsCount = $product->reviews_count ?? 0;

        $cartCount = \App\Models\GuestCart::where('session_id', session()->getId())->sum('quantity');

        return view('customer.products.show', compact('product', 'relatedProducts', 'averageRating', 'reviewsCount', 'cartCount'));
    }
}