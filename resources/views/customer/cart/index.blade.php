@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-6">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8 animate-fade-in">
            <a href="{{ route('customer.home') }}" class="p-3 bg-white dark:bg-navy-900 rounded-xl shadow-lg hover:shadow-xl transition-all">
                <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600 dark:text-slate-400"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">Keranjang Belanja</h1>
                <p class="text-slate-500 dark:text-slate-400">{{ $cartCount }} item</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 p-5 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl flex items-center gap-3 animate-fade-in">
                <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                <span class="text-green-800 dark:text-green-400 font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl flex items-center gap-3 animate-fade-in">
                <i data-lucide="alert-circle" class="w-6 h-6 text-red-600"></i>
                <span class="text-red-800 dark:text-red-400 font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <!-- Empty Cart -->
            <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-2xl p-16 text-center animate-fade-in">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-navy-800 dark:to-navy-700 flex items-center justify-center mx-auto mb-8">
                    <i data-lucide="shopping-cart" class="w-16 h-16 text-slate-400"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">Keranjang Kosong</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-8 text-lg">Yuk belanja sekarang dan temukan produk terbaik!</p>
                <a href="{{ route('customer.home') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl font-bold hover:shadow-lg hover:shadow-blue-500/30 transition-all hover:-translate-y-1">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    Belanja Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-lg p-6 animate-fade-in card-hover">
                            <div class="flex items-center gap-6">
                                <!-- Product Image -->
                                <div class="w-28 h-28 rounded-2xl bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="package" class="w-10 h-10 text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-2">{{ $item->product->name }}</h3>
                                    <p class="text-blue-600 font-bold text-lg mb-4">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    
                                    <!-- Quantity -->
                                    <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" class="flex items-center gap-3">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="this.previousElementSibling.stepDown(); this.form.submit()" class="w-10 h-10 rounded-xl border-2 border-slate-200 dark:border-white/10 flex items-center justify-center hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                                            <i data-lucide="minus" class="w-5 h-5"></i>
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" onchange="this.form.submit()" class="w-20 h-10 rounded-xl border-2 border-slate-200 dark:border-white/10 text-center text-lg font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white">
                                        <button type="button" onclick="this.previousElementSibling.stepUp(); this.form.submit()" class="w-10 h-10 rounded-xl border-2 border-slate-200 dark:border-white/10 flex items-center justify-center hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                                            <i data-lucide="plus" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Subtotal & Remove -->
                                <div class="text-right">
                                    <p class="text-2xl font-black text-slate-900 dark:text-white mb-4">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors">
                                            <i data-lucide="trash-2" class="w-6 h-6"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Checkout Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-2xl p-6 sticky top-24">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6 pb-6 border-b border-slate-200 dark:border-white/10">Ringkasan Pesanan</h3>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Subtotal ({{ $cartCount }} item)</span>
                                <span class="font-bold">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Pengiriman</span>
                                <span class="font-bold text-green-600">Gratis</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between text-2xl font-black pt-6 border-t-2 border-slate-200 dark:border-white/10 mb-8">
                            <span class="text-slate-900 dark:text-white">Total</span>
                            <span class="text-blue-600">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="space-y-4">
                            <a href="{{ route('customer.checkout') }}" class="w-full py-5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-2xl font-bold text-lg shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                                <i data-lucide="credit-card" class="w-6 h-6"></i>
                                <span>Lanjut ke Pembayaran</span>
                            </a>
                            
                            <form action="{{ route('customer.cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-5 border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-2xl font-bold text-lg hover:bg-slate-50 dark:hover:bg-navy-800 transition-all flex items-center justify-center gap-3">
                                    <i data-lucide="trash-2" class="w-6 h-6"></i>
                                    <span>Kosongkan Keranjang</span>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Trust Badges -->
                        <div class="mt-8 pt-8 border-t border-slate-200 dark:border-white/10">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <i data-lucide="shield-check" class="w-8 h-8 text-green-600 mx-auto mb-2"></i>
                                    <p class="text-xs text-slate-500">Aman</p>
                                </div>
                                <div>
                                    <i data-lucide="truck" class="w-8 h-8 text-blue-600 mx-auto mb-2"></i>
                                    <p class="text-xs text-slate-500">Cepat</p>
                                </div>
                                <div>
                                    <i data-lucide="headphones" class="w-8 h-8 text-purple-600 mx-auto mb-2"></i>
                                    <p class="text-xs text-slate-500">24/7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>
@endpush
@endsection