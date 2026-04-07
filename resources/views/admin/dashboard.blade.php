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
                            class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent">
                            Dashboard
                        </h1>
                        <span
                            class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-accent-50 dark:bg-accent-900/20 border border-accent-200 dark:border-accent-800">
                            <span class="h-2 w-2 rounded-full bg-accent-500 animate-pulse"></span>
                            <span class="text-xs font-semibold text-accent-600 dark:text-accent-400">Live</span>
                        </span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Selamat datang kembali, <span class="font-semibold text-accent-600 dark:text-accent-400">{{ auth()->user()->name }}</span>! 👋
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-3 bg-white/50 dark:bg-navy-800/50 backdrop-blur-md px-5 py-2.5 rounded-2xl border border-slate-200/60 dark:border-white/5 shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 shadow-lg shadow-accent-500/20 group cursor-default">
                            <i data-lucide="clock" class="h-6 w-6 text-white group-hover:rotate-12 transition-transform duration-300"></i>
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="h-1.5 w-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                                <p class="text-[10px] font-black uppercase tracking-widest text-accent-600 dark:text-accent-400 leading-none">Live Monitor</p>
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
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl hover:shadow-accent-500/10 transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/30">
                                <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                            </div>
                            <span class="flex items-center gap-1 text-xs font-semibold text-success bg-success/10 px-2 py-1 rounded-full">
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
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                            </div>
                            <span class="flex items-center gap-1 text-xs font-semibold text-success bg-success/10 px-2 py-1 rounded-full">
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
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl hover:shadow-warning/10 transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-warning/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning to-warning/80 flex items-center justify-center shadow-lg shadow-warning/30">
                                <i data-lucide="alert-circle" class="w-6 h-6 text-white"></i>
                            </div>
                            @if(($stats['low_stock_products'] ?? 0) > 0)
                                <span class="flex items-center gap-1 text-xs font-semibold text-warning bg-warning/10 px-2 py-1 rounded-full animate-pulse">
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
                            <i data-lucide="package" class="w-3 h-3"></i>
                            <span>Perlu restock</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Products -->
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl hover:shadow-success/10 transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-success/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center shadow-lg shadow-success/30">
                                <i data-lucide="package" class="w-6 h-6 text-white"></i>
                            </div>
                            <span class="flex items-center gap-1 text-xs font-semibold text-success bg-success/10 px-2 py-1 rounded-full">
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

            <!-- Charts & Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sales Chart dengan Modern Dropdown -->
                <div class="lg:col-span-2 bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.5s;">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white">Grafik Penjualan</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400" id="chartPeriodText">7 Hari Terakhir</p>
                        </div>

                        <!-- Modern Dropdown dengan Icon Titik 3 -->
                        <div class="relative" x-data="{ open: false, selected: '7' }">
                            <button @click="open = !open" 
                                    class="flex items-center gap-2 p-2 hover:bg-slate-100 dark:hover:bg-navy-800 rounded-xl transition-colors">
                                <i data-lucide="more-vertical" class="w-5 h-5 text-slate-500"></i>
                            </button>

                            <div x-show="open" 
                                 @click.outside="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-navy-800 rounded-xl shadow-2xl border border-slate-100 dark:border-white/10 py-2 z-50"
                                 style="display: none;">

                                <div class="px-4 py-2 border-b border-slate-100 dark:border-white/10 mb-2">
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Pilih Periode</p>
                                </div>

                                <button @click="selected = '3'; updateChart('3'); open = false" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-accent-50 dark:hover:bg-navy-700 hover:text-accent-600 transition-colors"
                                        :class="{'bg-accent-50 dark:bg-navy-700 text-accent-600': selected === '3'}">
                                    <i data-lucide="calendar-days" class="w-4 h-4"></i>
                                    <span>3 Hari</span>
                                    <i data-lucide="check" class="w-4 h-4 ml-auto" x-show="selected === '3'"></i>
                                </button>

                                <button @click="selected = '7'; updateChart('7'); open = false" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-accent-50 dark:hover:bg-navy-700 hover:text-accent-600 transition-colors"
                                        :class="{'bg-accent-50 dark:bg-navy-700 text-accent-600': selected === '7'}">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                    <span>7 Hari</span>
                                    <i data-lucide="check" class="w-4 h-4 ml-auto" x-show="selected === '7'"></i>
                                </button>

                                <button @click="selected = '14'; updateChart('14'); open = false" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-accent-50 dark:hover:bg-navy-700 hover:text-accent-600 transition-colors"
                                        :class="{'bg-accent-50 dark:bg-navy-700 text-accent-600': selected === '14'}">
                                    <i data-lucide="calendar-range" class="w-4 h-4"></i>
                                    <span>14 Hari</span>
                                    <i data-lucide="check" class="w-4 h-4 ml-auto" x-show="selected === '14'"></i>
                                </button>

                                <button @click="selected = '30'; updateChart('30'); open = false" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-accent-50 dark:hover:bg-navy-700 hover:text-accent-600 transition-colors"
                                        :class="{'bg-accent-50 dark:bg-navy-700 text-accent-600': selected === '30'}">
                                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                                    <span>30 Hari</span>
                                    <i data-lucide="check" class="w-4 h-4 ml-auto" x-show="selected === '30'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="h-72 bg-gradient-to-b from-slate-50 to-white dark:from-navy-800/50 dark:to-navy-800 rounded-xl flex items-center justify-center">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Payment Distribution -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.6s;">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-navy-900 dark:text-white">Metode Pembayaran</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Distribusi transaksi</p>
                    </div>
                    <div class="h-48 flex items-center justify-center">
                        <canvas id="paymentChart"></canvas>
                    </div>
                    <div class="mt-6 space-y-3">
                        @php
                            $colors = ['bg-accent-500', 'bg-success', 'bg-warning', 'bg-purple-500', 'bg-pink-500', 'bg-cyan-500'];
                        @endphp
                        @forelse($paymentData['labels'] as $index => $label)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-navy-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full {{ $colors[$index % count($colors)] }}"></div>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $label }}</span>
                                </div>
                                <span class="text-sm font-bold text-navy-900 dark:text-white">{{ $paymentData['percentages'][$index] }}%</span>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-xs text-slate-400">Belum ada data pembayaran</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Top Products & Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Products dengan Pagination -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.7s;">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white">Produk Terlaris</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Top 5 produk minggu ini</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="prevProductPage()" class="p-2 hover:bg-slate-100 dark:hover:bg-navy-800 rounded-lg transition-colors">
                                <i data-lucide="chevron-left" class="w-4 h-4 text-slate-500"></i>
                            </button>
                            <button onclick="nextProductPage()" class="p-2 hover:bg-slate-100 dark:hover:bg-navy-800 rounded-lg transition-colors">
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-500"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-4" id="productList">
                        @forelse($topProducts as $index => $product)
                            <div class="group flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-navy-800 hover:bg-accent-50 dark:hover:bg-navy-700 transition-all duration-300 hover:-translate-x-2 product-item">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500/20 to-accent-600/20 flex items-center justify-center">
                                    <span class="text-lg font-bold text-accent-600 dark:text-accent-400">#{{ $index + 1 }}</span>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-slate-200 dark:bg-navy-700 overflow-hidden flex-shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="package" class="w-6 h-6 text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-navy-900 dark:text-white truncate">{{ $product->name }}</h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $product->total_sold }} terjual</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-accent-600 dark:text-accent-400">Rp {{ number_format($product->total_revenue ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i data-lucide="shopping-bag" class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                                <p class="text-slate-500 dark:text-slate-400">Belum ada data penjualan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Transactions dengan Pagination -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.8s;">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white">Transaksi Terbaru</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">5 transaksi terakhir</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="prevTransactionPage()" class="p-2 hover:bg-slate-100 dark:hover:bg-navy-800 rounded-lg transition-colors">
                                <i data-lucide="chevron-left" class="w-4 h-4 text-slate-500"></i>
                            </button>
                            <button onclick="nextTransactionPage()" class="p-2 hover:bg-slate-100 dark:hover:bg-navy-800 rounded-lg transition-colors">
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-500"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-3" id="transactionList">
                        @forelse($recentTransactions as $transaction)
                            <div class="group flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-navy-800 hover:bg-slate-100 dark:hover:bg-navy-700 transition-all duration-300 transaction-item">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500/20 to-accent-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i data-lucide="receipt" class="w-5 h-5 text-accent-600 dark:text-accent-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-navy-900 dark:text-white font-mono text-sm">{{ $transaction->invoice_code }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $transaction->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-navy-900 dark:text-white">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                                        {{ ucfirst($transaction->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i data-lucide="receipt" class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                                <p class="text-slate-500 dark:text-slate-400">Belum ada transaksi</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
        // Animation classes
        const animationStyles = `
            @keyframes fade-in-up {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fade-in-down {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fade-in-up 0.6s ease-out forwards;
                opacity: 0;
            }

            .animate-fade-in-down {
                animation: fade-in-down 0.6s ease-out forwards;
            }
        `;

        const styleSheet = document.createElement('style');
        styleSheet.textContent = animationStyles;
        document.head.appendChild(styleSheet);

        // Data dari database
        const chartData = @json($salesData ?? []);
        const paymentStats = @json($paymentData ?? ['labels' => [], 'percentages' => []]);
        const allProducts = @json($topProducts ?? []);
        const allTransactions = @json($recentTransactions ?? []);
        let salesChart;
        let currentProductPage = 0;
        let currentTransactionPage = 0;
        const itemsPerPage = 5;

        // Real-time Clock dengan Bahasa Indonesia
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const timeStr = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });

            document.getElementById('currentDate').textContent = dateStr;
            document.getElementById('currentTime').textContent = timeStr + ' WIB';
        }

        // Update waktu setiap detik
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Pagination Functions
        function showProductPage(page) {
            const items = document.querySelectorAll('.product-item');
            const start = page * itemsPerPage;
            const end = start + itemsPerPage;

            items.forEach((item, index) => {
                if (index >= start && index < end) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function showTransactionPage(page) {
            const items = document.querySelectorAll('.transaction-item');
            const start = page * itemsPerPage;
            const end = start + itemsPerPage;

            items.forEach((item, index) => {
                if (index >= start && index < end) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function prevProductPage() {
            if (currentProductPage > 0) {
                currentProductPage--;
                showProductPage(currentProductPage);
            }
        }

        function nextProductPage() {
            const totalPages = Math.ceil(allProducts.length / itemsPerPage);
            if (currentProductPage < totalPages - 1) {
                currentProductPage++;
                showProductPage(currentProductPage);
            }
        }

        function prevTransactionPage() {
            if (currentTransactionPage > 0) {
                currentTransactionPage--;
                showTransactionPage(currentTransactionPage);
            }
        }

        function nextTransactionPage() {
            const totalPages = Math.ceil(allTransactions.length / itemsPerPage);
            if (currentTransactionPage < totalPages - 1) {
                currentTransactionPage++;
                showTransactionPage(currentTransactionPage);
            }
        }

        // Initialize pagination
        showProductPage(0);
        showTransactionPage(0);

        function updateChart(period) {
            const periodTexts = {
                '3': '3 Hari Terakhir',
                '7': '7 Hari Terakhir',
                '14': '14 Hari Terakhir',
                '30': '30 Hari Terakhir'
            };

            document.getElementById('chartPeriodText').textContent = periodTexts[period];

            // Fetch data dari server berdasarkan period
            fetch(`/admin/dashboard/chart-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (salesChart) {
                        salesChart.data.labels = data.labels;
                        salesChart.data.datasets[0].data = data.data;
                        salesChart.update('active');
                    }
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // Sales Chart
            const salesCtx = document.getElementById('salesChart');
            if (salesCtx) {
                salesChart = new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels || ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                        datasets: [{
                            label: 'Penjualan',
                            data: chartData.data || [2500000, 3200000, 2800000, 4100000, 3800000, 5200000, 4500000],
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#2563EB',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: '#2563EB',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                cornerRadius: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                                ticks: {
                                    color: '#64748B',
                                    callback: function(value) {
                                        return 'Rp ' + (value/1000).toFixed(0) + 'k';
                                    }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: '#64748B' }
                            }
                        }
                    }
                });
            }

            // Payment Chart
            const paymentCtx = document.getElementById('paymentChart');
            if (paymentCtx) {
                new Chart(paymentCtx, {
                    type: 'doughnut',
                    data: {
                        labels: paymentStats.labels,
                        datasets: [{
                            data: paymentStats.percentages,
                            backgroundColor: ['#2563EB', '#16A34A', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4'],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                cornerRadius: 12,
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.parsed + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
        </script>
    @endpush
@endsection