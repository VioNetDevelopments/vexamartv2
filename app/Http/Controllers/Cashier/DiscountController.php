<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\DiscountLog;
use App\Models\PriceOverrideLog;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'reason' => 'nullable|string|max:255',
        ]);

        $product = \App\Models\Product::find($request->product_id);
        $maxDiscount = 20; // Max discount for cashier without approval

        if ($request->discount_percentage > $maxDiscount) {
            return response()->json([
                'success' => false,
                'requires_approval' => true,
                'message' => 'Diskon melebihi batas. Memerlukan persetujuan supervisor!'
            ], 403);
        }

        $discountAmount = $product->sell_price * ($request->discount_percentage / 100);

        DiscountLog::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'discount_percentage' => $request->discount_percentage,
            'discount_amount' => $discountAmount,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'discount_amount' => $discountAmount,
            'final_price' => $product->sell_price - $discountAmount,
        ]);
    }

    public function overridePrice(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'new_price' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $product = \App\Models\Product::find($request->product_id);

        PriceOverrideLog::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'original_price' => $product->sell_price,
            'new_price' => $request->new_price,
            'reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'original_price' => $product->sell_price,
            'new_price' => $request->new_price,
        ]);
    }

    public function logs()
    {
        $discountLogs = DiscountLog::with(['user', 'product', 'transaction'])
            ->latest()
            ->paginate(20);

        $priceLogs = PriceOverrideLog::with(['user', 'product'])
            ->latest()
            ->paginate(20);

        return view('cashier.discount.logs', compact('discountLogs', 'priceLogs'));
    }
}