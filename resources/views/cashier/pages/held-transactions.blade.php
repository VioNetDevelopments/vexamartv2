@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center gap-4">
                <a href="{{ route('cashier.pos') }}" 
                   class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent">
                        Transaksi Hold
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">Kelola transaksi yang disimpan sementara</p>
                </div>
            </div>
            <a href="{{ route('cashier.pos') }}" 
               class="px-5 py-2.5 rounded-xl bg-accent-500 text-white font-medium hover:bg-accent-600 transition-colors shadow-lg shadow-accent-500/30">
                <i data-lucide="plus" class="inline h-4 w-4 mr-2"></i>
                Transaksi Baru
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center">
                        <i data-lucide="pause-circle" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Hold</p>
                <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $heldTransactions->total() }}</h3>
            </div>
            
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center">
                        <i data-lucide="shopping-cart" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Item</p>
                <h3 class="text-2xl font-bold text-success">{{ $heldTransactions->sum(fn($t) => count($t->items)) }}</h3>
            </div>
            
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                        <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Nilai</p>
                <h3 class="text-2xl font-bold text-purple-600">Rp {{ number_format($heldTransactions->sum('subtotal'), 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Transactions List -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-white/10">
                <h3 class="text-lg font-bold text-navy-900 dark:text-white">Daftar Transaksi Hold</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-navy-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Kode Hold</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Waktu Simpan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Items</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Subtotal</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Diskon</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Total</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($heldTransactions as $transaction)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-medium text-accent-600 dark:text-accent-400">
                                    {{ $transaction->transaction_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                {{ $transaction->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                {{ $transaction->customer?->name ?? 'Umum' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                    {{ count($transaction->items) }} item
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                Rp {{ number_format($transaction->discount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-navy-900 dark:text-white">
                                    Rp {{ number_format($transaction->subtotal - $transaction->discount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="recallTransaction({{ $transaction->id }})" 
                                            class="p-2 rounded-lg bg-accent-500 text-white hover:bg-accent-600 transition-colors"
                                            title="Ambil Transaksi">
                                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="deleteTransaction({{ $transaction->id }})" 
                                            class="p-2 rounded-lg border border-danger/20 text-danger hover:bg-danger/5 transition-colors"
                                            title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <i data-lucide="inbox" class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                                <p class="text-slate-500 dark:text-slate-400">Tidak ada transaksi hold</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($heldTransactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">
                {{ $heldTransactions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function recallTransaction(id) {
    if (!confirm('Ambil transaksi ini ke kasir?')) return;
    
    fetch(`/cashier/recall-transaction/${id}`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = '/cashier/pos';
        } else {
            alert(data.message || 'Gagal mengambil transaksi');
        }
    });
}

function deleteTransaction(id) {
    if (!confirm('Hapus transaksi hold ini?')) return;
    
    fetch(`/cashier/held-transaction/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        }
    })
    .then(() => {
        location.reload();
    });
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
</style>
@endpush
@endsection