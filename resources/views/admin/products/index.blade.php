@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto space-y-6" x-data="{ 
            loading: false,
            search: '{{ request('search') }}',
            category: '{{ request('category') }}',
            status: '{{ request('stock_status') }}',
            
            updateFilters(url = null) {
                this.loading = true;
                if (!url) {
                    const params = new URLSearchParams();
                    if (this.search) params.append('search', this.search);
                    if (this.category) params.append('category', this.category);
                    if (this.status) params.append('stock_status', this.status);
                    url = `{{ route('admin.products.index') }}?${params.toString()}`;
                }
                
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('product-container').innerHTML;
                        const newHeaderInfo = doc.getElementById('header-info').innerHTML;
                        
                        document.getElementById('product-container').innerHTML = newContent;
                        document.getElementById('header-info').innerHTML = newHeaderInfo;
                        
                        window.history.pushState({}, '', url);
                        lucide.createIcons();
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.loading = false;
                    });
            }
        }">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                            <i data-lucide="box" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">
                                Manajemen Produk
                            </h1>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Kelola katalog produk toko Anda</p>
                        </div>
                    </div>
                    <span id="header-info" class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
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

            <form @submit.prevent="updateFilters()" class="flex flex-wrap gap-4 relative">

                <!-- Search Input -->
                <div class="flex-1 min-w-64">
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Cari Produk</label>
                    <div class="relative group">
                        <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                        <input type="text" x-model="search" @input.debounce.500ms="updateFilters()" placeholder="Nama, SKU, atau Barcode..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>
                </div>
                
                <!-- Category Dropdown -->
                <div class="min-w-[200px]">
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between w-full pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span x-text="category ? document.querySelector(`button[data-id='cat-${category}']`)?.innerText || 'Semua Kategori' : 'Semua Kategori'"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto no-scrollbar"
                             style="top: 100%;">
                            <button type="button" @click="category = ''; open = false; updateFilters()"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20"
                                    :class="!category ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300'">
                                Semua Kategori
                            </button>
                            @foreach($categories as $cat)
                                <button type="button" data-id="cat-{{ $cat->id }}" @click="category = '{{ $cat->id }}'; open = false; updateFilters()"
                                        class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20"
                                        :class="category == '{{ $cat->id }}' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300'">
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Stock Status Dropdown -->
                <div class="min-w-[200px]">
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Status Stok</label>
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between w-full pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span x-text="status == 'available' ? 'Stok Aman' : (status == 'low' ? 'Stok Menipis' : (status == 'out' ? 'Stok Habis' : 'Semua Status'))"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto no-scrollbar"
                             style="top: 100%;">
                            <button type="button" @click="status = ''; open = false; updateFilters()"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20"
                                    :class="!status ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300'">
                                Semua Status
                            </button>
                            <button type="button" @click="status = 'available'; open = false; updateFilters()"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20"
                                    :class="status == 'available' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300'">
                                Stok Aman
                            </button>
                            <button type="button" @click="status = 'low'; open = false; updateFilters()"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20"
                                    :class="status == 'low' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300'">
                                Stok Menipis
                            </button>
                            <button type="button" @click="status = 'out'; open = false; updateFilters()"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20"
                                    :class="status == 'out' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300'">
                                Stok Habis
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center justify-center w-12 h-12 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 hover:text-accent-500 transition-all">
                        <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                    </a>
                </div>
            </form>
        </div>

        <div id="product-container" class="space-y-6">
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
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" id="delete-product-{{ $product->id }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        @click="confirmAction({
                                            title: 'Hapus Produknya, King?',
                                            text: 'Yakin mau didelete? Sayang banget lho, King!',
                                            formId: 'delete-product-{{ $product->id }}'
                                        })"
                                        class="p-2.5 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-500 hover:text-white transition-all shadow-sm active:scale-95" 
                                        title="Hapus">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
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
                        <h3 class="font-bold text-navy-900 dark:text-white text-base mb-1 line-clamp-2">{{ $product->name }}</h3>
                        
                        <!-- Stock Info -->
                        <p class="text-xs font-bold mb-2 {{ $product->stock > $product->min_stock ? 'text-success' : ($product->stock > 0 ? 'text-warning' : 'text-danger') }}">
                            Stok: {{ $product->stock }}
                        </p>

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
                        <button @click.prevent="updateFilters('{{ $products->previousPageUrl() }}')"
                           class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                Previous
                            </span>
                        </button>
                    @endif

                    @if($products->hasMorePages())
                        <button @click.prevent="updateFilters('{{ $products->nextPageUrl() }}')"
                           class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                Next
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </span>
                        </button>
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
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection