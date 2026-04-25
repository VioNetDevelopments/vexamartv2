<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'customer_name' => 'required_if:user_id,null|string|max:255',
            'customer_email' => 'required_if:user_id,null|email|max:255',
        ]);

        Review::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'customer_id' => auth()->check() ? null : null,
            'customer_name' => auth()->check() ? auth()->user()->name : $request->customer_name,
            'customer_email' => auth()->check() ? auth()->user()->email : $request->customer_email,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}