<?php

namespace App\Http\Controllers;

use App\Models\PaymentProvider;
use Illuminate\Http\Request;

class PaymentProviderController extends Controller
{
    /**
     * Get all providers (AJAX)
     */
    public function index()
    {
        $providers = PaymentProvider::orderBy('sort_order')->get();
        return response()->json($providers);
    }

    /**
     * Store new provider
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,ewallet,qris,other',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $provider = PaymentProvider::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Provider berhasil ditambahkan',
            'provider' => $provider
        ]);
    }

    /**
     * Update provider
     */
    public function update(Request $request, PaymentProvider $provider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank,ewallet,qris,other',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $provider->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Provider berhasil diupdate'
        ]);
    }

    /**
     * Toggle status
     */
    public function toggleStatus(PaymentProvider $provider)
    {
        $provider->update(['is_active' => !$provider->is_active]);
        return response()->json(['success' => true, 'is_active' => $provider->is_active]);
    }

    /**
     * Remove provider
     */
    public function destroy(PaymentProvider $provider)
    {
        $provider->delete();
        return response()->json(['success' => true, 'message' => 'Provider berhasil dihapus']);
    }
}
