@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-5xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4 animate-fade-in-down">
                <a href="{{ route('admin.products.index') }}" 
                   class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-navy-900 dark:text-white">
                        Tambah Produk Baru
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">Lengkapi form di bawah untuk menambahkan produk</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" 
                  id="createProductForm"
                  x-data="{ 
                      imagePreview: null,
                      generateSKU: false,
                      generateBarcode: false,
                      showAdvanced: false,
                      confirmCreate() {
                          Swal.fire({
                              title: 'Gas nambah produk, King?',
                              text: 'Cek lagi datanya, kalau udah mantap tinggal sikat, King!',
                              icon: 'question',
                              showCancelButton: true,
                              confirmButtonColor: '#10b981',
                              cancelButtonColor: '#64748b',
                              confirmButtonText: 'Yakin',
                              cancelButtonText: 'Ntar dulu',
                              reverseButtons: true,
                              background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                              color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
                          }).then((result) => {
                              if (result.isConfirmed) {
                                  document.getElementById('createProductForm').submit();
                              }
                          });
                      }
                  }"
                  @submit.prevent="confirmCreate()"
                  class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                                <i data-lucide="info" class="w-5 h-5 text-accent-500"></i>
                                Informasi Dasar
                            </h3>

                            <div class="space-y-4">
                                <!-- Product Name -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Nama Produk <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                           @class([
                                               'w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white',
                                               'border-danger' => $errors->has('name'),
                                           ])
                                           placeholder="Masukkan nama produk">
                                    @error('name')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Deskripsi</label>
                                    <textarea name="description" rows="3" 
                                              @class([
                                                  'w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white',
                                                  'border-danger' => $errors->has('description'),
                                              ])
                                              placeholder="Deskripsi produk (opsional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Kategori <span class="text-danger">*</span>
                                    </label>
                                    <select name="category_id" required
                                            @class([
                                                'w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white',
                                                'border-danger' => $errors->has('category_id'),
                                            ])>
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

                        <!-- Pricing & Stock -->
                        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                                <i data-lucide="tag" class="w-5 h-5 text-accent-500"></i>
                                Harga & Stok
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Buy Price -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Harga Beli <span class="text-danger">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                                        <input type="number" name="buy_price" value="{{ old('buy_price') }}" required min="0"
                                               @class([
                                                   'w-full rounded-xl border border-slate-200 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white',
                                                   'border-danger' => $errors->has('buy_price'),
                                               ])>
                                    </div>
                                    @error('buy_price')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sell Price -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Harga Jual <span class="text-danger">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                                        <input type="number" name="sell_price" value="{{ old('sell_price') }}" required min="0"
                                               @class([
                                                   'w-full rounded-xl border border-slate-200 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white',
                                                   'border-danger' => $errors->has('sell_price'),
                                               ])>
                                    </div>
                                    @error('sell_price')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Stock -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Stok Awal <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0"
                                           @class([
                                               'w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white',
                                               'border-danger' => $errors->has('stock'),
                                           ])>
                                    @error('stock')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Min Stock -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Stok Minimum <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" required min="0"
                                           @class([
                                               'w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white',
                                               'border-danger' => $errors->has('min_stock'),
                                           ])>
                                    @error('min_stock')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Settings -->
                        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                            <button type="button" @click="showAdvanced = !showAdvanced" 
                                    class="flex items-center gap-2 text-sm font-medium text-accent-600 dark:text-accent-400 hover:text-accent-700 mb-4">
                                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="{'rotate-180': showAdvanced}"></i>
                                <span>Pengaturan Lanjutan</span>
                            </button>

                            <div x-show="showAdvanced" x-collapse class="space-y-4">
                                <!-- SKU -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">SKU</label>
                                        <button type="button" @click="generateSKU = !generateSKU; if(generateSKU) $nextTick(() => $refs.sku.value = 'SKU-' + Math.random().toString(36).substr(2, 8).toUpperCase())" 
                                                class="text-xs text-accent-600 hover:text-accent-700">
                                            Auto Generate
                                        </button>
                                    </div>
                                    <input type="text" name="sku" x-ref="sku" value="{{ old('sku') }}" 
                                           :readonly="generateSKU"
                                           @class([
                                               'w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-mono focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white',
                                               'border-danger' => $errors->has('sku'),
                                           ])
                                           placeholder="SKU-XXXXXXXX">
                                    @error('sku')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Barcode -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Barcode</label>
                                        <button type="button" @click="generateBarcode = !generateBarcode; if(generateBarcode) $nextTick(() => $refs.barcode.value = '899' + Math.floor(Math.random() * 1000000000))" 
                                                class="text-xs text-accent-600 hover:text-accent-700">
                                            Auto Generate
                                        </button>
                                    </div>
                                    <input type="text" name="barcode" x-ref="barcode" value="{{ old('barcode') }}" 
                                           :readonly="generateBarcode"
                                           @class([
                                               'w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-mono focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white',
                                               'border-danger' => $errors->has('barcode'),
                                           ])
                                           placeholder="899XXXXXXXXX">
                                    @error('barcode')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Image Upload -->
                        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                                <i data-lucide="image" class="w-5 h-5 text-accent-500"></i>
                                Gambar Produk
                            </h3>

                            <div class="space-y-4">
                                <!-- Preview -->
                                <div class="aspect-square rounded-xl border-2 border-dashed border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-navy-800 flex items-center justify-center overflow-hidden cursor-pointer hover:border-accent-500 transition-colors"
                                     @click="$refs.imageInput.click()"
                                     @dragover.prevent
                                     @drop.prevent="handleDrop">
                                    <template x-if="imagePreview">
                                        <img :src="imagePreview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!imagePreview">
                                        <div class="text-center p-4">
                                            <i data-lucide="upload-cloud" class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-2"></i>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">Klik atau drag & drop</p>
                                            <p class="text-xs text-slate-400 mt-1">Max 2MB (JPG, PNG)</p>
                                        </div>
                                    </template>
                                </div>

                                <!-- File Input -->
                                <input type="file" name="image" accept="image/*" x-ref="imageInput" @change="handleFileSelect" class="hidden">

                                <button type="button" @click="$refs.imageInput.click()"
                                        class="w-full rounded-xl border border-slate-200 dark:border-white/10 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                                    Pilih Gambar
                                </button>

                                @error('image')
                                    <p class="text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                            <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                                <i data-lucide="toggle-left" class="w-5 h-5 text-accent-500"></i>
                                Status
                            </h3>

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-5 h-5 rounded-lg border-slate-300 text-accent-500 focus:ring-accent-500">
                                <div>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Produk Aktif</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Produk akan tampil di kasir</p>
                                </div>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-3">
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-medium shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all hover:-translate-y-0.5">
                                <i data-lucide="save" class="w-5 h-5"></i>
                                <span>Simpan Produk</span>
                            </button>
                            <a href="{{ route('admin.products.index') }}" 
                               class="w-full flex items-center justify-center gap-2 px-6 py-3 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-medium hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                                <i data-lucide="x" class="w-5 h-5"></i>
                                <span>Batal</span>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kegedean Boss!',
                        text: 'File gambarnya jangan lebih dari 2MB ya.',
                        confirmButtonColor: '#ef4444'
                    });
                    event.target.value = '';
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Salah Kamar!',
                        text: 'Cuma boleh file gambar aja nih.',
                        confirmButtonColor: '#f59e0b'
                    });
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Kegedean Boss!',
                        text: 'File gambarnya jangan lebih dari 2MB ya.',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }
                this.$refs.imageInput.files = event.dataTransfer.files;
                handleFileSelect.call(this, { target: { files: event.dataTransfer.files } });
            }
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
        [x-cloak] { display: none !important; }
        </style>
    @endpush
@endsection