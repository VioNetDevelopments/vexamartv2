@extends('layouts.customer')

@section('title', 'Flash Sales - Vexalyn Store')

@section('content')
<div class="min-h-screen py-16 bg-white" x-data="{
    modalOpen: false,
    product: null,
    qty: 1,
    adding: false,
    addedId: null,

    openProduct(p) { 
        this.product = p; 
        this.qty = 1; 
        this.modalOpen = true; 
        document.body.style.overflow = 'hidden'; 
        this.$nextTick(() => lucide.createIcons());
    },
    closeModal() { this.modalOpen = false; document.body.style.overflow = ''; },
    async addToCart(productId, quantity) {
        if (this.adding) return;
        this.adding = true;
        try {
            const fd = new FormData();
            fd.append('quantity', quantity || this.qty);
            fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
            const res = await fetch('/shop/cart/add/' + productId, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: fd
            });
            const data = await res.json();
            if (data.success) {
                this.$dispatch('cart-updated', { count: data.cartCount });
                this.addedId = productId;
                setTimeout(() => this.addedId = null, 2000);
            }
        } catch(e) { console.error(e); }
        this.adding = false;
    },
    quickAdd(id) { this.addToCart(id, 1); }
}" @keydown.escape.window="closeModal()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="reveal text-center mb-16">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-50 text-rose-600 text-[11px] font-bold uppercase tracking-[.2em] mb-4">
                <i data-lucide="zap" class="w-3.5 h-3.5 fill-rose-600"></i> Penawaran Terbatas
            </span>
            <h1 class="text-3xl md:text-[3rem] font-black text-slate-900 leading-tight tracking-tight">Flash Sale <span class="text-rose-600">Hari Ini</span></h1>
            <p class="text-slate-500 text-[16px] mt-4 max-w-lg mx-auto leading-relaxed">Jangan lewatkan diskon gila-gilaan hanya untuk waktu terbatas. Siapa cepat dia dapat!</p>
        </div>

        <!-- Active Flash Sales -->
        @if($flashSales->count() > 0)
            <div class="reveal grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-5 mb-20">
                @foreach($flashSales as $sale)
                    @php
                        $product = $sale->product;
                        $dummyRating = round(4 + (($product->id * 7) % 10) / 10, 1);
                        $finalPrice = $product->sell_price * (1 - $sale->discount_percentage / 100);
                        
                        $productJson = [
                            'id'            => $product->id,
                            'name'          => $product->name,
                            'image'         => $product->image ? asset('storage/' . $product->image) : '',
                            'description'   => $product->description ?? 'Belum ada deskripsi untuk produk ini.',
                            'price'         => $product->sell_price,
                            'finalPrice'    => round($finalPrice),
                            'discount'      => $sale->discount_percentage,
                            'stock'         => $product->stock,
                            'category'      => $product->category->name ?? 'Umum',
                            'rating'        => $dummyRating,
                            'isFlashSale'   => true,
                            'sold'          => $sale->sold_quantity,
                            'max'           => $sale->max_quantity,
                            'progress'      => round($sale->progress),
                        ];
                    @endphp

                    @php $product = $sale->product; @endphp
                    @include('customer.home._product-card', ['product' => $product])
                @endforeach
            </div>
        @else
            <!-- Empty state... -->
        @endif

        @if($upcomingSales->count() > 0)
            <!-- Upcoming sales... -->
        @endif
    </div>

    {{-- Sync the Modal with Home/Index fixed version --}}
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeModal()"></div>
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-[2rem] shadow-2xl shadow-slate-900/20 max-w-4xl w-full z-10 overflow-hidden" @click.stop>
            <button @click="closeModal()" class="absolute top-4 right-4 w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition z-20">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <template x-if="product">
                <div class="flex flex-col lg:flex-row max-h-[90vh] overflow-hidden">
                    <div class="lg:w-[45%] flex-shrink-0">
                        <div class="relative bg-slate-50 overflow-hidden min-h-[420px]">
                            <template x-if="product.image">
                                <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!product.image">
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                    <i data-lucide="package" class="w-16 h-16 text-slate-200"></i>
                                </div>
                            </template>
                            <template x-if="product.isFlashSale">
                                <span class="absolute top-4 left-4 px-3 py-1.5 rounded-lg bg-rose-600 text-white text-[10px] font-black shadow-lg uppercase tracking-wider">FLASH SALE</span>
                            </template>
                        </div>
                        <div class="grid grid-cols-3 gap-px bg-slate-100 border-t border-slate-100 bg-slate-50">
                            <div class="flex flex-col items-center justify-center gap-1 py-6 bg-white text-center">
                                <svg class="w-4 h-4 text-blue-500 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tight leading-normal">Original<br>100%</span>
                            </div>
                            <div class="flex flex-col items-center justify-center gap-1 py-6 bg-white text-center border-x border-slate-50">
                                <svg class="w-4 h-4 text-emerald-500 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tight leading-normal">Pengiriman<br>Cepat</span>
                            </div>
                            <div class="flex flex-col items-center justify-center gap-1 py-6 bg-white text-center">
                                <svg class="w-4 h-4 text-amber-500 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-tight leading-normal">Garansi<br>Toko</span>
                            </div>
                        </div>
                    </div>
                    <div class="lg:w-[55%] p-8 sm:p-10 flex flex-col">
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider" x-text="product.category"></span>
                        <h2 class="text-3xl font-extrabold text-slate-900 mt-3 leading-tight" x-text="product.name"></h2>
                        <template x-if="product.isFlashSale">
                            <div class="mt-4 p-3 bg-rose-50 rounded-2xl border border-rose-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="zap" class="w-3.5 h-3.5 text-rose-600 fill-rose-600 animate-pulse"></i>
                                        <span class="text-[10px] font-black text-rose-600 uppercase tracking-wider">FLASH SALE AKTIF</span>
                                    </div>
                                    <span class="text-[9px] font-bold text-rose-400 capitalize" x-text="'Sisa: ' + (product.max - product.sold)"></span>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-[9px] font-bold text-rose-900/50 uppercase">
                                        <span x-text="'Terjual ' + product.sold + ' item'"></span>
                                        <span x-text="product.progress + '%'"></span>
                                    </div>
                                    <div class="w-full h-1.5 bg-rose-200/50 rounded-full overflow-hidden">
                                        <div class="h-full bg-rose-500 rounded-full transition-all duration-1000" :style="'width: ' + product.progress + '%'"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        {{-- Rating --}}
                        <div class="flex items-center gap-2 mt-3">
                            <div class="flex gap-0.5">
                                <template x-for="i in 5">
                                    <svg class="w-4 h-4" :class="i <= Math.round(product.rating) ? 'text-amber-400 fill-amber-400' : 'text-slate-200'" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                </template>
                            </div>
                            <span class="text-[13px] text-slate-500 font-medium" x-text="product.rating + ' / 5'"></span>
                        </div>
                        <div class="mt-5">
                            <template x-if="product.discount > 0">
                                <div>
                                    <span class="text-[13px] text-slate-400 line-through" x-text="'Rp' + new Intl.NumberFormat('id-ID').format(product.price)"></span>
                                    <span class="block text-2xl font-black leading-tight mt-0.5" 
                                          :class="product.isFlashSale ? 'text-rose-600' : 'text-blue-600'" 
                                          x-text="'Rp' + new Intl.NumberFormat('id-ID').format(product.finalPrice)"></span>
                                </div>
                            </template>
                            <template x-if="product.discount <= 0">
                                <span class="text-2xl font-extrabold text-slate-900 leading-tight" x-text="'Rp' + new Intl.NumberFormat('id-ID').format(product.price)"></span>
                            </template>
                        </div>
                        <div class="mt-5 pt-5 border-t border-slate-100">
                            <h4 class="text-[12px] font-bold text-slate-400 uppercase tracking-wider mb-2">Deskripsi</h4>
                            <p class="text-[13px] text-slate-600 leading-relaxed" x-text="product.description"></p>
                        </div>
                        <div class="mt-auto pt-6">
                            <template x-if="product.stock > 0">
                                <div class="flex flex-col gap-4">
                                    <div class="flex items-center justify-between gap-4 text-sm font-semibold text-slate-600">
                                        <span>Jumlah</span>
                                        <div class="flex items-center border border-slate-200 rounded-full overflow-hidden bg-white shadow-sm">
                                            <button @click="qty = Math.max(1, qty-1)" class="w-12 h-12 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition text-xl font-bold">−</button>
                                            <input type="number" x-model.number="qty" min="1" :max="product.stock" class="w-20 h-12 text-center text-base font-black text-slate-900 border-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            <button @click="qty = Math.min(product.stock, qty+1)" class="w-12 h-12 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition text-xl font-bold">+</button>
                                        </div>
                                    </div>
                                    <button @click="addToCart(product.id, qty).then(() => closeModal())" :disabled="adding"
                                            class="w-full py-4 text-white rounded-[1.5rem] text-[14px] font-bold shadow-lg flex items-center justify-center gap-2 transition-all active:scale-[0.98]"
                                            :class="product.isFlashSale ? 'bg-rose-600 hover:bg-rose-700 disabled:bg-rose-400 shadow-rose-600/30' : 'bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 shadow-blue-600/30'">
                                        <template x-if="!adding">
                                            <span class="flex items-center gap-2">
                                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                                <span x-text="product.isFlashSale ? 'Beli Sekarang' : 'Tambah ke Keranjang'"></span>
                                            </span>
                                        </template>
                                        <template x-if="adding">
                                            <span class="flex items-center gap-2">
                                                <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                                Menambahkan...
                                            </span>
                                        </template>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Toast --}}
    <div x-show="addedId" x-transition class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3.5 bg-emerald-600 text-white text-[13px] font-semibold rounded-xl shadow-2xl shadow-emerald-600/30" x-cloak>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Berhasil ditambahkan ke keranjang!
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () { 
        lucide.createIcons();
        const obs=new IntersectionObserver(e=>{e.forEach(el=>{if(el.isIntersecting){el.target.classList.add('active');obs.unobserve(el.target)}})},{threshold:.1});
        document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
    });
</script>
@endpush
@endsection