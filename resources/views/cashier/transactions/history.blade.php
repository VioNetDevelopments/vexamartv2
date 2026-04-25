@extends('layouts.app')

@section('page-title', 'Histori Transaksi')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-600 to-green-700 flex items-center justify-center shadow-lg shadow-green-500/20">
                                <i data-lucide="history" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">
                                    Histori Transaksi
                                </h1>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Riwayat transaksi Anda</p>
                            </div>
                        </div>
                        <span
                            class="px-3 py-1 bg-green-500/10 text-green-600 dark:text-green-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            {{ $stats['total'] }} Transaksi
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up"
                    style="animation-delay: 0.1s;">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i data-lucide="receipt" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Transaksi</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $stats['total'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up"
                    style="animation-delay: 0.2s;">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-600 to-green-700 flex items-center justify-center shadow-lg shadow-green-500/30">
                                <i data-lucide="calendar" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Hari Ini</p>
                        <h3 class="text-2xl font-bold text-green-600">{{ $stats['today'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up"
                    style="animation-delay: 0.3s;">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-600 to-purple-700 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Penjualan</p>
                        <h3 class="text-2xl font-bold text-purple-600">Rp
                            {{ number_format($stats['total_sales'], 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up"
                    style="animation-delay: 0.4s;">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110">
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-600 to-orange-700 flex items-center justify-center shadow-lg shadow-orange-500/30">
                                <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Penjualan Hari Ini</p>
                        <h3 class="text-2xl font-bold text-orange-600">Rp
                            {{ number_format($stats['today_sales'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up overflow-visible relative z-20"
                style="animation-delay: 0.5s;">
                <form action="{{ route('cashier.transactions') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <div class="relative group">
                            <i data-lucide="search"
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-green-500 transition-colors"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari invoice atau pelanggan..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-green-500 focus:ring-4 focus:ring-green-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        </div>
                    </div>

                    <div>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Dari Tanggal"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-green-500 focus:ring-4 focus:ring-green-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>

                    <div>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai Tanggal"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-green-500 focus:ring-4 focus:ring-green-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>

                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                            class="flex items-center justify-between min-w-[160px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-green-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span>{{ request('payment_method') ? ucfirst(request('payment_method')) : 'Semua Metode' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300"
                                :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10"
                            style="top: 100%;">
                            <button type="button" onclick="selectPayment('', 'Semua Metode')"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-green-50 dark:hover:bg-green-900/20 {{ !request('payment_method') ? 'bg-green-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">Semua
                                Metode</button>
                            <button type="button" onclick="selectPayment('cash', 'Tunai')"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-green-50 dark:hover:bg-green-900/20 {{ request('payment_method') == 'cash' ? 'bg-green-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">Tunai</button>
                            <button type="button" onclick="selectPayment('qris', 'QRIS')"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-green-50 dark:hover:bg-green-900/20 {{ request('payment_method') == 'qris' ? 'bg-green-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">QRIS</button>
                            <button type="button" onclick="selectPayment('debit', 'Debit')"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-green-50 dark:hover:bg-green-900/20 {{ request('payment_method') == 'debit' ? 'bg-green-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">Debit</button>
                            <button type="button" onclick="selectPayment('ewallet', 'E-Wallet')"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-green-50 dark:hover:bg-green-900/20 {{ request('payment_method') == 'ewallet' ? 'bg-green-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">E-Wallet</button>
                        </div>
                        <input type="hidden" name="payment_method" id="paymentInput"
                            value="{{ request('payment_method') }}">
                    </div>

                    <button type="submit"
                        class="bg-green-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-green-600 shadow-lg shadow-green-500/30 transition-all active:scale-95">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <a href="{{ route('cashier.transactions') }}"
                        class="flex items-center justify-center px-4 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up"
                style="animation-delay: 0.6s;">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead
                            class="bg-gradient-to-r from-green-500/10 to-emerald-500/10 dark:from-green-900/20 dark:to-emerald-900/20">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">
                                    Invoice</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">
                                    Pelanggan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">
                                    Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">
                                    Metode</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase tracking-widest">
                                    Total</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($transactions as $transaction)
                                <tr class="group hover:bg-green-50/50 dark:hover:bg-green-900/5 transition-all">
                                    <td class="px-6 py-4">
                                        <p class="font-mono font-bold text-slate-900 dark:text-white">
                                            {{ $transaction->invoice_code }}</p>
                                        <p class="text-xs text-slate-500">{{ $transaction->created_at->format('H:i') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-900 dark:text-white">
                                            {{ $transaction->customer?->name ?? 'Umum' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-600 dark:text-slate-400">
                                            {{ $transaction->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black
                                                 {{ $transaction->payment_method === 'cash' ? 'bg-green-100 text-green-700' : '' }}
                                                 {{ $transaction->payment_method === 'qris' ? 'bg-blue-100 text-blue-700' : '' }}
                                                 {{ $transaction->payment_method === 'debit' ? 'bg-purple-100 text-purple-700' : '' }}
                                                 {{ $transaction->payment_method === 'ewallet' ? 'bg-orange-100 text-orange-700' : '' }}">
                                            {{ ucfirst($transaction->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <p class="font-bold text-slate-900 dark:text-white">Rp
                                            {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($transaction->payment_status === 'paid')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-green-100 text-green-700">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                                Lunas
                                            </span>
                                        @elseif($transaction->payment_status === 'pending')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-yellow-100 text-yellow-700">
                                                <i data-lucide="clock" class="w-3 h-3"></i>
                                                Pending
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-red-100 text-red-700">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('cashier.transactions.show', $transaction) }}"
                                                class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-500 hover:text-white transition-all"
                                                title="Lihat Detail">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ route('cashier.transactions.print', $transaction) }}" target="_blank"
                                                class="p-2.5 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 hover:bg-green-500 hover:text-white transition-all"
                                                title="Cetak">
                                                <i data-lucide="printer" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="receipt" class="w-16 h-16 text-slate-300 mb-4"></i>
                                            <p class="text-slate-500">Belum ada transaksi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-white/5">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                Menampilkan <span class="font-bold">{{ $transactions->firstItem() }}</span> - <span
                                    class="font-bold">{{ $transactions->lastItem() }}</span> dari <span
                                    class="font-bold">{{ $transactions->total() }}</span> transaksi
                            </div>
                            <div class="flex items-center gap-2">
                                @if($transactions->onFirstPage())
                                    <button disabled
                                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                                        <span class="flex items-center gap-1.5"><i data-lucide="chevron-left"
                                                class="w-4 h-4"></i>Previous</span>
                                    </button>
                                @else
                                    <a href="{{ $transactions->previousPageUrl() }}"
                                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-green-500 hover:text-white hover:border-green-500 hover:shadow-lg hover:shadow-green-500/30 transition-all text-sm font-bold">
                                        <span class="flex items-center gap-1.5"><i data-lucide="chevron-left"
                                                class="w-4 h-4"></i>Previous</span>
                                    </a>
                                @endif
                                @if($transactions->hasMorePages())
                                    <a href="{{ $transactions->nextPageUrl() }}"
                                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-green-500 hover:text-white hover:border-green-500 hover:shadow-lg hover:shadow-green-500/30 transition-all text-sm font-bold">
                                        <span class="flex items-center gap-1.5">Next<i data-lucide="chevron-right"
                                                class="w-4 h-4"></i></span>
                                    </a>
                                @else
                                    <button disabled
                                        class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                                        <span class="flex items-center gap-1.5">Next<i data-lucide="chevron-right"
                                                class="w-4 h-4"></i></span>
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
        <script>
            function selectPayment(value, text) {
                document.getElementById('paymentInput').value = value;
                event.target.closest('div').querySelectorAll('button').forEach(btn => {
                    btn.classList.remove('bg-green-500', 'text-white');
                    btn.classList.add('text-slate-600', 'dark:text-slate-300');
                });
                event.target.classList.remove('text-slate-600', 'dark:text-slate-300');
                event.target.classList.add('bg-green-500', 'text-white');
                document.querySelector('form').submit();
            }
            document.addEventListener('DOMContentLoaded', function () { lucide.createIcons(); });
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