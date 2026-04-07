<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      :class="{ 'dark': isDark }" 
      x-data="{ 
          sidebarOpen: true, 
          isDark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.isDark = !this.isDark;
              localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
          }
      }" 
      class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VexaMart - POS System</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Smooth scroll untuk sidebar */
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
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 bg-black/50 z-20 lg:hidden"></div>

        <!-- SIDEBAR -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" 
               class="fixed inset-y-0 left-0 z-30 flex flex-col bg-navy-900 border-r border-white/10 transition-all duration-300 ease-in-out lg:static lg:inset-0 shadow-2xl">
            
            <!-- Logo -->
            <div class="flex h-16 items-center justify-between px-6 border-b border-white/5">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-accent-500 text-white shadow-glow flex-shrink-0">
                        <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                    </div>
                    <span x-show="sidebarOpen" class="text-lg font-bold tracking-wide text-white transition-opacity duration-300">VexaMart</span>
                </a>
            </div>

           <!-- Navigation -->
<nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
    @php
        $userRole = auth()->user()->role;
        $currentRoute = request()->route()->getName();
        
        // Helper function untuk cek active route
        function isActiveRoute($routes) {
            if (!is_array($routes)) $routes = [$routes];
            foreach ($routes as $route) {
                if (request()->routeIs($route)) return true;
            }
            return false;
        }
        
        // Definisi menu dengan route dan role yang benar
        $menuItems = [
            [
                'name' => 'Dashboard', 
                'icon' => 'layout-dashboard', 
                'route' => route('admin.dashboard'), 
                'routeName' => 'admin.dashboard',
                'role' => ['owner', 'admin']
            ],
            [
                'name' => 'Kasir (POS)', 
                'icon' => 'monitor-smartphone', 
                'route' => route('cashier.pos'), 
                'routeName' => 'cashier.pos',
                'role' => ['cashier', 'admin', 'owner']
            ],
            [
                'name' => 'Produk', 
                'icon' => 'package', 
                'route' => route('admin.products.index'), 
                'routeName' => 'admin.products.*',
                'role' => ['owner', 'admin']
            ],
            [
                'name' => 'Transaksi', 
                'icon' => 'receipt', 
                'route' => route('admin.transactions.index'), 
                'routeName' => 'admin.transactions.*',
                'role' => ['owner', 'admin']
            ],
            [
                'name' => 'Stok', 
                'icon' => 'warehouse', 
                'route' => route('admin.stock.index'), 
                'routeName' => 'admin.stock.*',
                'role' => ['owner', 'admin']
            ],
            [
                'name' => 'Laporan', 
                'icon' => 'bar-chart-3', 
                'route' => route('admin.reports.index'), 
                'routeName' => 'admin.reports.*',
                'role' => ['owner', 'admin']
            ],
            [
                'name' => 'Pelanggan', 
                'icon' => 'users', 
                'route' => route('admin.customers.index'), 
                'routeName' => 'admin.customers.*',
                'role' => ['owner', 'admin']
            ],
            [
                'name' => 'Pengaturan', 
                'icon' => 'settings', 
                'route' => route('admin.settings.index'), 
                'routeName' => 'admin.settings.*',
                'role' => ['owner', 'admin']
            ],
        ];
    @endphp

    @foreach($menuItems as $item)
        @if(in_array($userRole, $item['role']))
            @php
                $isActive = request()->routeIs($item['routeName']);
            @endphp
            <a href="{{ $item['route'] }}" 
               class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-200 
                      hover:bg-white/10 hover:text-white hover:shadow-lg 
                      {{ $isActive ? 'bg-accent-500 text-white shadow-glow' : 'text-slate-400' }}">
                <i data-lucide="{{ $item['icon'] }}" class="h-5 w-5 shrink-0"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity duration-300">{{ $item['name'] }}</span>
                
                <!-- Tooltip saat collapse -->
                <div x-show="!sidebarOpen" 
                     class="absolute left-16 z-50 hidden rounded-md bg-navy-800 px-2 py-1 text-xs text-white shadow-lg group-hover:block whitespace-nowrap">
                    {{ $item['name'] }}
                </div>
            </a>
        @endif
    @endforeach
</nav>

            <!-- User Footer -->
            <div class="border-t border-white/5 p-4">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=2563EB&color=fff" 
                         alt="User" class="h-9 w-9 rounded-full ring-2 ring-white/20 flex-shrink-0">
                    <div x-show="sidebarOpen" class="overflow-hidden transition-opacity duration-300 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex flex-1 flex-col overflow-hidden">
            
            <!-- TOPBAR -->
            <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white/80 px-6 backdrop-blur-md dark:border-white/5 dark:bg-navy-900/80">
                
                <!-- Left -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-navy-900 dark:text-slate-400 dark:hover:bg-white/10 dark:hover:text-white">
                        <i data-lucide="panel-left" class="h-5 w-5"></i>
                    </button>

                    <!-- Search -->
                    <div class="relative hidden md:block">
                        <i data-lucide="search" class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" placeholder="Cari produk, transaksi..." 
                               class="h-10 w-64 rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm outline-none focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
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
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-danger"></span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium shadow-sm hover:border-accent-500 dark:border-white/10 dark:bg-navy-800">
                            <span class="hidden sm:inline truncate max-w-24">{{ auth()->user()->name }}</span>
                            <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400"></i>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl bg-white p-2 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-navy-800 dark:ring-white/10 z-50"
                             style="display: none;">
                            <a href="#" class="block rounded-lg px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-white/5">Profil</a>
                            <a href="#" class="block rounded-lg px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-white/5">Pengaturan</a>
                            <div class="my-1 border-t border-slate-100 dark:border-white/10"></div>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left rounded-lg px-4 py-2 text-sm text-danger hover:bg-red-50 dark:hover:bg-red-900/20">Logout</button>
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
        
        // Re-init icons after Alpine updates
        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => lucide.createIcons(), 100);
        });
    </script>
    
    @stack('scripts')
</body>
</html>