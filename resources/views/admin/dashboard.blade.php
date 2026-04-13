@extends('layouts.app')

@section('content')

    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <!-- Animated Background Elements -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto space-y-8">
            <!-- Header Section -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent flex items-center gap-3">
                            <i data-lucide="layout-dashboard" class="w-8 h-8 text-accent-600"></i>
                            Dashboard
                        </h1>
                        <span
                            class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-accent-50 dark:bg-accent-900/20 border border-accent-200 dark:border-accent-800">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-accent-500"></span>
                            </span>
                            <i data-lucide="radio" class="w-3 h-3 text-accent-600 dark:text-accent-400"></i>
                            <span class="text-xs font-semibold text-accent-600 dark:text-accent-400">Live</span>
                        </span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Selamat datang kembali, <span
                            class="font-semibold text-accent-600 dark:text-accent-400">{{ auth()->user()->name }}</span>! 👋
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div
                        class="hidden sm:flex items-center gap-3 bg-white/50 dark:bg-navy-800/50 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-slate-200/60 dark:border-white/5 shadow-sm hover:shadow-md transition-all duration-300">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 shadow-lg shadow-accent-500/20 group cursor-default">
                            <i data-lucide="clock"
                                class="h-6 w-6 text-white group-hover:rotate-12 transition-transform duration-300"></i>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="h-1.5 w-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                                <p
                                    class="text-[10px] font-black uppercase tracking-widest text-accent-600 dark:text-accent-400 leading-none">
                                    Live Monitor</p>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <p class="text-sm font-bold text-navy-900 dark:text-white" id="currentDate"></p>
                                <span class="text-slate-300 dark:text-white/10 text-xs">|</span>
                                <p class="text-sm font-black text-navy-900 dark:text-white font-mono" id="currentTime"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Total Sales -->
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl hover:shadow-accent-500/10 transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up"
                    style="animation-delay: 0.1s;">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/30">
                                <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                            </div>
                            <span
                                class="flex items-center gap-1 text-xs font-semibold text-success bg-success/10 px-2 py-1 rounded-full">
                                <i data-lucide="trending-up" class="w-3 h-3"></i>
                                +12.5%
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Penjualan Hari Ini</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white mb-2">
                            Rp {{ number_format($stats['today_sales'] ?? 0, 0, ',', '.') }}
                        </h3>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            <span>Update real-time</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Transactions -->
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up"
                    style="animation-delay: 0.2s;">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                            </div>
                            <span
                                class="flex items-center gap-1 text-xs font-semibold text-success bg-success/10 px-2 py-1 rounded-full">
                                <i data-lucide="trending-up" class="w-3 h-3"></i>
                                +5.2%
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Transaksi</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white mb-2">
                            {{ $stats['today_transactions'] ?? 0 }}
                        </h3>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <i data-lucide="activity" class="w-3 h-3"></i>
                            <span>Rata-rata harian</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Low Stock Alert -->
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl hover:shadow-warning/10 transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up"
                    style="animation-delay: 0.3s;">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-warning/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning to-warning/80 flex items-center justify-center shadow-lg shadow-warning/30">
                                <i data-lucide="alert-circle" class="w-6 h-6 text-white"></i>
                            </div>
                            @if(($stats['low_stock_products'] ?? 0) > 0)
                                <span
                                    class="flex items-center gap-1 text-xs font-semibold text-warning bg-warning/10 px-2 py-1 rounded-full animate-pulse">
                                    <i data-lucide="bell" class="w-3 h-3"></i>
                                    Perhatian
                                </span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Stok Kritis</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white mb-2">
                            {{ $stats['low_stock_products'] ?? 0 }}
                        </h3>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <i data-lucide="box" class="w-3 h-3"></i>
                            <span>Perlu restock</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Products -->
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl hover:shadow-success/10 transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up"
                    style="animation-delay: 0.4s;">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-success/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center shadow-lg shadow-success/30">
                                <i data-lucide="package" class="w-6 h-6 text-white"></i>
                            </div>
                            <span
                                class="flex items-center gap-1 text-xs font-semibold text-success bg-success/10 px-2 py-1 rounded-full">
                                <i data-lucide="check" class="w-3 h-3"></i>
                                Aktif
                            </span>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Produk</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white mb-2">
                            {{ $stats['total_products'] ?? 0 }}
                        </h3>
                        <div class="flex items-center gap-2 text-xs text-slate-400">
                            <i data-lucide="database" class="w-3 h-3"></i>
                            <span>Database</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sales Chart - Modern Card Design -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5" x-data="{ open: false }">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center">
                                    <i data-lucide="trending-up" class="w-4 h-4 text-white"></i>
                                </div>
                                <span>Grafik Penjualan</span>
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1" id="chartPeriodText">7 Hari Terakhir</p>
                        </div>
                        
                        <!-- 3-Dot Menu with Dropdown -->
                        <div class="relative">
                            <button @click="open = !open" 
                                    class="p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-navy-800 transition-all duration-300 hover:scale-110"
                                    :class="open ? 'bg-accent-500 text-white rotate-90' : 'text-slate-400'">
                                <i data-lucide="more-vertical" class="w-5 h-5 transition-transform duration-300 ease-out"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                 class="absolute right-0 mt-2 w-44 bg-white dark:bg-navy-800 rounded-xl shadow-2xl border border-slate-200 dark:border-white/10 py-2 z-50 overflow-hidden">
                                
                                <button @click="updateChart(3); open = false" 
                                        class="w-full px-4 py-2.5 text-left text-sm hover:bg-accent-500 hover:text-white transition-all duration-200 flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    <span>3 Hari</span>
                                </button>
                                <button @click="updateChart(7); open = false" 
                                        class="w-full px-4 py-2.5 text-left text-sm hover:bg-accent-500 hover:text-white transition-all duration-200 flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    <span>7 Hari</span>
                                </button>
                                <button @click="updateChart(14); open = false" 
                                        class="w-full px-4 py-2.5 text-left text-sm hover:bg-accent-500 hover:text-white transition-all duration-200 flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    <span>14 Hari</span>
                                </button>
                                <button @click="updateChart(30); open = false" 
                                        class="w-full px-4 py-2.5 text-left text-sm hover:bg-accent-500 hover:text-white transition-all duration-200 flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    <span>30 Hari</span>
                                </button>
                                
                                <!-- Divider -->
                                <div class="border-t border-slate-200 dark:border-white/10 my-1"></div>
                                
                                <button @click="refreshChart(); open = false" 
                                        class="w-full px-4 py-2.5 text-left text-sm hover:bg-accent-500 hover:text-white transition-all duration-200 flex items-center gap-2">
                                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                    <span>Refresh Data</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chart Container -->
                    <div class="h-80 relative">
                        <canvas id="salesChart"></canvas>
                        
                        <!-- Loading Animation -->
                        <div x-show="$store.dashboardState.chartLoading" 
                             class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-navy-900/50 backdrop-blur-sm rounded-xl">
                            <div class="flex flex-col items-center gap-3">
                                <div class="animate-spin rounded-full h-10 w-10 border-4 border-accent-500 border-t-transparent"></div>
                                <p class="text-sm text-slate-500">Memuat data...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Chart - Modern Card Design -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                                    <i data-lucide="credit-card" class="w-4 h-4 text-white"></i>
                                </div>
                                <span>Metode Pembayaran</span>
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Distribusi transaksi</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <!-- Doughnut Chart -->
                        <div class="w-48 h-48 relative flex-shrink-0">
                            <canvas id="paymentChart"></canvas>
                            
                            <!-- Center Text -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="text-center">
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Total</p>
                                    <p class="text-lg font-bold text-navy-900 dark:text-white" id="paymentTotal">0</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Methods List -->
                        <div class="flex-1 space-y-3" x-data>
                            <template x-for="method in $store.dashboardState.paymentMethods" :key="method.name">
                                <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full" :style="'background-color: ' + method.color"></div>
                                        <div class="flex items-center gap-2">
                                            <i :data-lucide="method.icon" class="w-4 h-4" :style="'color: ' + method.color"></i>
                                            <span class="text-sm font-medium text-navy-900 dark:text-white" x-text="method.name"></span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-navy-900 dark:text-white" x-text="method.percentage + '%'" ></p>
                                        <p class="text-xs text-slate-500" x-text="method.count + ' trx'"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Top Products & Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Products dengan Pagination -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up"
                    style="animation-delay: 0.7s;"
                    x-data="topProductsData()">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white">Produk Terlaris</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Top 5 produk minggu ini</p>
                        </div>
                        <div class="flex items-center gap-2" x-show="products.last_page > 1">
                            <button @click="prevPage()"
                                    :disabled="products.current_page <= 1"
                                    :class="products.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-100 dark:hover:bg-navy-800'"
                                    class="p-2 rounded-lg transition-colors">
                                <i data-lucide="chevron-left" class="w-4 h-4 text-slate-500"></i>
                            </button>
                            <span class="text-sm text-slate-500">
                                <span x-text="products.current_page"></span>/<span x-text="products.last_page"></span>
                            </span>
                            <button @click="nextPage()"
                                    :disabled="products.current_page >= products.last_page"
                                    :class="products.current_page >= products.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-100 dark:hover:bg-navy-800'"
                                    class="p-2 rounded-lg transition-colors">
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-500"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-accent-500 border-t-transparent"></div>
                    </div>

                    <!-- Products List -->
                    <div class="space-y-4" x-show="!loading">
                        <template x-for="(product, index) in products.data" :key="product.id">
                            <div class="group flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-navy-800 hover:bg-accent-50 dark:hover:bg-navy-700 transition-all duration-300 hover:-translate-x-2">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500/20 to-accent-600/20 flex items-center justify-center">
                                    <span class="text-lg font-bold text-accent-600 dark:text-accent-400">#<span x-text="((products.current_page - 1) * 5) + index + 1"></span></span>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-slate-200 dark:bg-navy-700 overflow-hidden flex-shrink-0">
                                    <template x-if="product.image">
                                        <img :src="'/storage/' + product.image" :alt="product.name"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    </template>
                                    <template x-if="!product.image">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="package" class="w-6 h-6 text-slate-400"></i>
                                        </div>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-navy-900 dark:text-white truncate" x-text="product.name"></h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400"><span x-text="product.total_sold || 0"></span> terjual</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-accent-600 dark:text-accent-400">Rp <span x-text="formatNumber(product.total_revenue || 0)"></span></p>
                                </div>
                            </div>
                        </template>

                        <div x-show="products.data.length === 0" class="text-center py-12">
                            <i data-lucide="shopping-bag" class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                            <p class="text-slate-500 dark:text-slate-400">Belum ada data penjualan</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions dengan Pagination -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up"
                    style="animation-delay: 0.8s;"
                    x-data="recentTransactionsData()">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white">Transaksi Terbaru</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">5 transaksi terakhir</p>
                        </div>
                        <div class="flex items-center gap-2" x-show="transactions.last_page > 1">
                            <button @click="prevPage()"
                                    :disabled="transactions.current_page <= 1"
                                    :class="transactions.current_page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-100 dark:hover:bg-navy-800'"
                                    class="p-2 rounded-lg transition-colors">
                                <i data-lucide="chevron-left" class="w-4 h-4 text-slate-500"></i>
                            </button>
                            <span class="text-sm text-slate-500">
                                <span x-text="transactions.current_page"></span>/<span x-text="transactions.last_page"></span>
                            </span>
                            <button @click="nextPage()"
                                    :disabled="transactions.current_page >= transactions.last_page"
                                    :class="transactions.current_page >= transactions.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-100 dark:hover:bg-navy-800'"
                                    class="p-2 rounded-lg transition-colors">
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-500"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div x-show="loading" class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-accent-500 border-t-transparent"></div>
                    </div>

                    <!-- Transactions List -->
                    <div class="space-y-3" x-show="!loading">
                        <template x-for="transaction in transactions.data" :key="transaction.id">
                            <div class="group flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-navy-800 hover:bg-slate-100 dark:hover:bg-navy-700 transition-all duration-300">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500/20 to-accent-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i data-lucide="receipt" class="w-5 h-5 text-accent-600 dark:text-accent-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-navy-900 dark:text-white font-mono text-sm" x-text="transaction.invoice_code"></p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400" x-text="formatTime(transaction.created_at)"></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-navy-900 dark:text-white">Rp <span x-text="formatNumber(transaction.grand_total)"></span></p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                          :class="transaction.payment_status === 'paid' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning'">
                                        <span x-text="capitalize(transaction.payment_status)"></span>
                                    </span>
                                </div>
                            </div>
                        </template>

                        <div x-show="transactions.data.length === 0" class="text-center py-12">
                            <i data-lucide="receipt" class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                            <p class="text-slate-500 dark:text-slate-400">Belum ada transaksi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let salesChart = null;
