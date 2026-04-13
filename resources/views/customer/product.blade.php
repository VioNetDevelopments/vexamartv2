@extends('layouts.customer')
@section('title', $product->name)
@section('content')
<div class="max-w-[1440px] mx-auto px-6 md:px-12 py-10">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-10">
        <a href="{{ route('customer.index') }}" class="hover:text-blue-600 transition">Shop</a>
        <i data-lucide="chevron-right" class="w-3 h-3 translate-y-[-1px]"></i>
        <a href="#" class="hover:text-blue-600 transition">{{ $product->category->name ?? 'General' }}</a>
        <i data-lucide="chevron-right" class="w-3 h-3 translate-y-[-1px]"></i>
        <span class="text-slate-900 dark:text-white">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
        <!-- LEFT: Image Gallery -->
        <div class="lg:col-span-7 flex flex-col gap-6">
            <div class="relative w-full aspect-square md:aspect-video lg:aspect-square bg-white dark:bg-slate-900 rounded-[32px] md:rounded-[48px] overflow-hidden border border-slate-100 dark:border-slate-800 shadow-2xl shadow-slate-200/50 dark:shadow-none group">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[1.5s] cursor-zoom-in">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-800 text-slate-200">
                        <i data-lucide="image" class="w-20 h-20 mb-4 opacity-10"></i>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Preview Not Available</p>
                    </div>
                @endif
                
                <!-- Zoom Indicator -->
                <div class="absolute bottom-6 right-6 w-12 h-12 glass dark:bg-slate-900/80 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <i data-lucide="maximize-2" class="w-5 h-5 text-slate-600 dark:text-slate-300"></i>
                </div>
            </div>
            
            <!-- Thumbnails (Placeholder for multi-image) -->
            <div class="flex gap-4">
                <div class="w-24 h-24 rounded-3xl border-2 border-brand p-1 cursor-pointer">
                    <div class="w-full h-full bg-slate-100 dark:bg-slate-800 rounded-2xl overflow-hidden">
                        @if($product->image)<img src="{{ asset('storage/'.$product->image) }}" class="w-full h-full object-cover">@endif
                    </div>
                </div>
                <!-- Other Thumbnails could go here -->
            </div>
        </div>

        <!-- RIGHT: Product Details -->
        <div class="lg:col-span-5 flex flex-col pt-4">
            <div class="flex items-center gap-3 mb-6">
                <span class="px-4 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-widest rounded-full">{{ $product->category->name ?? 'General' }}</span>
                <div class="flex items-center gap-1.5">
                    <div class="flex -space-x-1">
                        @for($i=0; $i<5; $i++)<i data-lucide="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>@endfor
                    </div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">5.0 (24 Reviews)</span>
                </div>
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white leading-[1.1] mb-6">{{ $product->name }}</h1>
            
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 leading-relaxed mb-10 max-w-md">
                {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini. Namun, kami menjamin kualitas terbaik dari setiap barang yang kami kirimkan untuk kepuasan pelanggan setia VexaMart.' }}
            </p>

            <!-- Pricing Table -->
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-[32px] p-8 border border-slate-100 dark:border-slate-800 mb-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Investment Price</p>
                <div class="flex items-baseline gap-4">
                    <span class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</span>
                    @if($product->buy_price && $product->buy_price < $product->sell_price)
                        <span class="text-lg font-bold text-slate-300 line-through">Rp{{ number_format($product->buy_price * 1.5, 0, ',', '.') }}</span>
                    @endif
                </div>
                
                <hr class="my-8 border-slate-200 dark:border-slate-800">
                
                <!-- Stock Meta -->
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Availability</p>
                        @if($product->stock > 0)
                            <div class="flex items-center gap-2 text-green-600">
                                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                                <span class="text-xs font-black uppercase tracking-widest">In Stock ({{ $product->stock }} units)</span>
                            </div>
                        @else
                            <span class="text-xs font-black text-red-500 uppercase tracking-widest">Out of Stock</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">SKU ID</p>
                        <span class="text-xs font-black text-slate-900 dark:text-white uppercase">{{ $product->product_code ?? 'VXM-'.rand(100,999) }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <div x-data="{ q: 1 }" class="flex items-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-2 shrink-0">
                    <button @click="if(q > 1) q--" class="w-10 h-10 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition text-slate-400"><i data-lucide="minus" class="w-4 h-4"></i></button>
                    <input type="number" x-model="q" name="quantity" class="w-12 text-center text-sm font-black bg-transparent outline-none pointer-events-none">
                    <button @click="if(q < {{ $product->stock }}) q++" class="w-10 h-10 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition text-slate-400"><i data-lucide="plus" class="w-4 h-4"></i></button>
                </div>
                
                <div class="flex-grow flex gap-4">
                    <button type="button" @click="buyAction({{ $product->id }}, q)" @if($product->stock <= 0) disabled @endif 
                            class="flex-grow py-4.5 bg-brand text-white font-black text-sm rounded-2xl shadow-2xl shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                        <i data-lucide="shopping-bag" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i> Acquire Now
                    </button>
                    <!-- Wishlist Placeholder -->
                    <button type="button" class="w-14 h-14 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors">
                        <i data-lucide="heart" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            
            <p class="text-[10px] font-bold text-slate-400 text-center mt-6">🔒 Encrypted Checkout • Fast Global Shipping • 100% Original</p>
        </div>
    </div>

    <!-- Related Products -->
    @if($related->count())
    <div class="mt-40">
        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-3 leading-none">Recommendations</p>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white">Related <span class="text-brand">Artifacts.</span></h2>
            </div>
            <a href="{{ route('customer.index') }}" class="text-sm font-black text-blue-600 hover:underline decoration-2 underline-offset-8">Explore All</a>
        </div>
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($related as $r)
                <div class="group hover-lift animate-reveal">
                    <a href="{{ route('customer.product.show', $r) }}">
                        <div class="relative aspect-square bg-white dark:bg-slate-900 rounded-[32px] overflow-hidden border border-slate-50 dark:border-slate-800 p-8 mb-6 group-hover:shadow-2xl group-hover:shadow-blue-500/5 transition-all">
                            @if($r->image)
                                <img src="{{ asset('storage/'.$r->image) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700">
                            @endif
                        </div>
                        <h4 class="text-sm font-black text-slate-900 dark:text-white mb-1 group-hover:text-brand transition">{{ $r->name }}</h4>
                        <p class="text-lg font-black text-slate-900 dark:text-white">Rp{{ number_format($r->sell_price, 0, ',', '.') }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
