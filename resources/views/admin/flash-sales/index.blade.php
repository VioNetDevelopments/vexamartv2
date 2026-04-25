@extends('layouts.app')

@section('page-title', 'Flash Sales')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <!-- Animated Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-red-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                                <i data-lucide="zap" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight flex items-center gap-3">
                                    Manajemen Flash Sale
                                    <span class="text-xs font-bold px-3 py-1 bg-accent-500/10 text-accent-600 rounded-lg border border-accent-500/20" id="stat-total-badge">
                                        {{ $stats['total'] }}
                                    </span>
                                </h1>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Kelola penawaran waktu terbatas</p>
                            </div>
                        </div>
                        </span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.flash-sales.create') }}" 
                       class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-300 hover:-translate-y-0.5">
                        <i data-lucide="plus-circle" class="w-5 h-5 group-hover:rotate-90 transition-transform"></i>
                        <span>Buat Flash Sale</span>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center shadow-lg shadow-slate-500/30">
                                <i data-lucide="zap" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Flash Sale</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white" id="stat-total">{{ $stats['total'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                                <i data-lucide="activity" class="w-6 h-6 text-white"></i>
                            </div>
                            <div id="active-status-indicator">
                                @if($stats['active'] > 0)
                                    <span class="flex items-center gap-1 text-xs font-semibold text-green-600 bg-green-500/10 px-2 py-1 rounded-full animate-pulse">
                                        <i data-lucide="bell" class="w-3 h-3"></i>
                                        Live
                                    </span>
                                @endif
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Sedang Berlangsung</p>
                        <h3 class="text-2xl font-bold text-green-600" id="stat-active">{{ $stats['active'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Akan Datang</p>
                        <h3 class="text-2xl font-bold text-blue-600" id="stat-upcoming">{{ $stats['upcoming'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-400/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-400 to-slate-500 flex items-center justify-center shadow-lg shadow-slate-500/30">
                                <i data-lucide="archive" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Berakhir</p>
                        <h3 class="text-2xl font-bold text-slate-600" id="stat-expired">{{ $stats['expired'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up overflow-visible relative z-20" style="animation-delay: 0.5s;">
                <form id="flashSaleFilterForm" action="{{ route('admin.flash-sales.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <div class="relative group">
                            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari flash sale..."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        </div>
                    </div>

                    <!-- Status Dropdown -->
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[180px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span id="statusText">{{ request('status') ? (request('status') == 'active' ? 'Sedang Berlangsung' : (request('status') == 'upcoming' ? 'Akan Datang' : 'Berakhir')) : 'Semua Status' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                             style="top: 100%;">
                            <button type="button" @click="selectStatus('', 'Semua Status'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ !request('status') ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Semua Status
                            </button>
                            <button type="button" @click="selectStatus('active', 'Sedang Berlangsung'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('status') == 'active' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Sedang Berlangsung
                            </button>
                            <button type="button" @click="selectStatus('upcoming', 'Akan Datang'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('status') == 'upcoming' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Akan Datang
                            </button>
                            <button type="button" @click="selectStatus('expired', 'Berakhir'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('status') == 'expired' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Berakhir
                            </button>
                        </div>
                        <input type="hidden" name="status" id="statusInput" value="{{ request('status') }}">
                    </div>

                    <button type="submit" class="bg-accent-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-accent-600 shadow-lg shadow-accent-500/30 transition-all active:scale-95">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.flash-sales.index') }}" class="flex items-center justify-center px-4 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                </form>
            </div>

            <!-- Flash Sales Table -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.6s;">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-accent-500/10 to-blue-500/10 dark:from-accent-900/20 dark:to-blue-900/20">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Diskon</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Stok</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Periode</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="flashSaleTableBody" class="divide-y divide-slate-100 dark:divide-white/5">
                            @include('admin.flash-sales.partials.table')
                        </tbody>
                    </table>
                </div>

                <div id="paginationContainer">
                    @if($flashSales->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-white/5">
                            {{ $flashSales->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
        // AJAX Filtering & Searching
        const flashSaleForm = document.getElementById('flashSaleFilterForm');
        const flashSaleTableBody = document.getElementById('flashSaleTableBody');
        const paginationContainer = document.getElementById('paginationContainer');
        const searchInput = flashSaleForm.querySelector('input[name="search"]');
        let searchTimeout;

        function fetchFlashSales(url = null) {
            const formData = new FormData(flashSaleForm);
            const params = new URLSearchParams(formData);
            const fetchUrl = url || `{{ route('admin.flash-sales.index') }}?${params.toString()}`;

            // Add loading state
            flashSaleTableBody.classList.add('opacity-50');

            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                flashSaleTableBody.innerHTML = data.html;
                paginationContainer.innerHTML = data.pagination || '';
                
                // Update stats cards
                if (data.stats) {
                    document.getElementById('stat-total').innerText = data.stats.total;
                    document.getElementById('stat-total-badge').innerText = data.stats.total;
                    document.getElementById('stat-active').innerText = data.stats.active;
                    document.getElementById('stat-upcoming').innerText = data.stats.upcoming;
                    document.getElementById('stat-expired').innerText = data.stats.expired;
                    
                    const activeIndicator = document.getElementById('active-status-indicator');
                    if (data.stats.active > 0) {
                        activeIndicator.innerHTML = `
                            <span class="flex items-center gap-1 text-xs font-semibold text-green-600 bg-green-500/10 px-2 py-1 rounded-full animate-pulse">
                                <i data-lucide="bell" class="w-3 h-3"></i>
                                Live
                            </span>`;
                    } else {
                        activeIndicator.innerHTML = '';
                    }
                }

                flashSaleTableBody.classList.remove('opacity-50');
                lucide.createIcons();
                updateRealTimeStatuses(); // Re-run status check
            });
        }

        flashSaleForm.addEventListener('submit', (e) => {
            e.preventDefault();
            fetchFlashSales();
        });

        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => fetchFlashSales(), 500);
        });

        function selectStatus(value, text) {
            document.getElementById('statusInput').value = value;
            document.getElementById('statusText').innerText = text;
            fetchFlashSales();
        }

        // Handle pagination links via AJAX
        document.addEventListener('click', (e) => {
            if (e.target.closest('#paginationContainer a')) {
                e.preventDefault();
                const url = e.target.closest('a').href;
                fetchFlashSales(url);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        // Real-time Status Check
        function updateRealTimeStatuses() {
            const rows = document.querySelectorAll('.flash-sale-row');
            const now = new Date();

            rows.forEach(row => {
                const startsAt = new Date(row.dataset.starts);
                const endsAt = new Date(row.dataset.ends);
                const isActive = row.dataset.active === '1';
                const statusCell = row.querySelector('.status-cell');
                const currentBadge = statusCell.querySelector('.status-badge');
                const currentStatus = currentBadge ? currentBadge.dataset.status : '';

                let newStatus = '';
                let badgeHTML = '';

                if (!isActive) {
                    newStatus = 'inactive';
                    badgeHTML = `
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-slate-100 text-slate-400 status-badge" data-status="inactive">
                            <i data-lucide="pause-circle" class="w-3 h-3"></i>
                            Dinonaktifkan
                        </span>`;
                } else if (now >= startsAt && now < endsAt) {
                    newStatus = 'active';
                    badgeHTML = `
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-green-100 text-green-700 animate-pulse status-badge" data-status="active">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            Berlangsung
                        </span>`;
                } else if (now < startsAt) {
                    newStatus = 'upcoming';
                    badgeHTML = `
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-blue-100 text-blue-700 status-badge" data-status="upcoming">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            Akan Datang
                        </span>`;
                } else {
                    newStatus = 'expired';
                    badgeHTML = `
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-slate-100 text-slate-600 status-badge" data-status="expired">
                            <i data-lucide="archive" class="w-3 h-3"></i>
                            Berakhir
                        </span>`;
                }

                if (newStatus !== currentStatus) {
                    statusCell.innerHTML = badgeHTML;
                    
                    // Update Period Indicators
                    const startIcon = row.querySelector('.start-icon');
                    const endIcon = row.querySelector('.end-icon');
                    const startRow = row.querySelector('.start-time-row');
                    
                    if (newStatus === 'expired') {
                        startIcon.classList.remove('text-green-500', 'animate-pulse');
                        startIcon.classList.add('text-slate-400');
                        startRow.classList.add('opacity-50');
                        
                        endIcon.classList.remove('text-red-500');
                        endIcon.classList.add('text-green-500');
                    } else {
                        startIcon.classList.add('text-green-500', 'animate-pulse');
                        startIcon.classList.remove('text-slate-400');
                        startRow.classList.remove('opacity-50');
                        
                        endIcon.classList.add('text-red-500');
                        endIcon.classList.remove('text-green-500');
                    }
                    
                    lucide.createIcons();
                }
            });
        }

        // Run status check every second
        setInterval(updateRealTimeStatuses, 1000);
        document.addEventListener('DOMContentLoaded', updateRealTimeStatuses);
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
        </style>
    @endpush
@endsection