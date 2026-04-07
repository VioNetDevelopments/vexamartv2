<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Search Products
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->where('is_active', true)
            ->take(5)
            ->get();

        foreach ($products as $product) {
            $results[] = [
                'id' => $product->id,
                'type' => 'product',
                'title' => $product->name,
                'subtitle' => "SKU: {$product->sku} • Stok: {$product->stock}",
                'url' => route('admin.products.edit', $product->id),
            ];
        }

        // Search Transactions
        $transactions = Transaction::where('invoice_code', 'like', "%{$query}%")
            ->take(5)
            ->get();

        foreach ($transactions as $transaction) {
            $results[] = [
                'id' => $transaction->id,
                'type' => 'transaction',
                'title' => $transaction->invoice_code,
                'subtitle' => "Total: Rp " . number_format((float) $transaction->grand_total, 0, ',', '.') . " • " . $transaction->created_at->format('d M Y'),
                'url' => route('admin.transactions.show', $transaction->id),
            ];
        }

        return response()->json($results);
    }
}
