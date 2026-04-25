@extends('layouts.app')

@section('page-title', 'Histori Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                <i data-lucide="history" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black gradient-text">Histori Transaksi</h1>
                <p class="text-sm text-slate-500">Daftar transaksi yang Anda lakukan</p>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
        <form action="{{ route('cashier.transactions') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice atau pelanggan..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                </div>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
            </div>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-blue-500/30 transition-all">
                <i data-lucide="filter" class="inline w-5 h-5 mr-2"></i>
                Filter
            </button>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-navy-800/50 border-b border-slate-200 dark:border-white/5">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Invoice</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Pembayaran</th>
                        <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($transactions as $transaction)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                    <span class="font-mono font-bold text-blue-600">{{ $transaction->invoice_code }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $transaction->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-slate-500">{{ $transaction->created_at->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $transaction->customer->name ?? 'Umum' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black uppercase {{ $transaction->payment_method === 'cash' ? 'bg-green-100 text-green-700' : ($transaction->payment_method === 'qris' ? 'bg-blue-100 text-blue-700' : ($transaction->payment_method === 'debit' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700')) }}">
                                    {{ ucfirst($transaction->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('cashier.transactions.show', $transaction) }}" 
                                       class="p-2 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-500 hover:text-white transition-all">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.transactions.print', $transaction) }}" target="_blank"
                                       class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-600 hover:bg-slate-500 hover:text-white transition-all">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center mb-4">
                                        <i data-lucide="receipt" class="w-8 h-8 text-slate-400"></i>
                                    </div>
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
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection