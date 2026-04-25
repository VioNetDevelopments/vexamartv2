@extends('layouts.customer')

@section('title', 'Pembayaran')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-6">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8 animate-fade-in">
            <a href="{{ route('customer.cart') }}" class="p-3 bg-white dark:bg-navy-900 rounded-xl shadow-lg hover:shadow-xl transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">Pembayaran</h1>
                <p class="text-slate-500 dark:text-slate-400">Lengkapi data untuk melanjutkan</p>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-8 p-5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl flex items-center gap-3 animate-fade-in">
                <i data-lucide="alert-circle" class="w-6 h-6 text-red-600"></i>
                <span class="text-red-800 dark:text-red-400 font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('customer.checkout.process') }}" method="POST" class="space-y-8">
            @csrf
            <input type="hidden" name="total" value="{{ $total }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Forms -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Information -->
                    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-8 animate-fade-in">
                        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-white/10">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i data-lucide="user" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white">Informasi Pelanggan</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Data pengiriman Anda</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="relative">
                                    <i data-lucide="user" class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                    <input type="text" name="name" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}" required
                                           class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-14 pr-5 py-4 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Nomor Telepon <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <i data-lucide="phone" class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input type="text" name="phone" value="{{ old('phone', auth()->check() ? auth()->user()->phone : '') }}" required
                                               class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-14 pr-5 py-4 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Email</label>
                                    <div class="relative">
                                        <i data-lucide="mail" class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                        <input type="email" name="email" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}"
                                               class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-14 pr-5 py-4 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Alamat Lengkap</label>
                                <div class="relative">
                                    <i data-lucide="map-pin" class="absolute left-5 top-5 w-5 h-5 text-slate-400"></i>
                                    <textarea name="address" rows="4"
                                              class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-14 pr-5 py-4 text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all resize-none">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-8 animate-fade-in" style="animation-delay: 0.1s;">
                        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-white/10">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-600 to-green-700 flex items-center justify-center shadow-lg shadow-green-500/30">
                                <i data-lucide="credit-card" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white">Metode Pembayaran</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Pilih metode pembayaran</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="cash" required class="sr-only peer" checked>
                                <div class="p-6 rounded-2xl border-2 border-slate-200 dark:border-white/10 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition-all hover:border-green-300 group-hover:shadow-lg group-hover:shadow-green-500/10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                            <i data-lucide="banknote" class="w-7 h-7 text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-lg">Tunai</p>
                                            <p class="text-xs text-slate-500">Bayar saat diterima</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="qris" required class="sr-only peer">
                                <div class="p-6 rounded-2xl border-2 border-slate-200 dark:border-white/10 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all hover:border-blue-300 group-hover:shadow-lg group-hover:shadow-blue-500/10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <i data-lucide="qr-code" class="w-7 h-7 text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-lg">QRIS</p>
                                            <p class="text-xs text-slate-500">Scan QR Code</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="debit" required class="sr-only peer">
                                <div class="p-6 rounded-2xl border-2 border-slate-200 dark:border-white/10 peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 transition-all hover:border-purple-300 group-hover:shadow-lg group-hover:shadow-purple-500/10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                            <i data-lucide="credit-card" class="w-7 h-7 text-purple-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-lg">Debit</p>
                                            <p class="text-xs text-slate-500">Kartu debit</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method" value="ewallet" required class="sr-only peer">
                                <div class="p-6 rounded-2xl border-2 border-slate-200 dark:border-white/10 peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-900/20 transition-all hover:border-orange-300 group-hover:shadow-lg group-hover:shadow-orange-500/10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                                            <i data-lucide="wallet" class="w-7 h-7 text-orange-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white text-lg">E-Wallet</p>
                                            <p class="text-xs text-slate-500">GoPay, OVO, Dana</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-2xl p-8 sticky top-24">
                        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-white/10">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-600 to-purple-700 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i data-lucide="receipt" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white">Ringkasan Pesanan</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $cartCount }} item</p>
                            </div>
                        </div>

                        <!-- Items List -->
                        <div class="space-y-4 mb-8 max-h-80 overflow-y-auto pr-2">
                            @foreach($cartItems as $item)
                                <div class="flex items-center gap-4 pb-4 border-b border-slate-100 dark:border-white/5 last:border-0">
                                    <div class="w-20 h-20 rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i data-lucide="package" class="w-8 h-8 text-slate-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-slate-900 dark:text-white text-sm truncate mb-1">{{ $item->product->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-bold text-slate-700 dark:text-slate-300 text-sm">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Price Summary -->
                        <div class="space-y-3 pt-6 border-t-2 border-slate-200 dark:border-white/10 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($tax > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500 dark:text-slate-400">PPN</span>
                                    <span class="font-bold text-slate-700 dark:text-slate-300">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-2xl font-black pt-4 border-t-2 border-slate-200 dark:border-white/10">
                                <span class="text-slate-900 dark:text-white">Total</span>
                                <span class="text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Payment Amount -->
                        <div class="mb-6">
                            <label class="block text-xs font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-3">Jumlah Bayar <span class="text-danger">*</span></label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-lg font-bold">Rp</span>
                                <input type="number" name="paid_amount" value="{{ $total }}" min="{{ $total }}" required
                                       class="w-full rounded-2xl border border-slate-200 dark:border-white/10 pl-14 pr-5 py-4 text-2xl font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Minimum: Rp {{ number_format($total, 0, ',', '.') }}</p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-2xl font-bold text-lg shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                            <i data-lucide="check-circle" class="w-6 h-6"></i>
                            <span>Bayar Sekarang</span>
                        </button>

                        <!-- Security Badge -->
                        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/10 text-center">
                            <div class="flex items-center justify-center gap-2 text-sm text-slate-500">
                                <i data-lucide="shield-check" class="w-5 h-5 text-green-600"></i>
                                <span>Transaksi Aman & Terenkripsi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>
@endpush
@endsection