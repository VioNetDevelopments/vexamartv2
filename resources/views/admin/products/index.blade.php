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
            <div class="relative z-30 bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.1s;">
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
                        <div x-data="{ 
                            open: false, 
                            selected: '{{ request('category') }}',
                            selectedLabel: '{{ $categories->where('id', request('category'))->first()->name ?? 'Semua Kategori' }}'
                        }" class="relative">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kategori</label>
                            
                            <!-- Hidden Input for Form -->
                            <input type="hidden" name="category" :value="selected">
                            
                            <!-- Dropdown Trigger -->
                            <button type="button" 
                                    @click="open = !open" 
                                    @click.away="open = false"
                                    class="w-full flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium transition-all focus:border-accent-500 focus:ring-2 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white group">
                                <span x-text="selectedLabel"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-1"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-1"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                 class="absolute z-[60] mt-1 w-full min-w-[220px] rounded-2xl bg-white/95 p-2 shadow-[0_20px_50px_rgba(0,0,0,0.2)] backdrop-blur-xl border border-white/20 dark:bg-navy-900/95 dark:border-white/5">
                                
                                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                    <button type="button" 
                                            @click="selected = ''; selectedLabel = 'Semua Kategori'; open = false; $nextTick(() => $el.closest('form').submit())"
                                            class="w-full text-left px-4 py-2 text-sm rounded-xl transition-colors"
                                            :class="selected == '' ? 'bg-accent-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                        Semua Kategori
                                    </button>
                                    @foreach($categories as $cat)
                                        <button type="button" 
                                                @click="selected = '{{ $cat->id }}'; selectedLabel = '{{ $cat->name }}'; open = false; $nextTick(() => $el.closest('form').submit())"
                                                class="w-full text-left px-4 py-2 text-sm rounded-xl transition-colors"
                                                :class="selected == '{{ $cat->id }}' ? 'bg-accent-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                            {{ $cat->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Stock Status -->
                        <div x-data="{ 
                            open: false, 
                            selected: '{{ request('stock_status') }}',
                            get selectedLabel() {
                                switch(this.selected) {
                                    case 'available': return 'Stok Aman';
                                    case 'low': return 'Stok Menipis';
                                    case 'out': return 'Stok Habis';
                                    default: return 'Semua Status';
                                }
                            }
                        }" class="relative">
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Status Stok</label>
                            
                            <!-- Hidden Input for Form -->
                            <input type="hidden" name="stock_status" :value="selected">
                            
                            <!-- Dropdown Trigger -->
                            <button type="button" 
                                    @click="open = !open" 
                                    @click.away="open = false"
                                    class="w-full flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium transition-all focus:border-accent-500 focus:ring-2 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white group">
                                <span x-text="selectedLabel"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-1"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-1"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                 class="absolute z-[60] mt-1 w-full min-w-[220px] rounded-2xl bg-white/95 p-2 shadow-[0_20px_50px_rgba(0,0,0,0.2)] backdrop-blur-xl border border-white/20 dark:bg-navy-900/95 dark:border-white/5">
                                
                                <div class="space-y-1">
                                    <button type="button" @click="selected = ''; open = false; $nextTick(() => $el.closest('form').submit())"
                                            class="w-full text-left px-4 py-2 text-sm rounded-xl transition-colors"
                                            :class="selected == '' ? 'bg-accent-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                        Semua Status
                                    </button>
                                    <button type="button" @click="selected = 'available'; open = false; $nextTick(() => $el.closest('form').submit())"
                                            class="w-full text-left px-4 py-2 text-sm rounded-xl transition-colors"
                                            :class="selected == 'available' ? 'bg-success text-white font-bold shadow-lg shadow-success/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                        Stok Aman
                                    </button>
                                    <button type="button" @click="selected = 'low'; open = false; $nextTick(() => $el.closest('form').submit())"
                                            class="w-full text-left px-4 py-2 text-sm rounded-xl transition-colors"
                                            :class="selected == 'low' ? 'bg-warning text-white font-bold shadow-lg shadow-warning/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                        Stok Menipis
                                    </button>
                                    <button type="button" @click="selected = 'out'; open = false; $nextTick(() => $el.closest('form').submit())"
                                            class="w-full text-left px-4 py-2 text-sm rounded-xl transition-colors"
                                            :class="selected == 'out' ? 'bg-danger text-white font-bold shadow-lg shadow-danger/20' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                        Stok Habis
                                    </button>
                                </div>
                            </div>
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
                        <div class="relative h-40 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-navy-800 dark:to-navy-700 overflow-hidden">
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
                        <div class="p-4 space-y-2.5">
                            <!-- Name & Category -->
                            <div class="space-y-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-100 dark:bg-navy-800 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>
                                <h3 class="font-bold text-navy-900 dark:text-white line-clamp-1">
                                    {{ $product->name }}
                                </h3>
                            </div>

                            <!-- Price -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-black text-accent-600 dark:text-accent-400">
                                        Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-medium text-slate-400 line-through">
                                        Rp {{ number_format($product->buy_price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Profit Badge -->
                            @if($product->sell_price > $product->buy_price)
                                <div class="flex items-center gap-2 pt-2 border-t border-slate-100 dark:border-white/5">
                                    <span class="text-[10px] font-bold text-success bg-success/10 px-2 py-0.5 rounded-full">
                                        +Rp {{ number_format($product->sell_price - $product->buy_price, 0, ',', '.') }} PROFIT
                                    </span>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2 pt-4">
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-accent-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-accent-500/20 hover:bg-accent-600 hover:shadow-accent-500/40 transition-all active:scale-95">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    <span>Edit</span>
                                </a>
                                <button type="button" 
                                        @click="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                        class="flex items-center justify-center h-[42px] px-4 border-2 border-slate-100 dark:border-white/5 text-slate-400 hover:text-danger hover:border-danger/20 hover:bg-danger/5 rounded-xl transition-all active:scale-95 group">
                                    <i data-lucide="trash-2" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                                </button>
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
    <div id="deleteModal" 
         x-data="{ 
            open: false, 
            productId: null, 
            productName: '',
            isDeleting: false
         }" 
         x-show="open" 
         x-cloak
         x-on:open-delete-modal.window="open = true; productId = $event.detail.id; productName = $event.detail.name"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        
        <!-- Backdrop -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-navy-950/40 backdrop-blur-md" 
             @click="open = false"></div>
        
        <!-- Modal Content -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4"
             class="relative bg-white dark:bg-navy-900 rounded-[2.5rem] p-8 max-w-md w-full shadow-[0_20px_50px_rgba(0,0,0,0.3)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/10 overflow-hidden">
            
            <!-- Background Decorative Shape -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-danger/5 rounded-full blur-3xl"></div>
            
            <div class="relative text-center">
                <!-- Icon with Pulse -->
                <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-danger/10 mb-6">
                    <div class="absolute inset-0 rounded-3xl bg-danger/10 animate-ping"></div>
                    <i data-lucide="trash-2" class="relative h-10 w-10 text-danger"></i>
                </div>
                
                <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-2 tracking-tight">Hapus Produk?</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                    Anda akan menghapus <span class="font-bold text-navy-900 dark:text-white" x-text="productName"></span> secara permanen. Tindakan ini tidak dapat dibatalkan.
                </p>
                
                <div class="flex flex-col gap-3">
                    <form :action="'{{ route('admin.products.index') }}/' + productId" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                @click="isDeleting = true"
                                class="w-full flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-danger text-white font-bold hover:bg-red-600 shadow-lg shadow-danger/20 transition-all active:scale-[0.98] disabled:opacity-50">
                            <i x-show="!isDeleting" data-lucide="trash-2" class="w-5 h-5"></i>
                            <div x-show="isDeleting" class="h-5 w-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span x-text="isDeleting ? 'Menghapus...' : 'Ya, Hapus Sekarang'"></span>
                        </button>
                    </form>
                    
                    <button @click="open = false" 
                            class="w-full px-6 py-4 rounded-2xl border-2 border-slate-100 dark:border-white/5 text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-50 dark:hover:bg-white/5 transition-all active:scale-[0.98]">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
        function confirmDelete(productId, productName) {
            window.dispatchEvent(new CustomEvent('open-delete-modal', { 
                detail: { id: productId, name: productName } 
            }));
            
            // Refresh icons inside modal after it opens
            setTimeout(() => {
                lucide.createIcons();
            }, 10);
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