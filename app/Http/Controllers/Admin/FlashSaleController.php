<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlashSaleController extends Controller
{
    public function index(Request $request)
    {
        $query = FlashSale::with(['product']);

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>', now());
            } elseif ($request->status === 'upcoming') {
                $query->where('starts_at', '>', now());
            } elseif ($request->status === 'expired') {
                $query->where('ends_at', '<=', now());
            }
        }

        $flashSales = $query->latest()->paginate(20);

        $stats = [
            'total' => FlashSale::count(),
            'active' => FlashSale::where('is_active', true)
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>', now())
                ->count(),
            'upcoming' => FlashSale::where('starts_at', '>', now())->count(),
            'expired' => FlashSale::where('ends_at', '<=', now())->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.flash-sales.partials.table', compact('flashSales'))->render(),
                'pagination' => $flashSales->links()->render(),
                'stats' => $stats
            ]);
        }

        return view('admin.flash-sales.index', compact('flashSales', 'stats'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('admin.flash-sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|integer|min:1|max:90',
            'max_quantity' => 'required|integer|min:1',
            'starts_at' => 'required|date|after:now',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ], [
            'ends_at.after' => 'Waktu berakhir harus setelah waktu mulai',
            'starts_at.after' => 'Waktu mulai harus setelah waktu sekarang',
        ]);

        // Check if product already has active flash sale
        $existing = FlashSale::where('product_id', $validated['product_id'])
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereBetween('starts_at', [now(), now()->addMonth()])
                    ->orWhereBetween('ends_at', [now(), now()->addMonth()]);
            })
            ->first();

        if ($existing) {
            return back()->withErrors(['product_id' => 'Produk ini sudah memiliki flash sale aktif!']);
        }

        FlashSale::create($validated);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale berhasil dibuat!');
    }

    public function edit(FlashSale $flashSale)
    {
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('admin.flash-sales.edit', compact('flashSale', 'products'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|integer|min:1|max:90',
            'max_quantity' => 'required|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        $flashSale->update($validated);

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale berhasil diupdate!');
    }

    public function toggleStatus(FlashSale $flashSale)
    {
        $flashSale->update(['is_active' => !$flashSale->is_active]);

        return back()->with('success', 'Status flash sale berhasil diubah!');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();

        return redirect()->route('admin.flash-sales.index')
            ->with('success', 'Flash sale berhasil dihapus!');
    }
}