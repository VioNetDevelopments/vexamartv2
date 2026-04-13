<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()->withCount('transactions');
        
        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('phone', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }
        
        // Membership filter
        if ($request->filled('membership')) {
            $query->where('membership', $request->membership);
        }
        
        $customers = $query->latest()->paginate(10);
        
        $stats = [
            'total' => Customer::count(),
            'regular' => Customer::where('membership', 'regular')->count(),
            'gold' => Customer::where('membership', 'gold')->count(),
            'platinum' => Customer::where('membership', 'platinum')->count(),
        ];
        
        if ($request->ajax()) {
            return view('admin.customers.partials.table', compact('customers'));
        }
        
        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['transactions' => function($q) {
            $q->with('items.product')->latest();
        }]);
        
        // Paginate transactions - 5 per page
        $transactions = $customer->transactions()->latest()->paginate(5);
        
        return view('admin.customers.show', compact('customer', 'transactions'));
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

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'membership' => 'required|in:regular,gold,platinum',
        ]);
        
        $customer->update($validated);
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Data pelanggan berhasil diupdate');
    }

    // Add method to manually adjust points
    public function adjustPoints(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'points' => 'required|integer',
            'reason' => 'nullable|string|max:255',
        ]);
        
        if ($validated['points'] > 0) {
            $customer->addPoints($validated['points']);
            $message = "Berhasil menambahkan {$validated['points']} poin";
        } else {
            $customer->deductPoints(abs($validated['points']));
            $message = "Berhasil mengurangi " . abs($validated['points']) . " poin";
        }
        
        // Log the adjustment (optional - create point_logs table)
        
        return redirect()->back()->with('success', $message);
    }

    /**
     * Delete customer
     */
    public function destroy(Customer $customer)
    {
        try {
            $customerName = $customer->name;
            $customer->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Pelanggan {$customerName} berhasil dihapus"
                ]);
            }
            
            return redirect()->route('admin.customers.index')
                ->with('success', "Pelanggan {$customerName} berhasil dihapus");
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus pelanggan. Pastikan pelanggan tidak memiliki transaksi aktif.'
                ], 400);
            }
            
            return redirect()->route('admin.customers.index')
                ->with('error', 'Gagal menghapus pelanggan. Pastikan pelanggan tidak memiliki transaksi aktif.');
        }
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