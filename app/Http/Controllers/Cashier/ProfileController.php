<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get cashier stats
        $stats = [
            'total_transactions' => \App\Models\Transaction::where('user_id', $user->id)->count(),
            'total_sales' => \App\Models\Transaction::where('user_id', $user->id)->sum('grand_total'),
            'shifts_completed' => \App\Models\CashierShift::where('user_id', $user->id)->where('status', 'closed')->count(),
        ];

        return view('cashier.profile.index', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

        session()->flash('success', 'Profil udah di-update nih!');

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

        $user = auth()->user();

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
}