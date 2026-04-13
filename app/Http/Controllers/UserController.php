<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }
        
        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }
        
        // ✅ FIXED: Paginate to 5 items per page
        $users = $query->latest()->paginate(5);
        
        $stats = [
            'total' => User::count(),
            'owner' => User::where('role', 'owner')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'cashier' => User::where('role', 'cashier')->count(),
            'active' => User::where('is_active', true)->count(),
        ];
        
        // Return partial view for AJAX requests
        if ($request->ajax()) {
            return view('admin.users.partials.table', compact('users'));
        }
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:owner,admin,cashier',
            'is_active' => 'boolean',
        ]);
        
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => $validated['is_active'] ?? true,
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:owner,admin,cashier',
            'is_active' => 'boolean',
        ]);
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->is_active = $validated['is_active'] ?? true;
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diupdate');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun Anda sendiri');
        }
        
        $user->delete();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        return redirect()->back()->with('success', 'Status user berhasil diubah');
    }
}