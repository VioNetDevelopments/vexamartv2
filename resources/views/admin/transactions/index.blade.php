@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ dateFrom: '{{ request('date_from', now()->startOfMonth()->format('Y-m-d')) }}', dateTo: '{{ request('date_to', now()->format('Y-m-d')) }}' }">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 animate-fade-in-down">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                <i data-lucide="receipt" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">Riwayat Transaksi</h1>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total <span class="text-accent-500 font-bold">{{ $transactions->total() }}</span> transaksi tercatat di sistem</p>
            </div>
        </div>
        
        <!-- Modern Status Badge -->
        <div class="flex items-center gap-3">
            <button @click="window.print()" class="group flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white dark:bg-navy-900 border border-slate-200/60 dark:border-white/5 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-navy-800 hover:shadow-md transition-all">
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
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
            <!-- Date From -->
            <div class="space-y-2">
                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Dari Tanggal</label>
                <div class="relative group">
                    <i data-lucide="calendar" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                    <input type="date" name="date_from" x-model="dateFrom"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                </div>
            </div>

            <!-- Date To -->
            <div class="space-y-2">
                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Sampai Tanggal</label>
                <div class="relative group">
                    <i data-lucide="calendar" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                    <input type="date" name="date_to" x-model="dateTo"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                </div>
            </div>

            <!-- Payment Method with Custom Dropdown -->
            <div class="space-y-2" x-data="{ 
                open: false, 
                selected: '{{ request('payment_method') }}',
                options: {
                    '': 'Semua Metode',
                    'cash': 'Tunai',
                    'qris': 'QRIS',
                    'debit': 'Debit',
                    'ewallet': 'E-Wallet'
                }
            }">
                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Metode Bayar</label>
                <input type="hidden" name="payment_method" :value="selected">
                <div class="relative">
                    <button type="button" @click="open = !open" @click.away="open = false"
                            class="w-full flex items-center justify-between pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                        <span x-text="options[selected] || 'Pilih Metode'"></span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10">
                        <template x-for="(label, key) in options">
                            <button type="button" @click="selected = key; open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20"
                                    :class="selected === key ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300'">
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
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="No. Invoice / Customer..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 flex items-center justify-center gap-2 bg-accent-500 text-white rounded-xl py-2.5 text-sm font-black uppercase tracking-widest hover:bg-accent-600 shadow-lg shadow-accent-500/30 transition-all active:scale-95">
                    <i data-lucide="sliders" class="w-4 h-4"></i>
                    Filter
                </button>
                <a href="{{ route('admin.transactions.index') }}" class="flex items-center justify-center px-4 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 transition-all">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
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
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Transaksi</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">{{ $transactions->total() }}</h3>
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
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">Rp{{ number_format($transactions->sum('grand_total'), 0, ',', '.') }}</h3>
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
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rata-rata/Trx</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">Rp{{ number_format($transactions->avg('grand_total') ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Popular Method -->
        <div class="group relative bg-white dark:bg-navy-900 p-6 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 hover:shadow-2xl transition-all duration-500 hover:-translate-y-1.5 overflow-hidden border border-white dark:border-white/5">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-warning/5 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative flex items-center gap-4">
                @php
                    $popularMethodData = \Illuminate\Support\Facades\DB::table('transactions')
                        ->select('payment_method', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
                        ->groupBy('payment_method')
                        ->orderByDesc('count')
                        ->first();
                    
                    $method = $popularMethodData ? $popularMethodData->payment_method : 'cash';
                    $icons = [
                        'cash' => 'banknote',
                        'qris' => 'qr-code',
                        'debit' => 'credit-card',
                        'ewallet' => 'wallet'
                    ];
                    $icon = $icons[$method] ?? 'zap';
                @endphp
                <div class="w-12 h-12 rounded-2xl bg-warning/5 flex items-center justify-center text-warning">
                    <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Metode Utama</p>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white tracking-tight">
                        {{ $popularMethodData ? ucfirst($method) : '-' }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table Section -->
    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-navy-800/50 border-b border-slate-100 dark:border-white/5">
                        <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">No. Invoice</th>
                        <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Waktu Transaksi</th>
                        <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Item</th>
                        <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Pembayaran</th>
                        <th class="px-6 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Total Bayar</th>
                        <th class="px-6 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/5">
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
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $trx->customer->name ?? 'Umum' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @php
                                $itemCount = $trx->items ? $trx->items->sum('qty') : 0;
                            @endphp
                            <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-800 text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                {{ $itemCount }} Item
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @php
                                $badgeClasses = [
                                    'cash' => 'bg-success/10 text-success border border-success/20',
                                    'qris' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800',
                                    'debit' => 'bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800',
                                    'ewallet' => 'bg-warning/10 text-warning border border-warning/20'
                                ];
                                $icons = [
                                    'cash' => 'banknote',
                                    'qris' => 'qr-code',
                                    'debit' => 'credit-card',
                                    'ewallet' => 'wallet'
                                ];
                                $method = $trx->payment_method;
                                $class = $badgeClasses[$method] ?? $badgeClasses['cash'];
                                $icon = $icons[$method] ?? $icons['cash'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $class }}">
                                <i data-lucide="{{ $icon }}" class="w-3 h-3"></i>
                                {{ ucfirst($method) }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right whitespace-nowrap">
                            <span class="text-base font-black text-navy-900 dark:text-white">Rp{{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-5 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.transactions.show', $trx) }}" 
                                   class="p-2.5 rounded-xl bg-accent-50 dark:bg-accent-500/10 text-accent-500 hover:bg-accent-500 hover:text-white transition-all shadow-sm active:scale-95" 
                                   title="Detail">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>
                                <a href="{{ route('admin.transactions.print', $trx) }}" target="_blank" 
                                   class="p-2.5 rounded-xl bg-slate-50 dark:bg-navy-800 text-slate-500 hover:bg-navy-900 dark:hover:bg-white hover:text-white dark:hover:text-navy-900 transition-all shadow-sm active:scale-95" 
                                   title="Cetak">
                                    <i data-lucide="printer" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-navy-800 flex items-center justify-center text-slate-200 dark:text-navy-700 mb-4 animate-bounce">
                                    <i data-lucide="receipt" class="w-10 h-10"></i>
                                </div>
                                <h4 class="text-lg font-bold text-navy-900 dark:text-white mb-1">Riwayat Kosong</h4>
                                <p class="text-sm text-slate-500">Belum ada transaksi yang tercatat dalam periode ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Modern Pagination -->
        @if($transactions->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 dark:border-white/5 bg-slate-50/30 dark:bg-navy-900/30">
            <div class="flex items-center justify-between">
                <!-- Info Text -->
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->total() }}</span> hasil
                </div>
                
                <!-- Simple Prev/Next Buttons -->
                <div class="flex items-center gap-2">
                    @if($transactions->onFirstPage())
                        <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                Previous
                            </span>
                        </button>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                Previous
                            </span>
                        </a>
                    @endif
                    
                    @if($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
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
@endsection