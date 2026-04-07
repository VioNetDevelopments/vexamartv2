@extends('layouts.app')

@section('content')
    <div class="h-[calc(100vh-8rem)]" x-data="posApp()" x-init="init()">
        <div class="flex h-full gap-4">

            <!-- LEFT: Product Grid -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Search & Filter Bar -->
                <div class="rounded-2xl bg-white p-4 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5 mb-4">
                    <div class="flex flex-wrap gap-3">
                        <!-- Search Input -->
                        <div class="flex-1 min-w-64">
                            <div class="relative">
                                <i data-lucide="search"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400"></i>
                                <input type="text" x-model="search" @input.debounce.300ms="searchProducts()"
                                    placeholder="Cari produk atau scan barcode..."
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-3 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white"
                                    @keydown.enter="scanBarcode()">
                                <i data-lucide="scan-barcode"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 cursor-pointer hover:text-accent-500"
                                    @click="$refs.barcodeInput.focus()"></i>
                            </div>
                            <input type="text" x-ref="barcodeInput" class="hidden" @input.debounce.300ms="scanBarcode()">
                        </div>

                        <!-- Category Filter -->
                        <select x-model="categoryId" @change="searchProducts()"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        <!-- Customer Select -->
                        <select x-model="customerId"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                            <option value="">Pelanggan Umum</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div
                    class="flex-1 overflow-y-auto rounded-2xl bg-white p-4 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template x-for="product in products" :key="product.id">
                            <div @click="addToCart(product)"
                                class="group cursor-pointer rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-lg hover:border-accent-500 transition-all duration-200 dark:bg-navy-800 dark:border-white/10">
                                <div class="aspect-square rounded-lg bg-slate-100 dark:bg-navy-700 overflow-hidden mb-3">
                                    <template x-if="product.image">
                                        <img :src="'/storage/' + product.image" :alt="product.name"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                    </template>
                                    <template x-if="!product.image">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="package" class="h-8 w-8 text-slate-400"></i>
                                        </div>
                                    </template>
                                </div>
                                <h4 class="font-medium text-sm text-navy-900 dark:text-white line-clamp-2"
                                    x-text="product.name"></h4>
                                <p class="text-xs text-slate-500 mt-1" x-text="product.category?.name"></p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-lg font-bold text-accent-500"
                                        x-text="formatRupiah(product.sell_price)"></span>
                                    <span class="text-xs px-2 py-1 rounded-full"
                                        :class="product.stock <= product.min_stock ? 'bg-warning/10 text-warning' : 'bg-success/10 text-success'"
                                        x-text="product.stock + ' stok'"></span>
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

            <!-- RIGHT: Cart -->
            <div class="w-96 flex flex-col overflow-hidden">
                <div
                    class="rounded-2xl bg-white shadow-soft dark:bg-navy-900 dark:border dark:border-white/5 h-full flex flex-col">
                    <!-- Cart Header -->
                    <div class="p-4 border-b border-slate-100 dark:border-white/5">
                        <h2 class="text-lg font-bold text-navy-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                            Keranjang Belanja
                        </h2>
                        <p class="text-xs text-slate-500 mt-1" x-text="cart.length + ' item'"></p>
                    </div>

                    <!-- Cart Items -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3">
                        <template x-for="(item, index) in cart" :key="item.product_id">
                            <div class="flex gap-3 p-3 rounded-xl bg-slate-50 dark:bg-navy-800">
                                <div
                                    class="h-16 w-16 rounded-lg bg-slate-200 dark:bg-navy-700 overflow-hidden flex-shrink-0">
                                    <template x-if="item.image">
                                        <img :src="'/storage/' + item.image" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!item.image">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="package" class="h-6 w-6 text-slate-400"></i>
                                        </div>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-navy-900 dark:text-white line-clamp-1"
                                        x-text="item.name"></h4>
                                    <p class="text-xs text-accent-500 font-bold" x-text="formatRupiah(item.price)"></p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <button @click="decreaseQty(index)"
                                            class="h-7 w-7 rounded-lg bg-white dark:bg-navy-700 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white transition-colors">
                                            <i data-lucide="minus" class="h-3 w-3"></i>
                                        </button>
                                        <span class="text-sm font-medium w-8 text-center" x-text="item.qty"></span>
                                        <button @click="increaseQty(index)"
                                            class="h-7 w-7 rounded-lg bg-white dark:bg-navy-700 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white transition-colors">
                                            <i data-lucide="plus" class="h-3 w-3"></i>
                                        </button>
                                        <button @click="removeFromCart(index)"
                                            class="ml-auto text-slate-400 hover:text-danger transition-colors">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
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

                    <!-- Cart Summary -->
                    <div class="p-4 border-t border-slate-100 dark:border-white/5 space-y-3">
                        <!-- Discount -->
                        <div class="flex items-center gap-2">
                            <input type="number" x-model="discount" placeholder="Diskon (Rp)"
                                class="flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm dark:border-white/10 dark:bg-navy-800 dark:text-white">
                            <button @click="discount = 0" class="text-xs text-slate-500 hover:text-danger">Reset</button>
                        </div>

                        <!-- Summary Rows -->
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-slate-500">
                                <span>Subtotal</span>
                                <span x-text="formatRupiah(subtotal)"></span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Diskon</span>
                                <span class="text-danger" x-text="'- ' + formatRupiah(discount)"></span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Pajak (<span x-text="taxRate"></span>%)</span>
                                <span x-text="formatRupiah(tax)"></span>
                            </div>
                            <div
                                class="flex justify-between text-lg font-bold text-navy-900 dark:text-white pt-2 border-t border-slate-100 dark:border-white/5">
                                <span>Total</span>
                                <span x-text="formatRupiah(grandTotal)"></span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-2">Metode Pembayaran</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button @click="selectPayment('cash')"
                                    :class="paymentMethod === 'cash' ? 'bg-accent-500 text-white' : 'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-300'"
                                    class="rounded-lg py-2 text-sm font-medium transition-colors">
                                    <i data-lucide="banknote" class="inline h-4 w-4 mr-1"></i> Tunai
                                </button>
                                <button @click="selectPayment('qris')"
                                    :class="paymentMethod === 'qris' ? 'bg-accent-500 text-white' : 'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-300'"
                                    class="rounded-lg py-2 text-sm font-medium transition-colors">
                                    <i data-lucide="qr-code" class="inline h-4 w-4 mr-1"></i> QRIS
                                </button>
                                <button @click="selectPayment('debit')"
                                    :class="paymentMethod === 'debit' ? 'bg-accent-500 text-white' : 'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-300'"
                                    class="rounded-lg py-2 text-sm font-medium transition-colors">
                                    <i data-lucide="credit-card" class="inline h-4 w-4 mr-1"></i> Debit
                                </button>
                                <button @click="selectPayment('ewallet')"
                                    :class="paymentMethod === 'ewallet' ? 'bg-accent-500 text-white' : 'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-300'"
                                    class="rounded-lg py-2 text-sm font-medium transition-colors">
                                    <i data-lucide="wallet" class="inline h-4 w-4 mr-1"></i> E-Wallet
                                </button>
                            </div>
                        </div>

                        <!-- QRIS Payment Section -->
                        <div x-show="paymentMethod === 'qris'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="p-4 rounded-xl bg-slate-50 dark:bg-navy-800 border border-slate-200 dark:border-white/10">
                            <div class="text-center mb-3">
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Scan QRIS untuk Bayar
                                </p>
                                <p class="text-xs text-slate-500">Gunakan GoPay, OVO, DANA, ShopeePay, atau Mobile Banking
                                </p>
                            </div>

                            <!-- QR Code Display -->
                            <div class="bg-white p-3 rounded-lg inline-block mx-auto mb-3">
                                <img :src="qrisQrCode" alt="QRIS Payment" class="w-40 h-40 mx-auto" x-show="!qrisLoading">
                                <div x-show="qrisLoading" class="w-40 h-40 flex items-center justify-center">
                                    <div
                                        class="animate-spin rounded-full h-12 w-12 border-4 border-accent-500 border-t-transparent">
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Info -->
                            <div class="text-center">
                                <p class="text-xs text-slate-500 mb-2">Total Pembayaran:</p>
                                <p class="text-lg font-bold text-accent-500" x-text="formatRupiah(grandTotal)"></p>
                            </div>

                            <!-- Payment Status -->
                            <div class="mt-3 flex items-center justify-center gap-2">
                                <div class="animate-pulse w-2 h-2 rounded-full bg-warning" x-show="qrisLoading"></div>
                                <span class="text-xs text-slate-500"
                                    x-text="qrisLoading ? 'Menunggu pembayaran...' : 'QR Code siap'"></span>
                            </div>

                            <!-- Manual Confirm Button -->
                            <button @click="confirmQrisPayment()" :disabled="qrisLoading"
                                class="w-full mt-3 rounded-lg bg-success py-2 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50">
                                <i data-lucide="check-circle" class="inline h-4 w-4 mr-1"></i> Konfirmasi Pembayaran
                            </button>
                        </div>

                        <!-- Cash Payment Section -->
                        <div x-show="paymentMethod === 'cash'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0">
                            <label class="block text-xs font-medium text-slate-500 mb-1">Jumlah Bayar</label>
                            <input type="number" x-model="paidAmount"
                                class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm font-bold dark:border-white/10 dark:bg-navy-800 dark:text-white"
                                placeholder="0">
                            <p x-show="paidAmount >= grandTotal && paidAmount > 0" class="text-xs text-success mt-1">
                                Kembalian: <span class="font-bold" x-text="formatRupiah(change)"></span>
                            </p>
                            <p x-show="paidAmount < grandTotal && paidAmount > 0" class="text-xs text-danger mt-1">
                                Kurang: <span class="font-bold" x-text="formatRupiah(grandTotal - paidAmount)"></span>
                            </p>
                        </div>

                        <!-- Checkout Button -->
                        <button @click="processTransaction()"
                            :disabled="cart.length === 0 || (paymentMethod === 'cash' && paidAmount < grandTotal) || (paymentMethod === 'qris' && qrisLoading)"
                            class="w-full rounded-xl bg-accent-500 py-3 text-sm font-bold text-white shadow-lg shadow-accent-500/30 hover:bg-accent-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            <i data-lucide="check-circle" class="inline h-4 w-4 mr-2"></i>
                            <span x-text="paymentMethod === 'qris' ? 'Tampilkan QRIS' : 'Proses Pembayaran'"></span>
                        </button>
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