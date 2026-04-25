<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user', 'customer'])->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(20);
        $products = Product::all();

        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'average' => round(Review::avg('rating'), 1),
            'five_star' => Review::where('rating', 5)->count(),
            'four_star' => Review::where('rating', 4)->count(),
            'three_star' => Review::where('rating', 3)->count(),
            'two_star' => Review::where('rating', 2)->count(),
            'one_star' => Review::where('rating', 1)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'products', 'stats'));
    }

    public function toggleStatus(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        return back()->with('success', 'Status ulasan berhasil diubah!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus!');
    }
}