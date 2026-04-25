@extends('layouts.customer')

@section('title', 'Notifikasi')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <!-- Animated Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                <i data-lucide="bell" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">
                                    Notifikasi
                                </h1>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pantau semua aktivitas akun Anda</p>
                            </div>
                        </div>
                        @if($unreadCount > 0)
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-semibold rounded-full flex items-center gap-1.5 animate-pulse">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                {{ $unreadCount }} Baru
                            </span>
                        @endif
                    </div>
                </div>

                @if($unreadCount > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" 
                               class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300 hover:-translate-y-0.5">
                            <i data-lucide="check-check" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                            <span>Tandai Semua Dibaca</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i data-lucide="bell" class="w-6 h-6 text-white"></i>
                            </div>
                            @if($stats['unread'] > 0)
                                <span class="flex items-center gap-1 text-xs font-semibold text-blue-600 bg-blue-500/10 px-2 py-1 rounded-full animate-pulse">
                                    <i data-lucide="dot" class="w-3 h-3"></i>
                                    Baru
                                </span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Notifikasi</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $stats['total'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                                <i data-lucide="mail-open" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Sudah Dibaca</p>
                        <h3 class="text-2xl font-bold text-green-600">{{ $stats['read'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-500/30">
                                <i data-lucide="mail" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Belum Dibaca</p>
                        <h3 class="text-2xl font-bold text-red-600">{{ $stats['unread'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i data-lucide="shopping-bag" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Pesanan</p>
                        <h3 class="text-2xl font-bold text-purple-600">{{ $stats['orders'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up overflow-visible relative z-20" style="animation-delay: 0.5s;">
                <form action="{{ route('notifications.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <!-- Search Input -->
                    <div class="flex-1 min-w-64">
                        <div class="relative group">
                            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari notifikasi..."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        </div>
                    </div>

                    <!-- Type Dropdown -->
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[180px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-blue-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span id="typeText">
                                @php
                                    $currentType = request('type');
                                    $mapping = [
                                        'new_transaction' => 'Transaksi Baru',
                                        'low_stock' => 'Stok Menipis',
                                        'out_of_stock' => 'Stok Habis',
                                        'price_updated' => 'Update Harga',
                                    ];
                                    echo $mapping[$currentType] ?? 'Semua Tipe';
                                @endphp
                            </span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                             style="top: 100%;">
                            <button type="button" @click="selectType('', 'Semua Tipe'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ !request('type') ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Semua Tipe
                            </button>
                            <button type="button" @click="selectType('new_transaction', 'Transaksi Baru'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('type') == 'new_transaction' ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                <i data-lucide="shopping-bag" class="inline w-3 h-3 mr-2"></i>Transaksi Baru
                            </button>
                            <button type="button" @click="selectType('low_stock', 'Stok Menipis'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('type') == 'low_stock' ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                <i data-lucide="alert-triangle" class="inline w-3 h-3 mr-2"></i>Stok Menipis
                            </button>
                            <button type="button" @click="selectType('out_of_stock', 'Stok Habis'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('type') == 'out_of_stock' ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                <i data-lucide="zap-off" class="inline w-3 h-3 mr-2"></i>Stok Habis
                            </button>
                            <button type="button" @click="selectType('price_updated', 'Update Harga'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('type') == 'price_updated' ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                <i data-lucide="tag" class="inline w-3 h-3 mr-2"></i>Update Harga
                            </button>
                        </div>
                        <input type="hidden" name="type" id="typeInput" value="{{ request('type') }}">
                    </div>

                    <!-- Status Dropdown -->
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[160px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-blue-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span id="statusText">{{ request('status') ? (request('status') == 'unread' ? 'Belum Dibaca' : 'Sudah Dibaca') : 'Semua Status' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                             style="top: 100%;">
                            <button type="button" @click="selectStatus('', 'Semua Status'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ !request('status') ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Semua Status
                            </button>
                            <button type="button" @click="selectStatus('unread', 'Belum Dibaca'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('status') == 'unread' ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Belum Dibaca
                            </button>
                            <button type="button" @click="selectStatus('read', 'Sudah Dibaca'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 {{ request('status') == 'read' ? 'bg-blue-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Sudah Dibaca
                            </button>
                        </div>
                        <input type="hidden" name="status" id="statusInput" value="{{ request('status') }}">
                    </div>

                    <button type="submit" class="bg-blue-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-blue-600 shadow-lg shadow-blue-500/30 transition-all active:scale-95">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <a href="{{ route('notifications.index') }}" class="flex items-center justify-center px-4 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                </form>
            </div>

            <!-- Notifications Table -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.6s;">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-blue-500/10 to-purple-500/10 dark:from-blue-900/20 dark:to-purple-900/20">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Notifikasi</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Tipe</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Waktu</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($notifications as $notification)
                                <tr class="group hover:bg-blue-50/50 dark:hover:bg-blue-900/5 transition-all {{ is_null($notification->read_at) ? 'bg-blue-50/30 dark:bg-blue-900/10' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            @php
                                                $iconConfig = [
                                                    'new_transaction' => ['icon' => 'shopping-bag', 'color' => 'bg-green-100 text-green-600'],
                                                    'payment_received' => ['icon' => 'check-circle', 'color' => 'bg-emerald-100 text-emerald-600'],
                                                    'low_stock' => ['icon' => 'alert-triangle', 'color' => 'bg-amber-100 text-amber-600'],
                                                    'out_of_stock' => ['icon' => 'zap-off', 'color' => 'bg-red-100 text-red-600'],
                                                    'restock' => ['icon' => 'package-plus', 'color' => 'bg-blue-100 text-blue-600'],
                                                    'customer_registered' => ['icon' => 'user-plus', 'color' => 'bg-purple-100 text-purple-600'],
                                                    'price_updated' => ['icon' => 'tag', 'color' => 'bg-cyan-100 text-cyan-600'],
                                                    'stock_adjustment' => ['icon' => 'settings', 'color' => 'bg-slate-100 text-slate-600'],
                                                    'product_deleted' => ['icon' => 'trash-2', 'color' => 'bg-rose-100 text-rose-600'],
                                                ];
                                                $config = $iconConfig[$notification->type] ?? ['icon' => 'bell', 'color' => 'bg-blue-100 text-blue-600'];
                                            @endphp
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $config['color'] }} shadow-sm">
                                                <i data-lucide="{{ $config['icon'] }}" class="w-6 h-6"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 dark:text-white tracking-tight">{{ $notification->title }}</p>
                                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">{{ $notification->message }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $typeLabels = [
                                                'new_transaction' => 'Transaksi Baru',
                                                'payment_received' => 'Pembayaran',
                                                'low_stock' => 'Stok Menipis',
                                                'out_of_stock' => 'Stok Habis',
                                                'restock' => 'Stok Masuk',
                                                'customer_registered' => 'Pelanggan Baru',
                                                'price_updated' => 'Update Harga',
                                                'stock_adjustment' => 'Edit Stok',
                                                'product_deleted' => 'Hapus Produk',
                                            ];
                                            $typeStyles = [
                                                'new_transaction' => 'bg-green-500/10 text-green-700',
                                                'payment_received' => 'bg-emerald-500/10 text-emerald-700',
                                                'low_stock' => 'bg-amber-500/10 text-amber-700',
                                                'out_of_stock' => 'bg-red-500/10 text-red-700',
                                                'restock' => 'bg-blue-500/10 text-blue-700',
                                                'customer_registered' => 'bg-purple-500/10 text-purple-700',
                                                'price_updated' => 'bg-cyan-500/10 text-cyan-700',
                                                'stock_adjustment' => 'bg-slate-500/10 text-slate-700',
                                                'product_deleted' => 'bg-rose-500/10 text-rose-700',
                                            ];
                                            $label = $typeLabels[$notification->type] ?? ucfirst(str_replace('_', ' ', $notification->type));
                                            $style = $typeStyles[$notification->type] ?? 'bg-slate-100 text-slate-700';
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $style }}">
                                            <i data-lucide="{{ $config['icon'] }}" class="w-3 h-3"></i>
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $notification->created_at->format('d M Y') }}</span>
                                            <span class="text-xs text-slate-500">{{ $notification->created_at->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(is_null($notification->read_at))
                                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-blue-100 text-blue-700 animate-pulse">
                                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                                Baru
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-slate-100 text-slate-600">
                                                <i data-lucide="check" class="w-3 h-3"></i>
                                                Dibaca
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            @if(isset($notification->data['url']) && $notification->data['url'])
                                                <a href="{{ $notification->data['url'] }}" 
                                                   class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-500 hover:text-white transition-all" title="Lihat Detail">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </a>
                                            @endif

                                            @if(is_null($notification->read_at))
                                                <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-2.5 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 hover:bg-green-500 hover:text-white transition-all" title="Tandai Dibaca">
                                                        <i data-lucide="check" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" @click="notify.confirm('Mau dihapus aja nih notifikasinya?', () => $el.closest('form').submit())" class="p-2.5 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-500 hover:text-white transition-all" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i data-lucide="bell-off" class="w-16 h-16 text-slate-300 mb-4"></i>
                                            <p class="text-slate-500 font-medium">Tidak ada notifikasi</p>
                                            <p class="text-slate-400 text-sm mt-1">Coba ubah filter pencarian</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($notifications->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-white/5">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $notifications->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $notifications->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $notifications->total() }}</span> notifikasi
                            </div>

                            <div class="flex items-center gap-2">
                                @if($notifications->onFirstPage())
                                    <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                                        <span class="flex items-center gap-1.5">
                                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                            Previous
                                        </span>
                                    </button>
                                @else
                                    <a href="{{ $notifications->previousPageUrl() }}"
                                       class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-blue-500 hover:text-white hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/30 transition-all text-sm font-bold">
                                        <span class="flex items-center gap-1.5">
                                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                            Previous
                                        </span>
                                    </a>
                                @endif

                                @if($notifications->hasMorePages())
                                    <a href="{{ $notifications->nextPageUrl() }}"
                                       class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-blue-500 hover:text-white hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/30 transition-all text-sm font-bold">
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

    @push('scripts')
        <script>
        function selectType(value, text) {
            document.getElementById('typeInput').value = value;
            document.getElementById('typeText').textContent = text;
            document.querySelector('form').submit();
        }

        function selectStatus(value, text) {
            document.getElementById('statusInput').value = value;
            document.getElementById('statusText').textContent = text;
            document.querySelector('form').submit();
        }

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
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        </style>
    @endpush
@endsection