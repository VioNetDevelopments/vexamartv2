@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6"
    x-data="{ 
            products: {{ json_encode($topProducts) }},
            categories: {{ json_encode($categoryPerformance) }},
            prodPage: 0,
            catPage: 0,
            perPage: 5,
            get paginatedProducts() {
                return this.products.slice(this.prodPage * this.perPage, (this.prodPage + 1) * this.perPage);
            },
            get paginatedCategories() {
                return this.categories.slice(this.catPage * this.perPage, (this.catPage + 1) * this.perPage);
            }
         }">
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
                            <i data-lucide="bar-chart-3" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">
                                Laporan Penjualan
                            </h1>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Analisis performa toko Anda</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                        {{ $dateFrom }} - {{ $dateTo }}
                    </span>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.reports.export.excel', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
                    class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-success to-success/80 text-white rounded-xl font-bold shadow-lg shadow-success/30 hover:shadow-success/50 transition-all duration-300 hover:-translate-y-0.5">
                    <i data-lucide="file-spreadsheet" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                    <span>Export Excel</span>
                </a>
                <a href="{{ route('admin.reports.export.pdf', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
                    class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-danger to-danger/80 text-white rounded-xl font-bold shadow-lg shadow-danger/30 hover:shadow-danger/50 transition-all duration-300 hover:-translate-y-0.5">
                    <i data-lucide="file-text" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                    <span>Export PDF</span>
                </a>
            </div>
        </div>

        <!-- Filter Card - SAME DESIGN AS STOCK PAGE -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up overflow-visible relative z-20" style="animation-delay: 0.1s;">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Dari Tanggal</label>
                    <div class="relative group">
                        <i data-lucide="calendar" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Sampai Tanggal</label>
                    <div class="relative group">
                        <i data-lucide="calendar" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Group By</label>
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[140px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span>{{ $groupBy == 'daily' ? 'Harian' : ($groupBy == 'weekly' ? 'Mingguan' : 'Bulanan') }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                             style="top: 100%;">
                            <a href="?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by=daily&payment_method={{ request('payment_method') }}"
                               class="block w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ $groupBy == 'daily' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Harian
                            </a>
                            <a href="?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by=weekly&payment_method={{ request('payment_method') }}"
                               class="block w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ $groupBy == 'weekly' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Mingguan
                            </a>
                            <a href="?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by=monthly&payment_method={{ request('payment_method') }}"
                               class="block w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ $groupBy == 'monthly' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Bulanan
                            </a>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Metode Bayar</label>
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[160px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span>{{ request('payment_method') ? ucfirst(request('payment_method')) : 'Semua' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                             style="top: 100%;">
                            <a href="?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by={{ $groupBy }}"
                               class="block w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ !request('payment_method') ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Semua
                            </a>
                            <a href="?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by={{ $groupBy }}&payment_method=cash"
                               class="block w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('payment_method') == 'cash' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Tunai
                            </a>
                            <a href="?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by={{ $groupBy }}&payment_method=qris"
                               class="block w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('payment_method') == 'qris' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                QRIS
                            </a>
                            <a href="?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by={{ $groupBy }}&payment_method=debit"
                               class="block w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('payment_method') == 'debit' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Debit
                            </a>
                            <a href="?date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by={{ $groupBy }}&payment_method=ewallet"
                               class="block w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('payment_method') == 'ewallet' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                E-Wallet
                            </a>
                        </div>
                    </div>
                </div>
                <button type="submit"
                    class="bg-accent-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-accent-600 shadow-lg shadow-accent-500/30 transition-all active:scale-95">
                    <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                </button>
            </form>
        </div>

        <!-- Stats Cards - SAME DESIGN AS DASHBOARD -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Total Penjualan -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/30">
                            <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Penjualan</p>
                    <h3 class="text-2xl font-bold text-accent-600 dark:text-accent-400">Rp {{ number_format($stats['total_sales'], 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Transaksi</p>
                    <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ number_format($stats['total_transactions']) }}</h3>
                </div>
            </div>

            <!-- Total Item -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i data-lucide="package" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Item</p>
                    <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ number_format($stats['total_items']) }}</h3>
                </div>
            </div>

            <!-- Rata-rata/Transaksi -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-warning/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning to-warning/80 flex items-center justify-center shadow-lg shadow-warning/30">
                            <i data-lucide="calculator" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Rata-rata/Transaksi</p>
                    <h3 class="text-2xl font-bold text-navy-900 dark:text-white">Rp {{ number_format($stats['avg_transaction'], 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Estimasi Profit -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.6s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-success/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center shadow-lg shadow-success/30">
                            <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Estimasi Profit</p>
                    <h3 class="text-2xl font-bold text-success">Rp {{ number_format($stats['total_profit'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Charts - SAME AS DASHBOARD (50-50) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sales Chart -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5 animate-fade-in-up" style="animation-delay: 0.7s;" x-data="{ open: false, period: 7 }">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
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
                                :class="open ? 'bg-accent-500 text-white' : 'text-slate-400'">
                            <i data-lucide="more-vertical" class="w-5 h-5 transition-transform duration-300" :class="open ? 'rotate-90' : ''"></i>
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
                    <div x-show="chartLoading"
                         class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-navy-900/50 backdrop-blur-sm rounded-xl">
                        <div class="flex flex-col items-center gap-3">
                            <div class="animate-spin rounded-full h-10 w-10 border-4 border-accent-500 border-t-transparent"></div>
                            <p class="text-sm text-slate-500">Memuat data...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method Chart -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-slate-100 dark:border-white/5 animate-fade-in-up" style="animation-delay: 0.8s;">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
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
                                <p class="text-lg font-black text-navy-900 dark:text-white" id="paymentTotal">0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods List -->
                    <div class="flex-1 space-y-3" id="paymentMethodsList">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products & Category Performance -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Products -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.9s;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-500/10">
                            <i data-lucide="trophy" class="w-5 h-5 text-yellow-500"></i>
                        </div>
                        <span>Produk Terlaris</span>
                    </h3>
                    <div class="flex items-center gap-2">
                        <button @click="prodPage > 0 ? prodPage-- : null" :disabled="prodPage === 0"
                            class="p-2 rounded-xl border border-slate-200 dark:border-white/10 disabled:opacity-30 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </button>
                        <button @click="(prodPage + 1) * perPage < products.length ? prodPage++ : null"
                            :disabled="(prodPage + 1) * perPage >= products.length"
                            class="p-2 rounded-xl border border-slate-200 dark:border-white/10 disabled:opacity-30 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                <div class="space-y-4">
                    <template x-for="(product, index) in paginatedProducts" :key="index">
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-navy-800 hover:bg-accent-50 dark:hover:bg-navy-700 transition-all duration-300 hover:-translate-x-2">
                            <div class="h-12 w-12 rounded-xl bg-slate-200 dark:bg-navy-700 overflow-hidden flex-shrink-0">
                                <template x-if="product.image">
                                    <img :src="'/storage/' + product.image" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!product.image">
                                    <div class="h-full w-full flex items-center justify-center">
                                        <i data-lucide="package" class="w-6 h-6 text-slate-400"></i>
                                    </div>
                                </template>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-navy-900 dark:text-white" x-text="product.name"></p>
                                <p class="text-sm text-slate-500" x-text="product.total_sold + ' terjual'"></p>
                            </div>
                            <p class="font-bold text-accent-600"
                                x-text="'Rp ' + Number(product.total_revenue).toLocaleString('id-ID')"></p>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Category Performance -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 1.0s;">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-green-500/10">
                            <i data-lucide="chart-pie" class="w-5 h-5 text-green-500"></i>
                        </div>
                        <span>Performa Kategori</span>
                    </h3>
                    <div class="flex items-center gap-2">
                        <button @click="catPage > 0 ? catPage-- : null" :disabled="catPage === 0"
                            class="p-2 rounded-xl border border-slate-200 dark:border-white/10 disabled:opacity-30 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </button>
                        <button @click="(catPage + 1) * perPage < categories.length ? catPage++ : null"
                            :disabled="(catPage + 1) * perPage >= categories.length"
                            class="p-2 rounded-xl border border-slate-200 dark:border-white/10 disabled:opacity-30 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                <div class="space-y-4">
                    <template x-for="(category, index) in paginatedCategories" :key="index">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-navy-800 hover:bg-accent-50 dark:hover:bg-navy-700 transition-all duration-300">
                            <div>
                                <p class="font-semibold text-navy-900 dark:text-white" x-text="category.name"></p>
                                <p class="text-sm text-slate-500" x-text="category.total_sold + ' item'"></p>
                            </div>
                            <p class="font-bold text-accent-600"
                                x-text="'Rp ' + Number(category.total_revenue).toLocaleString('id-ID')"></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Transactions Table - SAME DESIGN AS STOCK PAGE -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 1.1s;">
            <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-gradient-to-r from-slate-50 to-white dark:from-navy-800 dark:to-navy-900">
                <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/10">
                        <i data-lucide="receipt" class="w-5 h-5 text-blue-500"></i>
                    </div>
                    <span>Detail Transaksi</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-navy-800/50">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Invoice</th>
                            <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Tanggal</th>
                            <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Kasir</th>
                            <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Customer</th>
                            <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Metode</th>
                            <th class="px-6 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($transactions as $trx)
                            <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-accent-500 group-hover:scale-150 transition-transform"></div>
                                        <span class="font-mono text-sm font-black text-navy-900 dark:text-white leading-none">#{{ $trx->invoice_code }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-navy-900 dark:text-white">{{ $trx->created_at->format('d M Y') }}</span>
                                        <span class="text-[10px] font-medium text-slate-500">{{ $trx->created_at->format('H:i') }} WIB</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center text-slate-400">
                                            <i data-lucide="user" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ optional($trx->user)->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center text-slate-400">
                                            <i data-lucide="user" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $trx->customer->name ?? 'Umum' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    @php
                                        $paymentClasses = match ($trx->payment_method) {
                                            'cash' => 'bg-success/10 text-success border border-success/20',
                                            'qris' => 'bg-blue-50/50 text-blue-600 border border-blue-200',
                                            'debit' => 'bg-purple-100 dark:bg-purple-900/20 text-purple-600 border border-purple-200',
                                            default => 'bg-warning/10 text-warning border border-warning/20',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $paymentClasses }}">
                                        @if($trx->payment_method === 'cash')
                                            <i data-lucide="banknote" class="w-3 h-3"></i>
                                        @elseif($trx->payment_method === 'qris')
                                            <i data-lucide="qr-code" class="w-3 h-3"></i>
                                        @elseif($trx->payment_method === 'debit')
                                            <i data-lucide="credit-card" class="w-3 h-3"></i>
                                        @else
                                            <i data-lucide="wallet" class="w-3 h-3"></i>
                                        @endif
                                        {{ ucfirst($trx->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right whitespace-nowrap">
                                    <span class="text-base font-black text-navy-900 dark:text-white">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-navy-800 flex items-center justify-center text-slate-200 dark:text-navy-700 mb-4 animate-bounce">
                                            <i data-lucide="receipt" class="w-10 h-10"></i>
                                        </div>
                                        <h4 class="text-lg font-bold text-navy-900 dark:text-white mb-1">Belum Ada Transaksi</h4>
                                        <p class="text-sm text-slate-500">Tidak ada transaksi dalam periode ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination - LIKE STOCK PAGE -->
            @if($transactions->hasPages())
            <div class="px-6 py-5 border-t border-slate-100 dark:border-white/5 bg-slate-50/30 dark:bg-navy-900/30">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-slate-600 dark:text-slate-400">
                        Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->total() }}</span> transaksi
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if($transactions->onFirstPage())
                            <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                                <span class="flex items-center gap-1.5">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                    Previous
                                </span>
                            </button>
                        @else
                            <a href="{{ $transactions->previousPageUrl() }}&date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by={{ $groupBy }}&payment_method={{ request('payment_method') }}"
                               class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                                <span class="flex items-center gap-1.5">
                                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                    Previous
                                </span>
                            </a>
                        @endif
                        
                        @if($transactions->hasMorePages())
                            <a href="{{ $transactions->nextPageUrl() }}&date_from={{ $dateFrom }}&date_to={{ $dateTo }}&group_by={{ $groupBy }}&payment_method={{ request('payment_method') }}"
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let salesChart = null;
let paymentChart = null;
let chartDataCache = {};
let chartLoading = false;
let currentPeriod = 7;

function setChartLoading(value) {
    chartLoading = value;
}

// Blue gradient for sales chart
function createBlueGradient(ctx) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
    gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.1)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.01)');
    return gradient;
}

async function updateChart(period) {
    currentPeriod = period;
    setChartLoading(true);
    
    const periodTexts = {
        3: '3 Hari Terakhir',
        7: '7 Hari Terakhir',
        14: '14 Hari Terakhir',
        30: '30 Hari Terakhir'
    };
    
    document.getElementById('chartPeriodText').textContent = periodTexts[period] || '7 Hari Terakhir';
    
    // Check cache first
    if (chartDataCache[period]) {
        renderSalesChart(chartDataCache[period]);
        setChartLoading(false);
        return;
    }
    
    try {
        // Fetch from dashboard API (same as dashboard)
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
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;
    
    if (salesChart) {
        salesChart.destroy();
    }
    
    const gradientFill = createBlueGradient(ctx.getContext('2d'));
    
    salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Penjualan',
                data: data.data,
                backgroundColor: '#3B82F6',
                hoverBackgroundColor: '#2563EB',
                borderRadius: 8,
                borderSkipped: false,
                barThickness: 'flex',
                maxBarThickness: 40
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
    .catch(error => {
        console.error('Error loading payment data:', error);
    });
}

function renderPaymentChart(data) {
    const ctx = document.getElementById('paymentChart');
    if (!ctx) return;
    
    if (paymentChart) {
        paymentChart.destroy();
    }
    
    const methods = ['cash', 'qris', 'debit', 'ewallet'];
    const methodNames = {
        'cash': 'Cash',
        'qris': 'QRIS',
        'debit': 'Debit',
        'ewallet': 'E-Wallet'
    };
    const methodColors = {
        'cash': '#22C55E',
        'qris': '#3B82F6',
        'debit': '#F59E0B',
        'ewallet': '#8B5CF6'
    };
    const methodIcons = {
        'cash': 'banknote',
        'qris': 'qr-code',
        'debit': 'credit-card',
        'ewallet': 'wallet'
    };
    
    const total = data.total || 1;
    
    // Prepare data for chart
    const chartData = methods.map(key => data[key] || 0);
    const chartLabels = methods.map(key => methodNames[key]);
    const colors = methods.map(key => methodColors[key]);
    
    // Update total
    document.getElementById('paymentTotal').textContent = total.toLocaleString('id-ID');
    
    // Render doughnut chart
    paymentChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartLabels,
            datasets: [{
                data: chartData,
                backgroundColor: colors,
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
                    callbacks: {
                        label: (context) => context.label + ': ' + context.parsed
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
    
    // Update payment methods list
    const listContainer = document.getElementById('paymentMethodsList');
    if (listContainer) {
        listContainer.innerHTML = '';
        
        methods.forEach(key => {
            const count = data[key] || 0;
            if (count > 0) {
                const percentage = Math.round((count / total) * 100);
                const color = methodColors[key];
                const icon = methodIcons[key];
                const name = methodNames[key];
                
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors cursor-pointer';
                item.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full" style="background-color: ${color}"></div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="${icon}" class="w-4 h-4" style="color: ${color}"></i>
                            <span class="text-sm font-medium text-navy-900 dark:text-white">${name}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-navy-900 dark:text-white">${percentage}%</p>
                        <p class="text-xs text-slate-500">${count} trx</p>
                    </div>
                `;
                listContainer.appendChild(item);
            }
        });
        
        if (listContainer.children.length === 0) {
            listContainer.innerHTML = `
                <div class="text-center py-8">
                    <i data-lucide="credit-card" class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-2"></i>
                    <p class="text-sm text-slate-500">Belum ada data pembayaran</p>
                </div>
            `;
        }
        
        // Re-initialize Lucide icons
        setTimeout(() => lucide.createIcons(), 100);
    }
}

// Initialize charts on page load
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Load initial data
    updateChart(7);
    loadPaymentData();
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
</style>
@endpush
@endsection