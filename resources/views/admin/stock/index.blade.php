@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <!-- Animated Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent">
                            Manajemen Stok
                        </h1>
                        <span class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                            {{ $summary['total_products'] }} Produk
                        </span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">Pantau dan kelola inventori produk toko Anda</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('admin.stock.stock-in') }}" 
                       class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-success to-success/80 text-white rounded-xl font-medium shadow-lg shadow-success/30 hover:shadow-success/50 transition-all duration-300 hover:-translate-y-0.5">
                        <i data-lucide="plus-circle" class="w-5 h-5 group-hover:rotate-90 transition-transform"></i>
                        <span>Stok Masuk</span>
                    </a>
                    <a href="{{ route('admin.stock.history') }}" 
                       class="flex items-center gap-2 px-5 py-3 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-medium hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                        <i data-lucide="history" class="w-5 h-5"></i>
                        <span>Riwayat</span>
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/30">
                                <i data-lucide="package" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Produk</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $summary['total_products'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-warning/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning to-warning/80 flex items-center justify-center shadow-lg shadow-warning/30">
                                <i data-lucide="alert-triangle" class="w-6 h-6 text-white"></i>
                            </div>
                            @if($summary['low_stock'] > 0)
                                <span class="flex items-center gap-1 text-xs font-semibold text-warning bg-warning/10 px-2 py-1 rounded-full animate-pulse">
                                    <i data-lucide="bell" class="w-3 h-3"></i>
                                    Perhatian
                                </span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Stok Menipis</p>
                        <h3 class="text-2xl font-bold text-warning">{{ $summary['low_stock'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-danger/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-danger to-danger/80 flex items-center justify-center shadow-lg shadow-danger/30">
                                <i data-lucide="circle-x" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Stok Habis</p>
                        <h3 class="text-2xl font-bold text-danger">{{ $summary['out_of_stock'] }}</h3>
                    </div>
                </div>

                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-success/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center shadow-lg shadow-success/30">
                                <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Nilai Inventori</p>
                        <h3 class="text-2xl font-bold text-success">Rp {{ number_format($summary['total_value'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.5s;">
                <form action="{{ route('admin.stock.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-48">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    </div>
                    <select name="category" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="stock_status" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        <option value="">Semua Stok</option>
                        <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Stok Aman</option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                    <button type="submit" class="rounded-xl bg-accent-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-accent-600 transition-colors">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.stock.index') }}" class="rounded-xl border border-slate-200 dark:border-white/10 px-5 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Products Table -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.6s;">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-navy-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($products as $product)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-12 w-12 rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center">
                                                        <i data-lucide="package" class="h-6 w-6 text-slate-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-medium text-navy-900 dark:text-white">{{ $product->name }}</p>
                                                <p class="text-xs text-slate-500">{{ Str::limit($product->description, 30) ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-400">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm text-slate-600 dark:text-slate-300">{{ $product->sku }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-sm font-bold text-accent-600 dark:text-accent-400">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                            <p class="text-xs text-slate-500">Beli: Rp {{ number_format($product->buy_price, 0, ',', '.') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= $product->min_stock ? 'text-warning' : 'text-success') }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($product->stock <= 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-danger/10 text-danger">Habis</span>
                                        @elseif($product->stock <= $product->min_stock)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-warning/10 text-warning animate-pulse">Menipis</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success/10 text-success">Aman</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.products.show', $product) }}" 
                                               class="p-2 text-slate-400 hover:text-accent-500 transition-colors" title="Lihat Detail">
                                                <i data-lucide="eye" class="h-4 w-4"></i>
                                            </a>
                                            <a href="{{ route('admin.stock.adjust', $product) }}" 
                                               class="p-2 text-slate-400 hover:text-accent-500 transition-colors" title="Sesuaikan Stok">
                                                <i data-lucide="edit" class="h-4 w-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <i data-lucide="inbox" class="h-16 w-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                                        <p class="text-slate-500 dark:text-slate-400">Tidak ada data produk</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">
                        {{ $products->links() }}
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