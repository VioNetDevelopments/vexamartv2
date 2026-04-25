@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4 animate-fade-in-down">
                <a href="{{ route('admin.stock.index') }}" 
                   class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-navy-900 dark:text-white">
                        Sesuaikan Stok
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">{{ $product->name }}</p>
                </div>
            </div>

            @if(session('error'))
                <div class="animate-fade-in-up rounded-2xl bg-danger/10 border border-danger/20 p-4 flex items-center gap-3">
                    <i data-lucide="alert-circle" class="h-5 w-5 text-danger"></i>
                    <span class="text-danger font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="animate-fade-in-up rounded-2xl bg-success/10 border border-success/20 p-4 flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-5 w-5 text-success"></i>
                    <span class="text-success font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.stock.adjust.process', $product) }}" method="POST" 
                  x-data="{ 
                      quantity: 0,
                      currentStock: {{ $product->stock }},
                      minStock: {{ $product->min_stock }},
                      get newStock() { return this.currentStock + parseInt(this.quantity) || 0; },
                      get statusColor() {
                          if (this.newStock <= 0) return 'text-danger';
                          if (this.newStock <= this.minStock) return 'text-warning';
                          return 'text-success';
                      }
                  }"
                  class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf

                <!-- Product Info Card -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-20 w-20 rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center">
                                    <i data-lucide="package" class="h-8 w-8 text-slate-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-navy-900 dark:text-white">{{ $product->name }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $product->category->name ?? '-' }}</p>
                            <p class="text-xs font-mono text-slate-400 mt-1">SKU: {{ $product->sku }}</p>
                        </div>
                    </div>

                    <!-- Current Stock Info -->
                    <div class="grid grid-cols-3 gap-4 p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                        <div class="text-center">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Stok Saat Ini</p>
                            <p class="text-2xl font-bold {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= $product->min_stock ? 'text-warning' : 'text-success') }}">
                                {{ $product->stock }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Stok Minimum</p>
                            <p class="text-2xl font-bold text-navy-900 dark:text-white">{{ $product->min_stock }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Status</p>
                            @if($product->stock <= 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-danger/10 text-danger">Habis</span>
                            @elseif($product->stock <= $product->min_stock)
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-warning/10 text-warning">Menipis</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-success/10 text-success">Aman</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Adjustment Form -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="edit" class="w-5 h-5 text-accent-500"></i>
                        Penyesuaian Stok
                    </h3>

                    <div class="space-y-4">
                        <!-- Quantity Adjustment -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Jumlah Penyesuaian <span class="text-danger">*</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="number" name="quantity" x-model="quantity" required 
                                       class="w-32 rounded-xl border border-slate-200 px-4 py-2.5 text-sm text-center font-bold focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                                       placeholder="0">
                                <div class="flex-1 p-3 rounded-xl bg-slate-50 dark:bg-navy-800">
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Keterangan:</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300">
                                        <span class="text-success font-bold">Angka positif</span> = Tambah stok
                                        <span class="mx-2">|</span>
                                        <span class="text-danger font-bold">Angka negatif</span> = Kurangi stok
                                    </p>
                                </div>
                            </div>
                            @error('quantity')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview New Stock -->
                        <div class="p-4 rounded-xl bg-gradient-to-r from-accent-50 to-accent-100 dark:from-accent-900/20 dark:to-accent-900/10 border border-accent-200 dark:border-accent-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-accent-700 dark:text-accent-400 mb-1">Preview Stok Baru</p>
                                    <p class="text-xs text-accent-600 dark:text-accent-500">
                                        {{ $product->stock }} + <span x-text="quantity || 0" class="font-bold"></span> = 
                                        <span x-text="newStock" class="font-bold text-lg" :class="statusColor"></span>
                                    </p>
                                </div>
                                <i data-lucide="info" class="w-6 h-6 text-accent-500"></i>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Alasan <span class="text-danger">*</span>
                            </label>
                            <select name="reason" required 
                                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                                <option value="">-- Pilih Alasan --</option>
                                <option value="restock">Restock / Pembelian Baru</option>
                                <option value="damage">Barang Rusak</option>
                                <option value="expired">Kadaluarsa</option>
                                <option value="lost">Hilang</option>
                                <option value="correction">Koreksi Stok</option>
                                <option value="stocktake">Stock Opname</option>
                                <option value="other">Lainnya</option>
                            </select>
                            @error('reason')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Catatan</label>
                            <textarea name="note" rows="3" 
                                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                                      placeholder="Catatan tambahan (opsional)">{{ old('note') }}</textarea>
                            @error('note')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-medium shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all hover:-translate-y-0.5">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a href="{{ route('admin.stock.index') }}" 
                       class="px-6 py-3 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-medium hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
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