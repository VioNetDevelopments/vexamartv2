@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div>
                <h1 class="text-3xl font-bold text-navy-900 dark:text-white">
                    Penjualan Hari Ini
                </h1>
                <p class="text-slate-600 dark:text-slate-400" x-text="currentDate"></p>
            </div>
            <button onclick="window.print()" 
                    class="px-5 py-2.5 rounded-xl bg-accent-500 text-white font-medium hover:bg-accent-600 transition-colors shadow-lg shadow-accent-500/30">
                <i data-lucide="printer" class="inline h-4 w-4 mr-2"></i>
                Cetak Laporan
            </button>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Penjualan</p>
                <h3 class="text-2xl font-bold text-accent-600 dark:text-accent-400">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
            </div>
            
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center">
                        <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Transaksi</p>
                <h3 class="text-2xl font-bold text-success">{{ $totalTransactions }}</h3>
            </div>
            
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                        <i data-lucide="calculator" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Rata-rata/Transaksi</p>
                <h3 class="text-2xl font-bold text-purple-600">Rp {{ $totalTransactions > 0 ? number_format($totalSales / $totalTransactions, 0, ',', '.') : 0 }}</h3>
            </div>
            
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning to-warning/80 flex items-center justify-center">
                        <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Jam Operasional</p>
                <h3 class="text-2xl font-bold text-warning">{{ $completeHourlySales->where('total', '>', 0)->count() }} jam</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sales by Payment Method -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-5 h-5 text-accent-500"></i>
                    Penjualan Berdasarkan Metode Pembayaran
                </h3>
                <div class="space-y-3">
                    @php
                        $paymentMethods = [
                            'cash' => ['name' => 'Tunai', 'icon' => 'banknote', 'color' => 'from-green-500 to-green-600'],
                            'qris' => ['name' => 'QRIS', 'icon' => 'qr-code', 'color' => 'from-blue-500 to-blue-600'],
                            'debit' => ['name' => 'Debit', 'icon' => 'credit-card', 'color' => 'from-purple-500 to-purple-600'],
                            'ewallet' => ['name' => 'E-Wallet', 'icon' => 'wallet', 'color' => 'from-orange-500 to-orange-600']
                        ];
                    @endphp
                    
                    @foreach($paymentMethods as $key => $method)
                        @php
                            $sale = $salesByMethod->firstWhere('payment_method', $key);
                            $count = $sale->count ?? 0;
                            $total = $sale->total ?? 0;
                            $percentage = $totalSales > 0 ? ($total / $totalSales) * 100 : 0;
                        @endphp
                        @if($count > 0 || $key === 'cash' || $key === 'qris')
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-navy-800 hover:bg-slate-100 dark:hover:bg-navy-700 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $method['color'] }} flex items-center justify-center shadow-lg">
                                    <i data-lucide="{{ $method['icon'] }}" class="w-6 h-6 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-navy-900 dark:text-white">{{ $method['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $count }} transaksi</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-navy-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-500">{{ number_format($percentage, 1) }}%</p>
                            </div>
                        </div>
                        @endif
                    @endforeach
                    
                    @if($salesByMethod->isEmpty())
                    <p class="text-center text-slate-500 py-8">Belum ada data penjualan</p>
                    @endif
                </div>
            </div>

            <!-- Hourly Sales -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg" x-data="hourlySales()">
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-accent-500"></i>
                    Penjualan Per Jam
                </h3>
                <div class="space-y-2 max-h-96 overflow-y-auto" id="hourlySalesContainer">
                    @php $hours = range(0, 23); @endphp
                    @foreach($hours as $hour)
                        @php
                            $hourData = $completeHourlySales->firstWhere('hour', $hour);
                            $total = $hourData->total ?? 0;
                            $count = $hourData->count ?? 0;
                            $percentage = $totalSales > 0 ? ($total / $totalSales) * 100 : 0;
                            $currentHour = now()->hour;
                            $isCurrentHour = $hour == $currentHour;
                        @endphp
                        <div class="flex items-center gap-3" :class="'{{ $isCurrentHour }}' ? 'bg-accent-50 dark:bg-accent-900/20 rounded-lg p-1' : ''">
                            <span class="text-xs text-slate-500 w-12 font-mono">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</span>
                            <div class="flex-1 h-8 bg-slate-100 dark:bg-navy-800 rounded-lg overflow-hidden relative">
                                <div class="h-full bg-gradient-to-r from-accent-500 to-accent-600 rounded-lg transition-all duration-500 flex items-center justify-end px-2" 
                                     style="width: {{ min($percentage * 2, 100) }}%">
                                    @if($total > 0)
                                    <span class="text-xs text-white font-bold">{{ $count }}</span>
                                    @endif
                                </div>
                                @if($isCurrentHour)
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-bold text-accent-600">● Sekarang</span>
                                @endif
                            </div>
                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-300 w-20 text-right">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Real-time clock
document.addEventListener('alpine:init', () => {
    Alpine.data('currentDateTime', () => ({
        currentDate: '',
        init() {
            this.updateDate();
            setInterval(() => this.updateDate(), 1000);
        },
        updateDate() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            this.currentDate = now.toLocaleDateString('id-ID', options) + ' WIB';
        }
    }));
    
    Alpine.data('hourlySales', () => ({
        init() {
            this.highlightCurrentHour();
            setInterval(() => this.highlightCurrentHour(), 60000); // Update every minute
        },
        highlightCurrentHour() {
            const currentHour = new Date().getHours();
            document.querySelectorAll('#hourlySalesContainer > div').forEach((el, index) => {
                if (index === currentHour) {
                    el.classList.add('bg-accent-50', 'dark:bg-accent-900/20', 'rounded-lg', 'p-1');
                } else {
                    el.classList.remove('bg-accent-50', 'dark:bg-accent-900/20', 'rounded-lg', 'p-1');
                }
            });
        }
    }));
});

document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush

@push('styles')
<style>
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }

@media print {
    .no-print { display: none !important; }
    body { background: white; }
}
</style>
@endpush
@endsection