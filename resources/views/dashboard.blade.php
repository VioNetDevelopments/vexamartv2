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
                            class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full">
                            Live
                        </span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">
                        Selamat datang kembali, <span
                            class="font-semibold text-accent-600 dark:text-accent-400">{{ auth()->user()->name }}</span>! 👋
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-navy-900 dark:text-white">{{ now()->format('l, d F Y') }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ now()->format('H:i') }} WIB</p>
                    </div>
                    <button
                        class="group flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-medium shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-300 hover:-translate-y-0.5">
                        <i data-lucide="download" class="w-4 h-4 group-hover:rotate-12 transition-transform"></i>
                        <span>Export</span>
                    </button>
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
                            <i data-lucide="package" class="w-3 h-3"></i>
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

            <!-- Charts & Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sales Chart -->
                <div class="lg:col-span-2 bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up"
                    style="animation-delay: 0.5s;">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white">Grafik Penjualan</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">7 Hari Terakhir</p>
                        </div>
                        <select
                            class="px-4 py-2 bg-slate-50 dark:bg-navy-800 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-accent-500">
                            <option>7 Hari</option>
                            <option>30 Hari</option>
                            <option>Bulan Ini</option>
                        </select>
                    </div>

                    <div
                        class="h-72 bg-gradient-to-b from-slate-50 to-white dark:from-navy-800/50 dark:to-navy-800 rounded-xl flex items-center justify-center">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Payment Distribution -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up"
                    style="animation-delay: 0.6s;">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-navy-900 dark:text-white">Metode Pembayaran</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Distribusi transaksi</p>
                    </div>

                    <div class="h-48 flex items-center justify-center">
                        <canvas id="paymentChart"></canvas>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-accent-500"></div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Tunai</span>
                            </div>
                            <span class="text-sm font-bold text-navy-900 dark:text-white">65%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-success"></div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">QRIS</span>
                            </div>
                            <span class="text-sm font-bold text-navy-900 dark:text-white">25%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-warning"></div>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Lainnya</span>
                            </div>
                            <span class="text-sm font-bold text-navy-900 dark:text-white">10%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Top Products & Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Products -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up"
                    style="animation-delay: 0.7s;">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white">Produk Terlaris</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Top 5 produk minggu ini</p>
                        </div>
                        <a href="{{ route('admin.products.index') }}"
                            class="text-sm font-semibold text-accent-600 dark:text-accent-400 hover:text-accent-700 flex items-center gap-1 group">
                            Lihat Semua
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($topProducts as $index => $product)
                            <div
                                class="group flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-navy-800 hover:bg-accent-50 dark:hover:bg-navy-700 transition-all duration-300 hover:-translate-x-2">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500/20 to-accent-600/20 flex items-center justify-center">
                                    <span
                                        class="text-lg font-bold text-accent-600 dark:text-accent-400">#{{ $index + 1 }}</span>
                                </div>

                                <div class="w-14 h-14 rounded-xl bg-slate-200 dark:bg-navy-700 overflow-hidden flex-shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
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
                                    <p class="font-bold text-accent-600 dark:text-accent-400">Rp
                                        {{ number_format($product->total_revenue ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i data-lucide="shopping-bag"
                                    class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                                <p class="text-slate-500 dark:text-slate-400">Belum ada data penjualan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up"
                    style="animation-delay: 0.8s;">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white">Transaksi Terbaru</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">5 transaksi terakhir</p>
                        </div>
                        <a href="{{ route('admin.transactions.index') }}"
                            class="text-sm font-semibold text-accent-600 dark:text-accent-400 hover:text-accent-700 flex items-center gap-1 group">
                            Lihat Semua
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentTransactions as $transaction)
                            <div
                                class="group flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-navy-800 hover:bg-slate-100 dark:hover:bg-navy-700 transition-all duration-300">
                                <div
                                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500/20 to-accent-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i data-lucide="receipt" class="w-5 h-5 text-accent-600 dark:text-accent-400"></i>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-navy-900 dark:text-white font-mono text-sm">
                                        {{ $transaction->invoice_code }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $transaction->created_at->diffForHumans() }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="font-bold text-navy-900 dark:text-white">Rp
                                        {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
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

            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-accent-500 via-accent-600 to-accent-700 rounded-2xl p-6 shadow-xl shadow-accent-500/30 animate-fade-in-up"
                style="animation-delay: 0.9s;">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-center md:text-left">
                        <h3 class="text-xl font-bold text-white mb-2">🚀 Quick Actions</h3>
                        <p class="text-accent-100 text-sm">Akses cepat ke fitur utama</p>
                    </div>

                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('cashier.pos') }}"
                            class="group flex items-center gap-2 px-5 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-300 hover:-translate-y-1">
                            <i data-lucide="monitor-smartphone"
                                class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                            <span>Buka Kasir</span>
                        </a>
                        <a href="{{ route('admin.products.create') }}"
                            class="group flex items-center gap-2 px-5 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-300 hover:-translate-y-1">
                            <i data-lucide="plus" class="w-5 h-5 group-hover:rotate-90 transition-transform"></i>
                            <span>Tambah Produk</span>
                        </a>
                        <a href="{{ route('admin.stock.index') }}"
                            class="group flex items-center gap-2 px-5 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-300 hover:-translate-y-1">
                            <i data-lucide="package" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                            <span>Cek Stok</span>
                        </a>
                        <a href="{{ route('admin.reports.index') }}"
                            class="group flex items-center gap-2 px-5 py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl font-medium hover:bg-white/30 transition-all duration-300 hover:-translate-y-1">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                            <span>Laporan</span>
                        </a>
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

            // Add styles to head
            const styleSheet = document.createElement('style');
            styleSheet.textContent = animationStyles;
            document.head.appendChild(styleSheet);

            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();

                // Sales Chart
                const salesCtx = document.getElementById('salesChart');
                if (salesCtx) {
                    new Chart(salesCtx, {
                        type: 'line',
                        data: {
                            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                            datasets: [{
                                label: 'Penjualan',
                                data: [2500000, 3200000, 2800000, 4100000, 3800000, 5200000, 4500000],
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
                                        label: function (context) {
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
                                        callback: function (value) {
                                            return 'Rp ' + (value / 1000).toFixed(0) + 'k';
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
                            labels: ['Tunai', 'QRIS', 'Debit', 'E-Wallet'],
                            datasets: [{
                                data: [65, 25, 7, 3],
                                backgroundColor: ['#2563EB', '#16A34A', '#F59E0B', '#8B5CF6'],
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
                                        label: function (context) {
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