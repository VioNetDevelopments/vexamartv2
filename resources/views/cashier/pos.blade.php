@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-4rem)] bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950" 
     x-data="posApp()" 
     x-init="init()">
    
    <!-- Top Bar - Compact & Rounded -->
    <div class="px-6 py-3">
        <div class="flex items-center gap-3">
            <!-- Online Status Badge -->
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gradient-to-r from-success/10 to-success/5 border border-success/20">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-success"></span>
                </span>
                <span class="text-xs font-semibold text-success">Online</span>
            </div>
            
            <!-- Sales Stats -->
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-navy-800 border border-slate-200 dark:border-white/10">
                    <i data-lucide="trending-up" class="w-3.5 h-3.5 text-success"></i>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Penjualan</span>
                        <span class="text-xs font-bold text-success" x-text="formatRupiah(dailySales)"></span>
                    </div>
                </div>
                
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-navy-800 border border-slate-200 dark:border-white/10">
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
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-5 shadow-lg mb-6">
                <div class="flex gap-4">
                    <!-- Search Input -->
                    <div class="flex-[2]">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400"></i>
                            <input type="text" x-model="search" @input.debounce.300ms="searchProducts()"
                                   placeholder="Cari produk atau scan barcode..."
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-12 pr-10 py-3.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all"
                                   @keydown.enter="scanBarcode()">
                            <i data-lucide="scan-barcode" class="absolute right-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 cursor-pointer hover:text-accent-500 transition-colors" @click="$refs.barcodeInput.focus()"></i>
                        </div>
                        <input type="text" x-ref="barcodeInput" class="hidden" @input.debounce.300ms="scanBarcode()">
                    </div>

                    <!-- Category Filter -->
                    <select x-model="categoryId" @change="searchProducts()"
                            class="rounded-xl border border-slate-200 bg-white dark:bg-navy-800 px-5 py-3.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:text-white transition-all">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <!-- Customer Select -->
                    <select x-model="customerId"
                            class="rounded-xl border border-slate-200 bg-white dark:bg-navy-800 px-5 py-3.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:text-white transition-all">
                        <option value="">Pelanggan Umum</option>
                        @foreach($customers as $cust)
                        <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto bg-white dark:bg-navy-900 rounded-2xl p-5 shadow-lg" style="scroll-behavior: smooth;">
                <div class="grid grid-cols-3 gap-5">
                    <template x-for="product in products" :key="product.id">
                        <div @click="addToCart(product)"
                             class="group cursor-pointer rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-navy-800 p-4 shadow-sm hover:shadow-2xl hover:border-accent-500 hover:shadow-accent-500/10 transition-all duration-300 hover:-translate-y-2">
                            <div class="aspect-square rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-navy-700 dark:to-navy-600 overflow-hidden mb-4 relative">
                                <template x-if="product.image">
                                    <img :src="'/storage/' + product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </template>
                                <template x-if="!product.image">
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-lucide="package" class="h-12 w-12 text-slate-400"></i>
                                    </div>
                                </template>
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-full text-xs font-bold"
                                      :class="product.stock <= product.min_stock ? 'bg-warning text-white' : 'bg-success text-white'">
                                    <span x-text="product.stock"></span>
                                </span>
                            </div>
                            <h4 class="font-semibold text-sm text-navy-900 dark:text-white line-clamp-2 min-h-[2.75rem]" x-text="product.name"></h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2" x-text="product.category?.name"></p>
                            <div class="mt-3">
                                <span class="text-xl font-bold bg-gradient-to-r from-accent-600 to-accent-500 bg-clip-text text-transparent" x-text="formatRupiah(product.sell_price)"></span>
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
                    <button @click="clearCart()" 
                            class="p-2 rounded-lg hover:bg-danger/10 text-danger transition-colors"
                            :disabled="cart.length === 0"
                            :class="cart.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                            title="Hapus Keranjang">
                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                    </button>
                </div>

                <div class="p-3 overflow-y-auto" style="height: 240px;">
                    <template x-for="(item, index) in cart" :key="item.product_id">
                        <div class="flex gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-navy-800 mb-2 last:mb-0">
                            <div class="h-12 w-12 rounded-lg bg-slate-200 dark:bg-navy-700 overflow-hidden flex-shrink-0">
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
                                <h4 class="text-sm font-medium text-navy-900 dark:text-white line-clamp-1" x-text="item.name"></h4>
                                <p class="text-xs text-accent-600 dark:text-accent-400 font-bold" x-text="formatRupiah(item.price)"></p>
                                <div class="flex items-center gap-1.5 mt-1.5">
                                    <button @click="decreaseQty(index)"
                                            class="flex items-center justify-center w-6 h-6 rounded-lg bg-white dark:bg-navy-600 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white transition-all">
                                        <i data-lucide="minus" class="h-3 w-3"></i>
                                    </button>
                                    <span class="text-sm font-medium text-navy-900 dark:text-white w-5 text-center" x-text="item.qty"></span>
                                    <button @click="increaseQty(index)"
                                            class="flex items-center justify-center w-6 h-6 rounded-lg bg-white dark:bg-navy-600 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white transition-all">
                                        <i data-lucide="plus" class="h-3 w-3"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-navy-900 dark:text-white" x-text="formatRupiah(item.price * item.qty)"></p>
                            </div>
                        </div>
                    </template>
                    <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-full text-center py-8">
                        <i data-lucide="shopping-cart" class="h-10 w-10 text-slate-300 mb-2"></i>
                        <p class="text-slate-500 text-sm">Keranjang kosong</p>
                    </div>
                </div>
            </div>

            <!-- CARD 2: Payment -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg p-4 flex-1 overflow-y-auto">
                <div class="space-y-2 mb-4 pb-4 border-b border-slate-100 dark:border-white/10">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Subtotal</span>
                        <span class="font-bold text-navy-900 dark:text-white" x-text="formatRupiah(subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-2 border-t border-slate-200 dark:border-white/10">
                        <span class="text-navy-900 dark:text-white">Total</span>
                        <span class="text-accent-600 dark:text-accent-400" x-text="formatRupiah(grandTotal)"></span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="selectPayment('cash')"
                                :class="paymentMethod === 'cash' ? 'bg-accent-500 text-white border-accent-500' : 'bg-white dark:bg-navy-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-white/10'"
                                class="rounded-xl py-2.5 text-sm font-semibold border transition-all">
                            <i data-lucide="banknote" class="inline h-3.5 w-3.5 mr-1"></i> Tunai
                        </button>
                        <button @click="selectPayment('qris')"
                                :class="paymentMethod === 'qris' ? 'bg-accent-500 text-white border-accent-500' : 'bg-white dark:bg-navy-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-white/10'"
                                class="rounded-xl py-2.5 text-sm font-semibold border transition-all">
                            <i data-lucide="qr-code" class="inline h-3.5 w-3.5 mr-1"></i> QRIS
                        </button>
                        <button @click="selectPayment('debit')"
                                :class="paymentMethod === 'debit' ? 'bg-accent-500 text-white border-accent-500' : 'bg-white dark:bg-navy-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-white/10'"
                                class="rounded-xl py-2.5 text-sm font-semibold border transition-all">
                            <i data-lucide="credit-card" class="inline h-3.5 w-3.5 mr-1"></i> Debit
                        </button>
                        <button @click="selectPayment('ewallet')"
                                :class="paymentMethod === 'ewallet' ? 'bg-accent-500 text-white border-accent-500' : 'bg-white dark:bg-navy-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-white/10'"
                                class="rounded-xl py-2.5 text-sm font-semibold border transition-all">
                            <i data-lucide="wallet" class="inline h-3.5 w-3.5 mr-1"></i> E-Wallet
                        </button>
                    </div>
                </div>

                <!-- QRIS Payment -->
                <div x-show="paymentMethod === 'qris'" 
                     x-transition:enter="transition ease-out duration-300"
                     class="bg-gradient-to-br from-accent-50 to-accent-100 dark:from-accent-900/20 dark:to-accent-900/10 rounded-xl p-3 mb-3 border border-accent-200 dark:border-accent-800">
                    <div class="text-center">
                        <img :src="qrisQrCode" alt="QRIS" class="w-28 h-28 mx-auto" x-show="!qrisLoading">
                        <div x-show="qrisLoading" class="w-28 h-28 flex items-center justify-center mx-auto">
                            <div class="animate-spin rounded-full h-8 w-8 border-3 border-accent-500 border-t-transparent"></div>
                        </div>
                        <p class="text-xs text-accent-700 dark:text-accent-400 mt-2 font-bold" x-text="formatRupiah(grandTotal)"></p>
                    </div>
                </div>

                <!-- Cash Payment -->
                <div x-show="paymentMethod === 'cash'"
                     x-transition:enter="transition ease-out duration-300"
                     class="mb-3">
                    <input type="number" x-model="paidAmount" min="0" placeholder="Jumlah Bayar"
                           class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-3 py-2 text-sm font-bold focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:bg-navy-800 dark:text-white">
                    <p x-show="paidAmount >= grandTotal && paidAmount > 0" class="text-xs text-success mt-1">
                        ✓ Kembalian: <span class="font-bold" x-text="formatRupiah(change)"></span>
                    </p>
                    <p x-show="paidAmount < grandTotal && paidAmount > 0" class="text-xs text-danger mt-1">
                        ⚠ Kurang: <span class="font-bold" x-text="formatRupiah(grandTotal - paidAmount)"></span>
                    </p>
                </div>

                <button @click="processTransaction()"
                        :disabled="cart.length === 0 || (paymentMethod === 'cash' && paidAmount < grandTotal) || (paymentMethod === 'qris' && qrisLoading)"
                        class="w-full rounded-xl bg-gradient-to-r from-accent-500 to-accent-600 py-3 text-sm font-bold text-white shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:-translate-y-0.5">
                    <i data-lucide="check-circle" class="inline h-4 w-4 mr-2"></i>
                    <span>Proses Pembayaran</span>
                </button>
            </div>
        </div>
    </div>

    <!-- SUCCESS MODAL -->
    <div x-show="showSuccessModal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showSuccessModal = false"></div>
        <div class="relative bg-white dark:bg-navy-900 rounded-3xl p-8 max-w-md w-full shadow-2xl">
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-success/20 rounded-full animate-ping"></div>
                    <div class="relative w-20 h-20 bg-gradient-to-br from-success to-success/80 rounded-full flex items-center justify-center shadow-lg shadow-success/30">
                        <i data-lucide="check" class="h-10 w-10 text-white" style="stroke-width: 3;"></i>
                    </div>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-center text-navy-900 dark:text-white mb-2">Transaksi Berhasil!</h3>
            <p class="text-center text-slate-500 dark:text-slate-400 mb-6">Pembayaran berhasil diproses</p>
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-navy-800 dark:to-navy-700 rounded-2xl p-5 mb-6 border border-slate-200 dark:border-white/10">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-slate-500 dark:text-slate-400">No. Invoice</span>
                    <span class="text-xs px-2.5 py-1 bg-success/10 text-success rounded-full font-semibold">Lunas</span>
                </div>
                <p class="text-lg font-bold text-navy-900 dark:text-white font-mono mb-3" x-text="lastInvoice"></p>
                <div class="border-t border-slate-200 dark:border-white/10 pt-3 mt-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Total Pembayaran</span>
                        <span class="text-xl font-bold text-accent-600 dark:text-accent-400" x-text="formatRupiah(grandTotal)"></span>
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
    <div x-show="loading" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm">
        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-2xl">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-accent-500 border-t-transparent mx-auto"></div>
            <p class="text-center mt-4 text-sm">Memproses...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function posApp() {
    return {
        products: [], cart: [], search: '', categoryId: '', customerId: '',
        discount: 0, paymentMethod: 'cash', paidAmount: 0,
        loading: false, qrisLoading: false, qrisQrCode: '',
        showSuccessModal: false, lastInvoice: '', lastTransaction: null,
        dailySales: 0, dailyTransactions: 0,
        recentTransactions: [],
        storeName: '{{ config("app.name") }}',
        storeAddress: '',
        storePhone: '',
        receiptFooter: 'Terima kasih!',
        
        init() {
            this.searchProducts();
            this.loadDailySales();
            this.loadStoreSettings();
            setTimeout(() => lucide.createIcons(), 100);
        },
        
        loadStoreSettings() {
            fetch('/api/settings', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                this.storeName = data.store_name || this.storeName;
                this.storeAddress = data.store_address || '';
                this.storePhone = data.store_phone || '';
                this.receiptFooter = data.receipt_footer || 'Terima kasih!';
            })
            .catch(() => {});
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
            setTimeout(() => lucide.createIcons(), 50);
        },
        
        increaseQty(index) { 
            const item = this.cart[index]; 
            if (item.qty < item.max_stock) { 
                item.qty++; 
            } else { 
                alert('Stok tidak mencukupi!'); 
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
            this.cart.splice(index, 1);
            setTimeout(() => lucide.createIcons(), 50);
        },
        
        clearCart() { 
            if (this.cart.length === 0) return;
            if (confirm('Hapus semua item dari keranjang?')) {
                this.cart = [];
                setTimeout(() => lucide.createIcons(), 50);
            }
        },
        
        selectPayment(method) {
            this.paymentMethod = method;
            if (method !== 'qris') { 
                this.qrisLoading = false; 
                this.qrisQrCode = ''; 
            } else { 
                this.generateQrisCode(); 
            }
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
        
        processTransaction() {
            if (this.cart.length === 0) { alert('Keranjang masih kosong!'); return; }
            if (this.paymentMethod === 'cash' && this.paidAmount < this.grandTotal) { alert('Jumlah pembayaran kurang!'); return; }
            if (this.paymentMethod === 'qris' && this.qrisLoading) { alert('QR Code sedang diproses...'); return; }
            
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
                    paid_amount: this.paymentMethod === 'cash' ? this.paidAmount : this.grandTotal,
                })
            })
            .then(r => r.json()).then(data => {
                this.loading = false;
                if (data.success) {
                    this.lastInvoice = data.invoice_code;
                    this.lastTransaction = data.transaction;
                    this.showSuccessModal = true;
                    this.loadDailySales();
                } else { 
                    alert(data.message || 'Terjadi kesalahan'); 
                }
            })
            .catch(() => { 
                this.loading = false; 
                alert('Terjadi kesalahan. Silakan coba lagi.'); 
            });
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
            this.qrisLoading = false; 
            this.qrisQrCode = '';
            this.paymentMethod = 'cash';
            this.searchProducts();
        },
        
        printReceipt(transactionId) {
            // Get transaction data
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
                            -webkit-print-color-adjust: exact;
                            print-color-adjust: exact;
                        }
                        body { 
                            font-family: 'Courier New', monospace; 
                            width: 58mm; 
                            margin: 0 auto; 
                            padding: 5px; 
                            font-size: 10px;
                            line-height: 1.3;
                        }
                        .text-center { text-align: center; }
                        .bold { font-weight: bold; }
                        .line { border-bottom: 1px dashed #000; margin: 3px 0; }
                        .line-double { border-bottom: 2px dashed #000; margin: 4px 0; }
                        .item { display: flex; justify-content: space-between; margin: 2px 0; }
                        .item-name { flex: 1; padding-right: 5px; word-wrap: break-word; word-break: break-word; }
                        .item-qty { text-align: center; min-width: 30px; }
                        .item-price { text-align: right; min-width: 50px; }
                        .header { margin-bottom: 5px; }
                        .store-name { font-size: 12px; font-weight: bold; margin-bottom: 2px; }
                        .store-info { font-size: 9px; margin-bottom: 1px; }
                        .invoice { font-size: 11px; font-weight: bold; margin: 3px 0; }
                        .datetime { font-size: 9px; margin: 2px 0; }
                        .total { font-size: 12px; font-weight: bold; margin-top: 3px; padding-top: 3px; border-top: 2px dashed #000; }
                        .footer { margin-top: 5px; padding-top: 3px; border-top: 1px dashed #000; text-align: center; font-size: 9px; }
                        
                        @media print {
                            body { width: 100%; padding: 0; }
                            @page { margin: 0; size: 58mm auto; }
                        }
                    </style>
                </head>
                <body onload="window.print()">
                    <!-- Store Header -->
                    <div class="header text-center">
                        <div class="store-name">${this.storeName || '{{ config("app.name") }}'}</div>
                        ${this.storeAddress ? `<div class="store-info">${this.storeAddress}</div>` : ''}
                        ${this.storePhone ? `<div class="store-info">Telp: ${this.storePhone}</div>` : ''}
                    </div>
                    
                    <div class="line-double"></div>
                    
                    <!-- Invoice & DateTime -->
                    <div class="text-center">
                        <div class="invoice">${transaction.invoice_code}</div>
                        <div class="datetime">${new Date(transaction.created_at).toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'})}</div>
                        <div class="datetime">${new Date(transaction.created_at).toLocaleTimeString('id-ID')}</div>
                    </div>
                    
                    <div class="line"></div>
                    
                    <!-- Items List -->
                    <div class="items">
                        ${transaction.items.map(item => `
                            <div class="item">
                                <span class="item-name">${item.product.name}</span>
                                <span class="item-qty">${item.qty}x</span>
                                <span class="item-price">${item.subtotal.toLocaleString('id-ID')}</span>
                            </div>
                            ${item.qty > 1 ? `
                            <div class="item" style="font-size: 8px; color: #666; margin-top: -2px;">
                                <span style="padding-left: 20px;">${item.price.toLocaleString('id-ID')}</span>
                            </div>
                            ` : ''}
                        `).join('')}
                    </div>
                    
                    <div class="line"></div>
                    
                    <!-- Totals -->
                    <div class="item">
                        <span>Subtotal</span>
                        <span>${transaction.subtotal.toLocaleString('id-ID')}</span>
                    </div>
                    
                    ${transaction.discount > 0 ? `
                    <div class="item">
                        <span>Diskon</span>
                        <span>- ${transaction.discount.toLocaleString('id-ID')}</span>
                    </div>
                    ` : ''}
                    
                    ${transaction.tax > 0 ? `
                    <div class="item">
                        <span>Pajak</span>
                        <span>${transaction.tax.toLocaleString('id-ID')}</span>
                    </div>
                    ` : ''}
                    
                    <div class="item total">
                        <span>TOTAL</span>
                        <span>${transaction.grand_total.toLocaleString('id-ID')}</span>
                    </div>
                    
                    <!-- Cash Payment Details -->
                    ${transaction.payment_method === 'cash' ? `
                    <div class="line"></div>
                    <div class="item">
                        <span>Tunai</span>
                        <span>${transaction.paid_amount.toLocaleString('id-ID')}</span>
                    </div>
                    <div class="item">
                        <span>Kembalian</span>
                        <span>${transaction.change_amount.toLocaleString('id-ID')}</span>
                    </div>
                    ` : ''}
                    
                    <!-- Footer -->
                    <div class="footer">
                        <div class="bold">${this.receiptFooter || 'Terima kasih!'}</div>
                        <div style="margin-top: 2px;">Barang yang dibeli tidak dapat</div>
                        <div>ditukar/dikembalikan</div>
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
[x-cloak] { display: none !important; }
.overflow-y-auto { scroll-behavior: smooth; }
.overflow-y-auto::-webkit-scrollbar { width: 5px; }
.overflow-y-auto::-webkit-scrollbar-track { background: transparent; }
.overflow-y-auto::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
</style>
@endpush
@endsection