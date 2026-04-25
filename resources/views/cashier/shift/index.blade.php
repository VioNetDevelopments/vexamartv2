@extends('layouts.app')

@section('page-title', 'Shift Kasir')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-5xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between animate-fade-in-down">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                        <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-navy-900 dark:text-white">Shift Kasir</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Kelola shift kerja Anda</p>
                    </div>
                </div>
            </div>

            <!-- Active Shift -->
            @if($activeShift)
                <div
                    class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl shadow-2xl shadow-green-500/30 p-8 text-white animate-fade-in-up">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <i data-lucide="activity" class="w-8 h-8 animate-pulse"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black mb-1">Shift Sedang Berjalan</h2>
                                <p class="text-green-100">Dimulai: {{ $activeShift->started_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-green-100 mb-1">Modal Awal</p>
                            <p class="text-3xl font-black">Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <p class="text-sm text-green-100 mb-1">Total Transaksi</p>
                            <p class="text-2xl font-bold">{{ $activeShift->transactions->count() }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <p class="text-sm text-green-100 mb-1">Penjualan Tunai</p>
                            <p class="text-2xl font-bold">Rp
                                {{ number_format($activeShift->transactions->where('payment_method', 'cash')->sum('grand_total'), 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                            <p class="text-sm text-green-100 mb-1">Durasi</p>
                            <p class="text-2xl font-bold">{{ $activeShift->started_at->diffForHumans(now(), ['parts' => 2]) }}
                            </p>
                        </div>
                    </div>

                    <!-- Close Shift Button -->
                    <button onclick="document.getElementById('closeShiftModal').classList.remove('hidden')"
                        class="w-full py-4 bg-white text-green-600 rounded-2xl font-bold text-lg hover:shadow-xl transition-all flex items-center justify-center gap-3">
                        <i data-lucide="lock" class="w-6 h-6"></i>
                        <span>Tutup Shift</span>
                    </button>
                </div>

                @push('modals')
                    <!-- Close Shift Modal -->
                    <div id="closeShiftModal"
                        class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-md">
                        <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-2xl p-8 max-w-2xl w-full animate-fade-in-up">
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Tutup Shift</h3>

                            <form action="{{ route('cashier.shift.close', $activeShift) }}" method="POST" class="space-y-6">
                                @csrf

                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Modal
                                            Awal</label>
                                        <input type="text" value="Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}"
                                            readonly
                                            class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-bold bg-slate-100 dark:bg-navy-800">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Expected
                                            Cash</label>
                                        <input type="text"
                                            value="Rp {{ number_format($activeShift->starting_cash + $activeShift->transactions->where('payment_method', 'cash')->sum('grand_total'), 0, ',', '.') }}"
                                            readonly
                                            class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-bold bg-slate-100 dark:bg-navy-800">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kas Aktual (Hasil
                                        Penghitungan) <span class="text-danger">*</span></label>
                                    <input type="number" name="actual_cash" required min="0" step="0.01"
                                        class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-bold focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 dark:bg-navy-800 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Setor Tunai <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="cash_deposit" required min="0" step="0.01"
                                        class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-bold focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 dark:bg-navy-800 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Catatan
                                        Penutup</label>
                                    <textarea name="closing_notes" rows="3"
                                        class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 dark:bg-navy-800 dark:text-white resize-none"></textarea>
                                </div>

                                <div class="flex gap-4">
                                    <button type="button"
                                        onclick="document.getElementById('closeShiftModal').classList.add('hidden')"
                                        class="flex-1 py-4 border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-2xl font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="flex-1 py-4 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-2xl font-bold hover:shadow-lg hover:shadow-orange-500/30 transition-all">
                                        Tutup Shift
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endpush
            @else
                <!-- Open Shift Form -->
                <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-2xl p-8 animate-fade-in-up">
                    <div class="text-center mb-8">
                        <div
                            class="w-20 h-20 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-green-500/30">
                            <i data-lucide="play" class="w-10 h-10 text-white"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Mulai Shift Baru</h3>
                        <p class="text-slate-500 dark:text-slate-400">Masukkan modal awal kasir</p>
                    </div>

                    <form action="{{ route('cashier.shift.open') }}" method="POST" class="max-w-md mx-auto">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Modal Awal (Cash in
                                Hand) <span class="text-danger">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                                <input type="number" name="starting_cash" required min="0" step="0.01"
                                    value="{{ old('starting_cash', 0) }}"
                                    class="w-full rounded-xl border border-slate-200 dark:border-white/10 pl-12 pr-4 py-4 text-lg font-bold focus:border-green-500 focus:ring-4 focus:ring-green-500/20 dark:bg-navy-800 dark:text-white">
                            </div>
                            @error('starting_cash')
                                <p class="mt-2 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl font-bold text-lg hover:shadow-lg hover:shadow-green-500/30 transition-all flex items-center justify-center gap-3">
                            <i data-lucide="play" class="w-6 h-6"></i>
                            <span>Buka Shift</span>
                        </button>
                    </form>
                </div>
            @endif

            <!-- Recent Shifts -->
            @if($recentShifts->count() > 0)
                <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-6 animate-fade-in-up"
                    style="animation-delay: 0.2s;">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-6">Riwayat Shift</h3>
                    <div class="space-y-4">
                        @foreach($recentShifts as $shift)
                            <div
                                class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-navy-800 hover:bg-slate-100 dark:hover:bg-navy-700 transition-all">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-slate-200 dark:bg-navy-600 flex items-center justify-center">
                                        <i data-lucide="clock" class="w-6 h-6 text-slate-600 dark:text-slate-400"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white">
                                            {{ $shift->started_at->format('d M Y') }}</p>
                                        <p class="text-sm text-slate-500">{{ $shift->started_at->format('H:i') }} -
                                            {{ $shift->ended_at?->format('H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <p class="text-xs text-slate-500 mb-1">Total Transaksi</p>
                                        <p class="font-bold text-slate-900 dark:text-white">{{ $shift->transactions->count() }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-slate-500 mb-1">Selisih Kas</p>
                                        <p class="font-bold {{ $shift->cash_shortage >= 0 ? 'text-green-600' : 'text-danger' }}">
                                            Rp {{ number_format($shift->cash_shortage, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <a href="{{ route('cashier.shift.report', $shift) }}"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all">
                                        Lihat Laporan
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () { lucide.createIcons(); });
        </script>
    @endpush

    @push('styles')
        <style>
            @keyframes fade-in-up {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fade-in-down {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fade-in-up 0.6s ease-out forwards;
                opacity: 0;
            }

            .animate-fade-in-down {
                animation: fade-in-down 0.6s ease-out forwards;
            }
        </style>
    @endpush
@endsection