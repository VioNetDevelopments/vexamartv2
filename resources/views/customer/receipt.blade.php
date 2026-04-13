@extends('layouts.customer')
@section('title', 'Transaction Confirmed')
@section('content')
<div class="max-w-[1440px] mx-auto px-6 md:px-12 py-10 lg:py-16 flex flex-col items-center">
    <!-- Compact Header -->
    <div class="text-center mb-12 animate-reveal">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/5 border border-green-500/10 rounded-full text-green-600 dark:text-green-500 text-[9px] font-black uppercase tracking-[0.2em] mb-6">
            <i data-lucide="check" class="w-3 h-3"></i> Confirmed
        </div>
        <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tighter leading-tight mb-4">Transfer <span class="text-blue-600">Complete.</span></h1>
    </div>

    <!-- COMPACT DIGITAL SLIP -->
    <div class="relative w-full max-w-lg animate-reveal" style="animation-delay: 0.1s">
        <!-- Ticket Perforation Logic (Top) -->
        <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-6 h-6 bg-[#fcfdfe] dark:bg-slate-950 rounded-full z-10 border border-slate-50 dark:border-slate-900"></div>
        
        <div class="bg-white dark:bg-slate-900 rounded-[32px] shadow-[0_30px_70px_-20px_rgba(0,0,0,0.12)] border border-slate-100 dark:border-slate-800 overflow-hidden relative">
            <!-- Header Section (Compact) -->
            <div class="bg-slate-950 p-10 text-center relative overflow-hidden">
                 <div class="absolute inset-0 opacity-5 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                 <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-600/10 rounded-full blur-2xl"></div>
                 
                 <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 relative z-10">Proof of Transaction</p>
                 <h2 class="text-2xl font-black text-white relative z-10 tracking-tight">{{ $transaction->invoice_code }}</h2>
                 <p class="text-[10px] font-bold text-slate-400 mt-1 relative z-10 uppercase tracking-widest">{{ $transaction->created_at->format('d M Y • H:i') }}</p>
            </div>

            <!-- Content Body (Condensed) -->
            <div class="p-8 md:p-10">
                <div class="flex justify-between items-start mb-10 pb-6 border-b border-slate-50 dark:border-slate-800/50">
                    <div>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">Merchant</p>
                        <p class="text-xs font-black text-slate-900 dark:text-white uppercase leading-tight">{{ $settings['store_name'] ?? 'VexaMart' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-2">Gateway</p>
                        <div class="flex items-center justify-end gap-1.5">
                             <span class="text-xs font-black text-slate-900 dark:text-white uppercase">{{ $transaction->payment_method }}</span>
                             <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Product Lines (Compact) -->
                <div class="space-y-4 mb-10">
                    @foreach($transaction->items as $item)
                    <div class="flex justify-between items-center text-xs">
                        <div class="flex flex-col min-w-0">
                            <h4 class="font-black text-slate-800 dark:text-white uppercase truncate pr-4">{{ $item->product->name }}</h4>
                            <p class="text-[9px] font-bold text-slate-400 mt-0.5">{{ $item->qty }} UNITS</p>
                        </div>
                        <p class="font-black text-slate-900 dark:text-white shrink-0">Rp{{ number_format($item->subtotal,0,',','.') }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Totals Section (Compact) -->
                <div class="bg-slate-50 dark:bg-slate-800/40 rounded-2xl p-6 space-y-3">
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-black text-slate-400 uppercase tracking-widest text-[8px]">Base Unit Cost</span>
                        <span class="font-black text-slate-900 dark:text-white">Rp{{ number_format($transaction->subtotal,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-black text-slate-400 uppercase tracking-widest text-[8px]">Applicable Tax</span>
                        <span class="font-black text-slate-900 dark:text-white">Rp{{ number_format($transaction->tax,0,',','.') }}</span>
                    </div>
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 mt-1 flex justify-between items-end">
                        <div class="flex flex-col">
                            <p class="text-[8px] font-black text-blue-600 uppercase tracking-widest mb-1">Total Impact</p>
                            <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">Rp{{ number_format($transaction->grand_total,0,',','.') }}</p>
                        </div>
                        <i data-lucide="shield-check" class="w-6 h-6 text-green-500/30"></i>
                    </div>
                </div>

                <div class="mt-10 flex flex-col items-center">
                    <div class="w-16 h-16 p-2 bg-white dark:bg-slate-800 border-2 border-slate-100 dark:border-slate-700 rounded-2xl mb-4 flex items-center justify-center">
                        <i data-lucide="qr-code" class="w-8 h-8 text-slate-200"></i>
                    </div>
                    <p class="text-[8px] font-medium text-slate-300 dark:text-slate-600 uppercase break-all text-center leading-relaxed tracking-widest">
                        SECURE_ID: {{ substr(hash('sha256', $transaction->invoice_code), 0, 16) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Perforation Edge (Bottom) -->
        <div class="flex justify-center gap-2 mt-4 opacity-10">
             @for($i=0; $i<8; $i++)
             <div class="w-1.5 h-1.5 bg-slate-400 rounded-full"></div>
             @endfor
        </div>
    </div>

    <!-- Actions (Compact) -->
    <div class="mt-12 flex flex-wrap justify-center gap-4 animate-reveal" style="animation-delay: 0.3s">
        <button onclick="window.print()" class="px-8 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-950 dark:text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all flex items-center gap-2">
            <i data-lucide="printer" class="w-3.5 h-3.5 text-slate-400"></i> Print Slip
        </button>
        <a href="{{ route('customer.index') }}" class="px-8 py-3.5 bg-brand text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-xl shadow-blue-500/10 hover:scale-105 transition-all flex items-center gap-2">
            Catalogue <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
        </a>
    </div>
</div>
@endsection