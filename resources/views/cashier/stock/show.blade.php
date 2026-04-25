@extends('layouts.app')

@section('page-title', 'Detail Produk')

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center gap-4">
        <a href="{{ route('cashier.stock') }}" 
           class="p-3 bg-white dark:bg-navy-900 rounded-xl shadow hover:shadow-md transition-all">
            <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600"></i>
        </a>
        <div>
            <h1 class="text-3xl font-black gradient-text">Detail Produk</h1>
            <p class="text-sm text-slate-500">Informasi lengkap produk</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Image & Basic Info -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-6">
                <div class="aspect-square rounded-2xl bg-slate-100 dark:bg-navy-800 overflow-hidden mb-6">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="package" class="w-20 h-20 text-slate-400"></i>
                        </div>
                    @endif
                </div>

                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">{{ $product->name }}</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-slate-100 dark:border-white/5">
                        <span class="text-slate-500">SKU</span>
                        <span class="font-mono font-bold text-slate-700">{{ $product->sku ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100 dark:border-white/5">
                        <span class="text-slate-500">Kategori</span>
                        <span class="font-bold text-slate-700">{{ $product->category->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100 dark:border-white/5">
                        <span class="text-slate-500">Harga Jual</span>
                        <span class="font-bold text-blue-600">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-100 dark:border-white/5">
                        <span class="text-slate-500">Harga Beli</span>
                        <span class="font-bold text-slate-700">Rp {{ number_format($product->buy_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Information -->
        <div class="lg:col-span-2">
            <!-- Stock Status Card -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="warehouse" class="w-5 h-5 text-blue-500"></i>
                    Informasi Stok
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-navy-800/50">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="package" class="w-5 h-5 text-slate-400"></i>
                            <span class="text-sm text-slate-500">Stok Saat Ini</span>
                        </div>
                        <p class="text-3xl font-bold {{ $product->stock > $product->min_stock ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $product->stock }}
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-navy-800/50">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-slate-400"></i>
                            <span class="text-sm text-slate-500">Minimum Stok</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-700">{{ $product->min_stock }}</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-navy-800/50">
                        <div class="flex items-center gap-2 mb-2">
                            <i data-lucide="trending-up" class="w-5 h-5 text-slate-400"></i>
                            <span class="text-sm text-slate-500">Status</span>
                        </div>
                        @if($product->stock > $product->min_stock)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-black bg-green-100 text-green-700">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                Aman
                            </span>
                        @elseif($product->stock > 0)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-black bg-yellow-100 text-yellow-700">
                                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                Menipis
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-black bg-red-100 text-red-700">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                Habis
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Stock Alert -->
                @if($product->stock <= $product->min_stock)
                    <div class="mt-6 p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                        <div class="flex items-start gap-3">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="font-bold text-yellow-800 dark:text-yellow-400">Perhatian: Stok Menipis!</p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-500 mt-1">
                                    Stok produk ini sudah mencapai atau di bawah batas minimum. Segera lakukan restock.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-blue-500"></i>
                    Detail Produk
                </h3>

                <div class="space-y-4">
                    @if($product->description)
                        <div>
                            <label class="block text-sm font-bold text-slate-500 mb-2">Deskripsi</label>
                            <p class="text-slate-700 dark:text-slate-300">{{ $product->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-500 mb-2">Barcode</label>
                            <p class="font-mono text-slate-700 dark:text-slate-300">{{ $product->barcode ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-500 mb-2">Unit</label>
                            <p class="text-slate-700 dark:text-slate-300">{{ $product->unit ?? 'Pcs' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-500 mb-2">Dibuat</label>
                            <p class="text-slate-700 dark:text-slate-300">{{ $product->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-500 mb-2">Terakhir Diupdate</label>
                            <p class="text-slate-700 dark:text-slate-300">{{ $product->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Read-Only Notice -->
                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/10">
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                        <i data-lucide="lock" class="w-5 h-5 text-blue-600 flex-shrink-0"></i>
                        <div>
                            <p class="font-bold text-blue-800 dark:text-blue-400">Mode Read-Only</p>
                            <p class="text-sm text-blue-700 dark:text-blue-500">
                                Sebagai kasir, Anda hanya dapat melihat informasi produk. Untuk mengedit stok, hubungi admin.
                            </p>
                        </div>
                    </div>
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