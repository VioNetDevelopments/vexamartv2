@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Riwayat Pergerakan Stok</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Audit trail semua perubahan stok</p>
        </div>
        <a href="{{ route('admin.stock.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">
            ← Kembali ke Stok
        </a>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        <form action="{{ $product ? route('admin.stock.history.product', $product) : route('admin.stock.history') }}" method="GET" class="flex flex-wrap gap-4">
            @if($product)
            <input type="hidden" name="product" value="{{ $product->id }}">
            @endif
            <select name="product" class="rounded-lg border border-slate-200 px-4 py-2 text-sm dark:border-white/10 dark:bg-navy-800 dark:text-white min-w-48">
                <option value="">Semua Produk</option>
                @foreach($products as $p)
                <option value="{{ $p->id }}" {{ (isset($product) && $product->id==$p->id)?'selected':'' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <select name="type" class="rounded-lg border border-slate-200 px-4 py-2 text-sm dark:border-white/10 dark:bg-navy-800 dark:text-white">
                <option value="">Semua Tipe</option>
                <option value="in" {{ request('type')=='in'?'selected':'' }}>Stok Masuk</option>
                <option value="out" {{ request('type')=='out'?'selected':'' }}>Stok Keluar</option>
                <option value="sale" {{ request('type')=='sale'?'selected':'' }}>Penjualan</option>
                <option value="adjustment" {{ request('type')=='adjustment'?'selected':'' }}>Adjustment</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm dark:border-white/10 dark:bg-navy-800 dark:text-white">
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm dark:border-white/10 dark:bg-navy-800 dark:text-white">
            <button type="submit" class="rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white hover:bg-accent-600">Filter</button>
        </form>
    </div>

    <!-- History Table -->
    <div class="rounded-2xl bg-white shadow-soft overflow-hidden dark:bg-navy-900 dark:border dark:border-white/5">
        <table class="w-full">
            <thead class="bg-slate-50 dark:bg-navy-800/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Perubahan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Alasan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($movements as $movement)
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-navy-900 dark:text-white">{{ $movement->product->name }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $movement->type=='in'?'bg-success/10 text-success':($movement->type=='out'?'bg-danger/10 text-danger':'bg-warning/10 text-warning') }}">
                            {{ ucfirst($movement->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-bold {{ $movement->qty > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                        {{ $movement->stock_before }} → {{ $movement->stock_after }}
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500">{{ $movement->reason }}</td>
                    <td class="px-6 py-4 text-sm">{{ $movement->user->name }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-slate-500">Tidak ada riwayat pergerakan stok</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($movements->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">{{ $movements->links() }}</div>
        @endif
    </div>
</div>
@endsection