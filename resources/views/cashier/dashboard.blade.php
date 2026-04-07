@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Dashboard Kasir</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Ringkasan penjualan Anda hari ini</p>
        </div>
        <a href="{{ route('cashier.pos') }}" class="rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white shadow-lg shadow-accent-500/30 hover:bg-accent-600">
            <i data-lucide="monitor-smartphone" class="mr-2 inline h-4 w-4"></i> Buka Kasir
        </a>
    </div>

    <!-- Stats -->
    <div class="grid gap-6 sm:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Penjualan Anda</p>
                    <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">
                        Rp {{ number_format($stats['today_sales'] ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-accent-500 dark:bg-navy-800">
                    <i data-lucide="dollar-sign" class="h-6 w-6"></i>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Transaksi</p>
                    <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">
                        {{ $stats['today_transactions'] ?? 0 }}
                    </h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-50 text-purple-600 dark:bg-navy-800">
                    <i data-lucide="shopping-cart" class="h-6 w-6"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection