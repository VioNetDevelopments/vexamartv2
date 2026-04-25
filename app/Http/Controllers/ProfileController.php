<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Total stats across the entire store (matches admin transactions page)
        $totalTransactions = \App\Models\Transaction::count();
        $totalSales        = \App\Models\Transaction::sum('grand_total');
        $avgPerTransaction = $totalTransactions > 0
            ? round($totalSales / $totalTransactions)
            : 0;
        
        $stats = [
            'total_transactions' => $totalTransactions,
            'total_sales'        => $totalSales,
            'avg_per_transaction'=> $avgPerTransaction,
        ];
        
        return view('profile.index', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'avatar.image' => 'File harus berupa gambar',
            'avatar.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                \Storage::delete('public/' . $user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        // Refresh user to get updated values
        $user->refresh();

        session()->flash('success', 'Profil udah di-update nih!');

        // Return complete user data with avatar URL
        return response()->json([
            'success' => true,
            'message' => 'Profil udah di-update nih',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null,
                'initials' => strtoupper(substr($user->name, 0, 2)),
            ]
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi',
            'password.required' => 'Password baru harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah'
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        session()->flash('success', 'Profil dan Password joss, udah diganti!');

        return response()->json([
            'success' => true,
            'message' => 'Password joss! Udah diganti.'
        ]);
    }

    public function switchRole(Request $request)
    {
        $user = Auth::user();

        // Only admin can switch to cashier
        if ($user->role !== 'admin' && $user->role !== 'owner') {
            abort(403);
        }

        $request->validate([
            'role' => 'required|in:admin,cashier',
        ]);

        // Store original role in session
        session(['original_role' => $user->role]);

        // Update user role temporarily
        $user->update(['role' => $request->role]);

        return redirect()->route('cashier.pos')->with('success', 'Berhasil beralih ke role ' . $request->role);
    }

    public function restoreRole()
    {
        $user = Auth::user();
        $originalRole = session('original_role');

        if ($originalRole) {
            $user->update(['role' => $originalRole]);
            session()->forget('original_role');
        }

        return redirect()->route('admin.dashboard')->with('success', 'Kembali ke role admin');
    }
}