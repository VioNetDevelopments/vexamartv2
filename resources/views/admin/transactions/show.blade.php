@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" 
                   class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">
                        Detail Invoice
                    </h1>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Informasi lengkap transaksi</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.transactions.print', $transaction) }}" target="_blank"
                   class="flex items-center gap-2 px-5 py-3 bg-white dark:bg-navy-900 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-50 dark:hover:bg-navy-800 hover:shadow-md transition-all duration-300">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                    <span>Cetak</span>
                </a>
            </div>
        </div>

        <!-- Invoice Header Card -->
        <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
            <!-- Header with Status -->
            <div class="relative px-8 py-6 bg-gradient-to-r from-accent-500 via-purple-500 to-pink-500">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm font-medium mb-1">Nomor Invoice</p>
                        <h2 class="text-3xl font-black text-white font-mono">{{ $transaction->invoice_code }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-white/80 text-sm font-medium mb-1">Status Pembayaran</p>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider bg-white/20 text-white backdrop-blur-sm">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            {{ ucfirst($transaction->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Transaction Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-navy-800/50 border border-slate-200 dark:border-white/5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-500/10">
                            <i data-lucide="calendar" class="w-5 h-5 text-blue-500"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Tanggal</p>
                    </div>
                    <p class="text-lg font-black text-navy-900 dark:text-white">{{ $transaction->created_at->format('d F Y') }}</p>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $transaction->created_at->format('H:i') }} WIB</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-navy-800/50 border border-slate-200 dark:border-white/5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-purple-500/10">
                            <i data-lucide="user" class="w-5 h-5 text-purple-500"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kasir</p>
                    </div>
                    <p class="text-lg font-black text-navy-900 dark:text-white">{{ $transaction->user->name ?? '-' }}</p>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 capitalize">{{ $transaction->user->role ?? '-' }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-navy-800/50 border border-slate-200 dark:border-white/5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-green-500/10">
                            <i data-lucide="credit-card" class="w-5 h-5 text-green-500"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pembayaran</p>
                    </div>
                    <p class="text-lg font-black text-navy-900 dark:text-white capitalize">{{ ucfirst($transaction->payment_method) }}</p>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $transaction->payment_status === 'paid' ? 'Lunas' : 'Pending' }}</p>
                </div>
            </div>
        </div>

        <!-- Customer & Items Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Customer Info -->
            <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-white/10 bg-gradient-to-r from-slate-50 to-white dark:from-navy-800 dark:to-navy-900">
                    <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/10">
                            <i data-lucide="user" class="w-5 h-5 text-blue-500"></i>
                        </div>
                        <span>Informasi Pelanggan</span>
                    </h3>
                </div>
                <div class="p-6">
                    @if($transaction->customer)
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white text-2xl font-black shadow-lg">
                                {{ strtoupper(substr($transaction->customer->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-xl font-black text-navy-900 dark:text-white">{{ $transaction->customer->name }}</p>
                                @if($transaction->customer->membership)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                        @if($transaction->customer->membership === 'platinum') bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400
                                        @elseif($transaction->customer->membership === 'gold') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400
                                        @else bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-400 @endif">
                                        @if($transaction->customer->membership === 'platinum')
                                            <i data-lucide="crown" class="w-3 h-3"></i> Platinum
                                        @elseif($transaction->customer->membership === 'gold')
                                            <i data-lucide="star" class="w-3 h-3"></i> Gold
                                        @else
                                            <i data-lucide="user" class="w-3 h-3"></i> Regular
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            @if($transaction->customer->phone)
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50">
                                    <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $transaction->customer->phone }}</span>
                                </div>
                            @endif
                            @if($transaction->customer->email)
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50">
                                    <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $transaction->customer->email }}</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="user" class="w-8 h-8 text-slate-400"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pelanggan Umum</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-white/10 bg-gradient-to-r from-slate-50 to-white dark:from-navy-800 dark:to-navy-900">
                    <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-green-500/10">
                            <i data-lucide="wallet" class="w-5 h-5 text-green-500"></i>
                        </div>
                        <span>Ringkasan Pembayaran</span>
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Subtotal</span>
                        <span class="text-base font-bold text-navy-900 dark:text-white">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($transaction->discount > 0)
                    <div class="flex justify-between items-center p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Diskon</span>
                        <span class="text-base font-bold text-danger">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    @if($transaction->tax > 0)
                    <div class="flex justify-between items-center p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Pajak</span>
                        <span class="text-base font-bold text-navy-900 dark:text-white">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center p-4 rounded-2xl bg-gradient-to-br from-accent-500/10 to-accent-600/10 border-2 border-accent-500/20">
                        <span class="text-base font-black text-navy-900 dark:text-white">Total</span>
                        <span class="text-2xl font-black text-accent-600 dark:text-accent-400">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($transaction->payment_method === 'cash')
                    <div class="pt-4 border-t border-slate-100 dark:border-white/10 space-y-3">
                        <div class="flex justify-between items-center p-3 rounded-xl bg-success/10 border border-success/20">
                            <span class="text-sm font-medium text-success">Tunai</span>
                            <span class="text-base font-bold text-success">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-xl bg-slate-50 dark:bg-navy-800/50">
                            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Kembalian</span>
                            <span class="text-base font-bold text-navy-900 dark:text-white">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items List -->
        <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-white/10 bg-gradient-to-r from-slate-50 to-white dark:from-navy-800 dark:to-navy-900">
                <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-500/10">
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-purple-500"></i>
                    </div>
                    <span>Daftar Produk</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-navy-800/50 border-b border-slate-100 dark:border-white/5">
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Produk</th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Harga</th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Qty</th>
                            <th class="px-6 py-4 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @foreach($transaction->items as $item)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center">
                                                <i data-lucide="package" class="w-6 h-6 text-slate-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-navy-900 dark:text-white">{{ $item->product->name ?? 'Produk dihapus' }}</p>
                                        @if($item->product)
                                            <p class="text-xs text-slate-500">{{ $item->product->sku ?? '-' }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 dark:bg-navy-800 text-sm font-black text-navy-900 dark:text-white">
                                    {{ $item->qty }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-base font-bold text-accent-600 dark:text-accent-400">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush

@push('styles')
<style>
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; opacity: 0; }
.animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }
</style>
@endpush
@endsection