<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-white dark:bg-navy-950 border-r border-slate-200 dark:border-white/5 transition-colors duration-300 z-50">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-black text-slate-900 dark:text-white leading-tight">VEXALYN STORE</h1>
                <p class="text-[10px] font-medium text-slate-500 dark:text-slate-400">VIO ATMAJAYA</p>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="p-4 space-y-1 overflow-y-auto" style="height: calc(100% - 140px);">
        <!-- Menu Utama -->
        <div class="mb-6">
            <p class="px-3 mb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Menu Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-accent-500' }}"></i>
                <span class="text-sm font-bold">Dashboard</span>
            </a>

            <a href="{{ route('cashier.pos') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('cashier.pos') ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                <i data-lucide="monitor-smartphone" class="w-5 h-5 {{ request()->routeIs('cashier.pos') ? 'text-white' : 'text-slate-400 group-hover:text-accent-500' }}"></i>
                <span class="text-sm font-bold">Kasir (POS)</span>
            </a>
        </div>

        <!-- Manajemen -->
        <div class="mb-6">
            <p class="px-3 mb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Manajemen</p>
            
            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                <i data-lucide="box" class="w-5 h-5 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-slate-400 group-hover:text-accent-500' }}"></i>
                <span class="text-sm font-bold">Produk</span>
            </a>

            <a href="{{ route('admin.transactions.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.transactions.*') ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                <i data-lucide="receipt" class="w-5 h-5 {{ request()->routeIs('admin.transactions.*') ? 'text-white' : 'text-slate-400 group-hover:text-accent-500' }}"></i>
                <span class="text-sm font-bold">Transaksi</span>
            </a>

            <a href="{{ route('admin.stock.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.stock.*') ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                <i data-lucide="warehouse" class="w-5 h-5 {{ request()->routeIs('admin.stock.*') ? 'text-white' : 'text-slate-400 group-hover:text-accent-500' }}"></i>
                <span class="text-sm font-bold">Stok</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.customers.*') ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                <i data-lucide="users" class="w-5 h-5 {{ request()->routeIs('admin.customers.*') ? 'text-white' : 'text-slate-400 group-hover:text-accent-500' }}"></i>
                <span class="text-sm font-bold">Pelanggan</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                <i data-lucide="bar-chart-3" class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-slate-400 group-hover:text-accent-500' }}"></i>
                <span class="text-sm font-bold">Laporan</span>
            </a>
        </div>

        <!-- Pengaturan -->
        <div class="mb-6">
            <p class="px-3 mb-2 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Pengaturan</p>
            
            <a href="{{ route('admin.settings.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                <i data-lucide="settings" class="w-5 h-5 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400 group-hover:text-accent-500' }}"></i>
                <span class="text-sm font-bold">Pengaturan Toko</span>
            </a>

            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-accent-500 to-accent-600 text-white shadow-lg shadow-accent-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-navy-900' }}">
                <i data-lucide="user-cog" class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-accent-500' }}"></i>
                <span class="text-sm font-bold">User</span>
            </a>
        </div>
    </nav>

    <!-- User Profile -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-200 dark:border-white/10 bg-white dark:bg-navy-950">
        <div class="flex items-center gap-3 px-3 py-2 rounded-xl">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white font-bold shadow-lg shadow-accent-500/30">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
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
