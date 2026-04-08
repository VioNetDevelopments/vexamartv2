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
        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent">
                            Laporan Penjualan
                        </h1>
                        <span
                            class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full">
                            {{ $dateFrom }} - {{ $dateTo }}
                        </span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">Analisis performa toko Anda</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.reports.export.excel', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-success text-white rounded-xl font-medium hover:bg-green-700 transition-colors shadow-lg shadow-success/30">
                        <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                        <span>Export Excel</span>
                    </a>
                    <a href="{{ route('admin.reports.export.pdf', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-danger text-white rounded-xl font-medium hover:bg-red-700 transition-colors shadow-lg shadow-danger/30">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span>Export PDF</span>
                    </a>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up"
                style="animation-delay: 0.1s;">
                <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Dari
                            Tanggal</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}"
                            class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sampai
                            Tanggal</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}"
                            class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Group By</label>
                        <select name="group_by"
                            class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                            <option value="daily" {{ $groupBy == 'daily' ? 'selected' : '' }}>Harian</option>
                            <option value="weekly" {{ $groupBy == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                            <option value="monthly" {{ $groupBy == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Metode
                            Bayar</label>
                        <select name="payment_method"
                            class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                            <option value="">Semua</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="debit" {{ request('payment_method') == 'debit' ? 'selected' : '' }}>Debit</option>
                            <option value="ewallet" {{ request('payment_method') == 'ewallet' ? 'selected' : '' }}>E-Wallet
                            </option>
                        </select>
                    </div>
                    <button type="submit"
                        class="rounded-xl bg-accent-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-accent-600 transition-colors">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.2s;">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Penjualan</p>
                    <h3 class="mt-2 text-2xl font-bold text-accent-600 dark:text-accent-400">Rp
                        {{ number_format($stats['total_sales'], 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.3s;">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Transaksi</p>
                    <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">
                        {{ number_format($stats['total_transactions']) }}</h3>
                </div>
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.4s;">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Item</p>
                    <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">
                        {{ number_format($stats['total_items']) }}</h3>
                </div>
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.5s;">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Rata-rata/Transaksi</p>
                    <h3 class="mt-2 text-2xl font-bold text-navy-900 dark:text-white">Rp
                        {{ number_format($stats['avg_transaction'], 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.6s;">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Estimasi Profit</p>
                    <h3 class="mt-2 text-2xl font-bold text-success">Rp
                        {{ number_format($stats['total_profit'], 0, ',', '.') }}</h3>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sales Chart -->
                <div class="lg:col-span-2 bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.7s;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-navy-900 dark:text-white">Grafik Penjualan</h3>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-navy-800 transition-colors">
                                <i data-lucide="more-vertical" class="w-5 h-5 text-slate-400"></i>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-navy-800 rounded-xl shadow-xl border border-slate-100 dark:border-white/10 py-2 z-50">
                                <a href="?date_from={{ now()->subDays(3)->format('Y-m-d') }}"
                                    class="block px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-navy-700">3 Hari
                                    Terakhir</a>
                                <a href="?date_from={{ now()->subDays(7)->format('Y-m-d') }}"
                                    class="block px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-navy-700">7 Hari
                                    Terakhir</a>
                                <a href="?date_from={{ now()->subDays(15)->format('Y-m-d') }}"
                                    class="block px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-navy-700">15 Hari
                                    Terakhir</a>
                                <a href="?date_from={{ now()->subDays(30)->format('Y-m-d') }}"
                                    class="block px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-navy-700">30 Hari
                                    Terakhir</a>
                            </div>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Payment Chart -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.8s;">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Metode Pembayaran</h3>
                    <div class="h-48">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Products & Category Performance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Products -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.9s;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-navy-900 dark:text-white">Produk Terlaris</h3>
                        <div class="flex items-center gap-2">
                            <button @click="prodPage > 0 ? prodPage-- : null" :disabled="prodPage === 0"
                                class="p-1 rounded-lg border border-slate-200 dark:border-white/10 disabled:opacity-30">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </button>
                            <button @click="(prodPage + 1) * perPage < products.length ? prodPage++ : null"
                                :disabled="(prodPage + 1) * perPage >= products.length"
                                class="p-1 rounded-lg border border-slate-200 dark:border-white/10 disabled:opacity-30">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(product, index) in paginatedProducts" :key="index">
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                                <div class="h-12 w-12 rounded-xl bg-slate-200 dark:bg-navy-700 overflow-hidden">
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
                                    <p class="font-medium text-navy-900 dark:text-white" x-text="product.name"></p>
                                    <p class="text-sm text-slate-500" x-text="product.total_sold + ' terjual'"></p>
                                </div>
                                <p class="font-bold text-accent-600"
                                    x-text="'Rp ' + Number(product.total_revenue).toLocaleString('id-ID')"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Category Performance -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 1.0s;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-navy-900 dark:text-white">Performa Kategori</h3>
                        <div class="flex items-center gap-2">
                            <button @click="catPage > 0 ? catPage-- : null" :disabled="catPage === 0"
                                class="p-1 rounded-lg border border-slate-200 dark:border-white/10 disabled:opacity-30">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </button>
                            <button @click="(catPage + 1) * perPage < categories.length ? catPage++ : null"
                                :disabled="(catPage + 1) * perPage >= categories.length"
                                class="p-1 rounded-lg border border-slate-200 dark:border-white/10 disabled:opacity-30">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <template x-for="(category, index) in paginatedCategories" :key="index">
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                                <div>
                                    <p class="font-medium text-navy-900 dark:text-white" x-text="category.name"></p>
                                    <p class="text-sm text-slate-500" x-text="category.total_sold + ' item'"></p>
                                </div>
                                <p class="font-bold text-accent-600"
                                    x-text="'Rp ' + Number(category.total_revenue).toLocaleString('id-ID')"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up"
                style="animation-delay: 1.1s;">
                <div class="p-6 border-b border-slate-100 dark:border-white/5">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white">Detail Transaksi</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-navy-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Invoice</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Kasir</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Metode</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                    <td class="px-6 py-4 font-mono text-sm text-accent-600">{{ $trx->invoice_code }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $trx->user->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $trx->customer->name ?? 'Umum' }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 dark:bg-navy-800">
                                            {{ ucfirst($trx->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="font-bold text-navy-900 dark:text-white">Rp
                                            {{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();

                // Sales Chart - Updated to match Dashboard style
                const salesCtx = document.getElementById('salesChart');
                if (salesCtx) {
                    new Chart(salesCtx, {
                        type: 'line',
                        data: {
                            labels: @json($chartData['labels']),
                            datasets: [{
                                label: 'Penjualan',
                                data: @json($chartData['sales']),
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

                // Payment Chart - Updated to match Dashboard style
                const paymentCtx = document.getElementById('paymentChart');
                if (paymentCtx) {
                    new Chart(paymentCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($paymentDistribution->pluck('method')),
                            datasets: [{
                                data: @json($paymentDistribution->pluck('total')),
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
            })
        </script>
    @endpush

    @push('styles')
        <style>
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
        </style>
    @endpush
@endsection