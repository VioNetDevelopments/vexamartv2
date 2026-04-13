@extends('layouts.app')

@section('content')
<div class="h-full flex flex-col space-y-6 max-w-4xl mx-auto w-full">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white flex items-center gap-2">
                <i data-lucide="wallet" class="w-6 h-6 text-accent-500"></i>
                Manajemen Kas
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Kelola arus kas keluar masuk laci kasir.</p>
        </div>
        <a href="{{ route('cashier.pos') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-navy-800 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-navy-700 transition-colors shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke POS</span>
        </a>
    </div>

    @if(!$shift)
        <!-- No Active Shift State -->
        <div class="flex-1 bg-white dark:bg-navy-900 rounded-2xl shadow-lg border border-slate-200 dark:border-white/10 flex items-center justify-center">
            <div class="text-center py-12 px-6 max-w-sm">
                <div class="w-20 h-20 bg-warning/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="lock" class="w-10 h-10 text-warning"></i>
                </div>
                <h2 class="text-2xl font-bold text-navy-900 dark:text-white mb-2">Shift Belum Dibuka</h2>
                <p class="text-slate-500 dark:text-slate-400 mb-6">Anda harus membuka shift terlebih dahulu melalui halaman POS sebelum bisa mengelola kas.</p>
                <a href="{{ route('cashier.pos') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-accent-500 text-white font-medium hover:bg-accent-600 transition-colors shadow-lg shadow-accent-500/25">
                    Kembali ke POS
                </a>
            </div>
        </div>
    @else
        <!-- Active Shift Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Shift Info Card -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg border border-slate-200 dark:border-white/10 overflow-hidden col-span-1">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100 dark:border-white/5">
                        <h3 class="font-bold text-navy-900 dark:text-white text-lg">Informasi Shift</h3>
                        <span class="px-2 py-1 rounded-md text-xs font-semibold bg-success/10 text-success border border-success/20">
                            Aktif
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Saldo Awal</p>
                            <p class="text-lg font-bold text-navy-900 dark:text-white">Rp {{ number_format($shift->opening_balance, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 dark:bg-navy-800 p-3 rounded-lg border border-slate-100 dark:border-white/5">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Total Kas Masuk</span>
                            <span class="text-sm font-bold text-success">+ Rp {{ number_format($shift->cash_in, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 dark:bg-navy-800 p-3 rounded-lg border border-slate-100 dark:border-white/5">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Total Kas Keluar</span>
                            <span class="text-sm font-bold text-danger">- Rp {{ number_format($shift->cash_out, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Card (Cash In/Out Form) -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg border border-slate-200 dark:border-white/10 overflow-hidden col-span-1 md:col-span-2 flex flex-col">
                <div class="p-5 border-b border-slate-200 dark:border-white/10">
                    <h3 class="font-bold text-navy-900 dark:text-white">Catat Arus Kas</h3>
                </div>
                <!-- Since we have a modal for this in POS, we can re-implement the form here or use Alpine component later -->
                <div class="p-6 flex-1 flex flex-col items-center justify-center text-center">
                    <i data-lucide="wallet" class="w-16 h-16 text-slate-300 mb-4"></i>
                    <p class="text-slate-500 mb-6">Fitur input Kas Masuk / Keluar dapat diakses melalui Form Modal di halaman POS.</p>
                    <a href="{{ route('cashier.pos') }}" class="px-6 py-2.5 rounded-xl border border-accent-500 text-accent-600 hover:bg-accent-50 dark:hover:bg-accent-500/10 transition-colors font-medium text-sm">
                        Catat di POS
                    </a>
                </div>
            </div>
        </div>

        <!-- History Log -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg border border-slate-200 dark:border-white/10 overflow-hidden flex-1 flex flex-col mt-6">
            <div class="p-5 border-b border-slate-200 dark:border-white/10">
                <h3 class="font-bold text-navy-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="list" class="w-5 h-5 text-accent-500"></i>
                    Riwayat Kas Hari Ini
                </h3>
            </div>
            <div class="flex-1 overflow-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50 dark:bg-navy-800/50 text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-medium w-32">Waktu</th>
                            <th class="px-6 py-4 font-medium w-32">Tipe</th>
                            <th class="px-6 py-4 font-medium">Jumlah</th>
                            <th class="px-6 py-4 font-medium w-full">Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($cashLogs as $log)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-navy-800/50 transition-colors">
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $log->created_at->format('H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->type === 'in')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-success/10 text-success text-xs font-medium border border-success/20">
                                            <i data-lucide="arrow-down-left" class="w-3 h-3"></i> Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-danger/10 text-danger text-xs font-medium border border-danger/20">
                                            <i data-lucide="arrow-up-right" class="w-3 h-3"></i> Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold {{ $log->type === 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $log->type === 'in' ? '+' : '-' }} Rp {{ number_format($log->amount, 0, ',', '.') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-navy-900 dark:text-white text-wrap min-w-[200px]">
                                    {{ $log->reason }}
                                    @if($log->notes)
                                        <div class="text-xs text-slate-500 mt-1">{{ $log->notes }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                                    <i data-lucide="activity" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                                    Belum ada aktivitas kas untuk shift ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
