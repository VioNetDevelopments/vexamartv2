@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-3xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4 animate-fade-in-down">
                <a href="{{ route('admin.stock.index') }}"
                    class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent">
                        Stok Masuk
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">Catat penerimaan stok baru dari supplier</p>
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
            <form action="{{ route('admin.stock.stock-in.process') }}" method="POST" x-data="{ selectedProduct: null }"
                class="space-y-6 animate-fade-in-up" style="animation-delay: 0.1s;">
                @csrf

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-accent-500"></i>
                        Informasi Produk
                    </h3>

                    <div class="space-y-4">
                        <!-- Product Selection -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Pilih Produk <span class="text-danger">*</span>
                            </label>
                            <select name="product_id" required x-model="selectedProduct"
                                @change="fetchProductData(selectedProduct)"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}"
                                        data-name="{{ $product->name }}" data-buy-price="{{ $product->buy_price }}">
                                        {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Stock Info -->
                        <div x-show="selectedProduct" x-cloak class="p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-slate-500 dark:text-slate-400">Stok Saat Ini</p>
                                    <p class="text-lg font-bold text-navy-900 dark:text-white" id="currentStock">-</p>
                                </div>
                                <div>
                                    <p class="text-slate-500 dark:text-slate-400">Harga Beli</p>
                                    <p class="text-lg font-bold text-navy-900 dark:text-white" id="currentPrice">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20">
                    <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="circle-plus" class="w-5 h-5 text-success"></i>
                        Detail Penerimaan
                    </h3>

                    <div class="space-y-4">
                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Jumlah Stok Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="quantity" required min="1"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                                placeholder="0">
                            @error('quantity')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buy Price (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Harga Beli Baru (Opsional)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                                <input type="number" name="buy_price" min="0"
                                    class="w-full rounded-xl border border-slate-200 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                                    placeholder="Kosongkan jika tidak berubah">
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Isi jika ada perubahan harga beli
                                dari supplier</p>
                        </div>

                        <!-- Reason -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Alasan <span class="text-danger">*</span>
                            </label>
                            <select name="reason" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                                <option value="">-- Pilih Alasan --</option>
                                <option value="Pembelian dari supplier">Pembelian dari Supplier</option>
                                <option value="Retur dari pelanggan">Retur dari Pelanggan</option>
                                <option value="Penyesuaian stok">Penyesuaian Stok</option>
                                <option value="Transfer dari cabang lain">Transfer dari Cabang Lain</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            @error('reason')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Catatan</label>
                            <textarea name="notes" rows="3"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                                placeholder="Catatan tambahan (nomor invoice supplier, dll)"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-success to-success/80 text-white rounded-xl font-medium shadow-lg shadow-success/30 hover:shadow-success/50 transition-all hover:-translate-y-0.5">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>Catat Stok Masuk</span>
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
            function fetchProductData(productId) {
                const select = document.querySelector('select[name="product_id"]');
                const option = select.options[select.selectedIndex];

                if (productId) {
                    document.getElementById('currentStock').textContent = option.dataset.stock;
                    document.getElementById('currentPrice').textContent = 'Rp ' + parseInt(option.dataset.buyPrice).toLocaleString('id-ID');
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
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

            .animate-fade-in-up {
                animation: fade-in-up 0.6s ease-out forwards;
                opacity: 0;
            }

            .animate-fade-in-down {
                animation: fade-in-down 0.6s ease-out forwards;
            }

            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush
@endsection