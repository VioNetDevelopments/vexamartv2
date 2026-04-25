<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::with('product')
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->whereColumn('sold_quantity', '<', 'max_quantity')
            ->orderBy('starts_at', 'desc')
            ->get();

        $upcomingSales = FlashSale::with('product')
            ->where('is_active', true)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at', 'asc')
            ->limit(4)
            ->get();

        return view('customer.flash-sales.index', compact('flashSales', 'upcomingSales'));
    }

    public function buy(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:5'
        ]);

        if (!$flashSale->isRunning()) {
            return back()->with('error', 'Flash sale telah berakhir atau stok habis!');
        }

        $remainingStock = $flashSale->max_quantity - $flashSale->sold_quantity;
        if ($request->quantity > $remainingStock) {
            return back()->with('error', 'Stok flash sale tidak mencukupi!');
        }

        // Add to cart with flash sale price
        $flashPrice = $flashSale->product->sell_price * (1 - $flashSale->discount_percentage / 100);

        // Store flash sale info in session
        session()->flash('flash_sale_' . $flashSale->id, [
            'product_id' => $flashSale->product_id,
            'price' => $flashPrice,
            'discount' => $flashSale->discount_percentage,
            'quantity' => $request->quantity
        ]);

        // Redirect to cart with flash sale item
        return redirect()->route('customer.cart.flash.add', $flashSale);
    }
}