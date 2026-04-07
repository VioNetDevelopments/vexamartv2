@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" 
           class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
            <i data-lucide="arrow-left" class="h-5 w-5 text-slate-500"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Edit Produk</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Update informasi produk</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.products.update', $product) }}" 
          method="POST" 
          enctype="multipart/form-data"
          x-data="{ 
              imagePreview: null,
              generateSKU: false,
              generateBarcode: false
          }"
          class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Informasi Dasar</h3>
                    
                    <div class="space-y-4">
                        <!-- Product Name -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Nama Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('name') border-danger @enderror">
                            @error('name')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3" 
                                      class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('description') border-danger @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" required
                                    class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('category_id') border-danger @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing Card -->
                <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Harga & Stok</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Buy Price -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Harga Beli <span class="text-danger">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                                <input type="number" name="buy_price" value="{{ old('buy_price', $product->buy_price) }}" required min="0"
                                       class="w-full rounded-lg border border-slate-200 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('buy_price') border-danger @enderror">
                            </div>
                            @error('buy_price')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sell Price -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Harga Jual <span class="text-danger">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                                <input type="number" name="sell_price" value="{{ old('sell_price', $product->sell_price) }}" required min="0"
                                       class="w-full rounded-lg border border-slate-200 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('sell_price') border-danger @enderror">
                            </div>
                            @error('sell_price')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Stok Saat Ini <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('stock') border-danger @enderror">
                            @error('stock')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Min Stock -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Stok Minimum <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required min="0"
                                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('min_stock') border-danger @enderror">
                            @error('min_stock')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Image Upload -->
                <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Gambar Produk</h3>
                    
                    <div class="space-y-4">
                        <!-- Show Existing Image -->
                        @if($product->image)
                        <div class="relative aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-navy-800">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover"
                                 id="existingImage">
                            <button type="button" 
                                    @click="$refs.imageInput.click()"
                                    class="absolute inset-0 bg-black/50 opacity-0 hover:opacity-100 flex items-center justify-center transition-opacity">
                                <span class="text-white text-sm font-medium">Ganti Gambar</span>
                            </button>
                        </div>
                        @endif
                        
                        <!-- Preview Area (for new image) -->
                        <div x-show="imagePreview" 
                             class="relative aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-navy-800"
                             style="display: none;">
                            <img :src="imagePreview" class="w-full h-full object-cover">
                            <button type="button" 
                                    @click="imagePreview = null; $refs.imageInput.value = ''"
                                    class="absolute top-2 right-2 p-1 bg-danger text-white rounded-full hover:bg-red-700">
                                <i data-lucide="x" class="h-4 w-4"></i>
                            </button>
                        </div>
                        
                        <!-- Hidden File Input -->
                        <input type="file" 
                               name="image" 
                               accept="image/*" 
                               x-ref="imageInput"
                               @change="handleFileSelect"
                               class="hidden">
                        
                        @if(!$product->image)
                        <button type="button" 
                                @click="$refs.imageInput.click()"
                                class="w-full rounded-lg border border-slate-200 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5">
                            Pilih Gambar
                        </button>
                        @endif
                        
                        @error('image')
                            <p class="text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SKU & Barcode -->
                <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">SKU & Barcode</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" 
                                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-mono focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('sku') border-danger @enderror">
                            @error('sku')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Barcode</label>
                            <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" 
                                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-mono focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('barcode') border-danger @enderror">
                            @error('barcode')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4">Status</h3>
                    
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-accent-500 focus:ring-accent-500">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Produk Aktif</span>
                    </label>
                    <p class="text-xs text-slate-500 mt-2">Produk tidak aktif tidak akan muncul di kasir</p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    <button type="submit" 
                            class="w-full rounded-lg bg-accent-500 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-accent-500/30 hover:bg-accent-600 transition-all">
                        <i data-lucide="save" class="mr-2 inline h-4 w-4"></i> Update Produk
                    </button>
                    <a href="{{ route('admin.products.index') }}" 
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran gambar maksimal 2MB!');
            event.target.value = '';
            return;
        }
        
        if (!file.type.startsWith('image/')) {
            alert('Hanya file gambar yang diperbolehkan!');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            this.imagePreview = e.target.result;
        }.bind(this);
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection