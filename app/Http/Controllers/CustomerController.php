<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by membership
        if ($request->filled('membership')) {
            $query->where('membership', $request->membership);
        }

        $customers = $query->withCount('transactions')->latest()->paginate(15);

        // Summary stats
        $stats = [
            'total' => Customer::count(),
            'regular' => Customer::where('membership', 'regular')->count(),
            'gold' => Customer::where('membership', 'gold')->count(),
            'platinum' => Customer::where('membership', 'platinum')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Show customer details
     */
    public function show(Customer $customer)
    {
        $customer->load([
            'transactions' => function ($q) {
                $q->with('user')->latest()->take(10);
            }
        ]);

        $stats = [
            'total_transactions' => $customer->transactions()->count(),
            'total_spent' => $customer->transactions()->sum('grand_total'),
            'average_transaction' => $customer->transactions()->avg('grand_total'),
            'last_purchase' => $customer->transactions()->latest()->first(),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    /**
     * Show form to create customer
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store new customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string',
            'membership' => 'in:regular,gold,platinum',
        ]);

        Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    /**
     * Show form to edit customer
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string',
            'membership' => 'in:regular,gold,platinum',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil diupdate!');
    }

    /**
     * Delete customer
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus!');
    }

    /**
     * Add loyalty points
     */
    public function addPoints(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $customer->increment('loyalty_points', $validated['points']);

        // Log activity
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'points_added',
            'description' => "Menambah {$validated['points']} poin untuk {$customer->name}",
            'properties' => ['customer_id' => $customer->id, 'points' => $validated['points']],
        ]);

        return back()->with('success', 'Poin berhasil ditambahkan!');
    }
}