let paymentChart = null;
let chartDataCache = {};
let chartLoading = false;
let currentPeriod = 7;

document.addEventListener('alpine:init', () => {
    Alpine.store('dashboardState', {
        chartLoading: false,
        paymentMethods: []
    });
});

function setChartLoading(value) {
    chartLoading = value;
    if (window.Alpine?.store) {
        Alpine.store('dashboardState').chartLoading = value;
    }
}

function setPaymentMethods(methods) {
    if (window.Alpine?.store) {
        Alpine.store('dashboardState').paymentMethods = methods;
    }
}

// Payment methods data
let paymentMethods = [
    { name: 'Cash', color: '#22C55E', icon: 'banknote', percentage: 0, count: 0 },
    { name: 'QRIS', color: '#3B82F6', icon: 'qr-code', percentage: 0, count: 0 },
    { name: 'Debit', color: '#F59E0B', icon: 'credit-card', percentage: 0, count: 0 },
    { name: 'E-Wallet', color: '#8B5CF6', icon: 'wallet', percentage: 0, count: 0 }
];

// Blue gradient for sales chart (fixed color)
function createBlueGradient(ctx) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)'); // Blue
    gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.1)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.01)');
    return gradient;
}

async function updateChart(period) {
    currentPeriod = period;
    setChartLoading(true);
    document.getElementById('chartPeriodText').textContent = `${period} Hari Terakhir`;
    
    if (chartDataCache[period]) {
        renderSalesChart(chartDataCache[period]);
        setChartLoading(false);
        return;
    }
    
    try {
        const response = await fetch(`/admin/dashboard/chart-data?period=${period}`);
        const data = await response.json();
        
        chartDataCache[period] = data;
        renderSalesChart(data);
        setChartLoading(false);
    } catch (error) {
        console.error('Error loading chart data:', error);
        setChartLoading(false);
    }
}

