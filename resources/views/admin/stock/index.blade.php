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
                                <i data-lucide="warehouse" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">
                                    Manajemen Stok
                                </h1>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pantau dan kelola inventori produk toko Anda</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                            {{ $summary['total_products'] }} Produk
                        </span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.stock.stock-in') }}" 
                       class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-success to-success/80 text-white rounded-xl font-bold shadow-lg shadow-success/30 hover:shadow-success/50 transition-all duration-300 hover:-translate-y-0.5">
                        <i data-lucide="plus-circle" class="w-5 h-5 group-hover:rotate-90 transition-transform"></i>
                        <span>Stok Masuk</span>
                    </a>
                    <a href="{{ route('admin.stock.history') }}" 
                       class="group flex items-center gap-2 px-5 py-3 bg-white dark:bg-navy-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-50 dark:hover:bg-navy-800 hover:shadow-md transition-all duration-300">
                        <i data-lucide="history" class="w-5 h-5 text-slate-400 group-hover:text-accent-500 transition-colors"></i>
                        <span>Riwayat</span>
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/30">
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
                <form id="stockFilterForm" class="flex flex-wrap gap-4">
                    @csrf
                    <div class="flex-1 min-w-48">
                        <div class="relative group">
                            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari produk..."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        </div>
                    </div>
                    
                    <!-- Category Dropdown - FIXED -->
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[180px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span id="categoryText">{{ request('category') ? $categories->firstWhere('id', request('category'))->name : 'Semua Kategori' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto no-scrollbar"
                             style="top: 100%;">
                            <button type="button" @click="selectCategory('', 'Semua Kategori'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ !request('category') ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Semua Kategori
                            </button>
                            @foreach($categories as $cat)
                                <button type="button" @click="selectCategory('{{ $cat->id }}', '{{ $cat->name }}'); open = false"
                                        class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('category') == $cat->id ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="category" id="categoryInput" value="{{ request('category') }}">
                    </div>

                    <!-- Stock Status Dropdown - FIXED -->
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[160px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span class="mr-2" id="stockText">{{ request('stock_status') ? (request('stock_status') == 'available' ? 'Stok Aman' : (request('stock_status') == 'low' ? 'Stok Menipis' : 'Stok Habis')) : 'Semua Stok' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto no-scrollbar"
                             style="top: 100%;">
                            <button type="button" @click="selectStock('', 'Semua Stok'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ !request('stock_status') ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Semua Stok
                            </button>
                            <button type="button" @click="selectStock('available', 'Stok Aman'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('stock_status') == 'available' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Stok Aman
                            </button>
                            <button type="button" @click="selectStock('low', 'Stok Menipis'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('stock_status') == 'low' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Stok Menipis
                            </button>
                            <button type="button" @click="selectStock('out', 'Stok Habis'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('stock_status') == 'out' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Stok Habis
                            </button>
                        </div>
                        <input type="hidden" name="stock_status" id="stockInput" value="{{ request('stock_status') }}">
                    </div>

                    <button type="button" onclick="filterStock()" class="bg-accent-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-accent-600 shadow-lg shadow-accent-500/30 transition-all active:scale-95">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <button type="button" onclick="resetFilter()" class="flex items-center justify-center px-4 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            <!-- Products Table -->
            <div id="stockTableWrapper">
                @include('admin.stock.partials.table')
            </div>

        </div>
    </div>

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
        </style>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        initStockAjax();
    });

    function initStockAjax() {
        const tableWrapper = document.getElementById('stockTableWrapper');
        if (!tableWrapper) return;

        // Handle pagination links
        tableWrapper.addEventListener('click', function(event) {
            const link = event.target.closest('a.ajax-link');
            if (!link) return;
            
            event.preventDefault();
            const url = new URL(link.href, window.location.origin);
            fetchStockTable(url.searchParams.toString());
        });

        // Handle search input with debounce
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let debounceTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    filterStock();
                }, 500); // 500ms debounce
            });
        }
    }

    function selectCategory(value, text) {
        document.getElementById('categoryInput').value = value;
        document.getElementById('categoryText').textContent = text;
        filterStock();
    }

    function selectStock(value, text) {
        document.getElementById('stockInput').value = value;
        document.getElementById('stockText').textContent = text;
        filterStock();
    }

    function filterStock() {
        const search = document.getElementById('searchInput').value;
        const category = document.getElementById('categoryInput').value;
        const stockStatus = document.getElementById('stockInput').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (category) params.append('category', category);
        if (stockStatus) params.append('stock_status', stockStatus);
        
        fetchStockTable(params.toString());
    }

    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('categoryInput').value = '';
        document.getElementById('stockInput').value = '';
        document.getElementById('categoryText').textContent = 'Semua Kategori';
        document.getElementById('stockText').textContent = 'Semua Stok';
        fetchStockTable('');
    }

    async function fetchStockTable(queryString) {
        const tableWrapper = document.getElementById('stockTableWrapper');
        if (!tableWrapper) return;

        // Show loading state
        tableWrapper.style.opacity = '0.5';
        tableWrapper.style.pointerEvents = 'none';
        
        try {
            const response = await fetch("{{ route('admin.stock.index') }}?" + queryString, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const tableContent = doc.querySelector('#stockTableWrapper');
            
            if (tableContent) {
                tableWrapper.innerHTML = tableContent.innerHTML;
            } else {
                tableWrapper.innerHTML = html;
            }
            lucide.createIcons();
        } catch (error) {
            console.error('Error loading stock data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat!',
                text: 'Terjadi kesalahan saat memuat data stok.',
                confirmButtonColor: '#ef4444'
            });
        } finally {
            tableWrapper.style.opacity = '1';
            tableWrapper.style.pointerEvents = 'auto';
        }
    }
    </script>
    @endpush
@endsection