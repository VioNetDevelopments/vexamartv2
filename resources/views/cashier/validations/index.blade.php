@extends('layouts.app')

@section('page-title', 'Validasi Transaksi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-600 to-red-600 flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">
                                Validasi Transaksi
                            </h1>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Persetujuan pesanan dari customer</p>
                        </div>
                    </div>
                    @if($stats['pending'] > 0)
                        <span class="px-3 py-1 bg-orange-500/10 text-orange-600 dark:text-orange-400 text-xs font-semibold rounded-full flex items-center gap-1.5 animate-pulse">
                            <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                            {{ $stats['pending'] }} Pending
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/30">
                            <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Menunggu Persetujuan</p>
                    <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</h3>
                </div>
            </div>

            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-600 to-green-700 flex items-center justify-center shadow-lg shadow-green-500/30">
                            <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Disetujui Hari Ini</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ $stats['approved_today'] }}</h3>
                </div>
            </div>

            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-600 to-red-700 flex items-center justify-center shadow-lg shadow-red-500/30">
                            <i data-lucide="x-circle" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Ditolak Hari Ini</p>
                    <h3 class="text-2xl font-bold text-red-600">{{ $stats['rejected_today'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.4s;">
            <form action="{{ route('cashier.validations.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <div class="relative group">
                        <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-orange-500 transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>
                </div>
                <button type="submit" class="bg-orange-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition-all active:scale-95">
                    <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Cari
                </button>
            </form>
        </div>

        <!-- Validations List -->
        <div class="space-y-4">
            @forelse($validations as $validation)
                <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all animate-fade-in-up" style="animation-delay: {{ 0.1 * $loop->index }}s;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center">
                                <i data-lucide="shopping-bag" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <p class="font-mono font-bold text-slate-900 dark:text-white text-lg">{{ $validation->invoice_code }}</p>
                                <p class="text-sm text-slate-500">{{ $validation->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold animate-pulse">
                                Menunggu Persetujuan
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Pelanggan</p>
                            <p class="font-bold text-slate-900 dark:text-white">{{ $validation->customer?->name ?? 'Umum' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Total Item</p>
                            <p class="font-bold text-slate-900 dark:text-white">{{ $validation->items->sum('qty') }} item</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Total Pembayaran</p>
                            <p class="font-bold text-orange-600 text-lg">Rp {{ number_format($validation->grand_total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('cashier.validations.show', $validation) }}" class="px-6 py-3 border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all flex items-center gap-2">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                            <span>Lihat Detail</span>
                        </a>
                        <form action="{{ route('cashier.validations.approve', $validation) }}" method="POST" onsubmit="return confirm('Setujui transaksi ini?')">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-green-500/30 transition-all flex items-center gap-2">
                                <i data-lucide="check" class="w-5 h-5"></i>
                                <span>Terima</span>
                            </button>
                        </form>
                        <button onclick="document.getElementById('reject-{{ $validation->id }}').classList.remove('hidden')" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-red-500/30 transition-all flex items-center gap-2">
                            <i data-lucide="x" class="w-5 h-5"></i>
                            <span>Tolak</span>
                        </button>
                    </div>

                    <!-- Reject Form Modal -->
                    <div id="reject-{{ $validation->id }}" class="hidden mt-4 p-4 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800">
                        <form action="{{ route('cashier.validations.reject', $validation) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Alasan Penolakan</label>
                                <textarea name="rejection_reason" rows="3" required class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-red-500 focus:ring-4 focus:ring-red-500/20 dark:bg-navy-800 dark:text-white resize-none" placeholder="Masukkan alasan penolakan..."></textarea>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button" onclick="document.getElementById('reject-{{ $validation->id }}').classList.add('hidden')" class="px-4 py-2 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all">Konfirmasi Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white dark:bg-navy-900 rounded-3xl shadow-lg">
                    <i data-lucide="check-circle" class="w-20 h-20 text-green-500 mx-auto mb-6"></i>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Semua Transaksi Sudah Divalidasi</h3>
                    <p class="text-slate-500 dark:text-slate-400">Tidak ada transaksi yang menunggu persetujuan</p>
                </div>
            @endforelse
        </div>

        @if($validations->hasPages())
            <div class="flex justify-center">
                {{ $validations->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>
@endpush

@push('styles')
<style>
@keyframes fade-in-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes fade-in-down { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; opacity: 0; }
.animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }
</style>
@endpush
@endsection