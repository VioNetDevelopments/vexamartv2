@extends('layouts.customer')

@section('title', 'Beranda - ' . ($settings['store_name'] ?? 'VexaMart'))

@section('content')
<div class="min-h-screen">

    {{-- ═══════════════════════════════════════════════════════════════
        HERO — Split Layout (Codext SaaS Pattern)
    ═══════════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-0 min-h-[600px] lg:min-h-[680px]">

                {{-- LEFT — Blue gradient panel --}}
                <div class="relative flex flex-col justify-center py-16 lg:py-20 lg:pr-16 lg:border-r border-slate-100">
                    {{-- Background gradient — only left side --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 lg:rounded-r-[3rem] z-0"></div>
                    {{-- Decorative circles --}}
                    <div class="absolute top-10 left-10 w-32 h-32 bg-white/5 rounded-full"></div>
                    <div class="absolute bottom-10 right-10 w-48 h-48 bg-blue-400/10 rounded-full"></div>
                    <div class="absolute top-1/2 right-1/4 w-20 h-20 bg-white/[0.03] rounded-full"></div>

                    <div class="relative z-10 px-4 sm:px-0">
                        {{-- Badge --}}
                        <div class="hero-anim opacity-0 translate-y-4" style="animation-delay:0ms">
                            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-[11px] font-medium text-blue-100 backdrop-blur-sm">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                Platform belanja terpercaya
                            </span>
                        </div>

                        {{-- Headline — Inter bold, tight tracking --}}
                        <h1 class="hero-anim opacity-0 translate-y-4 mt-7 text-3xl sm:text-4xl md:text-[2.8rem] font-bold text-white leading-[1.1] tracking-[-0.03em]" style="animation-delay:80ms">
                            Belanja online<br>
                            <span class="text-blue-200">lebih mudah</span> dari<br>sebelumnya.
                        </h1>

                        {{-- Sub --}}
                        <p class="hero-anim opacity-0 translate-y-4 mt-5 text-blue-100/60 text-[15px] leading-relaxed max-w-md" style="animation-delay:160ms">
                            Ribuan produk kebutuhan sehari-hari dengan harga bersaing, pengiriman cepat, dan pelayanan terbaik.
                        </p>

                        {{-- Feature checklist — Codext style --}}
                        <div class="hero-anim opacity-0 translate-y-4 mt-8 space-y-3.5" style="animation-delay:240ms">
                            @foreach([
                                ['shield-check', 'Transaksi aman & terenkripsi'],
                                ['truck', 'Pengiriman cepat ke seluruh Indonesia'],
                                ['sparkles', 'Jaminan produk 100% original'],
                            ] as $item)
                                <div class="flex items-center gap-3 text-white/90">
                                    <div class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center">
                                        <i data-lucide="{{ $item[0] }}" class="w-3 h-3 text-blue-200"></i>
                                    </div>
                                    <span class="text-[13px] font-medium">{{ $item[1] }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- CTA buttons --}}
                        <div class="hero-anim opacity-0 translate-y-4 mt-9 flex flex-wrap gap-3" style="animation-delay:320ms">
                            <a href="#products" class="group inline-flex items-center gap-2 px-7 py-3.5 bg-white text-blue-700 text-[14px] font-bold rounded-xl hover:bg-blue-50 transition-all duration-200 shadow-xl shadow-blue-900/20">
                                Mulai Belanja
                                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform"></i>
                            </a>
                            <a href="{{ route('customer.membership.index') }}" class="inline-flex items-center gap-2 px-7 py-3.5 text-white text-[14px] font-semibold rounded-xl border border-white/20 hover:bg-white/10 transition-all duration-200">
                                <i data-lucide="crown" class="w-4 h-4 text-blue-200"></i>
                                Jadi Member
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT — Illustration / Visual panel --}}
                <div class="relative flex items-center justify-center py-12 lg:py-0 lg:pl-16">
                    <div class="hero-anim opacity-0 translate-y-4 w-full max-w-lg" style="animation-delay:200ms">
                        <div class="relative">
                            {{-- Main visual card --}}
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-blue-900/8 border border-slate-100 bg-white">
                                <img src="https://images.unsplash.com/photo-1604719312566-8912e9227c6a?w=800&q=80"
                                     alt="Minimarket"
                                     class="w-full h-[450px] object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/30 via-transparent to-transparent"></div>
                            </div>

                            {{-- Floating stat cards --}}
                            <div class="absolute -bottom-5 -left-5 bg-white rounded-2xl p-4 shadow-xl shadow-slate-900/8 border border-slate-100 hero-float" style="animation-delay:0s">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                                        <i data-lucide="package" class="w-5 h-5 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Produk</p>
                                        <p class="text-sm font-black text-slate-900">{{ number_format($stats['total_products']) }}+</p>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute -top-4 -right-4 bg-white rounded-2xl p-4 shadow-xl shadow-slate-900/8 border border-slate-100 hero-float" style="animation-delay:1.5s">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                                        <i data-lucide="users" class="w-5 h-5 text-emerald-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pelanggan</p>
                                        <p class="text-sm font-black text-slate-900">{{ number_format($stats['total_customers']) }}+</p>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute top-1/2 -translate-y-1/2 -right-6 bg-white rounded-2xl p-4 shadow-xl shadow-slate-900/8 border border-slate-100 hero-float hidden sm:block" style="animation-delay:2.5s">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                                        <i data-lucide="star" class="w-5 h-5 text-violet-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900">High Quality</p>
                                        <p class="text-[10px] font-bold text-slate-400">Terjamin Original</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════
        FEATURES — Icon cards (Codext \"Allows customization\" style)
    ═══════════════════════════════════════════════════════════════ --}}
    <section class="py-16 md:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-[-0.02em]">Kenapa memilih kami?</h2>
                <p class="text-slate-500 text-[15px] mt-3 max-w-lg mx-auto">Kami hadir untuk membuat pengalaman belanja online Anda lebih menyenangkan</p>
            </div>

            <div class="reveal grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach([
                    ['truck', 'Pengiriman Cepat', 'Pesanan diantar ke rumah Anda dengan cepat dan aman ke seluruh Indonesia.', 'blue'],
                    ['shield-check', 'Pembayaran Aman', 'Semua transaksi dilindungi enkripsi SSL 256-bit untuk keamanan data.', 'emerald'],
                    ['sparkles', 'Produk Berkualitas', 'Setiap produk dikurasi dan dijamin kualitasnya oleh tim kami.', 'violet'],
                    ['headphones', 'Support 24/7', 'Tim customer service kami selalu siap membantu kapan saja.', 'amber'],
                ] as $f)
                    <div class="group bg-white rounded-2xl p-6 border border-slate-100 hover:border-{{ $f[3] }}-200 hover:shadow-lg hover:shadow-{{ $f[3] }}-500/5 transition-all duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-{{ $f[3] }}-50 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="{{ $f[0] }}" class="w-6 h-6 text-{{ $f[3] }}-500"></i>
                        </div>
                        <h3 class="text-[15px] font-bold text-slate-900 mb-2">{{ $f[1] }}</h3>
                        <p class="text-[13px] text-slate-500 leading-relaxed">{{ $f[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════
        CATEGORIES — Clean row (Codext nav style)
    ═══════════════════════════════════════════════════════════════ --}}
    @if($categories->count() > 0)
        <section class="py-14 md:py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Kategori Populer</h2>
                    <a href="#products" class="text-[13px] font-medium text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-1">
                        Lihat semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
                <div class="reveal grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @php
                        $catIcons = ['package','coffee','milk','egg','apple','shirt','sparkles','droplets','flame','pill','baby','paw-print'];
                        $catColors = ['blue','amber','emerald','rose','green','violet','cyan','orange'];
                    @endphp
                    @foreach($categories->take(6) as $index => $category)
                        @php $c = $catColors[$index % count($catColors)]; @endphp
                        <a href="#products"
                           class="group flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:bg-white hover:border-{{ $c }}-200 hover:shadow-md transition-all duration-300">
                            <div class="w-11 h-11 rounded-xl bg-{{ $c }}-50 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                <i data-lucide="{{ $catIcons[$index % count($catIcons)] }}" class="w-5 h-5 text-{{ $c }}-500"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[13px] font-semibold text-slate-900 truncate">{{ $category->name }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $category->products_count }} produk</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ═══════════════════════════════════════════════════════════════
        FEATURED PRODUCTS — \"Pricing card\" style grid
    ═══════════════════════════════════════════════════════════════ --}}
    @if($featuredProducts->count() > 0)
        <section class="py-14 md:py-16 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal flex items-end justify-between mb-10">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-wider mb-2">
                            <i data-lucide="flame" class="w-3 h-3"></i> Promo
                        </span>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-[-0.02em]">Produk Unggulan</h2>
                    </div>
                    <a href="#products" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 text-[13px] font-medium text-blue-600 bg-white border border-blue-200 rounded-xl hover:bg-blue-50 transition-all">
                        Semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <div class="reveal grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($featuredProducts as $product)
                        @include('customer.home._product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ═══════════════════════════════════════════════════════════════
        PROMO BANNERS — Codext \"blog card\" style
    ═══════════════════════════════════════════════════════════════ --}}
    <section class="py-14 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal grid md:grid-cols-2 gap-5">
                {{-- Banner 1 --}}
                <a href="{{ route('customer.flash-sales.index') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 p-8 md:p-10 min-h-[200px] flex flex-col justify-between">
                    <div class="absolute -top-16 -right-16 w-48 h-48 bg-white/[0.06] rounded-full"></div>
                    <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-white/[0.04] rounded-full"></div>
                    <div class="relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-5">
                            <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                        </div>
                        <h3 class="text-white text-2xl font-bold tracking-tight">Flash Sale Hari Ini</h3>
                        <p class="text-blue-100/50 text-[14px] mt-2 max-w-xs">Diskon hingga 50% untuk ribuan produk pilihan. Jangan sampai kehabisan!</p>
                    </div>
                    <span class="relative z-10 self-end inline-flex items-center gap-2 text-white/70 text-[13px] font-medium group-hover:text-white transition-colors">
                        Belanja sekarang <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </a>
                {{-- Banner 2 --}}
                <a href="{{ route('customer.membership.index') }}" class="group relative overflow-hidden rounded-2xl bg-slate-900 p-8 md:p-10 min-h-[200px] flex flex-col justify-between">
                    <div class="absolute -top-16 -right-16 w-48 h-48 bg-blue-600/[0.06] rounded-full"></div>
                    <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-blue-600/[0.04] rounded-full"></div>
                    <div class="relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-blue-600/10 flex items-center justify-center mb-5">
                            <i data-lucide="crown" class="w-5 h-5 text-blue-400"></i>
                        </div>
                        <h3 class="text-white text-2xl font-bold tracking-tight">Program Membership</h3>
                        <p class="text-slate-500 text-[14px] mt-2 max-w-xs">Kumpulkan poin setiap belanja, tukar cashback dan dapatkan keuntungan eksklusif.</p>
                    </div>
                    <span class="relative z-10 self-end inline-flex items-center gap-2 text-slate-600 text-[13px] font-medium group-hover:text-blue-400 transition-colors">
                        Gabung gratis <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </a>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════
        NEW ARRIVALS
    ═══════════════════════════════════════════════════════════════ --}}
    @if($newArrivals->count() > 0)
        <section class="py-14 md:py-16 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal flex items-end justify-between mb-10">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider mb-2">
                            <i data-lucide="sparkles" class="w-3 h-3"></i> New
                        </span>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-[-0.02em]">Baru Datang</h2>
                    </div>
                </div>
                <div class="reveal grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($newArrivals->take(4) as $product)
                        @include('customer.home._product-card', ['product' => $product, 'badge' => 'new'])
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ═══════════════════════════════════════════════════════════════
        TESTIMONIALS — Codext \"user review\" card style
    ═══════════════════════════════════════════════════════════════ --}}
    @if($latestReviews->count() > 0)
        <section class="py-14 md:py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="reveal text-center mb-12">
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-[-0.02em]">Apa kata pelanggan kami</h2>
                    <p class="text-slate-500 text-[15px] mt-3">Ulasan jujur dari mereka yang sudah berbelanja</p>
                </div>

                <div class="reveal grid md:grid-cols-3 gap-5">
                    @foreach($latestReviews as $review)
                        <div class="bg-slate-50 rounded-2xl p-7 border border-slate-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                            {{-- Quote mark --}}
                            <div class="mb-4">
                                <i data-lucide="quote" class="w-8 h-8 text-blue-200"></i>
                            </div>
                            {{-- Stars --}}
                            <div class="flex gap-0.5 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                            {{-- Comment --}}
                            @if($review->comment)
                                <p class="text-slate-600 text-[14px] leading-relaxed mb-6 line-clamp-3">\"{{ $review->comment }}\"</p>
                            @else
                                <p class="text-slate-400 text-[14px] mb-6">Memberikan rating {{ $review->rating }}/5</p>
                            @endif
                            {{-- Author --}}
                            <div class="flex items-center gap-3 pt-5 border-t border-slate-200">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($review->customer_name ?? $review->user?->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-[13px] truncate">{{ $review->customer_name ?? $review->user?->name ?? 'Pelanggan' }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $review->product->name ?? 'Produk' }} · {{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ═══════════════════════════════════════════════════════════════
        ALL PRODUCTS
    ═══════════════════════════════════════════════════════════════ --}}
    <section id="products" class="py-14 md:py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-[-0.02em]">Semua Produk</h2>
                <p class="text-slate-500 text-[15px] mt-3">{{ $products->total() }} produk tersedia untuk Anda</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 md:gap-5">
                @foreach($products as $product)
                    @include('customer.home._product-card', ['product' => $product])
                @endforeach
            </div>
            @if($products->hasPages())
                <div class="mt-12 flex justify-center">{{ $products->links() }}</div>
            @endif
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════
        BOTTOM CTA — Codext footer CTA style
    ═══════════════════════════════════════════════════════════════ --}}
    <section class="py-16 md:py-20 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-64 h-64 bg-white/5 rounded-full blur-[60px]"></div>
            <div class="absolute bottom-0 right-1/4 w-48 h-48 bg-blue-400/10 rounded-full blur-[50px]"></div>
        </div>
        <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-white tracking-[-0.02em]">Siap mulai belanja?</h2>
            <p class="text-blue-100/60 text-[15px] mt-3 max-w-md mx-auto">Bergabunglah dengan {{ number_format($stats['total_customers']) }}+ pelanggan yang sudah mempercayai kami.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="#products" class="group inline-flex items-center gap-2 px-8 py-3.5 bg-white text-blue-700 text-[14px] font-bold rounded-xl hover:bg-blue-50 transition-all shadow-xl">
                    Mulai Belanja
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform"></i>
                </a>
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3.5 text-white text-[14px] font-semibold rounded-xl border border-white/20 hover:bg-white/10 transition-all">
                        Daftar Akun
                    </a>
                @endguest
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Hero entrance via JS (not CSS keyframes) for reliable timing
    document.querySelectorAll('.hero-anim').forEach(el => {
        const d = parseInt(el.style.animationDelay) || 0;
        setTimeout(() => {
            el.style.transition = 'opacity 0.55s ease, transform 0.55s cubic-bezier(0.16,1,0.3,1)';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, d);
    });

    // Reveal on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
});
</script>
@endpush