function refreshChart() {
    chartDataCache = {};
    updateChart(currentPeriod);
    loadPaymentData();
}

function renderSalesChart(data) {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    if (salesChart) {
        salesChart.destroy();
    }
    
    const gradientFill = createBlueGradient(ctx);
    
    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Penjualan',
                data: data.data,
                borderColor: '#2563EB',
                backgroundColor: gradientFill,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#2563EB',
                pointBorderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#2563EB',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 14,
                    cornerRadius: 14,
                    displayColors: false,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 15, weight: 'bold' },
                    callbacks: {
                        title: (context) => context[0].label,
                        label: (context) => 'Rp ' + context.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#64748B',
                        padding: 10,
                        font: { size: 11, weight: '500' },
                        callback: (value) => 'Rp ' + (value / 1000).toFixed(0) + 'k'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#64748B',
                        padding: 10,
                        font: { size: 11, weight: '500' }
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart',
                y: {
                    type: 'number',
                    easing: 'easeInOutQuart',
                    from: 0,
                    delay: (ctx) => ctx.index * 50
                }
            }
        }
    });
}

function loadPaymentData() {
    fetch('/admin/dashboard/payment-data', { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(data => {
        renderPaymentChart(data);
    })
    .catch(() => {});
}

function renderPaymentChart(data) {
    const ctx = document.getElementById('paymentChart').getContext('2d');
    
    if (paymentChart) {
        paymentChart.destroy();
    }
    
    const methods = ['cash', 'qris', 'debit', 'ewallet'];
    const total = data.total || 1;
    
    paymentMethods = methods.map((key, index) => {
        const count = data[key] || 0;
        const percentage = total > 0 ? ((count / total) * 100).toFixed(0) : 0;
        return {
            name: key === 'cash' ? 'Cash' : (key === 'qris' ? 'QRIS' : (key === 'debit' ? 'Debit' : 'E-Wallet')),
            color: ['#22C55E', '#3B82F6', '#F59E0B', '#8B5CF6'][index],
            icon: ['banknote', 'qr-code', 'credit-card', 'wallet'][index],
            percentage: percentage,
            count: count
        };
    }).filter(m => m.count > 0);
    
    setPaymentMethods(paymentMethods);
    document.getElementById('paymentTotal').textContent = total.toLocaleString('id-ID');
    
    paymentChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: paymentMethods.map(m => m.name),
            datasets: [{
                data: paymentMethods.map(m => m.count),
                backgroundColor: paymentMethods.map(m => m.color),
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: true,
                    callbacks: {
                        label: (context) => context.label + ': ' + context.parsed + ' (' + 
                            paymentMethods[context.dataIndex].percentage + '%)'
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
    
    setTimeout(() => lucide.createIcons(), 100);
}

// Clock Functionality
function updateClock() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dateStr = now.toLocaleDateString('id-ID', options);
    const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    
    const dateEl = document.getElementById('currentDate');
    const timeEl = document.getElementById('currentTime');
    
    if (dateEl) dateEl.textContent = dateStr;
    if (timeEl) timeEl.textContent = timeStr;
}

// Top Products Alpine Component
function topProductsData() {
    return {
        products: { data: [], current_page: 1, last_page: 1 },
        loading: false,
        
        init() {
            this.loadProducts(1);
            // Auto-refresh every 30 seconds
            setInterval(() => this.loadProducts(this.products.current_page), 30000);
        },
        
        async loadProducts(page = 1) {
            this.loading = true;
            try {
                const response = await fetch(`/admin/dashboard/top-products?page=${page}`);
                this.products = await response.json();
            } catch (error) {
                console.error('Error loading products:', error);
            } finally {
                this.loading = false;
                setTimeout(() => lucide.createIcons(), 100);
            }
        },
        
        prevPage() {
            if (this.products.current_page > 1) {
                this.loadProducts(this.products.current_page - 1);
            }
        },
        
        nextPage() {
            if (this.products.current_page < this.products.last_page) {
                this.loadProducts(this.products.current_page + 1);
            }
        },
        
        formatNumber(num) {
            return parseInt(num).toLocaleString('id-ID');
        }
    }
}

// Recent Transactions Alpine Component
function recentTransactionsData() {
    return {
        transactions: { data: [], current_page: 1, last_page: 1 },
        loading: false,
        
        init() {
            this.loadTransactions(1);
            // Auto-refresh every 30 seconds
            setInterval(() => this.loadTransactions(this.transactions.current_page), 30000);
        },
        
        async loadTransactions(page = 1) {
            this.loading = true;
            try {
                const response = await fetch(`/admin/dashboard/recent-transactions?page=${page}`);
                this.transactions = await response.json();
            } catch (error) {
                console.error('Error loading transactions:', error);
            } finally {
                this.loading = false;
                setTimeout(() => lucide.createIcons(), 100);
            }
        },
        
        prevPage() {
            if (this.transactions.current_page > 1) {
                this.loadTransactions(this.transactions.current_page - 1);
            }
        },
        
        nextPage() {
            if (this.transactions.current_page < this.transactions.last_page) {
                this.loadTransactions(this.transactions.current_page + 1);
            }
        },
        
        formatNumber(num) {
            return parseInt(num).toLocaleString('id-ID');
        },
        
        formatTime(dateStr) {
            const date = new Date(dateStr);
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(diff / 3600000);
            const days = Math.floor(diff / 86400000);
            
            if (minutes < 1) return 'Baru saja';
            if (minutes < 60) return `${minutes} menit yang lalu`;
            if (hours < 24) return `${hours} jam yang lalu`;
            return `${days} hari yang lalu`;
        },
        
        capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    }
}

// Initialize clock
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    updateClock();
    setInterval(updateClock, 1000);
    updateChart(7);
    loadPaymentData();
});
</script>
@endpush
@endsection