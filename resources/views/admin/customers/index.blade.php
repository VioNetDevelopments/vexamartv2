@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    customerId: null,
    customerData: { name: '', phone: '', email: '', address: '', membership: 'regular' }
}">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 animate-fade-in-down">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                <i data-lucide="users" class="w-7 h-7 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-navy-900 dark:text-white tracking-tight">Manajemen Pelanggan</h1>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total <span class="text-accent-500 font-bold">{{ $customers->total() }}</span> pelanggan aktif tercatat</p>
            </div>
        </div>
        <button @click="editMode=false; showModal=true; customerData={ name: '', phone: '', email: '', address: '', membership: 'regular' }" 
                class="group relative flex items-center gap-2 px-6 py-3 rounded-2xl bg-accent-500 text-white font-black uppercase tracking-widest text-xs shadow-xl shadow-accent-500/30 hover:bg-accent-600 transition-all hover:-translate-y-1 active:scale-95">
            <i data-lucide="user-plus" class="w-4 h-4 transition-transform group-hover:rotate-12"></i>
            <span>Tambah Pelanggan</span>
        </button>
    </div>

    <!-- Search & Filter Bar -->
    <div class="relative z-30 bg-white/80 dark:bg-navy-900/80 backdrop-blur-xl border border-white/20 dark:border-white/5 rounded-3xl p-4 shadow-xl shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up" style="animation-delay: 0.1s;">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap gap-4 items-center">
            <div class="flex-1 relative group">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, nomor telepon, atau email..."
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 pl-12 pr-4 py-3 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-8 py-3 rounded-2xl bg-navy-900 dark:bg-white text-white dark:text-navy-900 text-xs font-black uppercase tracking-widest hover:shadow-lg transition-all active:scale-95">Cari</button>
                <a href="{{ route('admin.customers.index') }}" class="p-3 rounded-2xl border border-slate-200 text-slate-400 hover:bg-slate-50 dark:border-white/10 dark:text-slate-500 transition-all active:rotate-180">
                    <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-navy-900 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 dark:shadow-black/20 border border-white dark:border-white/5 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="overflow-x-auto overflow-y-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-navy-800/30 border-b border-slate-100 dark:border-white/5">
                        <th class="px-8 py-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Identitas Pelanggan</th>
                        <th class="px-8 py-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Informasi Kontak</th>
                        <th class="px-8 py-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Membership</th>
                        <th class="px-8 py-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Loyalty Points</th>
                        <th class="px-8 py-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Riwayat Trx</th>
                        <th class="px-8 py-6 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                    @forelse($customers as $customer)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-accent-500/5 transition-all duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="relative flex-shrink-0">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white shadow-lg shadow-accent-500/20 group-hover:scale-110 transition-transform duration-500">
                                        <span class="text-xl font-black">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white dark:border-navy-900 bg-success animate-pulse" x-show="true"></div>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-black text-navy-900 dark:text-white leading-tight truncate group-hover:text-accent-500 transition-colors">{{ $customer->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">Member sejak {{ $customer->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="phone" class="w-3 h-3 text-slate-400 font-bold"></i>
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $customer->phone ?: '-' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="mail" class="w-3 h-3 text-slate-400 font-bold"></i>
                                    <span class="text-[11px] font-medium text-slate-400">{{ $customer->email ?: '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border" 
                                :class="{
                                    'bg-purple-500/10 text-purple-600 border-purple-500/20': '{{ $customer->membership }}' === 'platinum',
                                    'bg-warning/10 text-warning border-warning/20': '{{ $customer->membership }}' === 'gold',
                                    'bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/10': '{{ $customer->membership }}' === 'regular'
                                }">
                                <i data-lucide="crown" class="w-3 h-3" x-show="'{{ $customer->membership }}' !== 'regular'"></i>
                                {{ ucfirst($customer->membership) }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <div class="p-1.5 rounded-lg bg-accent-50 dark:bg-accent-500/10 text-accent-500">
                                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                                </div>
                                <span class="text-base font-black text-navy-900 dark:text-white">{{ number_format($customer->loyalty_points) }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">PTS</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $customer->transactions->count() }} <span class="text-[10px] text-slate-400 font-medium">Transaksi</span></span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="customerId={{ $customer->id }}; editMode=true; customerData={ name: '{{ $customer->name }}', phone: '{{ $customer->phone }}', email: '{{ $customer->email }}', address: '{{ $customer->address }}', membership: '{{ $customer->membership }}' }; showModal=true" 
                                        class="p-2.5 rounded-2xl bg-white dark:bg-navy-800 border border-slate-200 dark:border-white/5 text-slate-400 hover:text-accent-500 hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/20 transition-all active:scale-95">
                                    <i data-lucide="edit-3" class="h-4 w-4"></i>
                                </button>
                                <a href="{{ route('admin.customers.show', $customer) }}" 
                                   class="p-2.5 rounded-2xl bg-navy-900 dark:bg-white text-white dark:text-navy-900 hover:shadow-lg transition-all active:scale-95">
                                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 rounded-full bg-slate-50 dark:bg-navy-800 flex items-center justify-center text-slate-200 dark:text-navy-700 mb-6 animate-pulse">
                                    <i data-lucide="users" class="w-12 h-12"></i>
                                </div>
                                <h4 class="text-xl font-bold text-navy-900 dark:text-white mb-2">Database Kosong</h4>
                                <p class="text-sm text-slate-500 max-w-xs mx-auto">Belum ada pelanggan yang terdaftar. Ayo tambahkan pelanggan pertama Anda!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
        <div class="px-8 py-6 border-t border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-navy-900/50">
            {{ $customers->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Add/Edit Customer -->
    <template x-teleport="body">
        <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-navy-950/40 backdrop-blur-md" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showModal=false"></div>
            
            <div class="relative bg-white dark:bg-navy-900 rounded-[2.5rem] shadow-2xl border border-white dark:border-white/5 w-full max-w-xl overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-10"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-10">
                
                <!-- Modal Header -->
                <div class="relative p-8 pb-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-accent-500/10 flex items-center justify-center text-accent-500">
                                <i data-lucide="user-plus" class="w-6 h-6" x-show="!editMode"></i>
                                <i data-lucide="edit-3" class="w-6 h-6" x-show="editMode"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-navy-900 dark:text-white" x-text="editMode ? 'Edit Profil Pelanggan' : 'Daftar Pelanggan Baru'"></h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-0.5" x-text="editMode ? 'Perbarui informasi data pelanggan' : 'Lengkapi data untuk membuat member baru'"></p>
                            </div>
                        </div>
                        <button @click="showModal=false" class="p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form :action="editMode ? '{{ route('admin.customers.index') }}/' + customerId : '{{ route('admin.customers.store') }}'" 
                      method="POST" class="p-8 space-y-6">
                    @csrf
                    <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'">
                    
                    <div class="space-y-4">
                        <!-- Name & Membership -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                <div class="relative group">
                                    <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                                    <input type="text" name="name" :value="editMode ? customerData.name : ''" required 
                                           class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner"
                                           placeholder="Contoh: John Doe">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Membership Status</label>
                                <div class="relative group">
                                    <i data-lucide="crown" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                                    <select name="membership" x-model="customerData.membership"
                                            class="w-full pl-11 pr-10 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all appearance-none">
                                        <option value="regular">Regular Member</option>
                                        <option value="gold">Gold Member</option>
                                        <option value="platinum">Platinum Member</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nomor Telepon/WA</label>
                                <div class="relative group">
                                    <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                                    <input type="tel" name="phone" :value="editMode ? customerData.phone : ''" 
                                           class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner"
                                           placeholder="0812xxxx">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                                <div class="relative group">
                                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                                    <input type="email" name="email" :value="editMode ? customerData.email : ''" 
                                           class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner"
                                           placeholder="john@example.com">
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Domisili</label>
                            <div class="relative group">
                                <i data-lucide="map-pin" class="absolute left-4 top-4 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                                <textarea name="address" rows="3" x-text="editMode ? customerData.address : ''"
                                          class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all shadow-inner resize-none"
                                          placeholder="Tuliskan alamat lengkap..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4">
                        <button type="button" @click="showModal=false" 
                                class="flex-1 px-6 py-4 rounded-2xl border-2 border-slate-100 dark:border-white/5 text-sm font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-all active:scale-95">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-6 py-4 rounded-2xl bg-accent-500 text-white text-sm font-black uppercase tracking-widest shadow-xl shadow-accent-500/30 hover:bg-accent-600 transition-all hover:shadow-2xl hover:-translate-y-1 active:scale-95">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection