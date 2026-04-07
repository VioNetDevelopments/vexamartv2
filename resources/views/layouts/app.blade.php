<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" :class="{ 'dark': isDark }" x-data="{ 
          sidebarOpen: true, 
          isDark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.isDark = !this.isDark;
              localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
          }
      }" class="antialiased">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VexaMart - POS System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-navy-950 dark:text-slate-100 font-sans overflow-hidden">

    <div class="flex h-screen w-full" x-data="{ mobileMenuOpen: false }">
        
        <!-- Overlay Mobile -->
        <div x-show="mobileMenuOpen" 
             @click="mobileMenuOpen = false"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden backdrop-blur-sm"></div>

        <!-- SIDEBAR -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" 
               class="fixed inset-y-0 left-0 z-50 flex flex-col bg-navy-900 border-r border-white/10 transition-all duration-300 ease-in-out lg:static lg:inset-0 shadow-2xl">
            
            <!-- Logo -->
            <div class="flex h-16 items-center justify-between px-6 border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30 flex-shrink-0">
                        <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                    </div>
                    <span x-show="sidebarOpen" class="text-xl font-bold text-white transition-opacity duration-300">VexaMart</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
                @php
                    $menuItems = [
                        ['name' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => route('admin.dashboard'), 'role' => ['owner', 'admin']],
                        ['name' => 'Kasir (POS)', 'icon' => 'monitor-smartphone', 'route' => route('cashier.pos'), 'role' => ['cashier', 'admin', 'owner']],
                        ['name' => 'Produk', 'icon' => 'package', 'route' => route('admin.products.index'), 'role' => ['owner', 'admin']],
                        ['name' => 'Transaksi', 'icon' => 'receipt', 'route' => route('admin.transactions.index'), 'role' => ['owner', 'admin']],
                        ['name' => 'Stok', 'icon' => 'warehouse', 'route' => route('admin.stock.index'), 'role' => ['owner', 'admin']],
                        ['name' => 'Laporan', 'icon' => 'bar-chart-3', 'route' => route('admin.reports.index'), 'role' => ['owner', 'admin']],
                        ['name' => 'Pelanggan', 'icon' => 'users', 'route' => route('admin.customers.index'), 'role' => ['owner', 'admin']],
                        ['name' => 'Pengaturan', 'icon' => 'settings', 'route' => route('admin.settings.index'), 'role' => ['owner', 'admin']],
                    ];

                    $userRole = auth()->user()->role;
                @endphp

                @foreach($menuItems as $item)
                    @if(in_array($userRole, $item['role']))
                        @php
                            $isActive = request()->routeIs($item['route']);
                        @endphp
                        <a href="{{ $item['route'] }}" 
                           class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-200 
                                  {{ $isActive ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                            <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5 shrink-0"></i>
                            <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity duration-300">{{ $item['name'] }}</span>

                            @if($isActive)
                                <div x-show="sidebarOpen" class="ml-auto w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div>
                            @endif
                        </a>
                    @endif
                @endforeach
            </nav>

            <!-- Bottom Section - Removed Profile from here -->
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex flex-1 flex-col overflow-hidden">
            
            <!-- TOPBAR -->
            <header class="relative z-[50] flex h-16 items-center justify-between border-b border-slate-200 bg-white/80 px-6 backdrop-blur-md dark:border-white/5 dark:bg-navy-900/80">
                
                <!-- Left -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-navy-900 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-white">
                        <i data-lucide="panel-left" class="h-5 w-5"></i>
                    </button>
                    
                    <!-- Search -->
                    <div class="relative hidden md:block" x-data="{ 
                        search: '', 
                        results: [], 
                        loading: false,
                        showResults: false,
                        async performSearch() {
                            if (this.search.length < 2) {
                                this.results = [];
                                this.showResults = false;
                                return;
                            }
                            this.loading = true;
                            this.showResults = true;
                            try {
                                const response = await fetch(`/admin/search?q=${encodeURIComponent(this.search)}`);
                                this.results = await response.json();
                                // Re-initialize icons for new results
                                this.$nextTick(() => {
                                    if (window.lucide) window.lucide.createIcons();
                                });
                            } catch (e) {
                                console.error(e);
                            } finally {
                                this.loading = false;
                            }
                        }
                    }">
                        <div class="relative group">
                            <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                            <input type="text" 
                                   x-model="search"
                                   @input.debounce.300ms="performSearch()"
                                   @focus="if(results.length > 0) showResults = true"
                                   @keydown.escape="showResults = false"
                                   @click.away="showResults = false"
                                   placeholder="Cari produk, transaksi..." 
                                   class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm outline-none focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 focus:bg-white transition-all dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                               
                        <!-- Search Results Dropdown -->
                        <div x-show="showResults" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute top-full left-0 mt-3 w-80 bg-white dark:bg-navy-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-white/10 overflow-hidden z-[100]"
                             style="display: none;">
                            
                            <div class="p-3 max-h-[400px] overflow-y-auto custom-scrollbar">
                                <template x-if="loading">
                                    <div class="py-10 text-center">
                                        <div class="inline-block h-6 w-6 animate-spin rounded-full border-2 border-accent-500 border-t-transparent"></div>
                                        <p class="mt-2 text-xs text-slate-500">Mencari...</p>
                                    </div>
                                </template>
                                
                                <template x-if="!loading && results.length === 0">
                                    <div class="py-10 text-center">
                                        <i data-lucide="search-x" class="h-8 w-8 text-slate-300 mx-auto mb-2"></i>
                                        <p class="text-sm text-slate-500">Hasil tidak ditemukan</p>
                                    </div>
                                </template>

                                <div class="space-y-1">
                                    <template x-for="item in results" :key="item.type + '-' + item.id">
                                        <a :href="item.url" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                                            <div class="h-10 w-10 rounded-lg flex items-center justify-center flex-shrink-0" 
                                                 :class="item.type === 'product' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/20' : 'bg-purple-50 text-purple-600 dark:bg-purple-900/20'">
                                                <i :data-lucide="item.type === 'product' ? 'package' : 'receipt'" class="h-5 w-5"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-navy-900 dark:text-white truncate" x-text="item.title"></p>
                                                <p class="text-xs text-slate-500 truncate" x-text="item.subtitle"></p>
                                            </div>
                                            <i data-lucide="chevron-right" class="h-4 w-4 text-slate-300 group-hover:text-accent-500 transition-colors"></i>
                                        </a>
                                    </template>
                                </div>
                            </div>

                            <div x-show="results.length > 0" class="p-2 border-t border-slate-100 dark:border-white/10 bg-slate-50/50 dark:bg-white/5">
                                <p class="text-[10px] text-center text-slate-400">Tekan ESC untuk menutup</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right -->
                <div class="flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-accent-500 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-yellow-400">
                        <i x-show="!isDark" data-lucide="sun" class="h-5 w-5"></i>
                        <i x-show="isDark" data-lucide="moon" class="h-5 w-5"></i>
                    </button>

                    <!-- Notifications -->
                    <button class="relative rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-accent-500 dark:text-slate-400 dark:hover:bg-white/10">
                        <i data-lucide="bell" class="h-5 w-5"></i>
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-danger animate-pulse"></span>
                    </button>

                    <!-- Profile Dropdown - Perbaiki z-index -->
                    <div class="relative" x-data="{ open: false }" style="z-index: 60;">
                        <button @click="open = !open" @click.outside="open = false" 
                                class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-2 shadow-sm hover:border-accent-500 hover:shadow-md transition-all dark:border-white/10 dark:bg-navy-800">
                            <!-- Avatar dengan Online Status -->
                            <div class="relative">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <!-- Online Status Dot -->
                                <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-success border-2 border-white dark:border-navy-800 animate-pulse"></span>
                            </div>
                            
                            <div class="hidden lg:block text-left">
                                <p class="text-sm font-semibold text-navy-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</p>
                            </div>
                            
                            <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400"></i>
                        </button>
                        
                        <!-- Dropdown Menu - Perbaiki positioning -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 mt-2 w-72 origin-top-right rounded-2xl bg-white p-3 shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-navy-800 dark:ring-white/10"
                             style="display: none; z-index: 70;">
                            
                            <!-- User Info Card -->
                            <div class="mb-3 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 p-4 text-white">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div class="h-12 w-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                        </div>
                                        <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-success border-2 border-accent-500"></span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-accent-100">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center gap-2 text-xs">
                                    <span class="flex items-center gap-1 rounded-full bg-white/20 px-2 py-1">
                                        <span class="h-1.5 w-1.5 rounded-full bg-success animate-pulse"></span>
                                        Online
                                    </span>
                                    <span class="text-accent-100">Last login: {{ now()->format('H:i') }}</span>
                                </div>
                            </div>
                            
                            <!-- Menu Items -->
                            <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-accent-50 hover:text-accent-600 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-accent-400 transition-colors">
                                <i data-lucide="user" class="h-4 w-4"></i>
                                <span>Profil</span>
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-accent-50 hover:text-accent-600 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-accent-400 transition-colors">
                                <i data-lucide="settings" class="h-4 w-4"></i>
                                <span>Pengaturan</span>
                            </a>
                            <a href="#" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 hover:bg-accent-50 hover:text-accent-600 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-accent-400 transition-colors">
                                <i data-lucide="help-circle" class="h-4 w-4"></i>
                                <span>Bantuan</span>
                            </a>
                            
                            <div class="my-2 border-t border-slate-100 dark:border-white/10"></div>
                            
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-danger hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i data-lucide="log-out" class="h-4 w-4"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-6 dark:bg-navy-950">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Initialize Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
        
        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => lucide.createIcons(), 100);
        });
    </script>
    
    @stack('scripts')
</body>
</html>