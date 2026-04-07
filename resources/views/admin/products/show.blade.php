@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" 
               class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                <i data-lucide="arrow-left" class="h-5 w-5 text-slate-500"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $product->name }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-mono">{{ $product->sku }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" 
               class="inline-flex items-center rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white hover:bg-accent-600">
                <i data-lucide="edit" class="mr-2 h-4 w-4"></i> Edit
            </a>
            <a href="{{ route('admin.stock.adjust', $product) }}" 
               class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">
                <i data-lucide="package" class="mr-2 h-4 w-4"></i> Adjust Stok
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Product Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info Card -->
            <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Informasi Produk</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">Nama Produk</p>
                        <p class="font-medium text-navy-900 dark:text-white">{{ $product->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Kategori</p>
                        <p class="font-medium text-navy-900 dark:text-white">{{ $product->category->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">SKU</p>
                        <p class="font-mono text-navy-900 dark:text-white">{{ $product->sku }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Barcode</p>
                        <p class="font-mono text-navy-900 dark:text-white">{{ $product->barcode ?? '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-slate-500">Deskripsi</p>
                        <p class="text-navy-900 dark:text-white">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>
            </div>

            <!-- Pricing Card -->
            <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Harga & Profit</h3>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">Harga Beli</p>
                        <p class="text-xl font-bold text-slate-700 dark:text-slate-300">
                            Rp {{ number_format($product->buy_price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Harga Jual</p>
                        <p class="text-xl font-bold text-accent-500">
                            Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Profit/Unit</p>
                        <p class="text-xl font-bold text-success">
                            Rp {{ number_format($product->sell_price - $product->buy_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stock Card -->
            <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Informasi Stok</h3>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-slate-500">Stok Saat Ini</p>
                        <p class="text-2xl font-bold {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= $product->min_stock ? 'text-warning' : 'text-success') }}">
                            {{ $product->stock }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Minimum Stok</p>
                        <p class="text-xl font-bold text-navy-900 dark:text-white">{{ $product->min_stock }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Status</p>
                        @if($product->stock <= 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-danger/10 text-danger">Stok Habis</span>
                        @elseif($product->stock <= $product->min_stock)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-warning/10 text-warning">Stok Menipis</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success/10 text-success">Stok Aman</span>
                        @endif
                    </div>
                </div>
                
                <!-- Stock Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Stok Level</span>
                        <span>{{ min(100, ($product->stock / ($product->min_stock * 3)) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-navy-800 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all {{ $product->stock <= 0 ? 'bg-danger' : ($product->stock <= $product->min_stock ? 'bg-warning' : 'bg-success') }}" 
                             style="width: {{ min(100, ($product->stock / ($product->min_stock * 3)) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Image & Actions -->
        <div class="space-y-6">
            <!-- Product Image -->
            <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Gambar Produk</h3>
                <div class="aspect-square rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="package" class="h-24 w-24 text-slate-300"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Card -->
            <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Status</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Status Produk</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-success/10 text-success' : 'bg-slate-100 text-slate-500' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Dibuat</span>
                        <span class="text-sm text-navy-900 dark:text-white">{{ $product->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Terakhir Update</span>
                        <span class="text-sm text-navy-900 dark:text-white">{{ $product->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Aksi Cepat</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.stock.history.product', $product) }}" 
                       class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <i data-lucide="history" class="h-5 w-5 text-slate-400"></i>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Riwayat Stok</span>
                    </a>
                    <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="block">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 transition-colors text-left">
                            <i data-lucide="toggle-left" class="h-5 w-5 text-slate-400"></i>
                            <span class="text-sm text-slate-700 dark:text-slate-300">
                                {{ $product->is_active ? 'Nonaktifkan Produk' : 'Aktifkan Produk' }}
                            </span>
                        </button>
                    </form>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-left">
                            <i data-lucide="trash-2" class="h-5 w-5 text-danger"></i>
                            <span class="text-sm text-danger">Hapus Produk</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection