@extends('layouts.app')

@section('content')
<style>
    /* Fix native browser calendar icon color on the right side of date input */
    .dark input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1) brightness(0.9);
        cursor: pointer;
    }
</style>

<div class="p-4 sm:p-6 lg:p-8 space-y-8 min-h-screen" x-data="transactionManager()">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 animate-fade-in-down">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                <i data-lucide="receipt" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">Riwayat Transaksi</h1>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total <span class="text-accent-500 font-bold" x-text="stats.all_time_total">{{ $allTimeTotal }}</span> transaksi tercatat di sistem</p>
            </div>
        </div>
        
        <!-- Modern Status Badge -->
        <div class="flex items-center gap-3">
            <button @click="exportCsv()" class="group flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white dark:bg-navy-900 border border-slate-200/60 dark:border-white/5 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-navy-800 hover:shadow-md transition-all">
                <i data-lucide="printer" class="w-4 h-4 text-slate-400 group-hover:text-accent-500 transition-colors"></i>
                <span>Cetak Laporan</span>
            </button>
            
            <!-- Modern System Status Badge -->
            <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl bg-gradient-to-r from-success/10 to-success/5 border border-success/20 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-success"></span>
                </span>
                <span class="text-xs font-bold text-success uppercase tracking-wider">Online</span>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="relative z-40 bg-white/80 dark:bg-navy-900/80 backdrop-blur-xl border border-white/20 dark:border-white/5 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.1s;">
        <form @submit.prevent="applyFilters()" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
            <!-- Date Filter -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Dari Tanggal</label>
                <div class="relative group">
                    <i data-lucide="calendar" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                    <input type="date" name="date_from" x-model="filters.date_from"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
                <div class="relative group">
                    <i data-lucide="calendar" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                    <input type="date" name="date_to" x-model="filters.date_to"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                </div>
            </div>

            <!-- Payment Method with Custom Dropdown -->
            <div class="space-y-2" x-data="{ 
                open: false, 
                options: {
                    '': 'Semua Metode',
                    @foreach($paymentMethods as $method)
                    '{{ $method }}': '{{ $method == 'cash' ? 'Tunai' : (in_array(strtolower($method), ['bank', 'card']) ? ucfirst($method) : (strlen($method) <= 4 ? strtoupper($method) : ucfirst($method))) }}',
                    @endforeach
                }
            }">
                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Metode Bayar</label>
                <div class="relative">
                    <button type="button" @click="open = !open" @click.away="open = false"
                            class="w-full flex items-center justify-between pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                        <span x-text="options[filters.payment_method] || 'Pilih Metode'"></span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10">
                        <template x-for="(label, key) in options">
                            <button type="button" @click="filters.payment_method = key; open = false; applyFilters()"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20"
                                    :class="filters.payment_method === key ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300'">
                                <span x-text="label"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="space-y-2 lg:col-span-1">
                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Cari Invoice</label>
                <div class="relative group">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500"></i>
                    <input type="text" x-model="filters.search" @input.debounce.500ms="applyFilters()" placeholder="No. Invoice / Customer..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-accent-500 text-white rounded-xl py-2.5 text-sm font-black uppercase tracking-widest hover:bg-accent-600 shadow-lg shadow-accent-500/30 transition-all active:scale-95">
                    <i data-lucide="sliders" class="w-4 h-4"></i>
                    Filter
                </button>
                <button type="button" @click="resetFilters()" class="flex items-center justify-center px-4 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 transition-all">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up" style="animation-delay: 0.2s;">
        <!-- Total Transactions -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-accent-500/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-accent-50 dark:bg-accent-500/10 flex items-center justify-center text-accent-500">
                    <i data-lucide="receipt" class="w-6 h-6"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Transaksi</p>
                    <h3 class="text-xl lg:text-2xl font-black text-navy-900 dark:text-white tracking-tight" :class="{ 'truncate': sidebarOpen }" x-text="stats.total_count">0</h3>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-success/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-success/5 flex items-center justify-center text-success">
                    <i data-lucide="dollar-sign" class="w-6 h-6"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Pendapatan</p>
                    <h3 class="text-lg lg:text-xl xl:text-2xl font-black text-navy-900 dark:text-white tracking-tight" :class="{ 'truncate': sidebarOpen }" x-text="'Rp' + formatNumber(stats.total_revenue)">Rp0</h3>
                </div>
            </div>
        </div>

        <!-- Average Transaction -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-500/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-500">
                    <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rata-rata/Trx</p>
                    <h3 class="text-lg lg:text-xl xl:text-2xl font-black text-navy-900 dark:text-white tracking-tight" :class="{ 'truncate': sidebarOpen }" x-text="'Rp' + formatNumber(stats.average_revenue)">Rp0</h3>
                </div>
            </div>
        </div>

        <!-- Popular Method -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-warning/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110" :class="getMethodBg(stats.top_method)"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-colors" :class="getMethodBadge(stats.top_method)">
                    <i :data-lucide="getMethodIcon(stats.top_method)" class="w-6 h-6"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Metode Utama</p>
                    <h3 class="text-xl lg:text-2xl font-black text-navy-900 dark:text-white tracking-tight" :class="{ 'truncate': sidebarOpen }" x-text="stats.top_method">
                        -
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table Section -->
    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 overflow-hidden animate-fade-in-up" 
         style="animation-delay: 0.3s;"
         id="transactionTableContainer">
        @include('admin.transactions._table')
    </div>
</div>

@push('scripts')
<script>
function transactionManager() {
    return {
        filters: {
            date_from: '{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}',
            date_to: '{{ request('date_to', now()->format('Y-m-d')) }}',
            payment_method: '{{ request('payment_method', '') }}',
            search: '{{ request('search', '') }}'
        },
        stats: @json($stats),
        isLoading: false,

        init() {
            this.$nextTick(() => lucide.createIcons());
        },

        async applyFilters() {
            this.isLoading = true;
            const params = new URLSearchParams(this.filters).toString();
            try {
                const response = await fetch(`{{ route('admin.transactions.index') }}?${params}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                
                document.getElementById('transactionTableContainer').innerHTML = data.html;
                this.stats = data.stats;
                this.stats.top_method = this.stats.top_method === '-' ? '-' : this.stats.top_method.charAt(0).toUpperCase() + this.stats.top_method.slice(1);
                
                // Update URL without reload
                window.history.pushState({}, '', `{{ route('admin.transactions.index') }}?${params}`);
                
                this.$nextTick(() => lucide.createIcons());
            } catch (e) {
                console.error('Filter failed:', e);
            } finally {
                this.isLoading = false;
            }
        },

        async changePage(url) {
            this.isLoading = true;
            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                document.getElementById('transactionTableContainer').innerHTML = data.html;
                this.$nextTick(() => lucide.createIcons());
            } catch (e) {
                console.error('Page change failed:', e);
            } finally {
                this.isLoading = false;
            }
        },

        resetFilters() {
            this.filters = {
                date_from: '{{ now()->startOfMonth()->format('Y-m-d') }}',
                date_to: '{{ now()->format('Y-m-d') }}',
                payment_method: '',
                search: ''
            };
            this.applyFilters();
        },

        exportCsv() {
            const params = new URLSearchParams(this.filters).toString();
            window.location.href = `{{ route('admin.transactions.export') }}?${params}`;
        },

        formatNumber(num) {
            return Number(num).toLocaleString('id-ID');
        },

        getMethodIcon(method) {
            const icons = {
                'Cash': 'banknote',
                'Tunai': 'banknote',
                'Qris': 'qr-code',
                'Debit': 'credit-card',
                'Card': 'credit-card',
                'Bank': 'building-2',
                'Bank Transfer': 'building-2',
                'Ewallet': 'wallet',
                'E-Wallet': 'wallet'
            };
            return icons[method] || 'zap';
        },

        getMethodBadge(method) {
            const badges = {
                'Cash': 'bg-success/10 text-success',
                'Tunai': 'bg-success/10 text-success',
                'Qris': 'bg-blue-500/10 text-blue-500',
                'Debit': 'bg-purple-500/10 text-purple-500',
                'Card': 'bg-purple-500/10 text-purple-500',
                'Bank': 'bg-accent-500/10 text-accent-500',
                'Bank Transfer': 'bg-accent-500/10 text-accent-500',
                'Ewallet': 'bg-warning/10 text-warning',
                'E-Wallet': 'bg-warning/10 text-warning'
            };
            return badges[method] || 'bg-slate-500/10 text-slate-500';
        },

        getMethodBg(method) {
            const bgs = {
                'Cash': 'from-success/10',
                'Tunai': 'from-success/10',
                'Qris': 'from-blue-500/10',
                'Debit': 'from-purple-500/10',
                'Card': 'from-purple-500/10',
                'Bank': 'from-accent-500/10',
                'Bank Transfer': 'from-accent-500/10',
                'Ewallet': 'from-warning/10',
                'E-Wallet': 'from-warning/10'
            };
            return bgs[method] || 'from-slate-500/10';
        }
    }
}
</script>
@endpush
@endsection