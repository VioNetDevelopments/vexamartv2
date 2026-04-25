@extends('layouts.app')

@section('page-title', 'Pengembalian Barang')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-600 to-purple-700 flex items-center justify-center shadow-lg shadow-purple-500/20">
                    <i data-lucide="rotate-ccw" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-navy-900 dark:text-white">Pengembalian Barang</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Kelola retur produk</p>
                </div>
            </div>
        </div>

        <!-- Returns Table -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-purple-500/10 to-pink-500/10 dark:from-purple-900/20 dark:to-pink-900/20">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Kode Retur</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Invoice</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Produk</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Jumlah</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($returns as $return)
                            <tr class="group hover:bg-purple-50/50 dark:hover:bg-purple-900/5 transition-all">
                                <td class="px-6 py-4">
                                    <p class="font-mono font-bold text-slate-900 dark:text-white">{{ $return->return_code }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $return->transaction->invoice_code }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $return->product->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $return->quantity }} pcs
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-purple-600">Rp {{ number_format($return->total, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $return->created_at->format('d M Y, H:i') }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('cashier.returns.print', $return) }}" target="_blank" class="p-2.5 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 hover:bg-purple-500 hover:text-white transition-all">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="rotate-ccw" class="w-16 h-16 text-slate-300 mb-4"></i>
                                        <p class="text-slate-500">Belum ada retur</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($returns->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-white/5">
                    {{ $returns->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>
@endpush
@endsection