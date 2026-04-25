<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['company_name'] ?? config('app.name', 'VexaMart') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900" rel="stylesheet" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine Global State (Must be defined before Alpine initializes) -->
    <script>
        function globalState() {
            const safeGetItem = (key, def) => {
                try { return localStorage.getItem(key) || def; } catch (e) { return def; }
            };

            return {
                darkMode: safeGetItem('darkMode', 'false') === 'true',
                sidebarOpen: safeGetItem('sidebarOpen', 'true') !== 'false',
                userMenuOpen: false,

                initGlobal() {
                    if (this.darkMode) document.documentElement.classList.add('dark');
                    else document.documentElement.classList.remove('dark');

                    this.$watch('darkMode', value => {
                        if (value) {
                            document.documentElement.classList.add('dark');
                            try { localStorage.setItem('darkMode', 'true'); } catch (e) {}
                        } else {
                            document.documentElement.classList.remove('dark');
                            try { localStorage.setItem('darkMode', 'false'); } catch (e) {}
                        }
                    });
                },

                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                },

                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    try { localStorage.setItem('sidebarOpen', this.sidebarOpen); } catch (e) {}
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Performance: Prevent layout shift */
        [data-lucide] {
            display: inline-block;
            width: 1em;
            height: 1em;
            vertical-align: middle;
        }

        /* Icon sizes */
        .icon-sm {
            width: 1.25rem;
            height: 1.25rem;
        }

        .icon-md {
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Sidebar active state */
        .sidebar-active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #1e293b;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        /* Hide Scrollbar Utility */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes rotate-right {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-rotate-right {
            animation: rotate-right 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes rotate-left {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-rotate-left {
            animation: rotate-left 0.5s cubic-bezier(0.4, 0, 0.2, 1) reverse;
        }

        @keyframes wiggle {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }
        .animate-wiggle {
            animation: wiggle 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-navy-950 font-inter antialiased" 
      x-data="globalState()" 
      x-init="initGlobal()">

    <!-- Sidebar (Fully Toggleable) -->
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed left-0 top-0 h-full w-64 bg-white dark:bg-navy-950 border-r border-slate-200 dark:border-white/5 z-50 transition-transform duration-300 ease-in-out">

        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20 flex-shrink-0 overflow-hidden">
                    @if(isset($settings['store_logo']) && $settings['store_logo'])
                        <img src="{{ asset('storage/' . $settings['store_logo']) }}" alt="Logo"
                            class="w-full h-full object-cover">
                    @else
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
                    @endif
                </div>
                <div class="overflow-hidden">
                    <h1
                        class="text-lg font-black bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-400 dark:to-blue-500 bg-clip-text text-transparent leading-tight whitespace-nowrap">
                        {{ $settings['store_name'] ?? 'VEXALYN STORE' }}
                    </h1>
                    <p class="text-[10px] font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">
                        {{ $settings['store_tagline'] ?? ($settings['company_name'] ?? 'VIO ATMAJAYA') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="p-4 space-y-1 overflow-y-auto" style="height: calc(100% - 140px);">
            
            @if(auth()->user()->role === 'owner' || auth()->user()->role === 'admin')
                <!-- Menu Utama - Untuk Admin/Owner -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Menu Utama</p>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Dashboard</span>
                    </a>

                    <a href="{{ route('cashier.pos') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('cashier.pos') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="monitor-smartphone" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('cashier.pos') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Kasir (POS)</span>
                    </a>
                </div>

                <!-- Manajemen - Untuk Admin/Owner -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Manajemen</p>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.products.*') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="package" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Produk</span>
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.transactions.*') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="receipt" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.transactions.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Transaksi</span>
                    </a>

                    <a href="{{ route('admin.stock.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.stock.*') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="warehouse" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.stock.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Stok</span>
                    </a>

                    <a href="{{ route('admin.customers.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.customers.*') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="users" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.customers.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Pelanggan</span>
                    </a>

                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.reports.*') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Laporan</span>
                    </a>
                </div>

                <!-- Pengaturan - Untuk Admin/Owner -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Pengaturan</p>
                    
                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings.*') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="settings" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Pengaturan Toko</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="user-cog" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">User</span>
                    </a>

                    <a href="{{ route('admin.activity-logs') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.activity-logs') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="activity" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.activity-logs') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Activity Log</span>
                    </a>
                </div>
            @else
                <!-- Menu untuk Kasir -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Menu Utama</p>
                    
                    <a href="{{ route('cashier.pos') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('cashier.pos') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="monitor-smartphone" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('cashier.pos') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Kasir (POS)</span>
                    </a>
                </div>

                <div class="mb-6">
                    <p class="px-3 mb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Informasi</p>
                    
                    <a href="{{ route('admin.stock.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.stock.*') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="warehouse" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('admin.stock.*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Cek Stok</span>
                    </a>

                    <a href="{{ route('cashier.transactions') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('cashier.transactions*') ? 'sidebar-active text-white shadow-lg shadow-blue-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                        <i data-lucide="history" class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('cashier.transactions*') ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
                        <span class="text-sm font-bold whitespace-nowrap">Histori Transaksi</span>
                    </a>
                </div>
            @endif
        </nav>

        <!-- User Profile -->
        <div
            class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-200 dark:border-white/10 bg-white dark:bg-navy-950">
            <div class="flex items-center gap-3 px-3 py-2 rounded-xl">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white font-bold shadow-lg shadow-accent-500/30 flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0 overflow-hidden">
                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 capitalize">{{ auth()->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-danger transition-colors" title="Logout">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div :class="sidebarOpen ? 'ml-64' : 'ml-0'" class="transition-all duration-300 ease-in-out">
        <!-- Top Header Bar -->
        <header
            class="sticky top-0 z-[60] bg-white/80 dark:bg-navy-950/80 backdrop-blur-xl border-b border-slate-200 dark:border-white/5">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- Left: Hamburger Menu + Store Name -->
                <div class="flex items-center gap-4">
                    <!-- Hamburger Menu Button -->
                    <button @click="toggleSidebar()"
                        class="p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-navy-900 transition-colors text-slate-400 hover:text-accent-500">
                        <i data-lucide="menu" class="icon-md"></i>
                    </button>

                    <!-- Store Name (Gradient Text) -->
                    <div class="flex items-center gap-3">
                        <h2
                            class="text-xl font-black text-navy-900 dark:text-white">
                            {{ $settings['store_name'] ?? 'VEXALYN STORE' }}
                        </h2>
                    </div>
                </div>

                <!-- Right: Actions -->
                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode(); 
                        if(darkMode) { $el.classList.add('animate-rotate-left'); setTimeout(() => $el.classList.remove('animate-rotate-left'), 500) }
                        else { $el.classList.add('animate-rotate-right'); setTimeout(() => $el.classList.remove('animate-rotate-right'), 500) }"
                        class="p-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-navy-900 transition-colors relative">
                        <i data-lucide="sun" x-show="!darkMode" x-cloak class="icon-sm text-yellow-500"></i>
                        <i data-lucide="moon" x-show="darkMode" x-cloak
                            class="icon-sm text-slate-400"></i>
                    </button>

                    <!-- Notifications -->
                    <x-notification-bell />

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false"
                            class="flex items-center gap-3 p-2 pr-4 rounded-xl hover:bg-slate-100 dark:hover:bg-navy-900 transition-colors cursor-pointer">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-lg shadow-accent-500/30">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="text-left hidden sm:block overflow-hidden">
                                <p class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                     {{ auth()->user()->name }}
                                 </p>
                                 <p class="text-xs text-slate-500 dark:text-slate-400 capitalize truncate">
                                     {{ auth()->user()->role }}
                                 </p>
                            </div>
                            <i data-lucide="chevron-down" x-show="!userMenuOpen" x-cloak
                                class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                            <i data-lucide="chevron-up" x-show="userMenuOpen" x-cloak
                                class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-navy-900 rounded-xl shadow-2xl border border-slate-200 dark:border-white/10 py-2 z-50"
                            x-cloak>

                            <a href="{{ route('profile.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                                <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                <span class="text-slate-700 dark:text-slate-300">Profil</span>
                            </a>

                            <div class="border-t border-slate-200 dark:border-white/10 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-danger hover:bg-danger/5 transition-colors">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine Global State Removed from bottom -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Small delay to ensure DOM is ready
            setTimeout(() => {
                lucide.createIcons();
            }, 100);

            // Toast Configuration
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Global Flash Messages
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Mantap, King!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
                    });
                @endif
                
                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Waduh King, Gagal!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#ef4444',
                        background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
                    });
                @endif
        });

        // Global Confirmation Function
        window.confirmAction = function(options) {
            Swal.fire({
                title: options.title || 'Fix nih, King?',
                text: options.text || 'Pikirin dulu King, ntar nyesel!',
                icon: options.icon || 'warning',
                showCancelButton: true,
                confirmButtonColor: options.confirmColor || '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: options.confirmText || 'Yakin',
                cancelButtonText: options.cancelText || 'Bentar dulu',
                reverseButtons: true,
                background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
            }).then((result) => {
                if (result.isConfirmed) {
                    if (typeof options.callback === 'function') {
                        options.callback();
                    } else if (options.formId) {
                        document.getElementById(options.formId).submit();
                    }
                }
            });
        }

        // Re-init icons when Alpine updates
        document.addEventListener('alpine:initialized', () => {
            setTimeout(() => {
                lucide.createIcons();
            }, 200);
        });
    </script>
    @stack('scripts')
</body>

</html>