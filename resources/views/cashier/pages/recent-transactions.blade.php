@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div>
                <h1 class="text-3xl font-bold text-navy-900 dark:text-white">
                    Riwayat Transaksi
                </h1>
                <p class="text-slate-600 dark:text-slate-400">Daftar transaksi yang Anda proses hari ini</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-navy-800 border border-slate-200 dark:border-white/10">
                    <span class="text-sm text-slate-500 dark:text-slate-400">Total Transaksi:</span>
                    <span class="ml-2 text-lg font-bold text-accent-600">{{ $transactions->total() }}</span>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-navy-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Invoice</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Items</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Metode</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-bold text-navy-900 dark:text-white tracking-wider">
                                    {{ $transaction->invoice_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-navy-900 dark:text-white">
                                        {{ $transaction->created_at->format('H:i:s') }}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        {{ $transaction->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($transaction->customer)
                                    @if($transaction->customer->membership === 'platinum')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400">
                                            <i data-lucide="crown" class="w-3.5 h-3.5"></i>
                                            {{ $transaction->customer->name }}
                                        </span>
                                    @elseif($transaction->customer->membership === 'gold')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                            <i data-lucide="star" class="w-3.5 h-3.5"></i>
                                            {{ $transaction->customer->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-400">
                                            <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                            {{ $transaction->customer->name }}
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-400">
                                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                        Umum
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 dark:bg-navy-800 text-sm font-bold text-navy-900 dark:text-white">
                                    {{ $transaction->items->sum('qty') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-base font-bold text-accent-600 dark:text-accent-400">
                                    Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $paymentBadges = [
                                        'cash' => ['color' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400', 'icon' => 'banknote', 'label' => 'Tunai'],
                                        'qris' => ['color' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400', 'icon' => 'qr-code', 'label' => 'QRIS'],
                                        'debit' => ['color' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400', 'icon' => 'credit-card', 'label' => 'Debit'],
                                        'ewallet' => ['color' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400', 'icon' => 'wallet', 'label' => 'E-Wallet']
                                    ];
                                    $badge = $paymentBadges[$transaction->payment_method] ?? $paymentBadges['cash'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $badge['color'] }}">
                                    <i data-lucide="{{ $badge['icon'] }}" class="w-3.5 h-3.5"></i>
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="printReceipt({{ $transaction->id }})" 
                                        class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white transition-all duration-200 hover:shadow-lg hover:shadow-accent-500/30 hover:-translate-y-0.5"
                                        title="Cetak Struk">
                                    <i data-lucide="printer" class="w-5 h-5"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="inbox" class="w-10 h-10 text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Belum ada transaksi hari ini</p>
                                <p class="text-slate-400 text-sm mt-1">Transaksi yang Anda proses akan muncul di sini</p>
                            </td>
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
<script>
function printReceipt(transactionId) {
    const printWindow = window.open(`/cashier/receipt/${transactionId}`, '_blank');
    if (!printWindow) {
        alert('Pop-up diblokir. Izinkan pop-up untuk mencetak struk.');
    }
}

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