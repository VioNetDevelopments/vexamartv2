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
                            Manajemen Produk
                        </h1>
                        <span class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                            {{ $products->total() }} Produk
                        </span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">Kelola katalog produk toko Anda</p>
                </div>

                <a href="{{ route('admin.products.create') }}" 
                   class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-medium shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-300 hover:-translate-y-0.5">
                    <i data-lucide="plus" class="w-5 h-5 group-hover:rotate-90 transition-transform"></i>
                    <span>Tambah Produk</span>
                </a>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="animate-fade-in-up rounded-2xl bg-success/10 border border-success/20 p-4 flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-5 w-5 text-success"></i>
                    <span class="text-success font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters & Search -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.1s;">
                <form action="{{ route('admin.products.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cari Produk</label>
                            <div class="relative">
                                <i data-lucide="search" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Nama, SKU, atau Barcode..."
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Kategori</label>
                            <select name="category" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Stock Status -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status Stok</label>
                            <select name="stock_status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                                <option value="">Semua</option>
                                <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Stok Aman</option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-accent-500 text-white rounded-xl text-sm font-medium hover:bg-accent-600 transition-colors shadow-lg shadow-accent-500/30">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-medium hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                            Reset
                        </a>
                        <span class="ml-auto text-sm text-slate-500 dark:text-slate-400">
                            Menampilkan {{ $products->count() }} dari {{ $products->total() }} produk
                        </span>
                    </div>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="group bg-white dark:bg-navy-900 rounded-2xl overflow-hidden shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl hover:shadow-accent-500/10 transition-all duration-500 hover:-translate-y-2 animate-fade-in-up" style="animation-delay: {{ 0.1 + $loop->index * 0.05 }}s;">
                        <!-- Product Image -->
                        <div class="relative h-48 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-navy-800 dark:to-navy-700 overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i data-lucide="package" class="w-16 h-16 text-slate-300 dark:text-slate-600"></i>
                                </div>
                            @endif

                            <!-- Stock Badge -->
                            <div class="absolute top-3 right-3">
                                @if($product->stock <= 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-danger text-white shadow-lg">
                                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                        Habis
                                    </span>
                                @elseif($product->stock <= $product->min_stock)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-warning text-white shadow-lg animate-pulse">
                                        <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                        Menipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-success text-white shadow-lg">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        {{ $product->stock }}
                                    </span>
                                @endif
                            </div>

                            <!-- Quick Actions Overlay -->
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                                <a href="{{ route('admin.products.show', $product) }}" 
                                   class="p-2.5 bg-white/90 dark:bg-navy-800/90 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-accent-500 hover:text-white transition-colors" title="Lihat Detail">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="p-2.5 bg-white/90 dark:bg-navy-800/90 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-accent-500 hover:text-white transition-colors" title="Edit">
                                    <i data-lucide="edit" class="w-5 h-5"></i>
                                </a>
                                <button onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" 
                                        class="p-2.5 bg-white/90 dark:bg-navy-800/90 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-danger hover:text-white transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-5 space-y-3">
                            <!-- Category -->
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-400">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>

                            <!-- Name -->
                            <h3 class="font-semibold text-navy-900 dark:text-white line-clamp-2 min-h-[2.5rem]">
                                {{ $product->name }}
                            </h3>

                            <!-- SKU & Barcode -->
                            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                <span class="font-mono bg-slate-100 dark:bg-navy-800 px-2 py-1 rounded">{{ $product->sku }}</span>
                            </div>

                            <!-- Price -->
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Harga Jual</p>
                                    <p class="text-lg font-bold text-accent-600 dark:text-accent-400">
                                        Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Harga Beli</p>
                                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 line-through">
                                        Rp {{ number_format($product->buy_price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Profit Badge -->
                            @if($product->sell_price > $product->buy_price)
                                <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-white/10">
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Profit:</span>
                                    <span class="text-xs font-semibold text-success bg-success/10 px-2 py-1 rounded-full">
                                        +Rp {{ number_format($product->sell_price - $product->buy_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-3">
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-accent-500 text-white rounded-xl text-sm font-medium hover:bg-accent-600 transition-colors">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-medium hover:bg-danger hover:text-white hover:border-danger transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <i data-lucide="inbox" class="w-20 h-20 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                        <h3 class="text-lg font-semibold text-slate-700 dark:text-slate-300 mb-2">Tidak Ada Produk</h3>
                        <p class="text-slate-500 dark:text-slate-400 mb-6">Mulai tambahkan produk pertama Anda</p>
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-accent-500 text-white rounded-xl font-medium hover:bg-accent-600 transition-colors">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                            <span>Tambah Produk</span>
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="flex items-center justify-between pt-6">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                    </p>
                    <div class="flex items-center gap-2">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" x-data="{ open: false, productId: null, productName: '' }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative bg-white dark:bg-navy-900 rounded-2xl p-6 max-w-md w-full shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-danger/10 mb-4">
                    <i data-lucide="alert-triangle" class="h-7 w-7 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-2">Hapus Produk?</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-6">
                    Apakah Anda yakin ingin menghapus <strong class="text-navy-900 dark:text-white" x-text="productName"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3 justify-center">
                    <button @click="open = false" 
                            class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                        Batal
                    </button>
                    <form :action="'{{ route('admin.products.index') }}/' + productId" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-danger text-white hover:bg-red-700 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
        function confirmDelete(productId, productName) {
            const modal = document.getElementById('deleteModal');
            const alpineData = Alpine.$data(modal);
            alpineData.productId = productId;
            alpineData.productName = productName;
            alpineData.open = true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
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

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.6s ease-out forwards;
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

        /* Custom Pagination */
        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.75rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .pagination li a {
            background: white;
            border: 1px solid #E2E8F0;
            color: #0F172A;
        }

        .dark .pagination li a {
            background: #1E293B;
            border-color: rgba(255,255,255,0.1);
            color: #F8FAFC;
        }

        .pagination li a:hover {
            background: #2563EB;
            border-color: #2563EB;
            color: white;
        }

        .pagination li.active span {
            background: #2563EB;
            border-color: #2563EB;
            color: white;
        }

        .pagination li.disabled span {
            opacity: 0.5;
            cursor: not-allowed;
        }
        </style>
    @endpush
@endsection