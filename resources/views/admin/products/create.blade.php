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
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Tambah Produk Baru</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Isi form di bawah untuk menambahkan produk</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              imagePreview: null,
              generateSKU: false,
              generateBarcode: false
          }"
          class="space-y-6">
        @csrf

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
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('name') border-danger @enderror">
                            @error('name')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3" 
                                      class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('description') border-danger @enderror">{{ old('description') }}</textarea>
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
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                <input type="number" name="buy_price" value="{{ old('buy_price') }}" required min="0"
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
                                <input type="number" name="sell_price" value="{{ old('sell_price') }}" required min="0"
                                       class="w-full rounded-lg border border-slate-200 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('sell_price') border-danger @enderror">
                            </div>
                            @error('sell_price')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Stok Awal <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0"
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
                            <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" required min="0"
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
                        <div class="aspect-square rounded-xl border-2 border-dashed border-slate-200 dark:border-white/10 flex items-center justify-center overflow-hidden bg-slate-50 dark:bg-navy-800 cursor-pointer hover:border-accent-500 transition-colors"
                                     @click="$refs.imageInput.click()"
                                     @dragover.prevent
                                     @drop.prevent="handleDrop">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="text-center p-4">
                                    <i data-lucide="image" class="h-8 w-8 text-slate-400 mx-auto mb-2"></i>
                                    <p class="text-sm text-slate-500">Drag & drop atau klik untuk upload</p>
                                    <p class="text-xs text-slate-400 mt-1">Max 2MB (JPG, PNG)</p>
                                </div>
                            </template>
                        </div>
                           <input type="file" name="image" accept="image/*" x-ref="imageInput" @change="handleFileSelect"
                               class="hidden">
                           <button type="button" @click="$refs.imageInput.click()"
                                class="w-full rounded-lg border border-slate-200 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5">
                            Pilih Gambar
                        </button>
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
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">SKU</label>
                                <button type="button" @click="generateSKU = !generateSKU" 
                                        class="text-xs text-accent-500 hover:text-accent-600">
                                    Auto Generate
                                </button>
                            </div>
                            <input type="text" name="sku" value="{{ old('sku') }}" 
                                   :readonly="!generateSKU"
                                   class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-mono focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white @error('sku') border-danger @enderror">
                            @error('sku')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Barcode</label>
                                <button type="button" @click="generateBarcode = !generateBarcode" 
                                        class="text-xs text-accent-500 hover:text-accent-600">
                                    Auto Generate
                                </button>
                            </div>
                            <input type="text" name="barcode" value="{{ old('barcode') }}" 
                                   :readonly="!generateBarcode"
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
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-accent-500 focus:ring-accent-500">
                        <span class="text-sm text-slate-700 dark:text-slate-300">Produk Aktif</span>
                    </label>
                    <p class="text-xs text-slate-500 mt-2">Produk tidak aktif tidak akan muncul di kasir</p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-3">
                    <button type="submit" 
                            class="w-full rounded-lg bg-accent-500 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-accent-500/30 hover:bg-accent-600 transition-all">
                        <i data-lucide="save" class="mr-2 inline h-4 w-4"></i> Simpan Produk
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
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 2MB!');
                event.target.value = '';
                return;
            }

            // Validate file type
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

    function handleDrop(event) {
        const file = event.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 2MB!');
                return;
            }
            this.$refs.imageInput.files = event.dataTransfer.files;
            handleFileSelect.call(this, { target: { files: event.dataTransfer.files } });
        }
    }
</script>
@endpush
@endsection