@extends('layouts.app')

@section('page-title', 'Manajemen Ulasan')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between animate-fade-in-down">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                        <i data-lucide="star" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black gradient-text">Manajemen Ulasan</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Kelola ulasan dan rating produk</p>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up"
                style="animation-delay: 0.1s;">
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i data-lucide="message-square" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Total Ulasan</p>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['total'] }}</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/30">
                            <i data-lucide="star" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Rating Rata-rata</p>
                    <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['average'] }} / 5.0</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                            <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Ulasan Disetujui</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <i data-lucide="thumbs-up" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">5 Bintang</p>
                    <h3 class="text-2xl font-bold text-purple-600">{{ $stats['five_star'] }}</h3>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                style="animation-delay: 0.2s;">
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-6">Distribusi Rating</h3>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @for($i = 5; $i >= 1; $i--)
                        @php
                            $count = $stats[$i . '_star'] ?? 0;
                            $percentage = $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0;
                        @endphp
                        <div class="text-center p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <div class="flex items-center justify-center gap-1 mb-2">
                                @for($j = 1; $j <= $i; $j++)
                                    <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-yellow-400"></i>
                                @endfor
                            </div>
                            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $count }}</p>
                            <div class="w-full h-2 bg-slate-200 dark:bg-navy-700 rounded-full mt-2 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up"
                style="animation-delay: 0.3s;">
                <div class="p-6 border-b border-slate-200 dark:border-white/5">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white">Daftar Ulasan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-navy-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Pelanggan</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Produk</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase">Rating</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase">Komentar</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($reviews as $review)
                                <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($review->customer_name ?? $review->user?->name ?? 'A', 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 dark:text-white text-sm">
                                                    {{ $review->customer_name ?? $review->user?->name ?? 'Anonim' }}</p>
                                                <p class="text-xs text-slate-500">{{ $review->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-900 dark:text-white text-sm">
                                            {{ $review->product->name ?? 'Produk dihapus' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i data-lucide="star"
                                                    class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-slate-300' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2">
                                            {{ $review->comment ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($review->is_approved)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-green-100 text-green-700">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                                Disetujui
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black bg-slate-100 text-slate-600">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 rounded-xl {{ $review->is_approved ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white' : 'bg-green-50 text-green-600 hover:bg-green-500 hover:text-white' }} transition-all"
                                                    title="{{ $review->is_approved ? 'Tolak' : 'Setujui' }}">
                                                    <i data-lucide="{{ $review->is_approved ? 'thumbs-down' : 'thumbs-up' }}"
                                                        class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                                onsubmit="return confirm('Hapus ulasan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="message-square" class="w-16 h-16 text-slate-300 mb-4"></i>
                                            <p class="text-slate-500">Belum ada ulasan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($reviews->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-white/5">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();
            });
        </script>
    @endpush
@endsection