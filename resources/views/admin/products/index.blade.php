@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                            <i data-lucide="box" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-black bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent tracking-tight">
                                Manajemen Produk
                            </h1>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Kelola katalog produk toko Anda</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                        {{ $products->total() }} Produk
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.products.create') }}" 
               class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-300 hover:-translate-y-0.5">
                <i data-lucide="plus-circle" class="w-5 h-5 group-hover:rotate-90 transition-transform"></i>
                <span>Tambah Produk</span>
            </a>
        </div>

        <!-- Filter Section - SAME DESIGN AS STOCK PAGE -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up overflow-visible relative z-20" style="animation-delay: 0.1s;">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap gap-4">
                <!-- Search Input -->
                <div class="flex-1 min-w-64">
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Cari Produk</label>
                    <div class="relative group">
                        <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, SKU, atau Barcode..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>
                </div>
                
                <!-- Category Dropdown -->
                <div class="min-w-[200px]">
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between w-full pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span>{{ request('category') ? $categories->firstWhere('id', request('category'))->name : 'Semua Kategori' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                             style="top: 100%;">
                            <button type="button" @click="$refs.categoryInput.value = ''; open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ !request('category') ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Semua Kategori
                            </button>
                            @foreach($categories as $cat)
                                <button type="button" @click="$refs.categoryInput.value = '{{ $cat->id }}'; open = false"
                                        class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('category') == $cat->id ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="category" x-ref="categoryInput" value="{{ request('category') }}">
                    </div>
                </div>

                <!-- Stock Status Dropdown -->
                <div class="min-w-[200px]">
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Status Stok</label>
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between w-full pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span>{{ request('stock_status') ? (request('stock_status') == 'available' ? 'Stok Aman' : (request('stock_status') == 'low' ? 'Stok Menipis' : 'Stok Habis')) : 'Semua Status' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                             style="top: 100%;">
                            <button type="button" @click="$refs.statusInput.value = ''; open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ !request('stock_status') ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Semua Status
                            </button>
                            <button type="button" @click="$refs.statusInput.value = 'available'; open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('stock_status') == 'available' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Stok Aman
                            </button>
                            <button type="button" @click="$refs.statusInput.value = 'low'; open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('stock_status') == 'low' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Stok Menipis
                            </button>
                            <button type="button" @click="$refs.statusInput.value = 'out'; open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('stock_status') == 'out' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Stok Habis
                            </button>
                        </div>
                        <input type="hidden" name="stock_status" x-ref="statusInput" value="{{ request('stock_status') }}">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-accent-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-accent-600 shadow-lg shadow-accent-500/30 transition-all active:scale-95">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center justify-center w-12 h-12 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 hover:text-accent-500 transition-all">
                        <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="group bg-white dark:bg-navy-900 rounded-2xl overflow-hidden shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <!-- Product Image -->
                    <div class="relative h-48 overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-navy-800 dark:to-navy-700 flex items-center justify-center">
                                <i data-lucide="package" class="w-16 h-16 text-slate-400"></i>
                            </div>
                        @endif
                        
                        <!-- Stock Badge -->
                        <div class="absolute top-3 right-3">
                            @if($product->stock > $product->min_stock)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-success/90 text-white backdrop-blur-sm">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    {{ $product->stock }}
                                </span>
                            @elseif($product->stock > 0)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-warning/90 text-white backdrop-blur-sm">
                                    <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                    {{ $product->stock }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-danger/90 text-white backdrop-blur-sm">
                                    <i data-lucide="x-circle" class="w-3 h-3"></i>
                                    Habis
                                </span>
                            @endif
                        </div>

                        <!-- Quick Actions -->
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="p-2.5 bg-white rounded-xl text-slate-700 hover:bg-accent-500 hover:text-white transition-all transform hover:scale-110">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="p-2.5 bg-white rounded-xl text-slate-700 hover:bg-accent-500 hover:text-white transition-all transform hover:scale-110">
                                <i data-lucide="edit" class="w-5 h-5"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-white rounded-xl text-slate-700 hover:bg-danger hover:text-white transition-all transform hover:scale-110">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <!-- Category -->
                        <div class="mb-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-400">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>

                        <!-- Product Name -->
                        <h3 class="font-bold text-navy-900 dark:text-white text-base mb-2 line-clamp-2">{{ $product->name }}</h3>

                        <!-- Price -->
                        <div class="flex items-end justify-between mb-3">
                            <div>
                                <p class="text-lg font-black text-accent-600 dark:text-accent-400">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                @if($product->buy_price)
                                    <p class="text-xs text-slate-400 line-through">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Profit -->
                        @if($product->buy_price && $product->sell_price)
                            @php
                                $profit = $product->sell_price - $product->buy_price;
                            @endphp
                            <div class="mb-3">
                                <span class="inline-flex items-center gap-1 text-xs font-bold {{ $profit > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $profit > 0 ? '+' : '' }}Rp {{ number_format($profit, 0, ',', '.') }} PROFIT
                                </span>
                            </div>
                        @endif

                        <!-- Stock Info -->
                        <div class="pt-3 border-t border-slate-100 dark:border-white/5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500">Stok:</span>
                                <span class="font-bold {{ $product->stock > $product->min_stock ? 'text-success' : ($product->stock > 0 ? 'text-warning' : 'text-danger') }}">
                                    {{ $product->stock }} unit
                                </span>
                            </div>
                        </div>

                        <!-- Edit Button -->
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="mt-3 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-accent-500/30 transition-all">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                            <span>Edit</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-20">
                        <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="package" class="w-12 h-12 text-slate-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-navy-900 dark:text-white mb-2">Belum Ada Produk</h3>
                        <p class="text-slate-500 dark:text-slate-400 mb-6">Mulai tambahkan produk pertama Anda</p>
                        <a href="{{ route('admin.products.create') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all">
                            <i data-lucide="plus-circle" class="w-5 h-5"></i>
                            <span>Tambah Produk</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination - SAME AS STOCK PAGE -->
        @if($products->hasPages())
        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up">
            <div class="flex items-center justify-between">
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $products->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $products->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $products->total() }}</span> produk
                </div>

                <div class="flex items-center gap-2">
                    @if($products->onFirstPage())
                        <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                Previous
                            </span>
                        </button>
                    @else
                        <a href="{{ $products->previousPageUrl() }}"
                           class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                Previous
                            </span>
                        </a>
                    @endif

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}"
                           class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                Next
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </span>
                        </a>
                    @else
                        <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                Next
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush

@push('styles')
<style>
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; opacity: 0; }
.animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection