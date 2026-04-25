@extends('layouts.app')

@section('page-title', 'Proses Retur')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-4 animate-fade-in-down">
                <a href="{{ route('cashier.returns') }}"
                    class="p-3 bg-white dark:bg-navy-900 rounded-xl shadow hover:shadow-md transition-all">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-navy-900 dark:text-white">Proses Retur</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Invoice: {{ $transaction->invoice_code }}</p>
                </div>
            </div>

            <!-- Transaction Info -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Pelanggan</p>
                        <p class="font-bold text-slate-900 dark:text-white">{{ $transaction->customer?->name ?? 'Umum' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Tanggal</p>
                        <p class="font-bold text-slate-900 dark:text-white">
                            {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Total Transaksi</p>
                        <p class="font-bold text-blue-600">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Item yang Dapat Dikembalikan</h3>
                <div class="space-y-3">
                    @foreach($transaction->items as $item)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-navy-800">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-lg bg-slate-200 dark:bg-navy-700 overflow-hidden">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="package" class="w-6 h-6 text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $item->product->name }}</p>
                                    <p class="text-sm text-slate-500">Rp {{ number_format($item->price, 0, ',', '.') }} x
                                        {{ $item->qty }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-slate-900 dark:text-white">Rp
                                    {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Return Form -->
            <form action="{{ route('cashier.returns.store', $transaction) }}" method="POST"
                class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-6">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pilih Produk <span
                                class="text-danger">*</span></label>
                        <select name="product_id" required
                            class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-navy-800 dark:text-white">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($transaction->items as $item)
                                <option value="{{ $item->product_id }}" data-qty="{{ $item->qty }}">
                                    {{ $item->product->name }} (Max: {{ $item->qty }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Jumlah Retur <span
                                class="text-danger">*</span></label>
                        <input type="number" name="quantity" required min="1" max="1"
                            class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-bold focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-navy-800 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Alasan Pengembalian
                            <span class="text-danger">*</span></label>
                        <textarea name="reason" required rows="4"
                            class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-medium focus:border-purple-500 focus:ring-4 focus:ring-purple-500/20 dark:bg-navy-800 dark:text-white resize-none"
                            placeholder="Contoh: Produk rusak, tidak sesuai, kadaluarsa, dll."></textarea>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="button" onclick="history.back()"
                        class="flex-1 py-4 border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-2xl font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-2xl font-bold hover:shadow-lg hover:shadow-purple-500/30 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="rotate-ccw" class="w-6 h-6"></i>
                        <span>Proses Retur</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();

                // Update max quantity based on selected product
                document.querySelector('select[name="product_id"]').addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const maxQty = selectedOption.dataset.qty || 1;
                    document.querySelector('input[name="quantity"]').max = maxQty;
                });
            });
        </script>
    @endpush
@endsection