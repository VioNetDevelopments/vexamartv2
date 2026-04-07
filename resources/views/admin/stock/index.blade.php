@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ filterOpen: false }">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 animate-fade-in-down">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                <i data-lucide="package-search" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">Inventori Stok</h1>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Kelola dan pantau perputaran produk Anda</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.stock.stock-in') }}" class="group flex items-center gap-2 px-6 py-3 rounded-2xl bg-emerald-500 text-white font-black uppercase tracking-widest text-xs shadow-xl shadow-emerald-500/30 hover:bg-emerald-600 transition-all hover:-translate-y-1 active:scale-95">
                <i data-lucide="plus-circle" class="w-4 h-4 transition-transform group-hover:scale-110"></i>
                <span>Stok Masuk</span>
            </a>
            <a href="{{ route('admin.stock.history') }}" class="group flex items-center gap-2 px-6 py-3 rounded-2xl bg-white dark:bg-navy-900 border border-slate-200/60 dark:border-white/5 text-slate-700 dark:text-slate-300 font-black uppercase tracking-widest text-xs shadow-lg hover:bg-slate-50 dark:hover:bg-navy-800 transition-all hover:-translate-y-1 active:scale-95">
                <i data-lucide="history" class="w-4 h-4 text-slate-400 group-hover:text-accent-500 transition-colors"></i>
                <span>Laporan History</span>
            </a>
        </div>
    </div>

    <!-- Stock Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <!-- Total Products -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-accent-500/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-accent-50 dark:bg-accent-500/10 flex items-center justify-center text-accent-500">
                    <i data-lucide="layout-grid" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Katalog</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">{{ $summary['total_products'] }} <span class="text-[10px] text-slate-400 font-medium tracking-normal">Items</span></h3>
                </div>
            </div>
        </div>

        <!-- Low Stock -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-warning/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-warning/5 flex items-center justify-center text-warning">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Stok Menipis</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">{{ $summary['low_stock'] }} <span class="text-[10px] text-slate-400 font-medium tracking-normal">Kritis</span></h3>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-danger/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-danger/5 flex items-center justify-center text-danger">
                    <i data-lucide="x-octagon" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Stok Kosong</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight text-danger">{{ $summary['out_of_stock'] }} <span class="text-[10px] text-slate-400 font-medium tracking-normal text-slate-400">Habis</span></h3>
                </div>
            </div>
        </div>

        <!-- Inventory Value -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-500/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                    <i data-lucide="briefcase" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nilai Aset Stok</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">Rp{{ number_format($summary['total_value'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="relative z-40 bg-white/80 dark:bg-navy-900/80 backdrop-blur-xl border border-white/20 dark:border-white/5 rounded-3xl p-4 shadow-xl shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.2s;">
        <form action="{{ route('admin.stock.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
            <div class="lg:col-span-2 relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barcode atau nama produk..."
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-12 pr-4 py-3 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner">
            </div>
            <div class="relative">
                <select name="category" class="w-full pl-4 pr-10 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all appearance-none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
            </div>
            <div class="relative">
                <select name="stock_status" class="w-full pl-4 pr-10 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all appearance-none">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('stock_status')=='available'?'selected':'' }}>Stok Aman</option>
                    <option value="low" {{ request('stock_status')=='low'?'selected':'' }}>Menipis</option>
                    <option value="out" {{ request('stock_status')=='out'?'selected':'' }}>Habis</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-navy-950 dark:bg-white text-white dark:text-navy-950 rounded-2xl py-3 text-xs font-black uppercase tracking-widest hover:shadow-lg transition-all active:scale-95">Filter</button>
                <a href="{{ route('admin.stock.index') }}" class="p-3 rounded-2xl border border-slate-200 text-slate-400 hover:bg-slate-50 dark:border-white/10 dark:text-slate-500 transition-all active:rotate-180">
                    <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Inventory Table Section -->
    <div class="bg-white dark:bg-navy-900 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-navy-800/30 border-b border-slate-100 dark:border-white/5">
                        <th class="px-8 py-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Detail Produk</th>
                        <th class="px-8 py-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Kategori</th>
                        <th class="px-8 py-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest text-center">Volume Stok</th>
                        <th class="px-8 py-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Status Inventori</th>
                        <th class="px-8 py-6 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($products as $product)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-accent-500/5 transition-all duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 rounded-2xl bg-slate-100 dark:bg-navy-800 overflow-hidden shadow-inner group-hover:scale-105 transition-transform duration-500">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-slate-300"><i data-lucide="package" class="h-6 w-6"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-black text-navy-900 dark:text-white leading-tight group-hover:text-accent-500 transition-colors">{{ $product->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest font-mono">{{ $product->sku }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-800 text-[10px] font-black text-slate-500 uppercase tracking-widest font-bold">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-lg font-black {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= $product->min_stock ? 'text-warning' : 'text-navy-900 dark:text-white') }}">
                                    {{ $product->stock }}
                                </span>
                                <div class="w-16 h-1.5 bg-slate-100 dark:bg-navy-800 rounded-full overflow-hidden">
                                    <div class="h-full {{ $product->stock <= 0 ? 'bg-danger' : ($product->stock <= $product->min_stock ? 'bg-warning' : 'bg-success') }}" 
                                         style="width: {{ min(100, ($product->stock / (max(1, $product->min_stock * 2))) * 100) }}%"></div>
                                </div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Min: {{ $product->min_stock }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($product->stock <= 0)
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-danger/10 text-danger border border-danger/20">
                                    <i data-lucide="x-circle" class="w-3 h-3"></i> Terhenti
                                </span>
                            @elseif($product->stock <= $product->min_stock)
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-warning/10 text-warning border border-warning/20">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i> Restock
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-success/10 text-success border border-success/20">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Optimal
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="{{ route('admin.stock.adjust', $product) }}" 
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white dark:bg-navy-800 border border-slate-200 dark:border-white/5 text-xs font-black uppercase tracking-widest text-slate-700 dark:text-slate-300 hover:bg-navy-950 hover:text-white dark:hover:bg-white dark:hover:text-navy-950 transition-all shadow-sm active:scale-95">
                                <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                                Penyesuaian
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 rounded-full bg-slate-50 dark:bg-navy-800 flex items-center justify-center text-slate-200 dark:text-navy-700 mb-6 animate-pulse">
                                    <i data-lucide="package" class="w-12 h-12"></i>
                                </div>
                                <h4 class="text-xl font-bold text-navy-900 dark:text-white mb-2">Inventori Kosong</h4>
                                <p class="text-sm text-slate-500 max-w-xs mx-auto">Tidak ada data produk yang ditemukan dalam inventori.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="px-8 py-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/30">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection