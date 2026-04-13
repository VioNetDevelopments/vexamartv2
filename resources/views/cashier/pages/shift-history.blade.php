@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col space-y-6 max-w-4xl mx-auto w-full">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Riwayat Shift</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Lihat riwayat pembukaan dan penutupan kasir</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.location.reload()" class="p-2.5 rounded-xl bg-white dark:bg-navy-800 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-navy-700 transition-all">
                <i data-lucide="rotate-cw" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Shift History Table -->
    <div class="bg-white dark:bg-navy-800 rounded-2xl border border-slate-200 dark:border-white/10 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-navy-900/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Awal</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Akhir</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Selisih</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                    @forelse($shifts as $shift)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-navy-900/50 transition-colors">
                        <td class="px-6 py-4 text-sm">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 dark:text-white uppercase">{{ $shift->status }}</span>
                                <span class="text-xs text-slate-500">{{ $shift->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $shift->closing_cash ? 'Rp '.number_format($shift->closing_cash, 0, ',', '.') : '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($shift->status === 'closed')
                                @php
                                    $diff = $shift->closing_cash - ($shift->starting_cash + $shift->transactions_sum_grand_total);
                                @endphp
                                <span class="text-sm font-medium {{ $diff >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                    {{ $diff >= 0 ? '+' : '' }}Rp {{ number_format($diff, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-navy-700 text-slate-400 dark:text-slate-500 hover:text-accent-500 transition-colors">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-2xl bg-slate-50 dark:bg-navy-900 flex items-center justify-center mb-4">
                                    <i data-lucide="clock" class="w-8 h-8 text-slate-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Tidak Ada Riwayat</h3>
                                <p class="text-slate-500">Riwayat shift Anda akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($shifts->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-navy-900/50">
            {{ $shifts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
