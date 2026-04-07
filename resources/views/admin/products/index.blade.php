@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ 
    deleteModal: false, 
    deleteId: null, 
    deleteName: '',
    filterOpen: false
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Manajemen Produk</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola katalog produk Anda</p>
        </div>
        <a href="{{ route('admin.products.create') }}" 
           class="inline-flex items-center justify-center rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white shadow-lg shadow-accent-500/30 hover:bg-accent-600 transition-all">
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i> Tambah Produk
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="rounded-xl bg-success/10 border border-success/20 p-4 flex items-center gap-3">
        <i data-lucide="check-circle" class="h-5 w-5 text-success"></i>
        <span class="text-success">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Filters Card -->
    <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        <form action="{{ route('admin.products.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cari Produk</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama, SKU, Barcode"
                               class="w-full rounded-lg border border-slate-200 bg-white pl-10 pr-4 py-2 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kategori</label>
                    <select name="category" 
                            class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
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
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status Stok</label>
                    <select name="stock_status" 
                            class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        <option value="">Semua</option>
                        <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Stok Aman</option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                    <select name="status" 
                            class="w-full rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        <option value="">Semua</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" 
                        class="rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white hover:bg-accent-600 transition-colors">
                    <i data-lucide="filter" class="mr-2 inline h-4 w-4"></i> Filter
                </button>
                <a href="{{ route('admin.products.index') }}" 
                   class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5">
                    Reset
                </a>
                <span class="text-sm text-slate-500 ml-auto">
                    Total: {{ $products->total() }} produk
                </span>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="rounded-2xl bg-white shadow-soft overflow-hidden dark:bg-navy-900 dark:border dark:border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-navy-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">SKU / Barcode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-lg bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <i data-lucide="package" class="h-6 w-6 text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-navy-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-500">{{ Str::limit($product->description, 30) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 dark:bg-navy-800 dark:text-slate-300">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-mono text-slate-600 dark:text-slate-300">{{ $product->sku }}</p>
                            <p class="text-xs text-slate-400 font-mono">{{ $product->barcode }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-navy-900 dark:text-white">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                            <p class="text-xs text-slate-500">Beli: Rp {{ number_format($product->buy_price, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->stock <= 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger/10 text-danger">
                                    Habis
                                </span>
                            @elseif($product->stock <= $product->min_stock)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                                    {{ $product->stock }} (Menipis)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                                    {{ $product->stock }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="document.getElementById('toggle-form-{{ $product->id }}').submit()" 
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-success/10 text-success' : 'bg-slate-100 text-slate-500' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                            <form id="toggle-form-{{ $product->id }}" action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.show', $product) }}" 
                                   class="p-2 text-slate-400 hover:text-accent-500 transition-colors" title="Lihat">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="p-2 text-slate-400 hover:text-accent-500 transition-colors" title="Edit">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </a>
                                <button @click="deleteId = {{ $product->id }}; deleteName = '{{ $product->name }}'; deleteModal = true" 
                                        class="p-2 text-slate-400 hover:text-danger transition-colors" title="Hapus">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i data-lucide="inbox" class="h-12 w-12 text-slate-300 mx-auto mb-3"></i>
                            <p class="text-slate-500 dark:text-slate-400">Belum ada produk</p>
                            <a href="{{ route('admin.products.create') }}" class="text-accent-500 hover:text-accent-600 text-sm font-medium mt-2 inline-block">
                                Tambah produk pertama →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">
            {{ $products->links() }}
        </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="deleteModal = false"></div>
        <div class="relative bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl dark:bg-navy-900" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-danger/10 mb-4">
                    <i data-lucide="alert-triangle" class="h-6 w-6 text-danger"></i>
                </div>
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-2">Hapus Produk?</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-6">
                    Apakah Anda yakin ingin menghapus <strong class="text-navy-900 dark:text-white">{{ $product->name ?? '' }}</strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3 justify-center">
                    <button @click="deleteModal = false" 
                            class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5">
                        Batal
                    </button>
                    <form :action="'{{ route('admin.products.index') }}/' + deleteId" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 rounded-lg bg-danger text-white hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endpush
@endsection