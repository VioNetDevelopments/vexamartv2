@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ dateFrom: '{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}', dateTo: '{{ request('date_to', now()->format('Y-m-d')) }}' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Riwayat Transaksi</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Lihat dan kelola semua transaksi</p>
        </div>
        <div class="flex gap-2">
            <button @click="window.print()" class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">
                <i data-lucide="printer" class="mr-2 h-4 w-4"></i> Print
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" x-model="dateFrom" value="{{ request('date_from') }}"
                       class="rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" x-model="dateTo" value="{{ request('date_to') }}"
                       class="rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Metode Bayar</label>
                <select name="payment_method" class="rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    <option value="">Semua</option>
                    <option value="cash" {{ request('payment_method')=='cash'?'selected':'' }}>Tunai</option>
                    <option value="qris" {{ request('payment_method')=='qris'?'selected':'' }}>QRIS</option>
                    <option value="debit" {{ request('payment_method')=='debit'?'selected':'' }}>Debit</option>
                    <option value="ewallet" {{ request('payment_method')=='ewallet'?'selected':'' }}>E-Wallet</option>
                </select>
            </div>
            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Invoice / Nama Customer"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
            </div>
            <button type="submit" class="rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white hover:bg-accent-600">
                <i data-lucide="filter" class="mr-2 inline h-4 w-4"></i> Filter
            </button>
            <a href="{{ route('admin.transactions.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">Reset</a>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Total Transaksi</p>
            <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">{{ $transactions->total() }}</h3>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Total Penjualan</p>
            <h3 class="mt-2 text-2xl font-bold text-accent-500">Rp {{ number_format($transactions->sum('grand_total'), 0, ',', '.') }}</h3>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Rata-rata/Transaksi</p>
            <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">Rp {{ number_format($transactions->avg('grand_total') ?? 0, 0, ',', '.') }}</h3>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Metode Terpopuler</p>
            <h3 class="mt-2 text-2xl font-bold text-success">Tunai</h3>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="rounded-2xl bg-white shadow-soft overflow-hidden dark:bg-navy-900 dark:border dark:border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-navy-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Metode</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm font-medium text-accent-500">{{ $trx->invoice_code }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                            {{ $trx->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $trx->customer->name ?? 'Umum' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $trx->total_item }} item</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-navy-800">
                                {{ ucfirst($trx->payment_method) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-bold text-navy-900 dark:text-white">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.transactions.show', $trx) }}" class="p-2 text-slate-400 hover:text-accent-500 transition-colors" title="Lihat Detail">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>
                                <a href="{{ route('admin.transactions.print', $trx) }}" target="_blank" class="p-2 text-slate-400 hover:text-accent-500 transition-colors" title="Cetak">
                                    <i data-lucide="printer" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <i data-lucide="receipt" class="h-12 w-12 text-slate-300 mx-auto mb-3"></i>
                            <p class="text-slate-500 dark:text-slate-400">Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection