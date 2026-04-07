@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Dashboard</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Ringkasan aktivitas toko hari ini.</p>
        </div>
        <button class="rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white shadow-lg shadow-accent-500/30 hover:bg-accent-600">
            <i data-lucide="download" class="mr-2 inline h-4 w-4"></i> Export Laporan
        </button>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1 -->
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Penjualan</p>
                    <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">Rp 2.450.000</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-accent-500 dark:bg-navy-800">
                    <i data-lucide="dollar-sign" class="h-6 w-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="flex items-center text-success">
                    <i data-lucide="trending-up" class="mr-1 h-3 w-3"></i> +12.5%
                </span>
                <span class="ml-2 text-slate-400">dari kemarin</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Transaksi</p>
                    <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">48</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-50 text-purple-600 dark:bg-navy-800">
                    <i data-lucide="shopping-cart" class="h-6 w-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="flex items-center text-success">
                    <i data-lucide="trending-up" class="mr-1 h-3 w-3"></i> +5.2%
                </span>
                <span class="ml-2 text-slate-400">rata-rata</span>
            </div>
        </div>

        <!-- Card 3 (Warning) -->
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Stok Kritis</p>
                    <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">12</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-danger dark:bg-navy-800">
                    <i data-lucide="alert-circle" class="h-6 w-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-warning">Perlu restock segera</span>
            </div>
        </div>
        
        <!-- Card 4 -->
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pelanggan Baru</p>
                    <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">8</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-50 text-success dark:bg-navy-800">
                    <i data-lucide="user-plus" class="h-6 w-6"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="flex items-center text-slate-400">Bulan ini</span>
            </div>
        </div>
    </div>

    <!-- Chart Area Placeholder -->
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5 lg:col-span-2">
            <h3 class="mb-4 text-lg font-bold text-navy-900 dark:text-white">Grafik Penjualan</h3>
            <div class="flex h-64 items-center justify-center rounded-xl bg-slate-50 dark:bg-navy-800/50">
                <p class="text-slate-400">Area Grafik (Chart.js akan ditambahkan di langkah Laporan)</p>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <h3 class="mb-4 text-lg font-bold text-navy-900 dark:text-white">Produk Terlaris</h3>
            <div class="space-y-4">
                <!-- Item List Dummy -->
                @for($i=1; $i<=5; $i++)
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-slate-100 dark:bg-navy-800"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-navy-900 dark:text-white">Produk Contoh {{ $i }}</p>
                        <p class="text-xs text-slate-500">Terjual: {{ rand(10, 100) }} pcs</p>
                    </div>
                    <span class="text-sm font-bold text-accent-500">Rp {{ number_format(rand(50000, 200000)) }}</span>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection 