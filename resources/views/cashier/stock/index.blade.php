@extends('layouts.app')

@section('page-title', 'Cek Stok')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                <i data-lucide="warehouse" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">
                                    Cek Stok
                                </h1>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Lihat ketersediaan produk (Read-Only)</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                            {{ $summary['total_products'] }} Produk
                        </span>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i data-lucide="package" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Produk</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $summary['total_products'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-warning/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning to-warning/80 flex items-center justify-center shadow-lg shadow-warning/30">
                                <i data-lucide="alert-triangle" class="w-6 h-6 text-white"></i>
                            </div>
                            @if($summary['low_stock'] > 0)
                                <span class="flex items-center gap-1 text-xs font-semibold text-warning bg-warning/10 px-2 py-1 rounded-full animate-pulse">
                                    <i data-lucide="bell" class="w-3 h-3"></i>
                                    Perhatian
                                </span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Stok Menipis</p>
                        <h3 class="text-2xl font-bold text-warning">{{ $summary['low_stock'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-danger/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-danger to-danger/80 flex items-center justify-center shadow-lg shadow-danger/30">
                                <i data-lucide="circle-x" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Stok Habis</p>
                        <h3 class="text-2xl font-bold text-danger">{{ $summary['out_of_stock'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-success/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center shadow-lg shadow-success/30">
                                <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Nilai Inventori</p>
                        <h3 class="text-2xl font-bold text-success">Rp {{ number_format($summary['total_value'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up overflow-visible relative z-20" style="animation-delay: 0.5s;">
                <form action="{{ route('cashier.stock') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <div class="relative group">
                            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[180px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-blue-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span id="categoryText">{{ request('category') ? $categories->firstWhere('id', request('category'))->name : 'Semua Kategori' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto" style="top: 100%;">
                            <button type="button" @click="selectCategory('', 'Semua Kategori'); open = false" class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ !request('category') ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">Semua Kategori</button>
                            @foreach($categories as $cat)
                                <button type="button" @click="selectCategory('{{ $cat->id }}', '{{ $cat->name }}'); open = false" class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('category') == $cat->id ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">{{ $cat->name }}</button>
                            @endforeach
                        </div>
                        <input type="hidden" name="category" id="categoryInput" value="{{ request('category') }}">
                    </div>

                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[160px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-blue-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span id="stockText">{{ request('stock_status') ? (request('stock_status') == 'available' ? 'Stok Aman' : (request('stock_status') == 'low' ? 'Stok Menipis' : 'Stok Habis')) : 'Semua Stok' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto" style="top: 100%;">
                            <button type="button" @click="selectStock('', 'Semua Stok'); open = false" class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ !request('stock_status') ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">Semua Stok</button>
                            <button type="button" @click="selectStock('available', 'Stok Aman'); open = false" class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('stock_status') == 'available' ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">Stok Aman</button>
                            <button type="button" @click="selectStock('low', 'Stok Menipis'); open = false" class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('stock_status') == 'low' ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">Stok Menipis</button>
                            <button type="button" @click="selectStock('out', 'Stok Habis'); open = false" class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('stock_status') == 'out' ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">Stok Habis</button>
                        </div>
                        <input type="hidden" name="stock_status" id="stockInput" value="{{ request('stock_status') }}">
                    </div>

                    <button type="submit" class="bg-blue-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-blue-600 shadow-lg shadow-blue-500/30 transition-all active:scale-95">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <a href="{{ route('cashier.stock') }}" class="flex items-center justify-center px-4 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="relative h-48 overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-navy-700 dark:to-navy-600 flex items-center justify-center">
                                    <i data-lucide="package" class="w-16 h-16 text-slate-400"></i>
                                </div>
                            @endif
                            
                            <!-- Discount Badge Overlay -->
                            @if($product->discount > 0)
                                <div class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-gradient-to-r from-pink-500 to-rose-500 text-white text-xs font-bold shadow-lg shadow-pink-500/30 animate-pulse z-10">
                                    -{{ $product->discount }}%
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-400 mb-2">{{ $product->category->name ?? 'Uncategorized' }}</span>
                            <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex items-end justify-between mb-3">
                                <div>
                                    @if($product->discount > 0)
                                        <p class="text-xs text-slate-400 line-through">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                        <p class="text-lg font-black text-pink-600">Rp {{ number_format($product->sell_price * (1 - $product->discount / 100), 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-lg font-black text-blue-600">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-1.5 h-1.5 rounded-full {{ $product->stock > $product->min_stock ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}"></div>
                                <span class="text-[10px] font-bold uppercase tracking-wider {{ $product->stock > $product->min_stock ? 'text-success' : ($product->stock > 0 ? 'text-warning' : 'text-danger') }}">
                                    Stok: {{ $product->stock }}
                                </span>
                            </div>
                            <a href="{{ route('cashier.stock.show', $product) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-blue-500/30 transition-all">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                <span>Lihat Detail</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20">
                        <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="package" class="w-12 h-12 text-slate-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Tidak Ada Produk</h3>
                        <p class="text-slate-500 dark:text-slate-400">Coba ubah filter pencarian</p>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $products->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $products->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $products->total() }}</span> produk
                        </div>
                        <div class="flex items-center gap-2">
                            @if($products->onFirstPage())
                                <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                                    <span class="flex items-center gap-1.5"><i data-lucide="chevron-left" class="w-4 h-4"></i>Previous</span>
                                </button>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-blue-500 hover:text-white hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/30 transition-all text-sm font-bold">
                                    <span class="flex items-center gap-1.5"><i data-lucide="chevron-left" class="w-4 h-4"></i>Previous</span>
                                </a>
                            @endif
                            @if($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-blue-500 hover:text-white hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/30 transition-all text-sm font-bold">
                                    <span class="flex items-center gap-1.5">Next<i data-lucide="chevron-right" class="w-4 h-4"></i></span>
                                </a>
                            @else
                                <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                                    <span class="flex items-center gap-1.5">Next<i data-lucide="chevron-right" class="w-4 h-4"></i></span>
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
        function selectCategory(value, text) {
            document.getElementById('categoryInput').value = value;
            document.getElementById('categoryText').textContent = text;
            document.querySelector('form').submit();
        }
        function selectStock(value, text) {
            document.getElementById('stockInput').value = value;
            document.getElementById('stockText').textContent = text;
            document.querySelector('form').submit();
        }
        document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
        </script>
    @endpush

    @push('styles')
        <style>
        @keyframes fade-in-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in-down { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; opacity: 0; }
        .animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }
        </style>
    @endpush
@endsection