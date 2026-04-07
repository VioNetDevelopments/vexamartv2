@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.stock.index') }}" 
           class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
            <i data-lucide="arrow-left" class="h-5 w-5 text-slate-500"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Stok Masuk</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Catat penerimaan stok baru</p>
        </div>
    </div>

    @if(session('error'))
    <div class="rounded-xl bg-danger/10 border border-danger/20 p-4 text-danger">
        <i data-lucide="alert-circle" class="inline h-5 w-5 mr-2"></i>
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('admin.stock.stock-in.process') }}" method="POST" class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        @csrf
        
        <div class="space-y-6">
            <!-- Product Selection -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Pilih Produk <span class="text-danger">*</span>
                </label>
                <select name="product_id" required 
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                        x-data="{ selected: null }"
                        @change="selected = $event.target.value">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}">
                        {{ $product->name }} (Stok: {{ $product->stock }})
                    </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantity -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Jumlah Stok Masuk <span class="text-danger">*</span>
                </label>
                <input type="number" name="quantity" required min="1" 
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                       placeholder="0">
                @error('quantity')
                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buy Price (Optional) -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Harga Beli Baru (Opsional)
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                    <input type="number" name="buy_price" min="0" 
                           class="w-full rounded-lg border border-slate-200 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                           placeholder="Kosongkan jika tidak berubah">
                </div>
                <p class="text-xs text-slate-500 mt-1">Isi jika ada perubahan harga beli dari supplier</p>
            </div>

            <!-- Reason -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Alasan <span class="text-danger">*</span>
                </label>
                <select name="reason" required 
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
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
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                <textarea name="notes" rows="3" 
                          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                          placeholder="Catatan tambahan (nomor invoice supplier, dll)"></textarea>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-6 border-t border-slate-100 dark:border-white/5">
            <button type="submit" 
                    class="flex-1 rounded-lg bg-success px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                <i data-lucide="plus-circle" class="inline h-4 w-4 mr-2"></i> Catat Stok Masuk
            </button>
            <a href="{{ route('admin.stock.index') }}" 
               class="px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">Batal</a>
        </div>
    </form>
</div>
@endsection