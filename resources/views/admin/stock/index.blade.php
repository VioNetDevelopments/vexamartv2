@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ filterOpen: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Manajemen Stok</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pantau dan kelola inventori produk</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.stock.stock-in') }}" class="inline-flex items-center rounded-lg bg-success px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                <i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i> Stok Masuk
            </a>
            <a href="{{ route('admin.stock.history') }}" class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">
                <i data-lucide="history" class="mr-2 h-4 w-4"></i> Riwayat
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Total Produk</p>
            <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">{{ $summary['total_products'] }}</h3>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Stok Menipis</p>
            <h3 class="mt-2 text-2xl font-bold text-warning">{{ $summary['low_stock'] }}</h3>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Stok Habis</p>
            <h3 class="mt-2 text-2xl font-bold text-danger">{{ $summary['out_of_stock'] }}</h3>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Nilai Inventori</p>
            <h3 class="mt-2 text-2xl font-bold text-accent-500">Rp {{ number_format($summary['total_value'], 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        <form action="{{ route('admin.stock.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                       class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
            </div>
            <select name="category" class="rounded-lg border border-slate-200 px-4 py-2 text-sm dark:border-white/10 dark:bg-navy-800 dark:text-white">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="stock_status" class="rounded-lg border border-slate-200 px-4 py-2 text-sm dark:border-white/10 dark:bg-navy-800 dark:text-white">
                <option value="">Semua Stok</option>
                <option value="available" {{ request('stock_status')=='available'?'selected':'' }}>Stok Aman</option>
                <option value="low" {{ request('stock_status')=='low'?'selected':'' }}>Stok Menipis</option>
                <option value="out" {{ request('stock_status')=='out'?'selected':'' }}>Stok Habis</option>
            </select>
            <button type="submit" class="rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white hover:bg-accent-600">Filter</button>
            <a href="{{ route('admin.stock.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">Reset</a>
        </form>
    </div>

    <!-- Products Table -->
    <div class="rounded-2xl bg-white shadow-soft overflow-hidden dark:bg-navy-900 dark:border dark:border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-navy-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Min Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-slate-100 dark:bg-navy-800 overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full"><i data-lucide="package" class="h-5 w-5 text-slate-400"></i></div>
                                    @endif
                                </div>
                                <span class="font-medium text-navy-900 dark:text-white">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-slate-500">{{ $product->sku }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= $product->min_stock ? 'text-warning' : 'text-success') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $product->min_stock }}</td>
                        <td class="px-6 py-4">
                            @if($product->stock <= 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger">Habis</span>
                            @elseif($product->stock <= $product->min_stock)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">Menipis</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">Aman</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.stock.adjust', $product) }}" class="text-accent-500 hover:text-accent-600 text-sm font-medium">Adjust</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-8 text-center text-slate-500">Tidak ada data produk</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection