@extends('layouts.app')

@section('page-title', 'Edit Flash Sale')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4 animate-fade-in-down">
                <a href="{{ route('admin.flash-sales.index') }}"
                    class="p-2.5 rounded-xl bg-white dark:bg-navy-900 shadow-lg hover:shadow-xl transition-shadow">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">Edit Flash Sale</h1>
                    <p class="text-slate-500 dark:text-slate-400">Update detail penawaran terbatas</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.flash-sales.update', $flashSale) }}" method="POST" 
                @submit.prevent="notify.confirm('Yakin nih mau update flash sale-nya KING? Nanti harganya langsung berubah loh!', () => $el.submit())"
                class="space-y-6 animate-fade-in-up"
                style="animation-delay: 0.1s;">
                @csrf
                @method('PUT')

                <!-- Basic Info -->
                <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-8">
                    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-200 dark:border-white/10">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/30">
                            <i data-lucide="zap" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white">Informasi Flash Sale</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Detail penawaran</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label
                                class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Judul
                                Flash Sale *</label>
                            <input type="text" name="title" value="{{ old('title', $flashSale->title) }}" required
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:bg-navy-800 dark:text-white"
                                placeholder="Contoh: Flash Sale Spesial Hari Ini!">
                            @error('title')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Deskripsi</label>
                            <textarea name="description" rows="3"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:bg-navy-800 dark:text-white resize-none"
                                placeholder="Deskripsi flash sale (opsional)">{{ old('description', $flashSale->description) }}</textarea>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Pilih
                                Produk *</label>
                            <select name="product_id" required
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:bg-navy-800 dark:text-white">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $flashSale->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (Stok: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Diskon
                                    (%) *</label>
                                <div class="relative">
                                    <input type="number" name="discount_percentage"
                                        value="{{ old('discount_percentage', $flashSale->discount_percentage) }}" min="1" max="90" required
                                        class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:bg-navy-800 dark:text-white">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">%</span>
                                </div>
                                @error('discount_percentage')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Maksimal
                                    Stok *</label>
                                <input type="number" name="max_quantity" value="{{ old('max_quantity', $flashSale->max_quantity) }}" min="1"
                                    required
                                    class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:bg-navy-800 dark:text-white">
                                @error('max_quantity')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Mulai
                                    *</label>
                                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $flashSale->starts_at->format('Y-m-d\TH:i')) }}" required
                                    class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:bg-navy-800 dark:text-white">
                                @error('starts_at')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">Berakhir
                                    *</label>
                                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $flashSale->ends_at->format('Y-m-d\TH:i')) }}" required
                                    class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/20 dark:bg-navy-800 dark:text-white">
                                @error('ends_at')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $flashSale->is_active) ? 'checked' : '' }}
                                class="w-5 h-5 rounded-lg border-slate-300 text-accent-500 focus:ring-accent-500">
                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Aktifkan flash
                                sale</label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.flash-sales.index') }}"
                        class="px-8 py-4 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-4 rounded-xl bg-gradient-to-r from-accent-500 to-accent-600 text-white font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        <span>Update Flash Sale</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();
            });
        </script>
    @endpush
@endsection