@push('styles')
<style>
.hero-anim { opacity: 0; transform: translateY(16px); }
.hero-float { animation: hFloat 5s ease-in-out infinite; }
@keyframes hFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }

.reveal {
    opacity: 0; transform: translateY(14px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.reveal.active { opacity: 1; transform: translateY(0); }

.product-card {
    border-radius: 1rem; border: 1px solid #E2E8F0; background: #fff;
    transition: box-shadow 0.25s ease, border-color 0.25s ease; overflow: hidden;
}
.product-card:hover {
    box-shadow: 0 8px 24px -4px rgba(15,23,42,0.08); border-color: #CBD5E1;
}
.product-card .img-zoom { transition: transform 0.35s ease; }
.product-card:hover .img-zoom { transform: scale(1.04); }

.pagination a, .pagination span {
    border-radius: 0.625rem !important; border: 1px solid #E2E8F0 !important;
    color: #0F172A !important; font-weight: 500 !important; font-size: 0.8rem !important;
    padding: 0.4rem 0.75rem !important; transition: all 0.15s ease !important;
}
.pagination a:hover { border-color: #3B82F6 !important; background: rgba(59,130,246,0.05) !important; }
.pagination .active {
    background: #2563EB !important; border-color: #2563EB !important; color: #FFF !important;
    box-shadow: 0 2px 8px rgba(37,99,235,0.25) !important;
}
.pagination .disabled { opacity: 0.25 !important; }
</style>
@endpush
@endsection