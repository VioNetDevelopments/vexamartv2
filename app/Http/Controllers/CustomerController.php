<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('phone', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }

        $customers = $query->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'membership' => 'in:regular,gold,platinum',
        ]);

        Customer::create($validated);
        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'membership' => 'in:regular,gold,platinum',
        ]);

        $customer->update($validated);
        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil diupdate!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dihapus!');
    }

    public function show(Customer $customer)
    {
        $customer->load(['transactions' => function($q) {
            $q->latest()->take(10);
        }]);
        return view('admin.customers.show', compact('customer'));
    }

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