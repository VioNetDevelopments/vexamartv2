@extends('layouts.app')

@section('content')
    <div class="h-[calc(100vh-4rem)] bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 rounded-3xl"
        x-data="posApp()" x-init="init()">

        <!-- Top Bar - Compact & Rounded -->
        <div class="px-6 py-3">
            <div class="flex items-center gap-3">
                <!-- Online Status Badge -->
                <div
                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gradient-to-r from-success/10 to-success/5 border border-success/20">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-success"></span>
                    </span>
                    <span class="text-xs font-semibold text-success">Online</span>
                </div>

                <!-- Sales Stats -->
                <div class="flex items-center gap-2">
                    <div
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-navy-800 border border-slate-200 dark:border-white/10">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5 text-success"></i>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Penjualan</span>
                            <span class="text-xs font-bold text-success" x-text="formatRupiah(dailySales)"></span>
                        </div>
                    </div>

                    <div
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-navy-800 border border-slate-200 dark:border-white/10">
                        <i data-lucide="shopping-cart" class="w-3.5 h-3.5 text-accent-600"></i>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Transaksi</span>
                            <span class="text-xs font-bold text-navy-900 dark:text-white" x-text="dailyTransactions"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative h-full p-6 flex gap-6">

            <!-- LEFT: Product Grid -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Search & Filter Bar -->
                <div
                    class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg mb-6 animate-fade-in-down overflow-visible relative z-20">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative group">
                                <i data-lucide="search"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                                <input type="text" x-model="search" @input.debounce.300ms="searchProducts()"
                                    placeholder="Cari produk"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-12 pr-12 py-4 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-sm group-hover:bg-white dark:group-hover:bg-navy-700"
                                    @keydown.enter="scanBarcode()">
                                <button @click="$refs.barcodeInput.focus()"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                                    <i data-lucide="scan-barcode" class="h-5 w-5 text-slate-400"></i>
                                </button>
                            </div>
                            <input type="text" x-ref="barcodeInput" class="hidden" @input.debounce.300ms="scanBarcode()">
                        </div>

                        <!-- Category Filter Custom Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[200px] h-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-black dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white dark:hover:bg-navy-700 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="layout-grid" class="w-4 h-4 text-accent-500"></i>
                                    <span x-text="categoryName || 'Semua Kategori'">Semua Kategori</span>
                                </div>
                                <i data-lucide="chevron-down"
                                    class="w-4 h-4 text-slate-400 transition-transform duration-300"
                                    :class="{'rotate-180': open}"></i>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute z-[60] mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto no-scrollbar"
                                style="top: 100%;">
                                <button type="button"
                                    @click="categoryId = ''; categoryName = 'Semua Kategori'; searchProducts(); open = false"
                                    class="w-full text-left px-4 py-3 text-sm font-bold rounded-xl transition-all hover:bg-accent-50 dark:hover:bg-accent-900/20 mb-1"
                                    :class="!categoryId ? 'bg-accent-500 text-white shadow-lg shadow-accent-500/20' : 'text-slate-600 dark:text-slate-300'">
                                    Semua Kategori
                                </button>
                                @foreach($categories as $cat)
                                    <button type="button"
                                        @click="categoryId = '{{ $cat->id }}'; categoryName = '{{ $cat->name }}'; searchProducts(); open = false"
                                        class="w-full text-left px-4 py-3 text-sm font-bold rounded-xl transition-all hover:bg-accent-50 dark:hover:bg-accent-900/20 mb-1"
                                        :class="categoryId == '{{ $cat->id }}' ? 'bg-accent-500 text-white shadow-lg shadow-accent-500/20' : 'text-slate-600 dark:text-slate-300'">
                                        {{ $cat->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Customer Select Custom Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[200px] h-full px-5 py-4 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-black dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white dark:hover:bg-navy-700 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="users" class="w-4 h-4 text-blue-500"></i>
                                    <span x-text="customerName || 'Pelanggan Umum'">Pelanggan Umum</span>
                                </div>
                                <i data-lucide="chevron-down"
                                    class="w-4 h-4 text-slate-400 transition-transform duration-300"
                                    :class="{'rotate-180': open}"></i>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute z-[60] mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto no-scrollbar"
                                style="top: 100%;">
                                <button type="button"
                                    @click="customerId = ''; customerName = 'Pelanggan Umum'; open = false"
                                    class="w-full text-left px-4 py-3 text-sm font-bold rounded-xl transition-all hover:bg-blue-50 dark:hover:bg-blue-900/20 mb-1"
                                    :class="!customerId ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-600 dark:text-slate-300'">
                                    Pelanggan Umum
                                </button>
                                @foreach($customers as $cust)
                                    <button type="button"
                                        @click="customerId = '{{ $cust->id }}'; customerName = '{{ $cust->name }}'; open = false"
                                        class="w-full text-left px-4 py-3 text-sm font-bold rounded-xl transition-all hover:bg-blue-50 dark:hover:bg-blue-900/20 mb-1"
                                        :class="customerId == '{{ $cust->id }}' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'text-slate-600 dark:text-slate-300'">
                                        {{ $cust->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="flex-1 overflow-y-auto no-scrollbar bg-white dark:bg-navy-900 rounded-2xl p-5 shadow-lg"
                    style="scroll-behavior: smooth;">
                    <div class="grid grid-cols-3 gap-5">
                        <template x-for="product in products" :key="product.id">
                            <div @click="addToCart(product)"
                                class="group cursor-pointer rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-navy-800 p-4 shadow-sm hover:shadow-2xl hover:border-accent-500 hover:shadow-accent-500/10 transition-all duration-300 hover:-translate-y-2">
                                <div
                                    class="aspect-square rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-navy-700 dark:to-navy-600 overflow-hidden mb-4 relative">
                                    <template x-if="product.image">
                                        <img :src="'/storage/' + product.image" :alt="product.name"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </template>
                                    <template x-if="!product.image">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="package" class="h-12 w-12 text-slate-400"></i>
                                        </div>
                                    </template>
                                </div>
                                <h4 class="font-bold text-sm text-navy-900 dark:text-white line-clamp-2"
                                    x-text="product.name"></h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] font-black uppercase tracking-wider"
                                        :class="product.stock <= product.min_stock ? 'text-warning' : 'text-success'">
                                        Stok: <span x-text="product.stock"></span>
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" x-text="product.category?.name">
                                </p>
                                <div class="mt-3">
                                    <span
                                        class="text-xl font-bold bg-gradient-to-r from-accent-600 to-accent-500 bg-clip-text text-transparent"
                                        x-text="formatRupiah(product.sell_price)"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div x-show="products.length === 0" class="flex flex-col items-center justify-center h-80">
                        <i data-lucide="inbox" class="h-20 w-20 text-slate-300 mb-4"></i>
                        <p class="text-slate-500 font-medium">Tidak ada produk ditemukan</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Cart & Payment -->
            <div class="flex flex-col gap-4" style="flex-shrink: 0; width: 320px;">

                <!-- CARD 1: Cart Items -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden" style="height: 360px;">
                    <div class="p-4 border-b border-slate-100 dark:border-white/10 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-navy-900 dark:text-white flex items-center gap-2">
                                <i data-lucide="shopping-cart" class="h-4 w-4 text-accent-500"></i>
                                Keranjang
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                <span x-text="cart.length"></span> item •
                                <span x-text="formatRupiah(subtotal)"></span>
                            </p>
                        </div>
                        <button @click="clearCart()" class="p-2 rounded-lg hover:bg-danger/10 text-danger transition-colors"
                            :disabled="cart.length === 0" :class="cart.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                            title="Hapus Keranjang">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </div>

                    <div class="p-3 overflow-y-auto no-scrollbar" style="height: 240px;">
                        <template x-for="(item, index) in cart" :key="item.product_id">
                            <div class="flex gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-navy-800 mb-2 last:mb-0">
                                <div
                                    class="h-12 w-12 rounded-lg bg-slate-200 dark:bg-navy-700 overflow-hidden flex-shrink-0">
                                    <template x-if="item.image">
                                        <img :src="'/storage/' + item.image" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!item.image">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="package" class="h-5 w-5 text-slate-400"></i>
                                        </div>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-navy-900 dark:text-white line-clamp-1"
                                        x-text="item.name"></h4>
                                    <p class="text-xs text-accent-600 dark:text-accent-400 font-bold"
                                        x-text="formatRupiah(item.price)"></p>
                                    <div class="flex items-center gap-1.5 mt-1.5">
                                        <!-- Minus Button -->
                                        <button @click="decreaseQty(index)"
                                            class="flex items-center justify-center w-7 h-7 rounded-lg bg-slate-200 dark:bg-navy-800 border border-slate-300 dark:border-white/20 text-slate-900 dark:text-white hover:bg-accent-500 dark:hover:bg-accent-600 hover:text-white transition-all shadow-sm group/btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                        
                                        <span class="text-sm font-black text-navy-900 dark:text-white w-6 text-center"
                                            x-text="item.qty"></span>
                                            
                                        <!-- Plus Button -->
                                        <button @click="increaseQty(index)"
                                            class="flex items-center justify-center w-7 h-7 rounded-lg bg-slate-200 dark:bg-navy-800 border border-slate-300 dark:border-white/20 text-slate-900 dark:text-white hover:bg-accent-500 dark:hover:bg-accent-600 hover:text-white transition-all shadow-sm group/btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-right flex flex-col justify-between items-end">
                                    <button @click="removeFromCart(index)" class="p-1.5 rounded-lg text-slate-300 hover:text-danger hover:bg-danger/10 transition-all mb-2">
                                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                    </button>
                                    <p class="text-sm font-bold text-navy-900 dark:text-white"
                                        x-text="formatRupiah(item.price * item.qty)"></p>
                                </div>
                            </div>
                        </template>
                        <div x-show="cart.length === 0"
                            class="flex flex-col items-center justify-center h-full text-center py-8">
                            <i data-lucide="shopping-cart" class="h-10 w-10 text-slate-300 mb-2"></i>
                            <p class="text-slate-500 text-sm">Keranjang kosong</p>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Payment Summary (Simplified) -->
                <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-4 flex-1 overflow-y-auto">
                    <div class="space-y-2 mb-4 pb-4 border-b border-slate-100 dark:border-white/10">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
                            <span class="font-bold text-navy-900 dark:text-white" x-text="formatRupiah(subtotal)"></span>
                        </div>
                        <div
                            class="flex justify-between text-lg font-bold pt-2 border-t border-slate-200 dark:border-white/10">
                            <span class="text-navy-900 dark:text-white">Total</span>
                            <span class="text-accent-600 dark:text-accent-400" x-text="formatRupiah(grandTotal)"></span>
                        </div>
                    </div>

                    <!-- Pilih Pembayaran Button -->
                    <button @click="showPaymentMethodModal = true" :disabled="cart.length === 0"
                        class="w-full rounded-xl bg-gradient-to-r from-accent-500 to-accent-600 py-4 text-base font-bold text-white shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:-translate-y-0.5">
                        <i data-lucide="credit-card" class="inline h-5 w-5 mr-2"></i>
                        <span>Pilih Pembayaran</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- PAYMENT METHOD SELECTION MODAL -->
        <div x-show="showPaymentMethodModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showPaymentMethodModal = false"></div>

            <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-8 max-w-2xl w-full shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="text-center mb-8">
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-2">Metode Pembayaran</h3>
                    <p class="text-slate-500 dark:text-slate-400">Pilih metode pembayaran yang diinginkan</p>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <!-- Cash -->
                    <button @click="selectPaymentMethod('cash')"
                        class="group p-5 rounded-2xl border-2 border-slate-200 dark:border-white/10 hover:border-success hover:bg-success/5 transition-all duration-300 hover:-translate-y-1">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-success/30 group-hover:scale-110 transition-transform">
                            <i data-lucide="banknote" class="h-8 w-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-1">Uang Tunai</h4>
                        <p class="text-xs text-slate-500">Pembayaran tunai langsung</p>
                    </button>

                    <!-- QRIS -->
                    <button @click="selectPaymentMethod('qris')"
                        class="group p-5 rounded-2xl border-2 border-slate-200 dark:border-white/10 hover:border-accent-500 hover:bg-accent-500/5 transition-all duration-300 hover:-translate-y-1">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-accent-500/30 group-hover:scale-110 transition-transform">
                            <i data-lucide="qr-code" class="h-8 w-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-1">QRIS</h4>
                        <p class="text-xs text-slate-500">Scan kode QRIS</p>
                    </button>

                    <!-- E-Wallet -->
                    <button @click="selectPaymentMethod('ewallet')"
                        class="group p-5 rounded-2xl border-2 border-slate-200 dark:border-white/10 hover:border-orange-500 hover:bg-orange-500/5 transition-all duration-300 hover:-translate-y-1">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform">
                            <i data-lucide="wallet" class="h-8 w-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-1">E-Wallet</h4>
                        <p class="text-xs text-slate-500">Dana, OVO, GoPay, dll</p>
                    </button>

                    <!-- Debit Card -->
                    <button @click="selectPaymentMethod('debit')"
                        class="group p-5 rounded-2xl border-2 border-slate-200 dark:border-white/10 hover:border-blue-500 hover:bg-blue-500/5 transition-all duration-300 hover:-translate-y-1">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                            <i data-lucide="credit-card" class="h-8 w-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-1">Kartu Debit</h4>
                        <p class="text-xs text-slate-500">Pembayaran kartu debit</p>
                    </button>

                    <!-- Credit Card -->
                    <button @click="selectPaymentMethod('card')"
                        class="group p-5 rounded-2xl border-2 border-slate-200 dark:border-white/10 hover:border-indigo-500 hover:bg-indigo-500/5 transition-all duration-300 hover:-translate-y-1">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform">
                            <i data-lucide="credit-card" class="h-8 w-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-1">Kartu Kredit</h4>
                        <p class="text-xs text-slate-500">Pembayaran kartu kredit</p>
                    </button>

                    <!-- Bank Transfer -->
                    <button @click="selectPaymentMethod('bank')"
                        class="group p-5 rounded-2xl border-2 border-slate-200 dark:border-white/10 hover:border-purple-500 hover:bg-purple-500/5 transition-all duration-300 hover:-translate-y-1">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                            <i data-lucide="building-2" class="h-8 w-8 text-white"></i>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-1">Transfer Bank</h4>
                        <p class="text-xs text-slate-500">Transfer ke rekening bank</p>
                    </button>
                </div>

                <button @click="showPaymentMethodModal = false"
                    class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>
        </div>

        <!-- CASH PAYMENT MODAL -->
        <div x-show="showCashModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showCashModal = false"></div>

            <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-8 max-w-md w-full shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="text-center mb-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-success/30">
                        <i data-lucide="banknote" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-2">Pembayaran Tunai</h3>
                    <div
                        class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-navy-800 dark:to-navy-700 rounded-2xl p-4 mb-4">
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Total Tagihan</p>
                        <p class="text-3xl font-black text-accent-600 dark:text-accent-400"
                            x-text="formatRupiah(grandTotal)"></p>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Uang Diterima</label>
                    <input type="number" x-model="paidAmount" min="0" :placeholder="'Minimal ' + formatRupiah(grandTotal)"
                        class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-4 text-lg font-bold focus:border-success focus:ring-4 focus:ring-success/20 dark:bg-navy-800 dark:text-white transition-all">

                    <!-- Quick Cash Buttons -->
                    <div class="grid grid-cols-3 gap-2 mt-3">
                        <template x-for="amount in [50000, 100000, 200000]">
                            <button @click="paidAmount = amount"
                                class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-navy-800 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-success hover:text-white transition-all">
                                <span x-text="formatRupiah(amount)"></span>
                            </button>
                        </template>
                    </div>

                    <div x-show="paidAmount >= grandTotal && paidAmount > 0"
                        class="mt-3 p-3 rounded-xl bg-success/10 border border-success/20">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-success">✓ Kembalian</span>
                            <span class="text-lg font-black text-success" x-text="formatRupiah(change)"></span>
                        </div>
                    </div>

                    <div x-show="paidAmount < grandTotal && paidAmount > 0"
                        class="mt-3 p-3 rounded-xl bg-warning/10 border border-warning/20">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-warning">⚠ Kurang</span>
                            <span class="text-lg font-black text-warning"
                                x-text="formatRupiah(grandTotal - paidAmount)"></span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="showCashModal = false; showPaymentMethodModal = true"
                        class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                        Batal
                    </button>
                    <button @click="processCashPayment()" :disabled="paidAmount < grandTotal"
                        class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-success to-success/80 text-white font-bold shadow-lg shadow-success/30 disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-success/50 transition-all">
                        Konfirmasi Bayar
                    </button>
                </div>
            </div>
        </div>

        <!-- DEBIT CARD MODAL -->
        <div x-show="showDebitModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showDebitModal = false"></div>

            <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-8 max-w-md w-full shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="text-center mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/30">
                        <i data-lucide="credit-card" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-2">Kartu Debit</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Total: <span class="font-bold text-accent-600" x-text="formatRupiah(grandTotal)"></span></p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pilih Bank EDC</label>
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="bank in banks" :key="bank.id">
                                <button @click="selectedProvider = bank.name"
                                    :class="selectedProvider === bank.name ? 'border-blue-500 bg-blue-50/30 ring-2 ring-blue-500/20' : 'border-slate-100 dark:border-white/5 bg-white dark:bg-navy-800 shadow-sm'"
                                    class="w-full p-2 rounded-2xl border flex items-center gap-3 transition-all hover:border-blue-500 group text-left relative overflow-hidden">
                                    
                                    <!-- Logo Box (Identical to Bank Modal) -->
                                    <div class="w-16 h-8 flex-shrink-0 rounded-lg bg-white dark:bg-white flex items-center justify-center overflow-hidden p-0 shadow-sm">
                                        <img :src="'/storage/logo-bank/' + bank.name.toLowerCase() + '.jpg'" 
                                             :alt="bank.name"
                                             class="w-full h-full object-cover"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        <div class="hidden w-full h-full items-center justify-center font-black text-[10px] uppercase text-slate-300 bg-slate-50" x-text="bank.name.substring(0, 3)"></div>
                                    </div>
        
                                    <span class="text-[11px] font-black text-navy-900 dark:text-white truncate" x-text="bank.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nomor Kartu (4 Digit Terakhir)</label>
                        <input type="text" x-model="cardNumber" maxlength="4" placeholder="Contoh: 1234"
                            class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-lg font-mono font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all text-center tracking-[0.5em]">
                    </div>
                </div>

                <div class="flex gap-3 pt-6">
                    <button @click="showDebitModal = false; showPaymentMethodModal = true"
                        class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                        Batal
                    </button>
                    <button @click="processDebitPayment()" :disabled="!selectedProvider || cardNumber.length < 4"
                        class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold shadow-lg shadow-blue-500/30 disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-blue-500/50 transition-all">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>

        <!-- CREDIT CARD MODAL -->
        <div x-show="showCardModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showCardModal = false"></div>

            <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-8 max-w-md w-full shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="text-center mb-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/30">
                        <i data-lucide="credit-card" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-2">Kartu Kredit/Debit</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Total: <span
                            class="font-bold text-accent-600" x-text="formatRupiah(grandTotal)"></span></p>
                </div>

                <form @submit.prevent="processCardPayment" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nomor Kartu</label>
                        <input type="text" x-model="cardNumber" required placeholder="1234 5678 9012 3456"
                            @input="formatCardNumber($event)"
                            class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-lg font-mono font-bold tracking-widest focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tanggal Expired</label>
                            <input type="text" x-model="cardExpiry" required placeholder="MM/YY"
                                @input="formatCardExpiry($event)"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-lg font-mono font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">CVV</label>
                            <input type="text" x-model="cardCvv" required placeholder="123" maxlength="3"
                                @input="cardNumberOnly($event, 'cardCvv')"
                                class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-lg font-mono font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Pemilik Kartu</label>
                        <input type="text" x-model="cardHolder" required placeholder="NAMA PEMILIK KARTU"
                            @input="toUpperCaseHolder($event)"
                            class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm font-black tracking-wider focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white transition-all">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="showCardModal = false; showPaymentMethodModal = true"
                            class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all">
                            Bayar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- BANK TRANSFER MODAL -->
        <div x-show="showBankModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showBankModal = false"></div>

            <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-8 max-w-md w-full shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="text-center mb-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-purple-500/30">
                        <i data-lucide="building-2" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-2">Transfer Bank</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Total: <span
                            class="font-bold text-accent-600" x-text="formatRupiah(grandTotal)"></span></p>
                </div>

                <div class="space-y-3 mb-6 max-h-[300px] overflow-y-auto no-scrollbar pr-1">
                    <template x-for="bank in banks" :key="bank.id">
                        <button @click="selectedProvider = bank.name"
                            :class="selectedProvider === bank.name ? 'border-accent-500 bg-accent-50/30 ring-2 ring-accent-500/20' : 'border-slate-100 dark:border-white/5 bg-white dark:bg-navy-800 shadow-sm'"
                            class="w-full p-3 rounded-2xl border flex items-center gap-4 transition-all hover:border-accent-500 group text-left relative overflow-hidden">
                            
                            <!-- Logo Box (Compact) -->
                            <div class="w-20 h-10 flex-shrink-0 rounded-xl bg-white dark:bg-white flex items-center justify-center overflow-hidden p-0 shadow-sm">
                                <img :src="'/storage/logo-bank/' + bank.name.toLowerCase() + '.jpg'" 
                                     :alt="bank.name"
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                <div class="hidden w-full h-full items-center justify-center font-black text-[10px] uppercase text-slate-300 bg-slate-50" x-text="bank.name.substring(0, 3)"></div>
                            </div>

                            <!-- Text Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-black text-navy-900 dark:text-white text-sm" x-text="bank.name"></h4>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium truncate" x-text="'Bank ' + bank.name + ' - ' + bank.account_number"></p>
                            </div>

                            <!-- Custom Checkbox (Compact) -->
                            <div class="flex-shrink-0">
                                <div :class="selectedProvider === bank.name ? 'border-accent-500 bg-accent-500' : 'border-slate-200 dark:border-white/20 bg-transparent'"
                                     class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-300">
                                    <i data-lucide="check" x-show="selectedProvider === bank.name" class="w-3 h-3 text-white"></i>
                                </div>
                            </div>
                        </button>
                    </template>
                </div>

                <div class="flex gap-3">
                    <button @click="showBankModal = false; showPaymentMethodModal = true"
                        class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                        Batal
                    </button>
                    <button @click="processBankPayment()" :disabled="!selectedProvider"
                        class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold shadow-lg shadow-purple-500/30 disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-purple-500/50 transition-all">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>

        <!-- E-WALLET PAYMENT MODAL -->
        <div x-show="showEwalletModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showEwalletModal = false"></div>

            <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-8 max-w-md w-full shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="text-center mb-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-orange-500/30">
                        <i data-lucide="wallet" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-2">Pilih E-Wallet</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Total: <span
                            class="font-bold text-accent-600" x-text="formatRupiah(grandTotal)"></span></p>
                </div>

                <div class="space-y-3 mb-6 max-h-[300px] overflow-y-auto no-scrollbar pr-1">
                    <template x-for="wallet in ewallets" :key="wallet.id">
                        <button @click="selectedProvider = wallet.name"
                            :class="selectedProvider === wallet.name ? 'border-orange-500 bg-orange-50/30 ring-2 ring-orange-500/20' : 'border-slate-100 dark:border-white/5 bg-white dark:bg-navy-800 shadow-sm'"
                            class="w-full p-3 rounded-2xl border flex items-center gap-4 transition-all hover:border-orange-500 group text-left relative overflow-hidden">
                            
                            <!-- Logo Box (Compact) -->
                            <div class="w-20 h-10 flex-shrink-0 rounded-xl bg-white dark:bg-white flex items-center justify-center overflow-hidden p-0 shadow-sm">
                                <img :src="'/storage/logo-ewallet/' + wallet.name.toLowerCase() + '.jpg'" 
                                     :alt="wallet.name"
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                <div class="hidden w-full h-full items-center justify-center font-black text-[10px] uppercase text-slate-300 bg-slate-50" x-text="wallet.name.substring(0, 3)"></div>
                            </div>

                            <!-- Text Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-black text-navy-900 dark:text-white text-sm" x-text="wallet.name"></h4>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium truncate" x-text="'Bayar dengan aplikasi ' + wallet.name"></p>
                            </div>

                            <!-- Custom Checkbox (Compact) -->
                            <div class="flex-shrink-0">
                                <div :class="selectedProvider === wallet.name ? 'border-orange-500 bg-orange-500' : 'border-slate-200 dark:border-white/20 bg-transparent'"
                                     class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all duration-300">
                                    <i data-lucide="check" x-show="selectedProvider === wallet.name" class="w-3 h-3 text-white"></i>
                                </div>
                            </div>
                        </button>
                    </template>
                </div>

                <div class="flex gap-3">
                    <button @click="showEwalletModal = false; showPaymentMethodModal = true"
                        class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                        Batal
                    </button>
                    <button @click="processEwalletPayment()" :disabled="!selectedProvider"
                        class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold shadow-lg shadow-orange-500/30 disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-orange-500/50 transition-all">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
        <div x-show="showQrisModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showQrisModal = false"></div>

            <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-8 max-w-md w-full shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="text-center mb-6">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-accent-500/30">
                        <i data-lucide="qr-code" class="h-8 w-8 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-black text-navy-900 dark:text-white mb-2">QRIS / E-Wallet</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Scan QR code untuk pembayaran</p>
                </div>

                <div class="bg-white rounded-2xl p-6 mb-6 border-2 border-slate-200 dark:border-white/10">
                    <div x-show="qrisLoading" class="flex items-center justify-center h-48">
                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-accent-500 border-t-transparent">
                        </div>
                    </div>
                    <img x-show="!qrisLoading" :src="qrisQrCode" alt="QRIS" class="w-full h-auto mx-auto">
                </div>

                <div
                    class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-navy-800 dark:to-navy-700 rounded-2xl p-4 mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Total Pembayaran</span>
                        <span class="text-2xl font-black text-accent-600 dark:text-accent-400"
                            x-text="formatRupiah(grandTotal)"></span>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button @click="showQrisModal = false; showPaymentMethodModal = true"
                        class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                        Batal
                    </button>
                    <button @click="confirmQrisPayment()"
                        class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-accent-500 to-accent-600 text-white font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all">
                        Konfirmasi Bayar
                    </button>
                </div>
            </div>
        </div>

        <!-- SUCCESS MODAL -->
        <div x-show="showSuccessModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showSuccessModal = false"></div>
            <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-8 max-w-md w-full shadow-2xl">
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 bg-success/20 rounded-full animate-ping"></div>
                        <div
                            class="relative w-20 h-20 bg-gradient-to-br from-success to-success/80 rounded-full flex items-center justify-center shadow-lg shadow-success/30">
                            <i data-lucide="check" class="h-12 w-12 text-white" style="stroke-width: 4;"></i>
                        </div>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-center text-navy-900 dark:text-white mb-2">Transaksi Berhasil!</h3>
                <p class="text-center text-slate-500 dark:text-slate-400 mb-6">Pembayaran berhasil diproses</p>
                <div
                    class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-navy-800 dark:to-navy-700 rounded-2xl p-5 mb-6 border border-slate-200 dark:border-white/10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm text-slate-500 dark:text-slate-400">No. Invoice</span>
                        <span class="text-xs px-2.5 py-1 bg-success/10 text-success rounded-full font-semibold">Lunas</span>
                    </div>
                    <p class="text-lg font-bold text-navy-900 dark:text-white font-mono mb-4" x-text="lastInvoice"></p>
                    
                    <div class="mb-3">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Metode Pembayaran</span>
                        <div class="flex items-center gap-3 mt-1">
                            <template x-if="paymentMethod === 'ewallet' || paymentMethod === 'bank' || paymentMethod === 'debit'">
                                <div class="w-12 h-6 flex-shrink-0 rounded-md bg-white dark:bg-white flex items-center justify-center overflow-hidden p-0 shadow-sm">
                                    <img :src="'/storage/logo-' + (paymentMethod === 'ewallet' ? 'ewallet' : 'bank') + '/' + selectedProvider.toLowerCase() + '.jpg'" 
                                         class="w-full h-full object-cover"
                                         onerror="this.parentElement.style.display='none'">
                                </div>
                            </template>
                            <template x-if="paymentMethod === 'qris'">
                                <div class="w-8 h-6 flex-shrink-0 rounded-md bg-white flex items-center justify-center overflow-hidden p-0.5 shadow-sm">
                                    <i data-lucide="qr-code" class="w-4 h-4 text-accent-600"></i>
                                </div>
                            </template>
                            <template x-if="paymentMethod === 'cash'">
                                <div class="w-8 h-6 flex-shrink-0 rounded-md bg-success/10 flex items-center justify-center overflow-hidden p-0.5 shadow-sm">
                                    <i data-lucide="banknote" class="w-4 h-4 text-success"></i>
                                </div>
                            </template>
                            <span class="text-lg font-bold text-navy-900 dark:text-white font-mono uppercase" x-text="getPaymentMethodLabel()"></span>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 dark:border-white/10 pt-3 mt-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Total Pembayaran</span>
                            <span class="text-xl font-bold text-accent-600 dark:text-accent-400"
                                x-text="formatRupiah(grandTotal)"></span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button @click="printReceipt()"
                        class="flex-1 flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">
                        <i data-lucide="printer" class="h-5 w-5"></i>
                        <span>Cetak Struk</span>
                    </button>
                    <button @click="newTransaction()"
                        class="flex-1 flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all">
                        <i data-lucide="plus" class="h-5 w-5"></i>
                        <span>Transaksi Baru</span>
                    </button>
                </div>
                <button @click="showSuccessModal = false"
                    class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
        </div>

        <!-- LOADING OVERLAY -->
        <div x-show="loading" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-2xl">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-accent-500 border-t-transparent mx-auto">
                </div>
                <p class="text-center mt-4 text-sm font-bold text-navy-900 dark:text-white">Memproses...</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function posApp() {
                return {
                    products: [], cart: [], search: '', categoryId: '', customerId: '',
                    categoryName: 'Semua Kategori', customerName: 'Pelanggan Umum',
                    discount: 0, paymentMethod: '', paidAmount: 0,
                    loading: false, qrisLoading: false, qrisQrCode: '',
                    showSuccessModal: false, lastInvoice: '', lastTransaction: null,
                    dailySales: 0, dailyTransactions: 0,
                    recentTransactions: [],
                    storeName: '{{ config("app.name") }}',
                    storeAddress: '',
                    storePhone: '',
                    receiptFooter: 'Terima kasih!',

                    // Payment modals
                    showPaymentMethodModal: false,
                    showCashModal: false,
                    showDebitModal: false,
                    showCardModal: false,
                    showBankModal: false,
                    showEwalletModal: false,
                    showQrisModal: false,

                    // Card payment
                    cardNumber: '',
                    cardExpiry: '',
                    cardCvv: '',
                    cardHolder: '',

                    // Banks & E-Wallets from DB
                    banks: @json($banks),
                    ewallets: @json($ewallets),
                    selectedProvider: '',

                    init() {
                        this.searchProducts();
                        this.loadDailySales();
                        this.loadStoreSettings();
                        
                        // Auto-sync data every 10 seconds
                        setInterval(() => {
                            this.searchProducts();
                            this.loadDailySales();
                        }, 10000);

                        setTimeout(() => lucide.createIcons(), 100);
                    },

                    loadStoreSettings() {
                        fetch('/api/settings', { headers: { 'Accept': 'application/json' } })
                            .then(r => r.json())
                            .then(data => {
                                this.storeName = data.store_name || this.storeName;
                                this.storeAddress = data.store_address || '';
                                this.storePhone = data.store_phone || '';
                                this.storeLogo = data.store_logo_url || '';
                                this.storeTagline = data.store_tagline || '';
                                this.receiptFooter = data.receipt_footer || 'Terima kasih!';
                            })
                            .catch(() => { });
                    },

                    searchProducts() {
                        const params = new URLSearchParams({ search: this.search, category_id: this.categoryId });
                        fetch('/cashier/products?' + params, { headers: { 'Accept': 'application/json' } })
                            .then(r => r.json()).then(data => {
                                this.products = data.success ? (data.products || []) : [];
                                setTimeout(() => lucide.createIcons(), 100);
                            })
                            .catch(() => this.products = []);
                    },

                    scanBarcode() {
                        const barcode = this.search.trim();
                        if (!barcode) return;
                        fetch('/cashier/products/by-barcode?barcode=' + encodeURIComponent(barcode), { headers: { 'Accept': 'application/json' } })
                            .then(r => r.json()).then(data => {
                                if (data.success) {
                                    this.addToCart(data.product);
                                    this.search = '';
                                } else {
                                    alert(data.message || 'Produk tidak ditemukan');
                                }
                            })
                            .catch(() => alert('Gagal scan barcode'));
                    },

                    addToCart(product) {
                        const existing = this.cart.find(item => item.product_id === product.id);
                        if (existing) {
                            if (existing.qty < product.stock) {
                                existing.qty++;
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Stok Habis!',
                                    text: 'Waduh King, stok produk ini sudah mentok!',
                                    confirmButtonColor: '#3b82f6',
                                    background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
                                });
                            }
                        } else {
                            this.cart.push({
                                product_id: product.id,
                                name: product.name,
                                price: parseFloat(product.sell_price),
                                image: product.image,
                                qty: 1,
                                max_stock: product.stock
                            });
                        }
                        setTimeout(() => lucide.createIcons(), 50);
                    },

                    increaseQty(index) {
                        const item = this.cart[index];
                        if (item.qty < item.max_stock) {
                            item.qty++;
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Stok Terbatas!',
                                text: 'Gak bisa nambah lagi King, stoknya cuma segitu.',
                                confirmButtonColor: '#3b82f6',
                                background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
                            });
                        }
                        setTimeout(() => lucide.createIcons(), 50);
                    },

                    decreaseQty(index) {
                        if (this.cart[index].qty > 1) {
                            this.cart[index].qty--;
                        } else {
                            this.removeFromCart(index);
                        }
                        setTimeout(() => lucide.createIcons(), 50);
                    },

                    removeFromCart(index) {
                        Swal.fire({
                            title: 'Buang item ini, King?',
                            text: "Item ini bakal dihapus dari list keranjang.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Ya, Buang!',
                            cancelButtonText: 'Jangan deh',
                            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#1e293b',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.cart.splice(index, 1);
                                setTimeout(() => lucide.createIcons(), 50);
                            }
                        });
                    },

                    clearCart() {
                        if (this.cart.length === 0) return;
                        Swal.fire({
                            title: 'Yakin mau dihapus, KING?',
                            text: "Semua item di keranjang bakal ilang loh, ntar nyesel!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Yakin',
                            cancelButtonText: 'Bentar dulu',
                            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#1e293b',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.cart = [];
                                setTimeout(() => lucide.createIcons(), 50);
                            }
                        });
                    },

                    selectPaymentMethod(method) {
                        this.showPaymentMethodModal = false;
                        this.paymentMethod = method;
                        this.selectedProvider = ''; // Reset provider

                        if (method === 'cash') {
                            this.showCashModal = true;
                        } else if (method === 'debit') {
                            this.showDebitModal = true;
                        } else if (method === 'card') {
                            this.showCardModal = true;
                        } else if (method === 'bank') {
                            this.showBankModal = true;
                        } else if (method === 'ewallet') {
                            this.showEwalletModal = true;
                        } else if (method === 'qris') {
                            this.showQrisModal = true;
                            this.generateQrisCode();
                        }
                    },

                    processBankPayment() {
                        if (!this.selectedProvider) return;
                        this.showBankModal = false;
                        this.processTransaction();
                    },

                    processEwalletPayment() {
                        if (!this.selectedProvider) return;
                        this.showEwalletModal = false;
                        this.processTransaction();
                    },

                    generateQrisCode() {
                        this.qrisLoading = true;
                        const qrisData = { invoice: 'QRIS-' + Date.now(), amount: this.grandTotal, merchant: '{{ config("app.name") }}' };
                        setTimeout(() => {
                            const qrData = encodeURIComponent(JSON.stringify(qrisData));
                            this.qrisQrCode = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${qrData}`;
                            this.qrisLoading = false;
                        }, 1500);
                    },

                    processCashPayment() {
                        if (this.paidAmount < this.grandTotal) {
                            alert('Jumlah pembayaran kurang!');
                            return;
                        }
                        this.showCashModal = false;
                        this.processTransaction();
                    },

                    processDebitPayment() {
                        if (!this.selectedProvider || this.cardNumber.length < 4) {
                            alert('Mohon pilih bank dan isi 4 digit nomor kartu!');
                            return;
                        }
                        this.showDebitModal = false;
                        this.processTransaction();
                    },

                    processCardPayment() {
                        if (!this.cardNumber || !this.cardExpiry || !this.cardCvv || !this.cardHolder) {
                            alert('Mohon lengkapi semua data kartu!');
                            return;
                        }
                        this.showCardModal = false;
                        this.processTransaction();
                    },

                    // Card Input Formatters
                    formatCardNumber(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        let formatted = value.match(/.{1,4}/g)?.join(' ') || '';
                        this.cardNumber = formatted.substring(0, 19);
                    },
                    formatCardExpiry(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        if (value.length > 2) {
                            this.cardExpiry = value.substring(0, 2) + '/' + value.substring(2, 4);
                        } else {
                            this.cardExpiry = value;
                        }
                    },
                    cardNumberOnly(e, field) {
                        this[field] = e.target.value.replace(/\D/g, '');
                    },
                    toUpperCaseHolder(e) {
                        this.cardHolder = e.target.value.toUpperCase();
                    },

                    confirmQrisPayment() {
                        this.showQrisModal = false;
                        this.processTransaction();
                    },

                    processTransaction() {
                        if (this.cart.length === 0) { alert('Keranjang masih kosong!'); return; }

                        this.loading = true;
                        fetch('/cashier/transaction', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            },
                            body: JSON.stringify({
                                customer_id: this.customerId || null,
                                items: this.cart,
                                discount: this.discount,
                                payment_method: this.paymentMethod,
                                payment_provider: this.selectedProvider,
                                paid_amount: (this.paymentMethod === 'cash') ? this.paidAmount : this.grandTotal,
                            })
                        })
                            .then(r => r.json()).then(data => {
                                this.loading = false;
                                if (data.success) {
                                    this.lastInvoice = data.invoice_code;
                                    this.lastTransaction = data.transaction;
                                    this.showSuccessModal = true;
                                    this.loadDailySales();
                                    this.resetPaymentModals();
                                } else {
                                    alert(data.message || 'Terjadi kesalahan');
                                }
                            })
                            .catch(() => {
                                this.loading = false;
                                alert('Terjadi kesalahan. Silakan coba lagi.');
                            });
                    },

                    resetPaymentModals() {
                        this.showPaymentMethodModal = false;
                        this.showCashModal = false;
                        this.showDebitModal = false;
                        this.showCardModal = false;
                        this.showBankModal = false;
                        this.showEwalletModal = false;
                        this.showQrisModal = false;
                        this.paidAmount = 0;
                        this.cardNumber = '';
                        this.cardExpiry = '';
                        this.cardCvv = '';
                        this.cardHolder = '';
                        this.selectedBank = '';
                        this.qrisLoading = false;
                        this.qrisQrCode = '';
                    },

                    loadDailySales() {
                        fetch('/cashier/daily-sales', { headers: { 'Accept': 'application/json' } })
                            .then(r => r.json()).then(data => {
                                if (data.success) {
                                    this.dailySales = data.total_sales || 0;
                                    this.dailyTransactions = data.total_transactions || 0;
                                }
                            })
                            .catch(() => {
                                this.dailySales = 0;
                                this.dailyTransactions = 0;
                            });
                    },

                    getPaymentMethodLabel() {
                        const labels = {
                            'cash': 'Uang Tunai',
                            'qris': 'QRIS',
                            'ewallet': 'E-Wallet',
                            'debit': 'Kartu Debit',
                            'card': 'Kartu Kredit',
                            'bank': 'Transfer Bank'
                        };
                        let label = labels[this.paymentMethod] || this.paymentMethod;
                        if (this.selectedProvider) {
                            label += ' (' + this.selectedProvider + ')';
                        }
                        return label;
                    },

                    get subtotal() { return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0); },
                    get grandTotal() { return Math.max(0, this.subtotal - this.discount); },
                    get change() { return Math.max(0, this.paidAmount - this.grandTotal); },

                    formatRupiah(amount) { return 'Rp ' + parseFloat(amount || 0).toLocaleString('id-ID'); },

                    newTransaction() {
                        this.cart = [];
                        this.search = '';
                        this.discount = 0;
                        this.paidAmount = 0;
                        this.showSuccessModal = false;
                        this.paymentMethod = '';
                        this.resetPaymentModals();
                        this.searchProducts();
                    },

                    printReceipt(transactionId) {
                        const transaction = transactionId ?
                            this.recentTransactions.find(t => t.id === transactionId) :
                            this.lastTransaction;

                        if (!transaction) {
                            alert('Tidak ada data transaksi untuk dicetak');
                            return;
                        }

                        const printWindow = window.open('', '_blank');
                        printWindow.document.write(`
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <meta charset="utf-8">
                                    <title>Receipt - ${transaction.invoice_code}</title>
                                    <style>
                                        * { 
                                            margin: 0; 
                                            padding: 0; 
                                            box-sizing: border-box; 
                                        }
                                        body { 
                                            font-family: 'Courier New', Courier, monospace; 
                                            width: 58mm; 
                                            margin: 0 auto; 
                                            padding: 5px; 
                                            font-size: 10px;
                                            color: #000;
                                            line-height: 1.2;
                                        }
                                        .text-center { text-align: center; }
                                        .text-right { text-align: right; }
                                        .bold { font-weight: bold; }
                                        .uppercase { text-transform: uppercase; }
                                        
                                        /* Header Section */
                                        .header { margin-bottom: 10px; text-align: center; }
                                        .store-container { 
                                            display: flex; 
                                            align-items: center; 
                                            justify-content: center; 
                                            gap: 3px; /* Reduced gap to bring name closer to logo */
                                            margin-bottom: 4px; 
                                        }
                                        .store-logo { 
                                            height: 28px; 
                                            width: auto; 
                                            object-fit: contain; 
                                            filter: brightness(0); 
                                        }
                                        .store-name { 
                                            font-size: 12px; 
                                            font-weight: 900; 
                                            letter-spacing: 0.3px; 
                                            margin-top: 2px; /* Lowered slightly */
                                        }
                                        .store-address { font-size: 8px; line-height: 1.3; }
                                        
                                        .divider { border-bottom: 1px dashed #000; margin: 6px 0; }
                                        .divider-double { border-bottom: 2px dashed #000; margin: 6px 0; }

                                        /* Transaction Info */
                                        .info-section { font-size: 8px; margin-bottom: 8px; }
                                        .info-row { display: flex; justify-content: space-between; margin-bottom: 2px; }
                                        
                                        /* Items */
                                        .item-row { margin-bottom: 5px; }
                                        .item-top { display: flex; justify-content: space-between; font-weight: bold; }
                                        .item-bottom { font-size: 8px; color: #333; }

                                        /* Summary Section */
                                        .summary-section { margin-top: 5px; }
                                        .summary-row { display: flex; justify-content: space-between; margin-bottom: 2px; }
                                        .grand-total { font-size: 12px; font-weight: bold; border-top: 1px dashed #000; padding-top: 4px; margin-top: 4px; }

                                        .footer { margin-top: 15px; text-align: center; }
                                        .thanks { font-size: 10px; font-weight: bold; margin-bottom: 3px; }
                                        .notice { font-size: 7px; opacity: 0.8; }

                                        @media print {
                                            body { width: 58mm; padding: 2px; }
                                            @page { margin: 0; size: 58mm auto; }
                                        }
                                    </style>
                                </head>
                                <body onload="window.print()">
                                    <!-- Header -->
                                    <div class="header">
                                        <div class="store-container">
                                            ${this.storeLogo ? `<img src="${this.storeLogo}" class="store-logo" onerror="this.style.display='none'">` : ''}
                                            <div class="store-name uppercase">${this.storeName}</div>
                                        </div>
                                        <div class="store-address uppercase">
                                            ${this.storeAddress || ''}<br>
                                            ${this.storePhone ? `TELP: ${this.storePhone}` : ''}
                                        </div>
                                    </div>

                                    <div class="divider-double"></div>

                                    <!-- Meta -->
                                    <div class="info-section">
                                        <div class="info-row">
                                            <span>NO: ${transaction.invoice_code}</span>
                                            <span>${new Date(transaction.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })}</span>
                                        </div>
                                        <div class="info-row">
                                            <span>KASIR: ${this.cashierName || 'ADMIN'}</span>
                                            <span>${new Date(transaction.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span>
                                        </div>
                                        <div class="info-row">
                                            <span>PELANGGAN: ${transaction.customer ? transaction.customer.name : 'UMUM'}</span>
                                        </div>
                                    </div>

                                    <div class="divider"></div>

                                    <!-- Items -->
                                    <div class="purchase-list">
                                        ${transaction.items.map(item => `
                                            <div class="item-row">
                                                <div class="item-top">
                                                    <span>${item.product.name}</span>
                                                    <span>Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</span>
                                                </div>
                                                <div class="item-bottom">
                                                    ${item.qty} x Rp ${new Intl.NumberFormat('id-ID').format(item.price)}
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>

                                    <div class="divider"></div>

                                    <!-- Summary -->
                                    <div class="summary-section">
                                        <div class="summary-row">
                                            <span>TUNAI</span>
                                            <span>Rp ${new Intl.NumberFormat('id-ID').format(transaction.paid_amount || 0)}</span>
                                        </div>
                                        <div class="summary-row">
                                            <span>KEMBALI</span>
                                            <span>Rp ${new Intl.NumberFormat('id-ID').format(transaction.change_amount || 0)}</span>
                                        </div>

                                        <div class="divider"></div>

                                        ${transaction.tax > 0 ? `
                                        <div class="summary-row">
                                            <span>PAJAK (11%)</span>
                                            <span>Rp ${new Intl.NumberFormat('id-ID').format(transaction.tax)}</span>
                                        </div>
                                        ` : ''}

                                        <div class="summary-row">
                                            <span>SUBTOTAL</span>
                                            <span>Rp ${new Intl.NumberFormat('id-ID').format(transaction.subtotal)}</span>
                                        </div>

                                        <div class="summary-row grand-total">
                                            <span>TOTAL</span>
                                            <span>Rp ${new Intl.NumberFormat('id-ID').format(transaction.grand_total)}</span>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="footer uppercase">
                                        <div class="thanks">${this.receiptFooter || 'TERIMA KASIH!'}</div>
                                        <div class="notice">
                                            BARANG YANG SUDAH DIBELI TIDAK DAPAT DITUKAR/DIKEMBALIKAN
                                        </div>
                                    </div>
                                </body>
                                </html>
                            `);
                        printWindow.document.close();
                    }
                }
            }
        </script>
    @endpush

    @push('styles')
        <style>
            [x-cloak] {
                display: none !important;
            }

            .overflow-y-auto {
                scroll-behavior: smooth;
            }

            .overflow-y-auto::-webkit-scrollbar {
                width: 5px;
            }

            .overflow-y-auto::-webkit-scrollbar-track {
                background: transparent;
            }

            .overflow-y-auto::-webkit-scrollbar-thumb {
                background: #CBD5E1;
                border-radius: 3px;
            }
        </style>
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

            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush
@endsection