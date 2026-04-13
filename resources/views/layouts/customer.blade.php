<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Belanja') — {{ $settings['store_name'] ?? 'VexaMart' }}</title>
    
    <!-- Premium SaaS Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-secondary: #3b82f6;
            --brand-gradient: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
        }

        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        
        [x-cloak] { display: none !important; }

        /* Modern Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 20px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }

        /* High-End Design System Utility */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .bg-brand { background: var(--brand-gradient); }
        .text-brand {
            background: var(--brand-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Smooth Slide Animations */
        .sidebar-overlay {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(4px);
        }
        .sidebar-panel { transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1); }

        /* Micro-interactions */
        .hover-lift { transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .hover-lift:hover { transform: translateY(-4px); }

        /* Global Entry Animation */
        @keyframes pageReveal {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-reveal { animation: pageReveal 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="bg-[#fcfdfe] dark:bg-slate-950 text-slate-800 dark:text-slate-200 antialiased selection:bg-blue-100 selection:text-blue-600"
    x-data="{
        dark: localStorage.getItem('dark')==='1',
        drawer: false,
        chat: false,
        msgs: [],
        input: '',
        busy: false,
        cartCount: {{ $cartCount ?? 0 }},
        addedProduct: null,
        showAddedModal: false,
        init() {
            this.$watch('dark', v => {
                localStorage.setItem('dark', v ? '1' : '0');
                document.documentElement.classList.toggle('dark', v);
            });
            document.documentElement.classList.toggle('dark', this.dark);
        },
        async send() {
            if (!this.input.trim() || this.busy) return;
            const m = this.input.trim();
            this.msgs.push({ r: 'user', t: m });
            this.input = ''; this.busy = true;
            try {
                const res = await fetch('{{ route('customer.chat') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ message: m })
                });
                if (!res.ok) throw new Error('Status: ' + res.status);
                const d = await res.json();
                this.msgs.push({ r: 'ai', t: d.reply });
            } catch (e) { 
                console.error('Chat Error:', e);
                this.msgs.push({ r: 'ai', t: 'Waduh, koneksi Vexa lagi error: ' + e.message }); 
            }
            this.busy = false;
            this.$nextTick(() => { const b = this.$refs.chatbox; if(b) b.scrollTop = b.scrollHeight; });
        },
        async buyAction(pid, qty = 1) {
            try {
                const res = await fetch(`/shop/cart/add/${pid}`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ quantity: qty })
                });
                const d = await res.json();
                if (d.success) {
                    this.cartCount = d.cartCount;
                    this.showAddedModal = true;
                    setTimeout(() => { this.showAddedModal = false; }, 4000);
                } else {
                    alert(d.error || 'Terjadi kesalahan');
                }
            } catch (e) {
                alert('Gagal menambahkan produk ke keranjang');
            }
        }
    }">

    <!-- SUCCESS NOTIFICATION MODAL -->
    <div x-show="showAddedModal" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-12 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-12 scale-90"
         x-cloak
         class="fixed bottom-10 left-10 z-[200] w-[320px] bg-white dark:bg-slate-900 rounded-[32px] p-1 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.3)] border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="p-6 flex items-center gap-5">
            <div class="w-14 h-14 bg-green-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-500/20 shrink-0">
                <i data-lucide="check" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Success</p>
                <p class="text-sm font-black text-slate-800 dark:text-white leading-tight">Berhasil ditambahkan ke keranjang!</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 h-1 bg-green-500 transition-all duration-[4000ms] ease-linear" :style="showAddedModal ? 'width: 0%' : 'width: 100%'" x-init="$watch('showAddedModal', v => { if(v) { $el.style.width='100%'; setTimeout(() => $el.style.width='0%', 50); } })"></div>
    </div>
    <div x-show="false" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" x-cloak>
        <div class="w-full max-w-2xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl p-6">
            <input type="text" placeholder="Cari produk impianmu..." class="w-full text-xl font-medium outline-none bg-transparent border-b-2 border-slate-100 dark:border-slate-800 pb-2 mb-4">
        </div>
    </div>

    <!-- PRESTIGE HEADER -->
    <header class="fixed top-0 inset-x-0 z-50 h-[72px] flex items-center glass dark:bg-slate-900/80 border-b border-slate-200/50 dark:border-slate-800/50">
        <div class="w-full px-4 md:px-8 flex items-center justify-between">
            <!-- Left: Brand -->
            <div class="flex items-center gap-6">
                <button @click="drawer=true" class="group flex flex-col gap-1.5 p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <span class="w-5 h-0.5 bg-slate-600 dark:bg-slate-400 rounded-full group-hover:w-6 transition-all"></span>
                    <span class="w-6 h-0.5 bg-slate-600 dark:bg-slate-400 rounded-full"></span>
                    <span class="w-4 h-0.5 bg-slate-600 dark:bg-slate-400 rounded-full group-hover:w-6 transition-all"></span>
                </button>
                <a href="{{ route('customer.index') }}" class="flex items-center gap-3 active:scale-95 transition">
                    <div class="w-10 h-10 bg-brand rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 rotate-3 group-hover:rotate-0 transition-transform">
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-black text-lg tracking-tight leading-none text-slate-900 dark:text-white">{{ $settings['store_name'] ?? 'VexaMart' }}</span>
                        <span class="text-[9px] font-bold text-blue-500 uppercase tracking-widest mt-0.5">Premium Commerce</span>
                    </div>
                </a>
            </div>

            <!-- Center Search (Hidden on Mobile) -->
            <div class="hidden lg:flex flex-grow max-w-md mx-8">
                <div class="relative w-full">
                    <input type="text" placeholder="Search product..." class="w-full bg-slate-100 dark:bg-slate-800/50 border-none rounded-2xl px-5 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                    <i data-lucide="search" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                </div>
            </div>

            <!-- Right: Secondary Actions -->
            <div class="flex items-center gap-2">
                <button @click="dark=!dark" class="w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition active:scale-90">
                    <i data-lucide="sun" x-show="!dark" x-cloak class="w-5 h-5 text-amber-500"></i>
                    <i data-lucide="moon" x-show="dark" x-cloak class="w-5 h-5 text-blue-400"></i>
                </button>

                <a href="{{ route('customer.cart') }}" class="relative w-10 h-10 flex items-center justify-center rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition active:scale-90">
                    <i data-lucide="shopping-cart" class="w-5 h-5 text-slate-600 dark:text-slate-300"></i>
                    <template x-if="cartCount > 0">
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-4.5 px-1 bg-brand text-white text-[9px] font-black rounded-full flex items-center justify-center border-2 border-white dark:border-slate-900 shadow-sm" x-text="cartCount"></span>
                    </template>
                </a>

                <div class="w-px h-6 bg-slate-200 dark:bg-slate-800 mx-2 hidden md:block"></div>

                @auth
                <div class="relative group" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        <div class="w-8 h-8 rounded-xl bg-brand flex items-center justify-center text-white text-[11px] font-black shadow-md shadow-blue-500/10">
                            {{ strtoupper(substr(auth()->user()->name,0,2)) }}
                        </div>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 top-12 w-48 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-100 dark:border-slate-800 p-2 animate-reveal">
                        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                            <i data-lucide="user" class="w-4 h-4"></i> Profile
                        </a>
                        <hr class="my-1 border-slate-50 dark:border-slate-800">
                        <form method="POST" action="{{ route('logout') }}"> @csrf
                            <button class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 transition">
                                <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="flex items-center gap-3 px-1">
                    <a href="{{ route('login') }}" class="text-[12px] font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 transition">Sign In</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-brand text-white text-[12px] font-bold rounded-2xl shadow-xl shadow-blue-500/20 hover:scale-105 active:scale-95 transition-all">Get Started</a>
                </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- MODERN DRAWER -->
    <div x-show="drawer" x-cloak @click="drawer=false" x-transition.opacity class="fixed inset-0 sidebar-overlay z-[60]"></div>
    <nav class="fixed top-0 left-0 h-full w-[280px] bg-white dark:bg-slate-900 glass dark:backdrop-blur-none z-[70] sidebar-panel flex flex-col"
         :class="drawer ? 'translate-x-0' : '-translate-x-full'">
        <div class="px-6 h-[72px] flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
            <span class="font-black text-xl text-brand">{{ $settings['store_name'] ?? 'VexaMart' }}</span>
            <button @click="drawer=false" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
            </button>
        </div>
        <div class="p-4 flex-grow space-y-1">
            <p class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Navigation</p>
            @php $nav = [
                ['route'=>'customer.index','icon'=>'home','label'=>'Shop Home'],
                ['route'=>'customer.cart','icon'=>'shopping-bag','label'=>'My Cart'],
                ['route'=>'customer.checkout','icon'=>'check-circle','label'=>'Secure Checkout'],
                ['route'=>'customer.credits','icon'=>'code-2','label'=>'Developer Credits'],
            ]; @endphp
            @foreach($nav as $n)
            <a href="{{ route($n['route']) }}"
               class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-[13px] font-bold transition-all
                      {{ request()->routeIs($n['route']) ? 'bg-brand text-white shadow-xl shadow-blue-500/20 translate-x-1' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                <i data-lucide="{{ $n['icon'] }}" class="w-5 h-5"></i>
                {{ $n['label'] }}
            </a>
            @endforeach
        </div>
        <div class="p-6 border-t border-slate-100 dark:border-slate-800">
            <div class="bg-blue-50 dark:bg-blue-900/10 p-4 rounded-3xl">
                <p class="text-[11px] font-black text-blue-600 dark:text-blue-400 mb-1">PRO MEMBERSHIP</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-snug mb-3">Dapatkan diskon eksklusif dan gratis ongkir selamanya.</p>
                <button class="w-full py-2.5 bg-brand text-white text-[11px] font-black rounded-2xl shadow-lg">Upgrade Now</button>
            </div>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <main class="pt-[72px] min-h-screen">
        <div class="animate-reveal">
            @yield('content')
        </div>

        <!-- HIGH-END FOOTER -->
        <footer class="bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 mt-20 pt-20">
            <div class="max-w-7xl mx-auto px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-brand rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
                        </div>
                        <span class="font-black text-2xl text-slate-900 dark:text-white uppercase">{{ $settings['store_name'] ?? 'VexaMart' }}</span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed max-w-xs">Elevating your shopping experience with premium curated products and next-gen AI assistance. Built for the future of retail.</p>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 underline decoration-blue-500 decoration-2 underline-offset-8">Explore</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('customer.index') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-blue-600 transition">All Categories</a></li>
                        <li><a href="{{ route('customer.cart') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-blue-600 transition">Shopping Cart</a></li>
                        <li><a href="{{ route('customer.credits') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-blue-600 transition">Developer Team</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 underline decoration-blue-500 decoration-2 underline-offset-8">Support</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-blue-600 transition">Help Center</a></li>
                        <li><a href="#" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-blue-600 transition">Privacy Policy</a></li>
                        <li><a href="#" class="text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-blue-600 transition">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 underline decoration-blue-500 decoration-2 underline-offset-8">Connect</h4>
                    <div class="flex gap-4 mb-6">
                        <a href="#" class="group w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center border border-slate-100 dark:border-slate-800 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all active:scale-95 shadow-sm">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.058-1.69-.072-4.949-.072zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="group w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center border border-slate-100 dark:border-slate-800 hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all active:scale-95 shadow-sm">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-400 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="group w-12 h-12 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center border border-slate-100 dark:border-slate-800 hover:border-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all active:scale-95 shadow-sm">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-700 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </a>
                    </div>
                    <div class="flex items-center gap-3 text-sm font-semibold text-slate-600 dark:text-slate-400"><i data-lucide="phone" class="w-4 h-4 text-blue-600"></i> {{ $settings['store_phone'] ?? '+62 123 4567 890' }}</div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-8 py-10 border-t border-slate-50 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-xs font-bold text-slate-400">© 2026 {{ $settings['store_name'] ?? 'VexaMart' }} Inc. Standardized Platform for Global Commerce.</p>
                <div class="flex items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-800 opacity-60 hover:opacity-100 transition shadow-sm">
                        <i data-lucide="credit-card" class="w-4 h-4 text-slate-600 dark:text-slate-400"></i>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Visa</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-800 opacity-60 hover:opacity-100 transition shadow-sm">
                        <i data-lucide="landmark" class="w-4 h-4 text-slate-600 dark:text-slate-400"></i>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Bank</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-800 opacity-60 hover:opacity-100 transition shadow-sm">
                        <i data-lucide="qr-code" class="w-4 h-4 text-slate-600 dark:text-slate-400"></i>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">QRIS</span>
                    </div>
                </div>
                </div>
            </div>
        </footer>
    </main>

    <!-- FLOATING CS VEXA (Redesigned for Premium feel) -->
    <div class="fixed bottom-6 right-6 z-[100] group">
        <button @click="chat=!chat"
                class="w-16 h-16 bg-brand rounded-[24px] shadow-2xl flex items-center justify-center text-white hover:scale-110 active:scale-95 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition"></div>
            <i data-lucide="headphones" x-show="!chat" class="w-7 h-7 relative z-10"></i>
            <i data-lucide="x" x-show="chat" x-cloak class="w-6 h-6 relative z-10"></i>
            <span class="absolute top-3 right-3 w-3 h-3 bg-green-500 rounded-full border-2 border-white pulse-dot"></span>
        </button>

        <!-- Redesigned CS Chat Window -->
        <div x-show="chat" x-cloak
             x-transition:enter="transition duration-400 origin-bottom-right"
             x-transition:enter-start="opacity-0 scale-90 translate-y-10"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="absolute bottom-20 right-0 w-[400px] h-[600px] max-h-[80vh] bg-white dark:bg-slate-900 rounded-[32px] shadow-2xl border border-slate-200/50 dark:border-slate-800/50 overflow-hidden flex flex-col flex-grow">
            <!-- Glass Header -->
            <div class="bg-brand p-6 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 p-0.5 border-2 border-white/30 backdrop-blur-md overflow-hidden animate-float">
                        <img src="https://ui-avatars.com/api/?name=Vexa+AI&background=ffffff&color=2563eb&bold=true" class="w-full h-full object-cover rounded-xl">
                    </div>
                    <div>
                        <p class="text-white font-black text-lg leading-tight">Vexa Intelligent</p>
                        <p class="text-blue-100 text-[10px] font-black uppercase tracking-widest mt-0.5 flex items-center gap-1.5"><span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span> Ultra Performance AI</p>
                    </div>
                </div>
                <button @click="chat=false" class="p-2 bg-white/10 rounded-xl hover:bg-white/20 transition"><i data-lucide="minus" class="w-4 h-4 text-white"></i></button>
            </div>

            <!-- Messages Stream -->
            <div x-ref="chatbox" class="flex-grow overflow-y-auto p-6 space-y-6 bg-[#f8fafc] dark:bg-slate-950/40">
                <!-- Initial Welcome Message -->
                <div class="flex gap-4">
                    <div class="w-9 h-9 rounded-xl bg-brand flex items-center justify-center shrink-0 shadow-lg shadow-blue-500/10">
                         <i data-lucide="sparkles" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="bg-white dark:bg-slate-800 rounded-3xl rounded-tl-none px-4 py-3 shadow-sm border border-slate-100 dark:border-slate-800/50 max-w-[85%]">
                        <p class="text-[13px] font-medium text-slate-700 dark:text-slate-300 leading-relaxed">Selamat datang di {{ $settings['store_name'] ?? 'VexaMart' }}! 👋 Nama saya Vexa, asisten cerdas Anda. Ingin mencari produk tertentu atau butuh bantuan checkout?</p>
                    </div>
                </div>
                
                <template x-for="(m,i) in msgs" :key="i">
                    <div class="flex gap-4 animate-reveal" :class="m.r==='user'?'flex-row-reverse':''">
                        <div class="w-9 h-9 rounded-xl shrink-0 overflow-hidden flex items-center justify-center text-[10px] font-black text-white shadow-lg"
                             :class="m.r==='user'?'bg-brand':'bg-brand shadow-blue-500/10'">
                            <template x-if="m.r==='user'">
                                @auth<span>{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>@else<i data-lucide="user" class="w-4 h-4"></i>@endauth
                            </template>
                            <template x-if="m.r==='ai'">
                                <img src="https://ui-avatars.com/api/?name=Vexa+AI&background=ffffff&color=2563eb&bold=true" class="w-full h-full object-cover">
                            </template>
                        </div>
                        <div class="px-5 py-3.5 rounded-3xl max-w-[85%] text-[13px] font-medium leading-relaxed"
                             :class="m.r==='user'?'bg-brand text-white rounded-tr-none shadow-xl shadow-blue-500/20':'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-tl-none shadow-sm border border-slate-100 dark:border-slate-800/50'">
                            <p x-text="m.t"></p>
                        </div>
                    </div>
                </template>

                <div x-show="busy" class="flex gap-4">
                    <div class="w-9 h-9 rounded-xl bg-brand flex items-center justify-center shrink-0">
                         <i data-lucide="loader-2" class="w-5 h-5 text-white animate-spin"></i>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-5 py-4 rounded-3xl rounded-tl-none flex items-center gap-1.5 shadow-sm">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Input Dock -->
            <div class="p-6 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 shrink-0">
                <form @submit.prevent="send" class="relative flex items-center gap-3">
                    <div class="relative flex-grow">
                        <input x-model="input" type="text" placeholder="Type a message..."
                               class="w-full text-sm px-5 py-4 bg-slate-100 dark:bg-slate-800 rounded-[24px] outline-none focus:ring-4 focus:ring-blue-500/10 text-slate-800 dark:text-white placeholder-slate-400 transition-all border border-transparent focus:border-blue-500/20 shadow-inner":disabled="busy">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2">
                             <button type="button" class="p-1 text-slate-400 hover:text-blue-600 transition"><i data-lucide="smile" class="w-4 h-4"></i></button>
                        </div>
                    </div>
                    <button type="submit" :disabled="busy||!input.trim()"
                            class="w-14 h-14 bg-brand rounded-2xl flex items-center justify-center text-white disabled:opacity-40 hover:scale-105 active:scale-95 shadow-xl shadow-blue-500/20 transition-all shrink-0">
                        <i data-lucide="send" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Injection -->
    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
        document.addEventListener('alpine:initialized', () => lucide.createIcons());
    </script>
</body>
</html>