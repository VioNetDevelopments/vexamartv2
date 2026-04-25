<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Review;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\GuestCart;
use App\Models\Membership;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Search query
        $search = $request->input('search');

        // All active products (paginated) — with search
        $products = Product::with(['category', 'reviews'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->appends(['search' => $search]);

        // Categories with product counts
        $categories = Category::withCount('products')->get();

        // Featured: top-rated or discounted products
        $featuredProducts = Product::with(['category', 'reviews'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where('discount', '>', 0)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        // If not enough discounted products, fill with random
        if ($featuredProducts->count() < 4) {
            $featuredProducts = Product::with(['category', 'reviews'])
                ->where('is_active', true)
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->limit(4)
                ->get();
        }

        // New arrivals (latest 8)
        $newArrivals = Product::with(['category', 'reviews'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->limit(8)
            ->get();

        // Real stats from the database
        $stats = [
            'total_products'     => Product::where('is_active', true)->count(),
            'total_transactions' => Transaction::count(),
            'total_customers'    => Customer::count(),
            'total_categories'   => Category::count(),
        ];

        // Latest approved reviews for testimonials
        $latestReviews = Review::with(['product', 'user'])
            ->approved()
            ->latest()
            ->limit(3)
            ->get();

        // Membership plans for pricing section (unique by name, ordered by price)
        $memberships = Membership::where('is_active', true)
            ->orderBy('price')
            ->get()
            ->unique('name')
            ->values();

        $settings = Setting::pluck('value', 'key');
        $cartCount = $this->getCartCount();

        return view('customer.home.index', compact(
            'products',
            'categories',
            'featuredProducts',
            'newArrivals',
            'stats',
            'latestReviews',
            'memberships',
            'cartCount',
            'settings'
        ));
    }

    private function getCartCount()
    {
        return GuestCart::where('session_id', session()->getId())->sum('quantity');
    }
}