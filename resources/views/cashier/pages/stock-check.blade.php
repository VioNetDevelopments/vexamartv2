@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6" 
     x-data="stockCheckApp()" 
     x-init="init()">
    <div class="relative max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="animate-fade-in-down">
            <h1 class="text-3xl font-bold text-navy-900 dark:text-white">
                Cek Stok Produk
            </h1>
            <p class="text-slate-600 dark:text-slate-400">Pantau ketersediaan stok semua produk</p>
        </div>

        <!-- Search Bar & Stats Cards Row -->
        <div class="flex items-center gap-4">
            <!-- Search Bar - Smaller -->
            <div class="flex-1 max-w-lg">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400"></i>
                    <input type="text" 
                           x-model="search"
                           @input.debounce.300ms="searchProducts()"
                           placeholder="Cari nama atau SKU produk..."
                           class="w-full rounded-xl border border-slate-200 bg-white dark:bg-navy-900 pl-12 pr-4 py-3 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:text-white shadow-sm">
                </div>
            </div>
            
            <!-- Stats Cards - Modern & Premium -->
            <div class="flex gap-3">
                <!-- Available -->
                <div class="flex items-center gap-3 px-5 py-3 rounded-2xl bg-white dark:bg-navy-900 border-2 border-success/20 shadow-lg shadow-success/10">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center shadow-lg shadow-success/30">
                        <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Tersedia</p>
                        <p class="text-2xl font-bold text-success" x-text="stats.available"></p>
                    </div>
                </div>
                
                <!-- Low Stock -->
                <div class="flex items-center gap-3 px-5 py-3 rounded-2xl bg-white dark:bg-navy-900 border-2 border-warning/20 shadow-lg shadow-warning/10">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning to-warning/80 flex items-center justify-center shadow-lg shadow-warning/30">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Menipis</p>
                        <p class="text-2xl font-bold text-warning" x-text="stats.low"></p>
                    </div>
                </div>
                
                <!-- Out of Stock -->
                <div class="flex items-center gap-3 px-5 py-3 rounded-2xl bg-white dark:bg-navy-900 border-2 border-danger/20 shadow-lg shadow-danger/10">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-danger to-danger/80 flex items-center justify-center shadow-lg shadow-danger/30">
                        <i data-lucide="x-circle" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Habis</p>
                        <p class="text-2xl font-bold text-danger" x-text="stats.out"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-navy-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Produk</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">SKU</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Stok Saat Ini</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-slate-500 uppercase">Min. Stok</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        <template x-if="loading">
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-accent-500 border-t-transparent mx-auto"></div>
                                    <p class="text-slate-500 mt-4">Memuat data...</p>
                                </td>
                            </tr>
                        </template>
                        
                        <template x-for="product in products" :key="product.id">
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0">
                                            <template x-if="product.image">
                                                <img :src="'/storage/' + product.image" :alt="product.name" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!product.image">
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i data-lucide="package" class="w-6 h-6 text-slate-400"></i>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-navy-900 dark:text-white" x-text="product.name"></p>
                                            <p class="text-xs text-slate-500">ID: <span x-text="product.id"></span></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-navy-800 text-xs font-mono font-medium" x-text="product.sku"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-lg font-bold text-navy-900 dark:text-white" x-text="product.stock"></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-center text-slate-600 dark:text-slate-300" x-text="product.min_stock"></td>
                                <td class="px-6 py-4">
                                    <template x-if="product.stock <= 0">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-danger/10 text-danger border border-danger/20">
                                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                                            Habis
                                        </span>
                                    </template>
                                    <template x-if="product.stock > 0 && product.stock <= product.min_stock">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-warning/10 text-warning border border-warning/20">
                                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                                            Terbatas
                                        </span>
                                    </template>
                                    <template x-if="product.stock > product.min_stock">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-success/10 text-success border border-success/20">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                            Tersedia
                                        </span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300" x-text="product.category?.name || '-'"></td>
                            </tr>
                        </template>
                        
                        <template x-if="!loading && products.length === 0">
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center mx-auto mb-4">
                                        <i data-lucide="inbox" class="w-10 h-10 text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">Tidak ada produk</p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <template x-if="!loading && products.length > 0">
                <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <div class="text-sm text-slate-500 dark:text-slate-400">
                        Menampilkan <span class="font-semibold text-navy-900 dark:text-white" x-text="pagination.from"></span> - <span class="font-semibold text-navy-900 dark:text-white" x-text="pagination.to"></span> dari <span class="font-semibold text-navy-900 dark:text-white" x-text="pagination.total"></span> produk
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="prevPage()" 
                                :disabled="pagination.current_page <= 1"
                                :class="pagination.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-accent-500 hover:text-white'"
                                class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-sm font-medium transition-all duration-200 disabled:opacity-50">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </button>
                        
                        <template x-for="page in pagination.last_page" :key="page">
                            <button @click="goToPage(page)"
                                    :class="page === pagination.current_page ? 'bg-accent-500 text-white border-accent-500' : 'hover:bg-accent-500 hover:text-white'"
                                    class="w-10 h-10 rounded-xl border border-slate-200 dark:border-white/10 text-sm font-medium transition-all duration-200">
                                <span x-text="page"></span>
                            </button>
                        </template>
                        
                        <button @click="nextPage()" 
                                :disabled="pagination.current_page >= pagination.last_page"
                                :class="pagination.current_page >= pagination.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-accent-500 hover:text-white'"
                                class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-sm font-medium transition-all duration-200 disabled:opacity-50">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function stockCheckApp() {
    return {
        products: [],
        search: '',
        loading: false,
        stats: {
            available: 0,
            low: 0,
            out: 0
        },
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 6,
            total: 0,
            from: 0,
            to: 0
        },
        
        init() {
            this.loadProducts();
            this.loadStats();
            setTimeout(() => lucide.createIcons(), 100);
        },
        
        loadProducts(page = 1) {
            this.loading = true;
            
            const params = new URLSearchParams({
                page: page,
                per_page: 6,
                search: this.search
            });
            
            fetch('/cashier/stock-check-data?' + params, {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                this.products = data.data || [];
                this.pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    per_page: data.per_page,
                    total: data.total,
                    from: data.from,
                    to: data.to
                };
                this.loading = false;
                setTimeout(() => lucide.createIcons(), 100);
            })
            .catch(() => {
                this.products = [];
                this.loading = false;
            });
        },
        
        loadStats() {
            fetch('/cashier/stock-stats', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                this.stats = data;
            })
            .catch(() => {});
        },
        
        searchProducts() {
            this.loadProducts(1);
        },
        
        prevPage() {
            if (this.pagination.current_page > 1) {
                this.loadProducts(this.pagination.current_page - 1);
            }
        },
        
        nextPage() {
            if (this.pagination.current_page < this.pagination.last_page) {
                this.loadProducts(this.pagination.current_page + 1);
            }
        },
        
        goToPage(page) {
            if (page !== this.pagination.current_page) {
                this.loadProducts(page);
            }
        }
    }
}
</script>
@endpush

@push('styles')
<style>
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }
</style>
@endpush
@endsection