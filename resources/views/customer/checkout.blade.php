@extends('layouts.customer')
@section('title', 'Secure Checkout')
@section('content')
<div class="max-w-[1440px] mx-auto px-6 md:px-12 py-12 lg:py-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-20">
        <div class="animate-reveal">
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-6">
                <a href="{{ route('customer.cart') }}" class="hover:text-blue-600 transition">Shopping Bag</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900 dark:text-white">Checkout Protocol</span>
            </nav>
            <h1 class="text-5xl lg:text-7xl font-black text-slate-900 dark:text-white tracking-tight leading-none">Complete your <span class="text-brand">Order.</span></h1>
        </div>
        <div class="flex items-center gap-6 animate-reveal">
            <div class="flex flex-col items-end">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Encrypted By</p>
                <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-widest">VexaShield Core 2.0</p>
            </div>
            <div class="w-14 h-14 bg-green-500/10 border border-green-500/20 rounded-2xl flex items-center justify-center text-green-500">
                <i data-lucide="lock" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-12 p-6 glass dark:bg-red-900/10 border-red-500/20 text-red-600 dark:text-red-400 text-sm font-bold rounded-3xl flex items-center gap-4 animate-reveal">
            <i data-lucide="alert-circle" class="w-6 h-6"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('customer.checkout.process') }}" method="POST" x-data="{ pay: 'cash' }" class="animate-reveal">
        @csrf
        <input type="hidden" name="payment_method" :value="pay">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            
            <!-- LEFT: Forms (7 Cols) -->
            <div class="lg:col-span-8 space-y-10">
                
                <!-- SECTION: IDENTITAS -->
                <div class="bg-white dark:bg-slate-900 rounded-[48px] p-10 md:p-14 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl"></div>
                    
                    <div class="flex items-center gap-6 mb-12">
                        <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-500/20 relative z-10">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <div class="relative z-10">
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Personal Identity</h2>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Verification of recipient details</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8 relative z-10">
                        <div class="group space-y-3">
                             <label class="px-1 text-[10px] font-black text-slate-400 group-focus-within:text-blue-600 uppercase tracking-widest transition-colors">Recipient Name*</label>
                             <input type="text" name="name" required value="{{ auth()->user()?->name ?? old('name') }}" placeholder="Enter full name"
                                    class="w-full px-8 py-5 bg-slate-50 dark:bg-slate-800/40 rounded-[20px] text-sm font-black border-2 border-transparent focus:border-blue-500/10 focus:ring-0 focus:bg-white dark:focus:bg-slate-800 transition-all outline-none">
                        </div>
                        <div class="group space-y-3">
                             <label class="px-1 text-[10px] font-black text-slate-400 group-focus-within:text-blue-600 uppercase tracking-widest transition-colors">Verified Phone*</label>
                             <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="+62 ..."
                                    class="w-full px-8 py-5 bg-slate-50 dark:bg-slate-800/40 rounded-[20px] text-sm font-black border-2 border-transparent focus:border-blue-500/10 focus:ring-0 focus:bg-white dark:focus:bg-slate-800 transition-all outline-none">
                        </div>
                        <div class="md:col-span-2 group space-y-3">
                             <label class="px-1 text-[10px] font-black text-slate-400 group-focus-within:text-blue-600 uppercase tracking-widest transition-colors">Digital Email</label>
                             <input type="email" name="email" value="{{ auth()->user()?->email ?? old('email') }}" placeholder="mail@example.com"
                                    class="w-full px-8 py-5 bg-slate-50 dark:bg-slate-800/40 rounded-[20px] text-sm font-black border-2 border-transparent focus:border-blue-500/10 focus:ring-0 focus:bg-white dark:focus:bg-slate-800 transition-all outline-none">
                        </div>
                        <div class="md:col-span-2 group space-y-3">
                             <label class="px-1 text-[10px] font-black text-slate-400 group-focus-within:text-blue-600 uppercase tracking-widest transition-colors">Delivery Objective (Address)</label>
                             <textarea name="address" rows="3" placeholder="Jl. Raya Modern No. 01..."
                                       class="w-full px-8 py-5 bg-slate-50 dark:bg-slate-800/40 rounded-[20px] text-sm font-black border-2 border-transparent focus:border-blue-500/10 focus:ring-0 focus:bg-white dark:focus:bg-slate-800 transition-all outline-none resize-none">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION: PAYMENT -->
                <div class="bg-white dark:bg-slate-900 rounded-[48px] p-10 md:p-14 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full blur-3xl"></div>

                    <div class="flex items-center gap-6 mb-12">
                        <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-green-500/20 relative z-10">
                            <i data-lucide="credit-card" class="w-6 h-6"></i>
                        </div>
                        <div class="relative z-10">
                            <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Financial Protocol</h2>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Selection of transaction gateway</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative z-10">
                        @php $methods = [
                            'cash'    => ['label'=>'Physical Cash',     'icon'=>'banknote', 'desc'=>'Pay on courier arrival'],
                            'qris'    => ['label'=>'Instant QRIS',      'icon'=>'qr-code',  'desc'=>'Scan and pay instantly'],
                            'debit'   => ['label'=>'Direct Transfer',   'icon'=>'landmark', 'desc'=>'Manual bank verification'],
                            'ewallet' => ['label'=>'Digital Wallet',    'icon'=>'wallet',   'desc'=>'OVO, GoPay, and more'],
                        ]; @endphp
                        @foreach($methods as $val => $opt)
                            <button type="button" @click="pay='{{ $val }}'"
                                    class="group flex items-center gap-6 p-6 rounded-[28px] border-2 transition-all text-left overflow-hidden relative"
                                    :class="pay==='{{ $val }}' 
                                        ? 'border-blue-600 bg-blue-600 text-white shadow-xl shadow-blue-500/20 active:scale-95' 
                                        : 'border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 text-slate-900 dark:text-slate-400 hover:border-slate-200 dark:hover:border-slate-700'">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                                     :class="pay==='{{ $val }}' ? 'bg-white/20 text-white' : 'bg-white dark:bg-slate-800 text-slate-400 group-hover:text-blue-600'">
                                    <i data-lucide="{{ $opt['icon'] }}" class="w-6 h-6"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black uppercase tracking-tight">{{ $opt['label'] }}</span>
                                    <span class="text-[10px] font-bold opacity-60 uppercase tracking-widest" :class="pay==='{{ $val }}' ? 'text-white' : 'text-slate-400'">{{ $opt['desc'] }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RIGHT: SUMMARY (5 Cols) -->
            <div class="lg:col-span-4 lg:sticky lg:top-[100px]">
                <div class="bg-slate-900 rounded-[48px] border border-slate-800 p-10 md:p-12 shadow-2xl flex flex-col relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-600/10 rounded-full blur-3xl transition-transform group-hover:scale-110"></div>
                    
                    <h2 class="text-3xl font-black text-white mb-10 tracking-tight relative z-10">Review Bag.</h2>

                    <!-- Compact Items List -->
                    <div class="space-y-6 mb-12 max-h-[350px] overflow-y-auto pr-4 scrollbar-none relative z-10">
                        @foreach($cartItems as $item)
                        <div class="flex gap-5 items-center group/item">
                            <div class="w-16 h-16 bg-white/5 rounded-2xl overflow-hidden shrink-0 border border-white/5 p-1">
                                @if($item->product->image)<img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-cover rounded-xl grayscale group-hover/item:grayscale-0 transition-all duration-500">@endif
                            </div>
                            <div class="flex-grow min-w-0">
                                <h4 class="text-[11px] font-black text-white truncate mb-1 uppercase tracking-tight">{{ $item->product->name }}</h4>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em]">{{ $item->quantity }} Units</p>
                            </div>
                            <p class="text-xs font-black text-white shrink-0">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>

                    <!-- Final Math -->
                    <div class="space-y-4 pt-10 border-t border-white/10 mb-10 relative z-10">
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-black text-slate-500 uppercase tracking-widest">Base Value</span>
                            <span class="font-black text-white">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-black text-slate-500 uppercase tracking-widest">Platform Tax ({{ $settings['tax_rate'] ?? 0 }}%)</span>
                            <span class="font-black text-white">Rp{{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="pt-8 mt-6 border-t border-white/10 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-3">Commitment Total</p>
                                <p class="text-4xl font-black text-white tracking-tighter">Rp{{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                            <i data-lucide="shield-check" class="w-10 h-10 text-blue-500/20 mb-1"></i>
                        </div>
                    </div>

                    <input type="hidden" name="total" value="{{ $total }}">
                    <input type="hidden" name="paid_amount" value="{{ $total }}">

                    <button type="submit" 
                            class="w-full py-5 bg-white text-slate-900 font-black text-sm uppercase tracking-[0.2em] rounded-[24px] shadow-2xl hover:bg-brand hover:text-white hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-4 relative z-10">
                        Commit Order <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                    
                    <p class="text-[9px] font-black text-slate-600 text-center uppercase tracking-[0.3em] mt-10 relative z-10">Verification Protocol V.26.11</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection