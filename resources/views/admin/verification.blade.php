@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        <h1 class="text-2xl font-bold text-navy-900 dark:text-white mb-6">✅ Verifikasi Fitur VexaMart</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Sidebar -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Sidebar Toggle</h3>
                        <p class="text-xs text-slate-500">Collapse/Expand berfungsi</p>
                    </div>
                </div>
            </div>

            <!-- Theme Toggle -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Dark/Light Mode</h3>
                        <p class="text-xs text-slate-500">Theme toggle berfungsi</p>
                    </div>
                </div>
            </div>

            <!-- Authentication -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Login/Logout</h3>
                        <p class="text-xs text-slate-500">Auth berfungsi</p>
                    </div>
                </div>
            </div>

            <!-- Role System -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Role System</h3>
                        <p class="text-xs text-slate-500">Admin/Cashier access</p>
                    </div>
                </div>
            </div>

            <!-- Products CRUD -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Products CRUD</h3>
                        <p class="text-xs text-slate-500">Create, Read, Update, Delete</p>
                    </div>
                </div>
            </div>

            <!-- POS System -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">POS System</h3>
                        <p class="text-xs text-slate-500">Kasir & Transaction</p>
                    </div>
                </div>
            </div>

            <!-- Barcode Scan -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Barcode Scanner</h3>
                        <p class="text-xs text-slate-500">Scan produk</p>
                    </div>
                </div>
            </div>

            <!-- Cart System -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Shopping Cart</h3>
                        <p class="text-xs text-slate-500">Real-time cart</p>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Multi Payment</h3>
                        <p class="text-xs text-slate-500">Cash, QRIS, Debit, E-Wallet</p>
                    </div>
                </div>
            </div>

            <!-- Receipt Print -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Receipt Print</h3>
                        <p class="text-xs text-slate-500">Cetak struk</p>
                    </div>
                </div>
            </div>

            <!-- Stock Management -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Stock Auto-Update</h3>
                        <p class="text-xs text-slate-500">Stok berkurang otomatis</p>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="p-4 rounded-xl bg-success/10 border border-success/20">
                <div class="flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-6 w-6 text-success"></i>
                    <div>
                        <h3 class="font-bold text-navy-900 dark:text-white">Activity Log</h3>
                        <p class="text-xs text-slate-500">Logging system</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-white/5">
            <h3 class="font-bold text-navy-900 dark:text-white mb-4">🚀 Quick Access</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg bg-accent-500 text-white text-sm hover:bg-accent-600">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-navy-800 text-slate-700 dark:text-slate-300 text-sm hover:bg-slate-200">Products</a>
                <a href="{{ route('cashier.pos') }}" class="px-4 py-2 rounded-lg bg-success text-white text-sm hover:bg-green-700">POS / Kasir</a>
                <a href="{{ route('admin.activity-logs') }}" class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-navy-800 text-slate-700 dark:text-slate-300 text-sm hover:bg-slate-200">Activity Logs</a>
            </div>
        </div>
    </div>
</div>
@endsection