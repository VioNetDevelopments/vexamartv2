@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
    <div class="relative max-w-6xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between animate-fade-in-down">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.products.index') }}" 
                   class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-navy-900 dark:text-white">
                        Detail Produk
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">{{ $product->name }}</p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('admin.products.edit', $product) }}" 
                   class="flex items-center gap-2 px-5 py-2.5 bg-accent-500 text-white rounded-xl font-medium hover:bg-accent-600 transition-colors shadow-lg shadow-accent-500/30">
                    <i data-lucide="edit" class="w-5 h-5"></i>
                    <span>Ubah</span>
                </a>
                <a href="{{ route('admin.stock.adjust', $product) }}" 
                   class="flex items-center gap-2 px-5 py-2.5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-medium hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span>Sesuaikan Stok</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Image & Basic Info -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Image -->
                        <div class="aspect-square rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="package" class="w-24 h-24 text-slate-300 dark:text-slate-600"></i>
                            </div>
                            @endif
                        </div>

                        <!-- Quick Info -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Nama Produk</p>
                                <h2 class="text-xl font-bold text-navy-900 dark:text-white">{{ $product->name }}</h2>
                            </div>
                            
                            <div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Kategori</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-accent-50 dark:bg-accent-900/20 text-accent-600 dark:text-accent-400">
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">SKU</p>
                                    <p class="font-mono text-sm font-medium text-navy-900 dark:text-white">{{ $product->sku }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Barcode</p>
                                    <p class="font-mono text-sm font-medium text-navy-900 dark:text-white">{{ $product->barcode ?? '-' }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Status</p>
                                @if($product->is_active)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-sm font-medium bg-success/10 text-success">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Aktif
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-sm font-medium bg-slate-100 dark:bg-navy-800 text-slate-500">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    Nonaktif
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($product->description)
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-white/10">
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">Deskripsi</p>
                        <p class="text-slate-700 dark:text-slate-300">{{ $product->description }}</p>
                    </div>
                    @endif
                </div>

                <!-- Pricing & Stock -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="tag" class="w-5 h-5 text-accent-500"></i>
                        Harga & Stok
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Harga Beli</p>
                            <p class="text-2xl font-bold text-slate-700 dark:text-slate-300">
                                Rp {{ number_format($product->buy_price, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <div class="p-4 rounded-xl bg-accent-50 dark:bg-accent-900/20">
                            <p class="text-sm text-accent-600 dark:text-accent-400 mb-1">Harga Jual</p>
                            <p class="text-2xl font-bold text-accent-600 dark:text-accent-400">
                                Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <div class="p-4 rounded-xl bg-success/10 dark:bg-success/20">
                            <p class="text-sm text-success mb-1">Keuntungan</p>
                            <p class="text-2xl font-bold text-success">
                                Rp {{ number_format($product->sell_price - $product->buy_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Stok Saat Ini</p>
                            <div class="flex items-center gap-2">
                                <p class="text-2xl font-bold {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= $product->min_stock ? 'text-warning' : 'text-success') }}">
                                    {{ $product->stock }}
                                </p>
                                @if($product->stock <= 0)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-danger text-white">Habis</span>
                                @elseif($product->stock <= $product->min_stock)
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-warning text-white animate-pulse">Menipis</span>
                                @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-success text-white">Aman</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Stok Minimum</p>
                            <p class="text-2xl font-bold text-navy-900 dark:text-white">{{ $product->min_stock }}</p>
                        </div>
                    </div>
                </div>

                </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Informasi</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-white/10">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Dibuat</span>
                            <span class="text-sm font-medium text-navy-900 dark:text-white">{{ $product->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-white/10">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Terakhir Update</span>
                            <span class="text-sm font-medium text-navy-900 dark:text-white">{{ $product->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Total Terjual</span>
                            <span class="text-sm font-medium text-navy-900 dark:text-white">0 pcs</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.5s;">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Aksi Cepat</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.stock.history.product', $product) }}" 
                           class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                            <i data-lucide="history" class="w-5 h-5 text-slate-400"></i>
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Riwayat Stok</span>
                        </a>
                        
                        <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="block">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors text-left">
                                <i data-lucide="toggle-left" class="w-5 h-5 text-slate-400"></i>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </span>
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-danger/5 transition-colors text-left">
                                <i data-lucide="trash-2" class="w-5 h-5 text-danger"></i>
                                <span class="text-sm font-medium text-danger">Hapus Produk</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Barcode Section -->
                @if($product->barcode)
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.6s;">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="barcode" class="w-5 h-5 text-accent-500"></i>
                        Barcode Produk
                    </h3>
                    
                    <div class="flex flex-col items-center justify-center p-4 bg-slate-50 dark:bg-navy-800 rounded-xl">
                        <!-- Barcode Image -->
                        <div class="bg-white p-3 rounded-lg shadow-inner mb-3">
                            <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $product->barcode }}&code=Code128&translate-esc=on" 
                                 alt="Barcode {{ $product->barcode }}"
                                 class="h-16 w-auto">
                        </div>
                        
                        <!-- Barcode Number -->
                        <p class="font-mono text-base font-bold text-navy-900 dark:text-white mb-1">{{ $product->barcode }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 text-center uppercase tracking-wider">Scan for details</p>
                        
                        <!-- Download Button -->
                        <button onclick="downloadBarcode()" 
                                class="w-full mt-4 flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-navy-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-white/5 rounded-xl text-xs font-bold hover:bg-accent-500 hover:text-white transition-all active:scale-95 group">
                            <i data-lucide="download" class="w-4 h-4 group-hover:-translate-y-0.5 transition-transform"></i>
                            <span>UNDUH BARCODE</span>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function downloadBarcode() {
    const barcode = '{{ $product->barcode }}';
    const url = `https://barcode.tec-it.com/barcode.ashx?data=${barcode}&code=Code128&translate-esc=on`;
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = `barcode-${barcode}.png`;
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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
</style>
@endpush
@endsection