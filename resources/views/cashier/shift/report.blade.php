@extends('layouts.app')

@section('page-title', 'Laporan Shift')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-5xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between animate-fade-in-down">
                <div class="flex items-center gap-4">
                    <a href="{{ route('cashier.shift') }}" 
                        class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                        <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">Laporan Shift</h1>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $shift->user->name }} - {{ $shift->started_at->format('d M Y') }}</p>
                    </div>
                </div>
                <button onclick="window.print()"
                    class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-blue-500/30 transition-all flex items-center gap-2">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                    <span>Cetak Laporan</span>
                </button>
            </div>

            <!-- Shift Info -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Shift Dibuka</p>
                        <p class="font-bold text-slate-900 dark:text-white">{{ $shift->started_at->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Shift Ditutup</p>
                        <p class="font-bold text-slate-900 dark:text-white">{{ $shift->ended_at?->format('H:i') ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Modal Awal</p>
                        <p class="font-bold text-green-600">Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Selisih Kas</p>
                        <p class="font-bold {{ $shift->cash_shortage >= 0 ? 'text-green-600' : 'text-danger' }}">
                            Rp {{ number_format($shift->cash_shortage, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Sales Summary -->
                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Penjualan per Metode Pembayaran</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                    <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-500/20">
                        <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Tunai</p>
                        <p class="text-sm font-black text-green-700 dark:text-green-400">Rp {{ number_format($summary['cash_sales'], 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-500/20">
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1">QRIS</p>
                        <p class="text-sm font-black text-blue-700 dark:text-blue-400">Rp {{ number_format($summary['qris_sales'], 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-500/20">
                        <p class="text-[10px] font-black text-purple-600 uppercase tracking-widest mb-1">Debit</p>
                        <p class="text-sm font-black text-purple-700 dark:text-purple-400">Rp {{ number_format($summary['debit_sales'], 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-orange-50 dark:bg-orange-900/20 border border-orange-100 dark:border-orange-500/20">
                        <p class="text-[10px] font-black text-orange-600 uppercase tracking-widest mb-1">E-Wallet</p>
                        <p class="text-sm font-black text-orange-700 dark:text-orange-400">Rp {{ number_format($summary['ewallet_sales'], 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-500/20">
                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">Kartu</p>
                        <p class="text-sm font-black text-indigo-700 dark:text-indigo-400">Rp {{ number_format($summary['card_sales'], 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/20 border border-slate-200 dark:border-white/10">
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest mb-1">Transfer</p>
                        <p class="text-sm font-black text-slate-700 dark:text-slate-200">Rp {{ number_format($summary['bank_sales'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Transactions List -->
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="list" class="w-5 h-5 text-blue-500"></i>
                            Daftar Transaksi
                        </h3>
                        <span class="px-3 py-1 bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-full border border-slate-200 dark:border-white/5">
                            {{ count($transactions) }} Transaksi
                        </span>
                    </div>

                    <div class="bg-slate-50 dark:bg-navy-950/50 rounded-2xl border border-slate-200 dark:border-white/5 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-white dark:bg-navy-900 border-b border-slate-200 dark:border-white/5">
                                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Invoice</th>
                                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Waktu</th>
                                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Pelanggan</th>
                                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest">Metode</th>
                                        <th class="px-6 py-4 text-right text-[10px] font-black text-slate-500 uppercase tracking-widest">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    @foreach($transactions as $transaction)
                                        <tr class="hover:bg-white dark:hover:bg-navy-900/50 transition-colors group">
                                            <td class="px-6 py-4">
                                                <span class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400 px-2 py-1 bg-blue-50 dark:bg-blue-900/20 rounded-md border border-blue-100 dark:border-blue-500/20">
                                                    {{ $transaction->invoice_code }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2 text-sm font-bold text-slate-600 dark:text-slate-300">
                                                    <i data-lucide="clock" class="w-3.5 h-3.5 opacity-50"></i>
                                                    {{ $transaction->created_at->format('H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-navy-800 flex items-center justify-center text-[10px] font-black text-slate-600 dark:text-slate-400">
                                                        {{ strtoupper(substr($transaction->customer?->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-200">
                                                        {{ $transaction->customer?->name ?? 'Umum' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $methodColors = [
                                                        'cash' => 'bg-green-100 text-green-700 border-green-200',
                                                        'qris' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                        'debit' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                        'ewallet' => 'bg-orange-100 text-orange-700 border-orange-200',
                                                        'card' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                                        'bank' => 'bg-slate-200 text-slate-700 border-slate-300',
                                                    ];
                                                    $colorClass = $methodColors[strtolower($transaction->payment_method)] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                                @endphp
                                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border {{ $colorClass }}">
                                                    {{ $transaction->payment_method }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="text-sm font-black text-slate-900 dark:text-white">
                                                    Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-white dark:bg-navy-900 border-t-2 border-slate-200 dark:border-white/10">
                                        <td colspan="4" class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Total Penjualan</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-lg font-black text-blue-600 dark:text-blue-400">
                                                Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () { lucide.createIcons(); });
        </script>
    @endpush

    @push('styles')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                .max-w-5xl,
                .max-w-5xl * {
                    visibility: visible;
                }

                .max-w-5xl {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                button {
                    display: none !important;
                }
            }
        </style>
    @endpush
@endsection