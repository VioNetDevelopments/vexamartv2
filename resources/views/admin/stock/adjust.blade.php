@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.stock.index') }}" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5">
            <i data-lucide="arrow-left" class="h-5 w-5 text-slate-500"></i>
        </a>
        <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Adjust Stok: {{ $product->name }}</h1>
    </div>

    @if(session('error'))
    <div class="mb-4 p-4 rounded-xl bg-danger/10 border border-danger/20 text-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.stock.adjust.process', $product) }}" method="POST" class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        @csrf
        
        <!-- Current Stock Info -->
        <div class="mb-6 p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-slate-500">Stok Saat Ini</p>
                    <p class="font-bold text-lg">{{ $product->stock }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Min. Stok</p>
                    <p class="font-bold text-lg">{{ $product->min_stock }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Status</p>
                    <p class="font-bold {{ $product->stock <= $product->min_stock ? 'text-warning' : 'text-success' }}">
                        {{ $product->stock <= $product->min_stock ? 'Perlu Restock' : 'Aman' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Adjustment Form -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Jumlah Penyesuaian</label>
                <div class="flex items-center gap-3">
                    <input type="number" name="quantity" required 
                           class="w-32 rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                           placeholder="0">
                    <span class="text-slate-500">
                        (+) Tambah / (-) Kurangi
                    </span>
                </div>
                @error('quantity')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alasan <span class="text-danger">*</span></label>
                <select name="reason" required class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    <option value="">Pilih Alasan</option>
                    <option value="restock">Restock / Pembelian Baru</option>
                    <option value="damage">Barang Rusak</option>
                    <option value="expired">Kadaluarsa</option>
                    <option value="lost">Hilang</option>
                    <option value="correction">Koreksi Stok</option>
                    <option value="other">Lainnya</option>
                </select>
                @error('reason')<p class="mt-1 text-xs text-danger">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                <textarea name="note" rows="3" class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ old('note') }}</textarea>
            </div>
        </div>

        <!-- Preview -->
        <div class="mt-6 p-4 rounded-xl bg-accent-50 dark:bg-accent-900/20">
            <p class="text-sm text-accent-700 dark:text-accent-300">
                <strong>Preview:</strong> Stok {{ $product->stock }} 
                <span x-text="document.querySelector('[name=quantity]').value > 0 ? '+' : '-'"></span> 
                <span x-text="Math.abs(document.querySelector('[name=quantity]').value) || 0"></span> 
                = <strong x-text="{{ $product->stock }} + (document.querySelector('[name=quantity]').value || 0)"></strong>
            </p>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex gap-3">
            <button type="submit" class="flex-1 rounded-lg bg-accent-500 py-2.5 text-sm font-medium text-white hover:bg-accent-600">
                <i data-lucide="save" class="inline h-4 w-4 mr-2"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.stock.index') }}" class="px-4 py-2.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">Batal</a>
        </div>
    </form>
</div>
@endsection