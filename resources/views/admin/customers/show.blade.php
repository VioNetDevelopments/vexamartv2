@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-7xl mx-auto space-y-6">
        <!-- Header with Back Button -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.customers.index') }}" 
                   class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/30">
                        <i data-lucide="user-circle" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent tracking-tight">
                            Detail Pelanggan
                        </h1>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Informasi lengkap dan riwayat transaksi</p>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.customers.edit', $customer) }}" 
                   class="flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-300 hover:-translate-y-0.5">
                    <i data-lucide="edit" class="w-5 h-5"></i>
                    <span>Edit Profil</span>
                </a>
            </div>
        </div>

        <!-- Top Section: Profile & Contact Info -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Customer Profile Card - LEFT SIDE -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <!-- Profile Header with Gradient -->
                    <div class="relative h-40 bg-gradient-to-br from-accent-500 via-purple-500 to-pink-500">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute -bottom-16 left-1/2 -translate-x-1/2">
                            <div class="h-32 w-32 rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl">
                                <div class="h-full w-full rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white text-4xl font-black shadow-lg">
                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="pt-20 pb-6 px-6">
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-black text-navy-900 dark:text-white mb-3">{{ $customer->name }}</h2>
                            
                            <!-- Membership Badge -->
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider
                                @if($customer->membership === 'platinum') bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg shadow-purple-500/30
                                @elseif($customer->membership === 'gold') bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg shadow-yellow-500/30
                                @else bg-gradient-to-r from-slate-500 to-slate-600 text-white shadow-lg shadow-slate-500/30 @endif">
                                @if($customer->membership === 'platinum')
                                    <i data-lucide="crown" class="w-4 h-4"></i> Platinum Member
                                @elseif($customer->membership === 'gold')
                                    <i data-lucide="star" class="w-4 h-4"></i> Gold Member
                                @else
                                    <i data-lucide="user" class="w-4 h-4"></i> Regular
                                @endif
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-navy-800/50 border border-slate-200 dark:border-white/10">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-accent-500/10 mx-auto mb-2">
                                    <i data-lucide="shopping-bag" class="w-5 h-5 text-accent-500"></i>
                                </div>
                                <p class="text-center text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Transaksi</p>
                                <p class="text-center text-2xl font-black text-navy-900 dark:text-white">{{ $customer->transactions_count ?? 0 }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-navy-800/50 border border-slate-200 dark:border-white/10">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-purple-500/10 mx-auto mb-2">
                                    <i data-lucide="gift" class="w-5 h-5 text-purple-500"></i>
                                </div>
                                <p class="text-center text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Poin</p>
                                <p class="text-center text-2xl font-black text-purple-600 dark:text-purple-400">{{ number_format($customer->loyalty_points) }}</p>
                            </div>
                        </div>

                        <!-- Points Adjustment Button -->
                        <div class="mt-4">
                            <button onclick="openAdjustPointsModal({{ $customer->id }})" 
                                    class="w-full px-4 py-3 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold hover:bg-purple-500 hover:text-white transition-all">
                                <i data-lucide="plus-circle" class="inline w-4 h-4 mr-2"></i>
                                Sesuaikan Poin
                            </button>
                        </div>

                        <!-- Member Since -->
                        <div class="pt-6 border-t border-slate-100 dark:border-white/10">
                            <div class="flex items-center justify-center gap-2 text-slate-500 dark:text-slate-400">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                <span class="text-sm font-medium">Member sejak {{ $customer->created_at->format('d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Contact Info & Transactions Stacked -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Contact Information - TOP -->
                <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-white/10 bg-gradient-to-r from-slate-50 to-white dark:from-navy-800 dark:to-navy-900">
                        <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/10">
                                <i data-lucide="contact" class="w-5 h-5 text-blue-500"></i>
                            </div>
                            <span>Informasi Kontak</span>
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($customer->email)
                                <div class="group p-5 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-navy-800/50 dark:to-navy-800 border border-slate-200 dark:border-white/5 hover:border-blue-500/50 transition-all duration-300">
                                    <div class="flex items-start gap-4">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-500/10 flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <i data-lucide="mail" class="w-6 h-6 text-blue-500"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Email</p>
                                            <p class="text-sm font-semibold text-navy-900 dark:text-white break-all">{{ $customer->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($customer->phone)
                                <div class="group p-5 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-navy-800/50 dark:to-navy-800 border border-slate-200 dark:border-white/5 hover:border-green-500/50 transition-all duration-300">
                                    <div class="flex items-start gap-4">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-green-500/10 flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <i data-lucide="phone" class="w-6 h-6 text-green-500"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Telepon</p>
                                            <p class="text-sm font-semibold text-navy-900 dark:text-white">{{ $customer->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($customer->address)
                                <div class="group p-5 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 dark:from-navy-800/50 dark:to-navy-800 border border-slate-200 dark:border-white/5 hover:border-red-500/50 transition-all duration-300 md:col-span-2">
                                    <div class="flex items-start gap-4">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-red-500/10 flex-shrink-0 group-hover:scale-110 transition-transform">
                                            <i data-lucide="map-pin" class="w-6 h-6 text-red-500"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Alamat</p>
                                            <p class="text-sm font-semibold text-navy-900 dark:text-white leading-relaxed">{{ $customer->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @if(!$customer->email && !$customer->phone && !$customer->address)
                            <div class="text-center py-12">
                                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="contact" class="w-10 h-10 text-slate-400"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada informasi kontak</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Transaction History - BOTTOM (Same width as Contact Info) -->
                <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="px-6 py-5 border-b border-slate-100 dark:border-white/10 bg-gradient-to-r from-slate-50 to-white dark:from-navy-800 dark:to-navy-900">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-black text-navy-900 dark:text-white flex items-center gap-2">
                                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-accent-500/10">
                                    <i data-lucide="history" class="w-5 h-5 text-accent-500"></i>
                                </div>
                                <span>Riwayat Transaksi</span>
                            </h3>
                            <span class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                Total {{ $transactions->total() }} transaksi
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-navy-800/50 border-b border-slate-100 dark:border-white/5">
                                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">No. Invoice</th>
                                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Waktu Transaksi</th>
                                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Item</th>
                                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Pembayaran</th>
                                    <th class="px-6 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Total Bayar</th>
                                    <th class="px-6 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                @forelse($transactions as $transaction)
                                    <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-accent-500 group-hover:scale-150 transition-transform"></div>
                                                <span class="font-mono text-sm font-black text-navy-900 dark:text-white leading-none">#{{ $transaction->invoice_code }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-navy-900 dark:text-white">{{ $transaction->created_at->format('d M Y') }}</span>
                                                <span class="text-[10px] font-medium text-slate-500">{{ $transaction->created_at->format('H:i') }} WIB</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-800 text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                                {{ $transaction->items->sum('qty') }} Item
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter
                                                @if($transaction->payment_method === 'cash') bg-success/10 text-success border border-success/20
                                                @elseif($transaction->payment_method === 'qris') bg-blue-50/50 text-blue-600 border border-blue-200
                                                @elseif($transaction->payment_method === 'debit') bg-purple-100 dark:bg-purple-900/20 text-purple-600 border border-purple-200
                                                @else bg-warning/10 text-warning border border-warning/20 @endif">
                                                @if($transaction->payment_method === 'cash')
                                                    <i data-lucide="banknote" class="w-3 h-3"></i>
                                                @elseif($transaction->payment_method === 'qris')
                                                    <i data-lucide="qr-code" class="w-3 h-3"></i>
                                                @elseif($transaction->payment_method === 'debit')
                                                    <i data-lucide="credit-card" class="w-3 h-3"></i>
                                                @else
                                                    <i data-lucide="wallet" class="w-3 h-3"></i>
                                                @endif
                                                {{ ucfirst($transaction->payment_method) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right whitespace-nowrap">
                                            <span class="text-base font-black text-navy-900 dark:text-white">Rp{{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.transactions.show', $transaction) }}" 
                                                   class="p-2.5 rounded-xl bg-accent-50 dark:bg-accent-500/10 text-accent-500 hover:bg-accent-500 hover:text-white transition-all shadow-sm active:scale-95" 
                                                   title="Detail Invoice">
                                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-20 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-navy-800 flex items-center justify-center text-slate-200 dark:text-navy-700 mb-4 animate-bounce">
                                                    <i data-lucide="receipt" class="w-10 h-10"></i>
                                                </div>
                                                <h4 class="text-lg font-bold text-navy-900 dark:text-white mb-1">Belum Ada Transaksi</h4>
                                                <p class="text-sm text-slate-500">Pelanggan ini belum melakukan transaksi</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($transactions->hasPages())
                    <div class="px-6 py-5 border-t border-slate-100 dark:border-white/5 bg-slate-50/30 dark:bg-navy-900/30">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->total() }}</span> transaksi
                            </div>
                            
                            <div class="flex items-center gap-2">
                                @if($transactions->onFirstPage())
                                    <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                                        <span class="flex items-center gap-1.5">
                                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                            Previous
                                        </span>
                                    </button>
                                @else
                                    <a href="{{ $transactions->previousPageUrl() }}" 
                                       class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                                        <span class="flex items-center gap-1.5">
                                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                            Previous
                                        </span>
                                    </a>
                                @endif
                                
                                @if($transactions->hasMorePages())
                                    <a href="{{ $transactions->nextPageUrl() }}" 
                                       class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
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