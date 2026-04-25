@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.stock.index') }}" 
                   class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-navy-900 dark:text-white">
                        Riwayat Pergerakan Stok
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">Audit trail semua perubahan stok</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.1s;">
            <form action="{{ $product ? route('admin.stock.history.product', $product) : route('admin.stock.history') }}" method="GET" class="flex flex-wrap gap-4">
                @if($product)
                <input type="hidden" name="product" value="{{ $product->id }}">
                @endif
                <select name="product" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white min-w-48 transition-all">
                    <option value="">Semua Produk</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ (isset($product) && $product->id==$p->id)?'selected':'' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
                <select name="type" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    <option value="">Semua Tipe</option>
                    <option value="in" {{ request('type')=='in'?'selected':'' }}>Stok Masuk</option>
                    <option value="out" {{ request('type')=='out'?'selected':'' }}>Stok Keluar</option>
                    <option value="sale" {{ request('type')=='sale'?'selected':'' }}>Penjualan</option>
                    <option value="adjustment" {{ request('type')=='adjustment'?'selected':'' }}>Penyesuaian</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                <button type="submit" class="rounded-xl bg-accent-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-accent-600 transition-colors">
                    <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                </button>
            </form>
        </div>

        <!-- History Table -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-navy-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Produk</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Tipe</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Perubahan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Stok</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Alasan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($movements as $movement)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                {{ $movement->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-navy-900 dark:text-white">
                                {{ $movement->product->name }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                $typeColors = [
                                    'in' => 'bg-success/10 text-success',
                                    'out' => 'bg-danger/10 text-danger',
                                    'sale' => 'bg-accent/10 text-accent',
                                    'adjustment' => 'bg-warning/10 text-warning'
                                ];
                                $typeLabels = [
                                    'in' => 'Masuk',
                                    'out' => 'Keluar',
                                    'sale' => 'Penjualan',
                                    'adjustment' => 'Penyesuaian'
                                ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $typeColors[$movement->type] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $typeLabels[$movement->type] ?? ucfirst($movement->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold {{ $movement->qty > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                {{ $movement->stock_before }} → {{ $movement->stock_after }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                {{ $movement->reason }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ $movement->user->name }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <i data-lucide="inbox" class="h-16 w-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                                <p>Belum ada riwayat pergerakan stok</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($movements->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">
                {{ $movements->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>
@endpush
@endsection