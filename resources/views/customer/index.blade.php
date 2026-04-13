@extends('layouts.customer')
@section('title', 'Beranda')
@section('content')
<div class="w-full">
    <!-- PREMIUM HERO SECTION (SaaS 2026 Style) -->
    <section class="relative animate-reveal">
        <div class="relative w-full h-[320px] md:h-[480px] lg:h-[560px] bg-slate-900 group overflow-hidden" 
             x-data="{ active: 0, slides: 4, auto() { setInterval(() => { this.active = (this.active + 1) % this.slides }, 6000) } }"
             x-init="auto()">
            
            <!-- Slides Container -->
            <div class="relative w-full h-full">
                <!-- Slide 1: Welcome -->
                <div x-show="active === 0" x-transition.opacity.duration.1000ms class="absolute inset-0 flex items-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/60 to-transparent z-10"></div>
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2070" class="absolute inset-0 w-full h-full object-cover grayscale opacity-40">
                    <div class="relative z-20 px-8 md:px-20 max-w-2xl translate-y-4 animate-reveal">
                        <p class="text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-4">FUTURE OF COMMERCE</p>
                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-tight mb-6">Elevate Your <span class="text-brand">Lifestyle.</span></h1>
                        <p class="text-slate-400 text-sm md:text-lg mb-8 leading-relaxed">Nikmati pengalaman belanja masa depan dengan kurasi produk terbaik dan sistem pembayaran tercepat di kelasnya.</p>
                        <div class="flex items-center gap-4">
                            <a href="#products" class="px-8 py-3.5 bg-brand text-white font-black text-sm rounded-none shadow-2xl shadow-blue-500/30 hover:scale-105 active:scale-95 transition-all">Explore Collections</a>
                            <div class="w-px h-8 bg-slate-800"></div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-none">Starting from<br><span class="text-white text-sm font-black tracking-normal">Rp5.000</span></p>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: Tech Special -->
                <div x-show="active === 1" x-cloak x-transition.opacity.duration.1000ms class="absolute inset-0 flex items-center">
                    <div class="absolute inset-0 bg-gradient-to-l from-blue-950 via-blue-950/60 to-transparent z-10"></div>
                    <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&q=80&w=2070" class="absolute inset-0 w-full h-full object-cover">
                    <div class="relative z-20 px-8 md:px-20 ml-auto text-right max-w-2xl">
                        <p class="text-[10px] font-black text-blue-300 uppercase tracking-[0.3em] mb-4">NEW COLLECTION</p>
                        <h2 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">Innovate Your <span class="text-blue-400">Desk.</span></h2>
                        <p class="text-blue-100 text-sm md:text-lg mb-8 leading-relaxed opacity-80">Temukan perlengkapan kerja paling modern yang dirancang untuk meningkatkan produktivitas dan estetika ruangan Anda.</p>
                        <a href="#products" class="inline-block px-8 py-3.5 border-2 border-white text-white font-black text-sm rounded-none hover:bg-white hover:text-blue-950 transition-all">View Products</a>
                    </div>
                </div>

                <!-- Slide 3: Flash Sale -->
                <div x-show="active === 2" x-cloak x-transition.opacity.duration.1000ms class="absolute inset-0 flex items-center justify-center text-center">
                    <div class="absolute inset-0 bg-slate-950"></div>
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=2070" class="absolute inset-0 w-full h-full object-cover opacity-20">
                    <div class="relative z-20 max-w-3xl px-6">
                        <div class="inline-block px-4 py-1.5 bg-red-600 text-white text-[10px] font-black rounded-none uppercase tracking-widest mb-6 animate-pulse">LIMITED TIME OFFER</div>
                        <h2 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-8 italic tracking-tighter">MIDNIGHT <span class="text-brand">DRIVE.</span></h2>
                        <div class="flex items-center justify-center gap-10">
                            <div><p class="text-4xl font-black text-white leading-none">50%</p><p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Off Select Items</p></div>
                            <div class="w-px h-12 bg-slate-800"></div>
                            <button class="px-10 py-4 bg-white text-slate-950 font-black text-sm rounded-none hover:bg-brand hover:text-white transition-all">Shop Flash Sale</button>
                        </div>
                    </div>
                </div>

                <!-- Slide 4: Branding -->
                <div x-show="active === 3" x-cloak x-transition.opacity.duration.1000ms class="absolute inset-0 flex flex-col items-center justify-center">
                   <div class="absolute inset-0 bg-blue-600"></div>
                   <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                   <div class="relative z-20 text-center">
                       <i data-lucide="zap" class="w-20 h-20 text-white mx-auto mb-6 animate-float"></i>
                       <h2 class="text-4xl md:text-6xl font-black text-white leading-tight mb-4 uppercase">Empower Your <span class="underline decoration-white/30 decoration-8 underline-offset-8">Routine.</span></h2>
                       <p class="text-white/80 font-medium max-w-lg mx-auto mb-10 text-sm md:text-base italic">"Kualitas tinggi bukan lagi pilihan, tapi standar yang kami sajikan di setiap detil produk."</p>
                       <div class="flex gap-4 justify-center">
                           <div class="w-12 h-12 rounded-full border border-white/30 flex items-center justify-center text-white"><i data-lucide="instagram" class="w-5 h-5"></i></div>
                           <div class="w-12 h-12 rounded-full border border-white/30 flex items-center justify-center text-white"><i data-lucide="twitter" class="w-5 h-5"></i></div>
                           <div class="w-12 h-12 rounded-full border border-white/30 flex items-center justify-center text-white"><i data-lucide="youtube" class="w-5 h-5"></i></div>
                       </div>
                   </div>
                </div>
            </div>

            <!-- Custom Controls (SaaS Style) -->
            <div class="absolute bottom-10 left-10 z-30 flex items-center gap-2">
                <template x-for="i in Array.from({length: slides}, (_, index) => index)">
                    <button @click="active = i" 
                            class="h-1.5 transition-all duration-500"
                            :class="active === i ? 'w-12 bg-brand' : 'w-4 bg-white/30 hover:bg-white/50'"></button>
                </template>
            </div>
            
            <div class="absolute right-10 bottom-10 z-30 hidden md:flex gap-4">
                <button @click="active = (active - 1 + slides) % slides" class="w-12 h-12 border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition backdrop-blur-md">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </button>
                <button @click="active = (active + 1) % slides" class="w-12 h-12 border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition backdrop-blur-md">
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- CONTENT SECTION -->
    <section id="products" class="max-w-[1440px] mx-auto px-6 md:px-10 py-20">
        <!-- Section Title -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16 px-4 md:px-0">
            <div class="animate-reveal">
                <div class="flex items-center gap-2 text-blue-500 mb-2">
                    <span class="w-10 h-0.5 bg-blue-500"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest leading-none">Catalogue 2026</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white mb-0">Discover New <span class="text-brand">Arrivals.</span></h2>
                <p class="text-slate-500 text-sm mt-4 font-medium">Berdasarkan data tren belanja global, inilah koleksi terpilih hari ini.</p>
            </div>
            
            <!-- Filters (SaaS Aesthetic) -->
            <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-none">
                <button class="px-5 py-2.5 bg-brand text-white text-[12px] font-bold rounded-2xl shadow-xl shadow-blue-500/10">All Categories</button>
                @foreach($categories ?? [] as $cat)
                    <button class="px-5 py-2.5 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 text-[12px] font-bold rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-brand hover:text-brand transition-all whitespace-nowrap">{{ optional($cat)->name }}</button>
                @endforeach
                <div class="w-px h-6 bg-slate-200 dark:bg-slate-800 mx-2"></div>
                <button class="w-11 h-11 flex items-center justify-center bg-slate-50 dark:bg-slate-900 rounded-2xl hover:text-brand transition"><i data-lucide="sliders-horizontal" class="w-4 h-4 text-slate-400"></i></button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-10">
            @forelse($products as $product)
                <div class="group relative flex flex-col hover-lift animate-reveal">
                    <!-- Image Card -->
                    <div class="relative aspect-[4/5] bg-white dark:bg-slate-900 rounded-[24px] md:rounded-[32px] overflow-hidden border border-slate-50 dark:border-slate-800 p-2 md:p-3 transition-shadow hover:shadow-[0_20px_50px_rgba(37,99,235,0.06)]">
                        <a href="{{ route('customer.product.show', $product) }}" class="block w-full h-full rounded-[18px] md:rounded-[24px] overflow-hidden relative">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center">
                                    <i data-lucide="box" class="w-12 h-12 text-slate-200 dark:text-slate-700"></i>
                                </div>
                            @endif

                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($product->stock <= 0)
                                    <span class="px-3 py-1 bg-slate-900/80 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest rounded-lg">Sold Out</span>
                                @elseif($product->stock < 5)
                                    <span class="px-3 py-1 bg-amber-500/80 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-widest rounded-lg">Limited</span>
                                @endif
                                <span class="px-3 py-1 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md text-slate-800 dark:text-white text-[9px] font-black uppercase tracking-widest rounded-lg shadow-sm">{{ $product->category->name ?? 'General' }}</span>
                            </div>

                            <!-- Fast Action (Mobile Hidden) -->
                            <div class="absolute inset-x-4 bottom-4 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all hidden md:block">
                                <button type="button" @click.stop.prevent="buyAction({{ $product->id }})" @if($product->stock <= 0) disabled @endif class="w-full py-3.5 bg-white text-slate-900 text-xs font-black rounded-2xl shadow-xl hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center gap-2">
                                    <i data-lucide="plus" class="w-4 h-4"></i> Add To Bag
                                </button>
                            </div>
                        </a>
                    </div>

                    <!-- Meta -->
                    <div class="mt-6 px-4">
                        <a href="{{ route('customer.product.show', $product) }}">
                            <h3 class="text-[15px] md:text-lg font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-brand transition mb-1 leading-tight">{{ $product->name }}</h3>
                        </a>
                        <div class="flex items-center justify-between">
                            <p class="text-[17px] md:text-xl font-black text-slate-900 dark:text-white">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</p>
                            <div class="flex gap-0.5">
                                <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                                <span class="text-[11px] font-black text-slate-400 ml-1">4.9</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-40 text-center">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="ghost" class="w-10 h-10 text-slate-200"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">No items found.</h3>
                    <p class="text-slate-500 font-medium">Coba cek kategori lain atau kembali besok!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination / Load More -->
        <div class="mt-24 text-center">
            <button class="group relative px-10 py-4 bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-black text-sm rounded-none border border-slate-200 dark:border-slate-800 hover:bg-slate-50 transition-all overflow-hidden active:scale-95">
                <span class="relative z-10 flex items-center gap-3">LOAD MORE COLLECTION <i data-lucide="chevron-down" class="w-4 h-4 group-hover:translate-y-1 transition-transform"></i></span>
            </button>
        </div>
    </section>

    <!-- TRUST SECTION -->
    <section class="bg-blue-600 py-24 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        <div class="max-w-7xl mx-auto px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-white">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/20">
                        <i data-lucide="shield-check" class="w-7 h-7"></i>
                    </div>
                    <h4 class="text-xl font-black mb-2 uppercase">Secure Payment</h4>
                    <p class="text-blue-100 text-sm opacity-80 leading-relaxed">Sistem pembayaran QRIS dan Transfer terenkripsi dengan standar keamanan perbankan tertinggi.</p>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/20">
                        <i data-lucide="truck" class="w-7 h-7"></i>
                    </div>
                    <h4 class="text-xl font-black mb-2 uppercase">Express Delivery</h4>
                    <p class="text-blue-100 text-sm opacity-80 leading-relaxed">Kemitraan strategis dengan logistik global memastikan produk sampai ke tangan Anda secepat kilat.</p>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/20">
                        <i data-lucide="refresh-ccw" class="w-7 h-7"></i>
                    </div>
                    <h4 class="text-xl font-black mb-2 uppercase">7-Day Return</h4>
                    <p class="text-blue-100 text-sm opacity-80 leading-relaxed">Garansi pengembalian dana penuh jika produk tidak sesuai dengan ekspektasi kualitas kami.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .scrollbar-none::-webkit-scrollbar { display: none; }
    .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection