@extends('layouts.customer')
@section('title', 'Shopping Cart')
@section('content')
<div class="max-w-[1440px] mx-auto px-6 md:px-12 py-8 lg:py-12">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-8">
        <a href="{{ route('customer.index') }}" class="hover:text-blue-600 transition">Catalogue</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-900 dark:text-white">Review Bag</span>
    </nav>

    @if(count($cartItems))
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- LEFT: Items (8 Cols) -->
            <div class="lg:col-span-8 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none">Your <span class="text-brand">Bag.</span></h1>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ count($cartItems) }} Items</span>
                </div>

                <div class="space-y-3">
                    @foreach($cartItems as $item)
                        <div class="group relative flex items-center gap-6 p-4 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 transition-all hover:bg-slate-50 dark:hover:bg-slate-800/40 animate-reveal">
                            <!-- Compact Image -->
                            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-2xl overflow-hidden shrink-0 border border-slate-50 dark:border-slate-800 p-1">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-cover rounded-xl group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><i data-lucide="package" class="w-6 h-6 text-slate-200"></i></div>
                                @endif
                            </div>

                            <!-- Content Row -->
                            <div class="flex-grow grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-8 items-center">
                                <div class="md:col-span-1">
                                    <p class="text-[8px] font-black text-blue-500 uppercase tracking-widest mb-1">{{ $item->product->category->name ?? 'General' }}</p>
                                    <h3 class="text-sm font-black text-slate-800 dark:text-white leading-tight truncate group-hover:text-blue-600 transition-colors">{{ $item->product->name }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1">Ref: {{ $item->product->id }}</p>
                                </div>

                                <div class="flex items-center justify-center">
                                    <div class="flex items-center bg-slate-100/50 dark:bg-slate-800/50 rounded-xl p-1 border border-slate-200/20 dark:border-slate-700/20 shadow-inner">
                                        <form action="{{ route('customer.cart.update', $item->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                            <button type="submit" @if($item->quantity <= 1) disabled @endif class="w-8 h-8 flex items-center justify-center hover:bg-white dark:hover:bg-slate-700 rounded-lg transition shadow-sm disabled:opacity-20"><i data-lucide="minus" class="w-3.5 h-3.5"></i></button>
                                        </form>
                                        <span class="w-8 text-center text-xs font-black text-slate-700 dark:text-white">{{ $item->quantity }}</span>
                                        <form action="{{ route('customer.cart.update', $item->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                            <button type="submit" @if($item->quantity >= $item->product->stock) disabled @endif class="w-8 h-8 flex items-center justify-center hover:bg-white dark:hover:bg-slate-700 rounded-lg transition shadow-sm disabled:opacity-20"><i data-lucide="plus" class="w-3.5 h-3.5"></i></button>
                                        </form>
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-6 pr-2">
                                    <div class="text-right">
                                        <p class="text-sm font-black text-slate-900 dark:text-white">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                    <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="text-slate-300 hover:text-red-500 transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    <a href="{{ route('customer.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-blue-600 transition-colors uppercase tracking-widest">
                        <i data-lucide="arrow-left" class="w-3 h-3"></i> Back To Selection
                    </a>
                </div>
            </div>

            <!-- RIGHT: Compact Summary (4 Cols) -->
            <div class="lg:col-span-4 lg:sticky lg:top-[90px]">
                <div class="bg-slate-950 rounded-[32px] p-8 overflow-hidden relative shadow-2xl border border-white/5">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-600/10 rounded-full blur-3xl"></div>
                    
                    <h2 class="text-xl font-black text-white mb-8 tracking-tight">Order Profile.</h2>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Aggregate Value</span>
                            <span class="text-xs font-black text-white">Rp{{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Processing Fee</span>
                            <span class="text-[9px] font-black text-green-500 uppercase tracking-widest">Free Flow</span>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-white/5 mb-8 flex justify-between items-end">
                        <div>
                            <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Total Manifest</p>
                            <p class="text-3xl font-black text-white tracking-tighter">Rp{{ number_format($cartTotal, 0, ',', '.') }}</p>
                        </div>
                        <i data-lucide="activity" class="w-6 h-6 text-blue-500/20"></i>
                    </div>

                    <a href="{{ route('customer.checkout') }}" 
                       class="w-full py-4 bg-brand text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-600/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        Execute Checkout <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                    
                    <div class="mt-6 flex flex-col items-center gap-3">
                         <div class="flex items-center gap-2 opacity-30 grayscale invert">
                            <i data-lucide="shield-check" class="w-4 h-4 text-white"></i>
                            <span class="text-[8px] font-black text-white uppercase tracking-widest">Secured by VexaShield</span>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="py-32 flex flex-col items-center text-center animate-reveal">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-900 rounded-[32px] flex items-center justify-center mb-8 shadow-inner">
                <i data-lucide="shopping-bag" class="w-8 h-8 text-slate-200"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">Your bag is empty.</h3>
            <p class="text-slate-400 text-sm mb-10 max-w-sm mx-auto">Mulai kurasi produk impian Anda sekarang.</p>
            <a href="{{ route('customer.index') }}" 
               class="px-8 py-4 bg-brand text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/10 hover:scale-105 transition-all">Start Selection</a>
        </div>
    @endif
</div>
@endsection