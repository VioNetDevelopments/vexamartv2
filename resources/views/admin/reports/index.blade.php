@extends('layouts.app')

@section('content')

<style>
canvas{
    max-height:250px;
}
</style>

<div class="space-y-6"
x-data="{
dateFrom:'{{ $dateFrom }}',
dateTo:'{{ $dateTo }}',
groupBy:'{{ $groupBy }}'
}">

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 animate-fade-in-down">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                <i data-lucide="bar-chart-big" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">Analisis Laporan</h1>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pantau performa bisnis Anda secara real-time</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.export.excel',['date_from'=>$dateFrom,'date_to'=>$dateTo]) }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-xl bg-success text-white text-sm font-bold shadow-lg shadow-success/20 hover:bg-success/90 hover:-translate-y-0.5 transition-all active:scale-95">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 transition-transform group-hover:scale-110"></i>
                <span>Export Excel</span>
            </a>
            <a href="{{ route('admin.reports.export.pdf',['date_from'=>$dateFrom,'date_to'=>$dateTo]) }}"
               class="group flex items-center gap-2 px-5 py-2.5 rounded-xl bg-danger text-white text-sm font-bold shadow-lg shadow-danger/20 hover:bg-danger/90 hover:-translate-y-0.5 transition-all active:scale-95">
                <i data-lucide="file-text" class="w-4 h-4 transition-transform group-hover:scale-110"></i>
                <span>Export PDF</span>
            </a>
        </div>
    </div>


    <!-- Filter Section -->
    <div class="relative z-40 bg-white/80 dark:bg-navy-900/80 backdrop-blur-xl border border-white/20 dark:border-white/5 rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.1s;">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
            <!-- Date From -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Dari Tanggal</label>
                <div class="relative group">
                    <i data-lucide="calendar" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" x-model="dateFrom"
                           class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner">
                </div>
            </div>

            <!-- Date To -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
                <div class="relative group">
                    <i data-lucide="calendar" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                    <input type="date" name="date_to" value="{{ $dateTo }}" x-model="dateTo"
                           class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner">
                </div>
            </div>

            <!-- Group By -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Grup Data</label>
                <select name="group_by" x-model="groupBy"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    <option value="daily">Harian</option>
                    <option value="weekly">Mingguan</option>
                    <option value="monthly">Bulanan</option>
                </select>
            </div>

            <!-- Payment Method -->
            <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Metode Bayar</label>
                <select name="payment_method"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    <option value="">Semua Metode</option>
                    <option value="cash" {{ request('payment_method')=='cash'?'selected':'' }}>Tunai</option>
                    <option value="qris" {{ request('payment_method')=='qris'?'selected':'' }}>QRIS</option>
                    <option value="debit" {{ request('payment_method')=='debit'?'selected':'' }}>Debit</option>
                    <option value="ewallet" {{ request('payment_method')=='ewallet'?'selected':'' }}>E-Wallet</option>
                </select>
            </div>

            <!-- Action -->
            <button type="submit" class="w-full bg-accent-500 text-white rounded-2xl py-3.5 text-xs font-black uppercase tracking-widest hover:bg-accent-600 shadow-xl shadow-accent-500/30 transition-all active:scale-95">
                <i data-lucide="filter" class="inline w-3 h-3 mr-2"></i> Terapkan Filter
            </button>
        </form>
    </div>


    <!-- Analysis Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up" style="animation-delay: 0.2s;">
        <!-- Total Sales -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-accent-500/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-accent-50 dark:bg-accent-500/10 flex items-center justify-center text-accent-500">
                    <i data-lucide="banknote" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Penjualan</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">Rp{{ number_format($stats['total_sales'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-success/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-success/5 flex items-center justify-center text-success">
                    <i data-lucide="receipt" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Transaksi</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">{{ number_format($stats['total_transactions']) }}</h3>
                </div>
            </div>
        </div>

        <!-- Average Transaction -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-500/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-500">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rerata Transaksi</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">Rp{{ number_format($stats['avg_transaction'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Profit Estimate -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-500/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Estimasi Profit</p>
                    <h3 class="text-2xl font-black text-emerald-600 tracking-tight">Rp{{ number_format($stats['total_profit'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>


    <!-- Visualization Charts -->
    <div class="grid gap-6 lg:grid-cols-3 animate-fade-in-up" style="animation-delay: 0.3s;">
        <!-- Sales Performance Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-navy-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Tren Performa</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Penjualan vs Volume Transaksi</p>
                </div>
                <div class="p-2 rounded-xl bg-slate-50 dark:bg-navy-800 text-slate-400 hover:text-accent-500 transition-colors">
                    <i data-lucide="maximize-2" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Payment Method Distribution -->
        <div class="bg-white dark:bg-navy-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5">
            <div class="mb-8">
                <h3 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Distribusi Bayar</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Preferensi Pelanggan</p>
            </div>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="paymentChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-2xl font-black text-navy-900 dark:text-white">{{ number_format($stats['total_transactions']) }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Total Trx</span>
                </div>
            </div>
        </div>
    </div>



    <!-- Analytics Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 0.4s;">
        <!-- Top Products List -->
        <div class="lg:col-span-1 bg-white dark:bg-navy-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 overflow-hidden">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Produk Terlaris</h3>
                <div class="w-10 h-10 rounded-xl bg-warning/10 text-warning flex items-center justify-center">
                    <i data-lucide="flame" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="space-y-6">
                @foreach($topProducts as $index => $product)
                <div class="group flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                    <div class="relative">
                        <div class="h-14 w-14 rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden shadow-inner group-hover:scale-105 transition-transform">
                            @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">
                            @else
                            <div class="flex items-center justify-center h-full text-slate-300">
                                <i data-lucide="package" class="w-6 h-6"></i>
                            </div>
                            @endif
                        </div>
                        <div class="absolute -top-2 -left-2 w-6 h-6 rounded-lg bg-navy-900 text-white flex items-center justify-center text-[10px] font-black italic shadow-lg border border-white/20">
                            #{{ $index + 1 }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-black text-navy-900 dark:text-white truncate">{{ $product->name }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">{{ $product->total_sold }} pcs terjual</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-black text-accent-500">Rp{{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                        <div class="w-12 h-1 bg-slate-100 dark:bg-navy-800 rounded-full mt-2 overflow-hidden">
                            <div class="h-full bg-accent-500" style="width: {{ min(100, ($product->total_sold / ($topProducts->first()->total_sold ?: 1)) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Detailed Transactions List -->
        <div class="lg:col-span-2 bg-white dark:bg-navy-900 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 overflow-hidden flex flex-col">
            <div class="p-8 pb-4 flex items-center justify-between">
                <h3 class="text-xl font-black text-navy-900 dark:text-white tracking-tight">Detail Transaksi Terbaru</h3>
                <span class="px-4 py-1.5 rounded-xl bg-accent-500/10 text-accent-500 text-[10px] font-black uppercase tracking-widest leading-none">Real-time Feed</span>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-navy-800/30 border-b border-slate-100 dark:border-white/5">
                            <th class="px-8 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Invoice</th>
                            <th class="px-8 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Customer</th>
                            <th class="px-8 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Metode</th>
                            <th class="px-8 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                        @foreach($transactions as $trx)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-accent-500/5 transition-all">
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span class="font-mono text-xs font-black text-navy-900 dark:text-white leading-none">#{{ $trx->invoice_code }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">{{ $trx->created_at->format('d M, H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $trx->customer->name ?? 'Umum' }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter bg-slate-100 dark:bg-navy-800 text-slate-500">
                                    <i data-lucide="{{ $trx->payment_method === 'cash' ? 'banknote' : 'zap' }}" class="w-3 h-3"></i>
                                    {{ $trx->payment_method }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="text-sm font-black text-navy-900 dark:text-white">Rp{{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
            <div class="px-8 py-6 border-t border-slate-100 dark:border-white/5 bg-slate-50/20">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </div>


</div>


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded',function(){

lucide.createIcons();

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Penjualan (IDR)',
                data: @json($chartData['sales']),
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6366f1',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 3
            }, {
                label: 'Volume Transaksi',
                data: @json($chartData['transactions']),
                borderColor: '#10b981',
                borderDash: [5, 5],
                yAxisID: 'y1',
                pointRadius: 0,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 10 }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 10 } }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 10 } }
                }
            }
        }
    });

    new Chart(document.getElementById('paymentChart'), {
        type: 'doughnut',
        data: {
            labels: @json($paymentDistribution->pluck('method')),
            datasets: [{
                data: @json($paymentDistribution->pluck('total')),
                backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#8b5cf6'],
                hoverOffset: 15,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { family: 'Plus Jakarta Sans', weight: 'bold', size: 10 }
                    }
                }
            }
        }
    });

});

</script>

@endpush

@endsection