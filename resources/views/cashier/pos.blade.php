@extends('layouts.app')

@section('content')
    <div class="h-[calc(100vh-8rem)]" x-data="posApp()" x-init="init()">
        <div class="flex h-full gap-4">

            <!-- LEFT: Product Grid -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Search & Filters -->
                <div class="relative z-30 bg-white/80 dark:bg-navy-900/80 backdrop-blur-xl border border-white/20 dark:border-white/5 rounded-2xl p-4 shadow-xl shadow-slate-200/50 dark:shadow-black/20 mb-4 animate-fade-in-down">
                    <div class="flex flex-wrap gap-4 items-end">
                        <!-- Search Input -->
                        <div class="flex-1 min-w-[300px]">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Cari Produk</label>
                            <div class="relative group">
                                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                                <input type="text" x-model="search" @input.debounce.300ms="searchProducts()"
                                    placeholder="Nama, SKU, atau scan barcode..."
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-12 pr-12 py-3 text-sm font-medium focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner"
                                    @keydown.enter="scanBarcode()">
                                <button @click="$refs.barcodeInput.focus()" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 rounded-lg hover:bg-white dark:hover:bg-navy-700 text-slate-400 hover:text-accent-500 transition-all">
                                    <i data-lucide="scan-barcode" class="h-5 w-5"></i>
                                </button>
                            </div>
                            <input type="text" x-ref="barcodeInput" class="hidden" @input.debounce.300ms="scanBarcode()">
                        </div>

                        <!-- Category Filter -->
                        <div x-data="{ 
                            open: false, 
                            selectedLabel: 'Semua Kategori'
                        }" class="relative w-full md:w-56">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Kategori</label>
                            <button type="button" @click="open = !open" @click.away="open = false"
                                    class="w-full flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm font-bold transition-all hover:bg-white focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                                <span x-text="selectedLabel"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                            </button>
                            <div x-show="open" x-transition class="absolute z-50 mt-2 w-full rounded-2xl bg-white/95 dark:bg-navy-900/95 p-2 shadow-2xl backdrop-blur-xl border border-white/20 dark:border-white/5">
                                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                    <button @click="categoryId = ''; selectedLabel = 'Semua Kategori'; open = false; searchProducts()"
                                            class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all"
                                            :class="categoryId === '' ? 'bg-accent-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                        Semua Kategori
                                    </button>
                                    @foreach($categories as $cat)
                                        <button @click="categoryId = '{{ $cat->id }}'; selectedLabel = '{{ $cat->name }}'; open = false; searchProducts()"
                                                class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all"
                                                :class="categoryId === '{{ $cat->id }}' ? 'bg-accent-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                            {{ $cat->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Customer Filter -->
                        <div x-data="{ 
                            open: false, 
                            selectedLabel: 'Pelanggan Umum'
                        }" class="relative w-full md:w-64">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Pelanggan</label>
                            <button type="button" @click="open = !open" @click.away="open = false"
                                    class="w-full flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm font-bold transition-all hover:bg-white focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                                <span x-text="selectedLabel"></span>
                                <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                            </button>
                            <div x-show="open" x-transition class="absolute z-50 mt-2 w-full rounded-2xl bg-white/95 dark:bg-navy-900/95 p-2 shadow-2xl backdrop-blur-xl border border-white/20 dark:border-white/5">
                                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                    <button @click="customerId = ''; selectedLabel = 'Pelanggan Umum'; open = false"
                                            class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all"
                                            :class="customerId === '' ? 'bg-accent-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                        Pelanggan Umum
                                    </button>
                                    @foreach($customers as $cust)
                                        <button @click="customerId = '{{ $cust->id }}'; selectedLabel = '{{ $cust->name }}'; open = false"
                                                class="w-full text-left px-4 py-2.5 text-sm rounded-xl transition-all"
                                                :class="customerId === '{{ $cust->id }}' ? 'bg-accent-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5'">
                                            {{ $cust->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="flex-1 overflow-y-auto rounded-3xl bg-white/50 dark:bg-navy-950/50 backdrop-blur-xl border border-slate-200/50 dark:border-white/5 p-4 shadow-xl shadow-slate-200/20 dark:shadow-black/40 custom-scrollbar animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                        <template x-for="product in products" :key="product.id">
                            <div @click="addToCart(product)"
                                class="group relative flex flex-col cursor-pointer rounded-2xl bg-white dark:bg-navy-900 p-3 shadow-md hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 border border-slate-100 dark:border-white/5">
                                
                                <!-- Product Image Container -->
                                <div class="relative aspect-[4/3] rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden mb-4 shadow-inner">
                                    <template x-if="product.image">
                                        <img :src="'/storage/' + product.image" :alt="product.name"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </template>
                                    <template x-if="!product.image">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="package" class="h-10 w-10 text-slate-200 dark:text-navy-700"></i>
                                        </div>
                                    </template>
                                    
                                    <!-- Stock Badge -->
                                    <div class="absolute top-2 right-2">
                                        <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider backdrop-blur-md"
                                            :class="product.stock <= product.min_stock ? 'bg-danger/20 text-danger border border-danger/20' : 'bg-success/20 text-success border border-success/20'"
                                            x-text="product.stock + ' STOK'"></span>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1">
                                    <p class="text-[10px] font-bold text-accent-500 uppercase tracking-widest mb-1" x-text="product.category?.name || 'UMUM'"></p>
                                    <h4 class="font-bold text-sm text-navy-900 dark:text-white line-clamp-2 leading-tight mb-2 h-9" x-text="product.name"></h4>
                                    
                                    <div class="flex items-center justify-between mt-auto pt-2 border-t border-slate-50 dark:border-white/5">
                                        <span class="text-base font-black text-navy-900 dark:text-white" x-text="formatRupiah(product.sell_price)"></span>
                                        <div class="bg-accent-500 h-8 w-8 rounded-lg flex items-center justify-center text-white shadow-lg shadow-accent-500/30 group-hover:scale-110 transition-transform">
                                            <i data-lucide="plus" class="h-4 w-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div x-show="products.length === 0" class="flex flex-col items-center justify-center h-64">
                        <i data-lucide="inbox" class="h-16 w-16 text-slate-300 mb-4"></i>
                        <p class="text-slate-500">Tidak ada produk ditemukan</p>
                    </div>
                </div>
            </div>

                <!-- RIGHT: Cart Sidebar -->
                <div class="w-[420px] flex flex-col animate-fade-in-right">
                    <div class="h-full flex flex-col bg-white dark:bg-navy-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-white/5 overflow-hidden">
                        
                        <!-- Cart Header -->
                        <div class="p-5 bg-gradient-to-r from-navy-900 to-navy-800 dark:from-navy-950 dark:to-navy-900 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 rounded-xl bg-white/10 backdrop-blur-md">
                                        <i data-lucide="shopping-cart" class="h-6 w-6"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-black tracking-tight">KASIR POS</h2>
                                        <p class="text-xs text-slate-400" x-text="cart.length + ' Item ditambahkan'"></p>
                                    </div>
                                </div>
                                <button @click="newTransaction()" class="p-2 rounded-xl hover:bg-white/10 text-slate-400 hover:text-white transition-colors">
                                    <i data-lucide="rotate-ccw" class="h-5 w-5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
                            <template x-for="(item, index) in cart" :key="item.product_id">
                                <div class="flex gap-4 p-3.5 rounded-2xl bg-slate-50 dark:bg-navy-800/50 border border-transparent hover:border-accent-500/30 hover:bg-white dark:hover:bg-navy-800 transition-all group">
                                    <!-- Item Image -->
                                    <div class="h-20 w-20 rounded-xl bg-white dark:bg-navy-700 overflow-hidden flex-shrink-0 shadow-sm">
                                        <template x-if="item.image">
                                            <img :src="'/storage/' + item.image" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!item.image">
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <i data-lucide="package" class="h-8 w-8"></i>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Item Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-1">
                                            <h4 class="text-sm font-bold text-navy-900 dark:text-white line-clamp-1 group-hover:text-accent-500 transition-colors" x-text="item.name"></h4>
                                            <button @click="removeFromCart(index)" class="text-slate-300 hover:text-danger p-1 transition-colors">
                                                <i data-lucide="x-circle" class="h-4 w-4"></i>
                                            </button>
                                        </div>
                                        <p class="text-xs font-black text-accent-500 mb-3" x-text="formatRupiah(item.price)"></p>
                                        
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-1 bg-white dark:bg-navy-900 rounded-lg border border-slate-200 dark:border-white/5 p-1">
                                                <button @click="decreaseQty(index)" class="h-7 w-7 rounded-md hover:bg-slate-100 dark:hover:bg-white/5 flex items-center justify-center text-slate-500 transition-colors">
                                                    <i data-lucide="minus" class="h-3 w-3"></i>
                                                </button>
                                                <span class="text-sm font-bold w-10 text-center" x-text="item.qty"></span>
                                                <button @click="increaseQty(index)" class="h-7 w-7 rounded-md hover:bg-slate-100 dark:hover:bg-white/5 flex items-center justify-center text-slate-500 transition-colors">
                                                    <i data-lucide="plus" class="h-3 w-3"></i>
                                                </button>
                                            </div>
                                            <p class="text-sm font-black text-navy-900 dark:text-white" x-text="formatRupiah(item.price * item.qty)"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                        <!-- Empty Cart -->
                        <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-full">
                            <i data-lucide="shopping-cart" class="h-16 w-16 text-slate-300 mb-4"></i>
                            <p class="text-slate-500 text-sm">Keranjang kosong</p>
                            <p class="text-slate-400 text-xs mt-1">Pilih produk untuk memulai</p>
                        </div>
                    </div>

                        <!-- Pay Summary Section -->
                        <div class="p-6 bg-slate-50 dark:bg-navy-950/50 border-t border-slate-100 dark:border-white/5 space-y-5">
                            <!-- Discount Input -->
                            <div class="relative group">
                                <i data-lucide="ticket" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-accent-500"></i>
                                <input type="number" x-model="discount" placeholder="Punya voucher diskon? (Rp)"
                                    class="w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 py-2.5 text-sm dark:border-white/10 dark:bg-navy-800 dark:text-white focus:ring-4 focus:ring-accent-500/10 transition-all font-bold">
                            </div>

                            <!-- Totals Table -->
                            <div class="space-y-2.5">
                                <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                                    <span class="text-xs font-bold uppercase tracking-widest">Subtotal</span>
                                    <span class="text-sm font-black" x-text="formatRupiah(subtotal)"></span>
                                </div>
                                <div class="flex justify-between items-center text-slate-600 dark:text-slate-400">
                                    <span class="text-xs font-bold uppercase tracking-widest">Pajak (<span x-text="taxRate"></span>%)</span>
                                    <span class="text-sm font-black" x-text="formatRupiah(tax)"></span>
                                </div>
                                <div class="flex justify-between items-center text-danger" x-show="discount > 0">
                                    <span class="text-xs font-bold uppercase tracking-widest">Diskon</span>
                                    <span class="text-sm font-black" x-text="'- ' + formatRupiah(discount)"></span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-slate-200 dark:border-white/5">
                                    <span class="text-sm font-black text-navy-900 dark:text-white uppercase tracking-tighter">TOTAL AKHIR</span>
                                    <span class="text-2xl font-black text-accent-500" x-text="formatRupiah(grandTotal)"></span>
                                </div>
                            </div>

                            <!-- Payment Options -->
                            <div class="grid grid-cols-2 gap-3">
                                <template x-for="method in [{id:'cash', label:'TUNAI', icon:'banknote'}, {id:'qris', label:'QRIS', icon:'qr-code'}, {id:'debit', label:'DEBIT', icon:'credit-card'}, {id:'ewallet', label:'E-WALLET', icon:'wallet'}]">
                                    <button @click="selectPayment(method.id)"
                                        :class="paymentMethod === method.id ? 'bg-accent-500 text-white shadow-lg shadow-accent-500/30' : 'bg-white dark:bg-navy-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/5 hover:bg-slate-50 transition-all'"
                                        class="flex items-center justify-center gap-2 rounded-xl py-3 text-[10px] font-black tracking-widest transition-all active:scale-95">
                                        <i :data-lucide="method.icon" class="h-4 w-4"></i>
                                        <span x-text="method.label"></span>
                                    </button>
                                </template>
                            </div>

                            <!-- Form Checkout Buttons -->
                            <div class="space-y-3">
                                <!-- Cash Input (If selected) -->
                                <div x-show="paymentMethod === 'cash'" x-transition class="animate-fade-in-up">
                                    <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">JUMLAH TUNAI</label>
                                    <div class="relative group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-slate-400 group-focus-within:text-accent-500 transition-colors">Rp</span>
                                        <input type="number" x-model="paidAmount"
                                            class="w-full rounded-xl border-2 border-accent-500/20 bg-white pl-12 pr-4 py-4 text-xl font-black text-navy-900 dark:bg-navy-800 dark:text-white focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 transition-all shadow-inner"
                                            placeholder="0">
                                    </div>
                                    <div class="mt-3 p-3 rounded-xl bg-success/10 border border-success/20 flex justify-between items-center" x-show="paidAmount >= grandTotal && paidAmount > 0">
                                        <span class="text-xs font-bold text-success uppercase">KEMBALIAN</span>
                                        <span class="text-lg font-black text-success" x-text="formatRupiah(change)"></span>
                                    </div>
                                </div>

                                <button @click="processTransaction()"
                                    :disabled="cart.length === 0 || (paymentMethod === 'cash' && paidAmount < grandTotal)"
                                    class="relative overflow-hidden w-full group rounded-2xl bg-accent-500 py-4 text-sm font-black text-white shadow-xl shadow-accent-500/30 hover:bg-accent-600 disabled:opacity-50 disabled:grayscale transition-all active:scale-95 uppercase tracking-widest">
                                    <span class="relative z-10 flex items-center justify-center gap-2">
                                        <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                                        <span x-text="paymentMethod === 'qris' ? 'GENERATE QRIS' : 'PROSES PEMBAYARAN'"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <!-- QRIS Payment Modal (Large QR for Customer) -->
        <div x-show="showQrisModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-2xl p-8 max-w-lg w-full shadow-2xl dark:bg-navy-900 text-center"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                <h3 class="text-xl font-bold text-navy-900 dark:text-white mb-2">Pembayaran QRIS</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-6">Minta customer untuk scan QR code di bawah</p>

                <!-- Large QR Code -->
                <div class="bg-white p-6 rounded-xl inline-block mb-6 shadow-lg">
                    <img :src="qrisQrCode" alt="QRIS Payment" class="w-64 h-64 mx-auto" x-show="!qrisLoading">
                    <div x-show="qrisLoading" class="w-64 h-64 flex items-center justify-center">
                        <div class="animate-spin rounded-full h-20 w-20 border-4 border-accent-500 border-t-transparent">
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="mb-6 p-4 bg-slate-50 dark:bg-navy-800 rounded-xl">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-500">Total Pembayaran</span>
                        <span class="font-bold text-lg" x-text="formatRupiah(grandTotal)"></span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Invoice</span>
                        <span class="font-mono" x-text="tempInvoiceCode"></span>
                    </div>
                </div>

                <!-- Payment Supported -->
                <div class="mb-6">
                    <p class="text-xs text-slate-500 mb-2">Dukungan Pembayaran:</p>
                    <div class="flex justify-center gap-2 flex-wrap">
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">GoPay</span>
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">OVO</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">DANA</span>
                        <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-medium">ShopeePay</span>
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">Mobile Banking</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-center">
                    <button @click="checkQrisPayment()" :disabled="qrisLoading"
                        class="px-6 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">
                        <i data-lucide="refresh-cw" class="inline h-4 w-4 mr-1"></i> Cek Status
                    </button>
                    <button @click="confirmQrisPayment()" :disabled="qrisLoading"
                        class="px-6 py-2 rounded-lg bg-success text-white hover:bg-green-700">
                        <i data-lucide="check-circle" class="inline h-4 w-4 mr-1"></i> Sudah Dibayar
                    </button>
                    <button @click="cancelQrisPayment()" class="px-6 py-2 rounded-lg bg-danger text-white hover:bg-red-700">
                        <i data-lucide="x" class="inline h-4 w-4 mr-1"></i> Batal
                    </button>
                </div>

                <!-- Auto Check Info -->
                <p class="text-xs text-slate-400 mt-4">
                    <i data-lucide="info" class="inline h-3 w-3 mr-1"></i>
                    Sistem akan otomatis cek pembayaran setiap 5 detik
                </p>
            </div>
        </div>

        <!-- Success Modal (After Payment Complete) -->
        <div x-show="showSuccessModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl dark:bg-navy-900 text-center"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-success/10 mb-4">
                    <i data-lucide="check" class="h-8 w-8 text-success"></i>
                </div>
                <h3 class="text-xl font-bold text-navy-900 dark:text-white mb-2">Transaksi Berhasil!</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-4">Invoice: <span class="font-mono font-bold"
                        x-text="lastInvoice"></span></p>
                <div class="flex gap-3 justify-center">
                    <button @click="printReceipt()"
                        class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">
                        <i data-lucide="printer" class="inline h-4 w-4 mr-1"></i> Cetak
                    </button>
                    <button @click="sendWhatsapp()" class="px-4 py-2 rounded-lg bg-success text-white hover:bg-green-700">
                        <i data-lucide="message-circle" class="inline h-4 w-4 mr-1"></i> WhatsApp
                    </button>
                    <button @click="newTransaction()"
                        class="px-4 py-2 rounded-lg bg-accent-500 text-white hover:bg-accent-600">
                        Transaksi Baru
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div x-show="loading" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm" style="display: none;">
            <div class="bg-white rounded-2xl p-6 shadow-2xl dark:bg-navy-900">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-accent-500 border-t-transparent mx-auto">
                </div>
                <p class="text-center mt-4 text-slate-600 dark:text-slate-300">Memproses...</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function posApp() {
                return {
                    products: [],
                    cart: [],
                    search: '',
                    categoryId: '',
                    customerId: '',
                    discount: 0,
                    taxRate: 0,
                    paymentMethod: 'cash',
                    paidAmount: 0,
                    loading: false,
                    qrisLoading: false,
                    qrisQrCode: '',
                    tempInvoiceCode: '',
                    showQrisModal: false,
                    showSuccessModal: false,
                    lastInvoice: '',
                    lastTransaction: null,
                    qrisCheckInterval: null,

                    init() {
                        this.searchProducts();
                        this.loadSettings();

                        // Keyboard shortcuts
                        document.addEventListener('keydown', (e) => {
                            if (e.key === 'F2' && !this.showSuccessModal && !this.showQrisModal) {
                                e.preventDefault();
                                this.$refs.barcodeInput.focus();
                            }
                            if (e.key === 'F10' && this.cart.length > 0 && !this.showQrisModal) {
                                e.preventDefault();
                                this.processTransaction();
                            }
                            if (e.key === 'Escape' && this.showSuccessModal) {
                                this.newTransaction();
                            }
                        });
                    },

                    loadSettings() {
                        fetch('/api/settings')
                            .then(r => r.json())
                            .then(data => {
                                this.taxRate = parseFloat(data.tax_rate || 0);
                            });
                    },

                    searchProducts() {
                        const params = new URLSearchParams({
                            search: this.search,
                            category_id: this.categoryId
                        });

                        fetch('/cashier/products?' + params, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(r => {
                                const contentType = r.headers.get('content-type');
                                if (contentType && contentType.indexOf('application/json') !== -1) {
                                    return r.json();
                                } else {
                                    throw new Error('Server mengembalikan HTML, bukan JSON');
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    this.products = data.products || [];
                                } else {
                                    this.products = [];
                                }
                                setTimeout(() => lucide.createIcons(), 100);
                            })
                            .catch(err => {
                                console.error('Fetch Error:', err);
                                this.products = [];
                            });
                    },

                    scanBarcode() {
                        const barcode = this.search.trim();
                        if (!barcode) return;

                        fetch('/cashier/products/by-barcode?barcode=' + encodeURIComponent(barcode), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(r => {
                                const contentType = r.headers.get('content-type');
                                if (contentType && contentType.indexOf('application/json') !== -1) {
                                    return r.json();
                                } else {
                                    throw new Error('Server mengembalikan HTML');
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    this.addToCart(data.product);
                                    this.search = '';
                                } else {
                                    alert(data.message || 'Produk tidak ditemukan');
                                }
                            })
                            .catch(err => {
                                console.error('Barcode Scan Error:', err);
                                alert('Gagal scan barcode. Pastikan produk ada.');
                            });
                    },

                    addToCart(product) {
                        const existing = this.cart.find(item => item.product_id === product.id);

                        if (existing) {
                            if (existing.qty < product.stock) {
                                existing.qty++;
                            } else {
                                alert('Stok tidak mencukupi!');
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
                    },

                    increaseQty(index) {
                        const item = this.cart[index];
                        if (item.qty < item.max_stock) {
                            item.qty++;
                        } else {
                            alert('Stok tidak mencukupi!');
                        }
                    },

                    decreaseQty(index) {
                        const item = this.cart[index];
                        if (item.qty > 1) {
                            item.qty--;
                        } else {
                            this.removeFromCart(index);
                        }
                    },

                    removeFromCart(index) {
                        this.cart.splice(index, 1);
                    },

                    selectPayment(method) {
                        this.paymentMethod = method;

                        // Reset QRIS state when changing payment method
                        if (method !== 'qris') {
                            this.qrisLoading = false;
                            this.qrisQrCode = '';
                            this.showQrisModal = false;
                        }
                    },

                    get subtotal() {
                        return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                    },

                    get tax() {
                        return (this.subtotal - this.discount) * (this.taxRate / 100);
                    },

                    get grandTotal() {
                        return this.subtotal - this.discount + this.tax;
                    },

                    get change() {
                        return this.paidAmount - this.grandTotal;
                    },

                    formatRupiah(amount) {
                        return 'Rp ' + parseFloat(amount).toLocaleString('id-ID');
                    },

                    // Generate QRIS QR Code
                    generateQrisCode() {
                        this.qrisLoading = true;

                        // Generate temporary invoice code
                        this.tempInvoiceCode = 'QRIS-' + Date.now();

                        // In production, call payment gateway API (Midtrans, Xendit, etc.)
                        // For now, generate static QRIS with payment info
                        const qrisData = {
                            invoice: this.tempInvoiceCode,
                            amount: this.grandTotal,
                            merchant: '{{ config("app.name") }}',
                            timestamp: new Date().toISOString()
                        };

                        // Simulate API call to payment gateway
                        setTimeout(() => {
                            // Generate QR code with payment data
                            const qrData = encodeURIComponent(JSON.stringify(qrisData));
                            this.qrisQrCode = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${qrData}`;
                            this.qrisLoading = false;

                            // Show QRIS modal for customer to scan
                            this.showQrisModal = true;

                            // Auto check payment status every 5 seconds
                            this.startQrisCheck();
                        }, 1500);
                    },

                    // Start auto check payment status
                    startQrisCheck() {
                        this.qrisCheckInterval = setInterval(() => {
                            this.checkQrisPayment();
                        }, 5000);
                    },

                    // Stop auto check
                    stopQrisCheck() {
                        if (this.qrisCheckInterval) {
                            clearInterval(this.qrisCheckInterval);
                            this.qrisCheckInterval = null;
                        }
                    },

                    // Check QRIS payment status (call payment gateway API)
                    checkQrisPayment() {
                        // In production, call payment gateway API to check status
                        // For demo, we'll simulate
                        console.log('Checking QRIS payment status...', this.tempInvoiceCode);

                        // Example API call:
                        // fetch('/api/payment/qris/status/' + this.tempInvoiceCode)
                        //     .then(r => r.json())
                        //     .then(data => {
                        //         if (data.status === 'paid') {
                        //             this.confirmQrisPayment();
                        //         }
                        //     });
                    },

                    // Confirm QRIS payment manually (after customer shows payment proof)
                    confirmQrisPayment() {
                        this.stopQrisCheck();
                        this.processTransaction();
                    },

                    // Cancel QRIS payment
                    cancelQrisPayment() {
                        this.stopQrisCheck();
                        this.showQrisModal = false;
                        this.qrisLoading = false;
                        this.qrisQrCode = '';
                        this.paymentMethod = 'cash';
                    },

                    processTransaction() {
                        if (this.cart.length === 0) {
                            alert('Keranjang masih kosong!');
                            return;
                        }

                        // For QRIS, show QR code first before processing transaction
                        if (this.paymentMethod === 'qris' && !this.showQrisModal) {
                            this.generateQrisCode();
                            return;
                        }

                        // For QRIS with modal open, process the transaction
                        if (this.paymentMethod === 'qris' && this.showQrisModal) {
                            this.loading = true;
                            this.showQrisModal = false;
                        }

                        // For cash, validate payment amount
                        if (this.paymentMethod === 'cash' && this.paidAmount < this.grandTotal) {
                            alert('Jumlah pembayaran kurang!');
                            return;
                        }

                        this.loading = true;

                        fetch('/cashier/transaction', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                customer_id: this.customerId || null,
                                items: this.cart,
                                discount: this.discount,
                                payment_method: this.paymentMethod,
                                paid_amount: this.paymentMethod === 'cash' ? this.paidAmount : this.grandTotal,
                                notes: this.paymentMethod === 'qris' ? 'QRIS Payment - ' + this.tempInvoiceCode : null,
                                qris_invoice: this.paymentMethod === 'qris' ? this.tempInvoiceCode : null
                            })
                        })
                            .then(r => {
                                const contentType = r.headers.get('content-type');
                                if (contentType && contentType.indexOf('application/json') !== -1) {
                                    return r.json();
                                } else {
                                    throw new Error('Server mengembalikan HTML, bukan JSON');
                                }
                            })
                            .then(data => {
                                this.loading = false;
                                if (data.success) {
                                    this.lastInvoice = data.invoice_code;
                                    this.lastTransaction = data.transaction;
                                    this.showSuccessModal = true;
                                    this.stopQrisCheck();
                                } else {
                                    alert(data.message || 'Terjadi kesalahan saat memproses transaksi');
                                }
                            })
                            .catch(err => {
                                this.loading = false;
                                console.error('Transaction Error:', err);
                                alert('Terjadi kesalahan: ' + err.message + '. Silakan coba lagi.');
                            });
                    },

                    printReceipt() {
                        const printWindow = window.open('', '_blank');
                        printWindow.document.write(`
                                <html>
                                <head>
                                    <title>Receipt - ${this.lastInvoice}</title>
                                    <style>
                                        body { font-family: monospace; width: 58mm; margin: 0; padding: 10px; }
                                        .text-center { text-align: center; }
                                        .bold { font-weight: bold; }
                                        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
                                        .qr-code { margin: 10px auto; text-align: center; }
                                        .qr-code img { width: 100px; height: 100px; }
                                    </style>
                                </head>
                                <body>
                                    <div class="text-center">
                                        <h3>VexaMart</h3>
                                        <p>Jl. Teknologi No. 12</p>
                                        <div class="line"></div>
                                        <p>${this.lastInvoice}</p>
                                        <p>${new Date().toLocaleString('id-ID')}</p>
                                        <div class="line"></div>
                                    </div>
                                    ${this.lastTransaction.items.map(item => `
                                        <div style="display:flex;justify-content:space-between;">
                                            <span>${item.product.name} x${item.qty}</span>
                                            <span>${item.subtotal.toLocaleString('id-ID')}</span>
                                        </div>
                                    `).join('')}
                                    <div class="line"></div>
                                    <div style="display:flex;justify-content:space-between;" class="bold">
                                        <span>TOTAL</span>
                                        <span>${this.lastTransaction.grand_total.toLocaleString('id-ID')}</span>
                                    </div>
                                    <div class="qr-code">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=${encodeURIComponent(this.lastInvoice)}" alt="QR">
                                        <p style="font-size:9px;">Scan untuk detail</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="line"></div>
                                        <p>Terima kasih!</p>
                                    </div>
                                </body>
                                </html>
                            `);
                        printWindow.document.close();
                        printWindow.print();
                    },

                    sendWhatsapp() {
                        if (this.lastTransaction?.customer?.phone) {
                            const message = `Halo ${this.lastTransaction.customer.name},\n\nTerima kasih telah berbelanja di VexaMart!\n\nInvoice: ${this.lastInvoice}\nTotal: Rp ${this.lastTransaction.grand_total.toLocaleString('id-ID')}\n\nSampai jumpa lagi!`;
                            window.open(`https://wa.me/${this.lastTransaction.customer.phone}?text=${encodeURIComponent(message)}`, '_blank');
                        } else {
                            alert('Nomor WhatsApp pelanggan tidak tersedia');
                        }
                    },

                    newTransaction() {
                        this.cart = [];
                        this.search = '';
                        this.discount = 0;
                        this.paidAmount = 0;
                        this.showSuccessModal = false;
                        this.showQrisModal = false;
                        this.qrisLoading = false;
                        this.qrisQrCode = '';
                        this.tempInvoiceCode = '';
                        this.paymentMethod = 'cash';
                        this.stopQrisCheck();
                        this.searchProducts();
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();
            });
        </script>
    @endpush
@endsection