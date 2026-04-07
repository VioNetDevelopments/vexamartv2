@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    customerId: null,
    customerData: { name: '', phone: '', email: '', address: '', membership: 'regular' }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Manajemen Pelanggan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola data pelanggan dan loyalty points</p>
        </div>
        <button @click="editMode=false; showModal=true" class="inline-flex items-center rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white hover:bg-accent-600">
            <i data-lucide="user-plus" class="mr-2 h-4 w-4"></i> Tambah Pelanggan
        </button>
    </div>

    <!-- Search -->
    <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, atau email..."
                           class="w-full rounded-lg border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-1 focus:ring-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                </div>
            </div>
            <button type="submit" class="rounded-lg bg-accent-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-accent-600">Cari</button>
            <a href="{{ route('admin.customers.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">Reset</a>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="rounded-2xl bg-white shadow-soft overflow-hidden dark:bg-navy-900 dark:border dark:border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-navy-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Membership</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Poin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Transaksi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-accent-100 dark:bg-accent-900/30 flex items-center justify-center">
                                    <span class="text-sm font-bold text-accent-600">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-navy-900 dark:text-white">{{ $customer->name }}</p>
                                    <p class="text-xs text-slate-500">Member sejak {{ $customer->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <p class="text-slate-600 dark:text-slate-300">{{ $customer->phone ?: '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $customer->email ?: '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $customer->membership=='platinum'?'bg-purple-100 text-purple-700':($customer->membership=='gold'?'bg-warning/10 text-warning':'bg-slate-100 text-slate-700') }}">
                                {{ ucfirst($customer->membership) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-accent-500">{{ number_format($customer->loyalty_points) }}</span>
                            <span class="text-xs text-slate-500">poin</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                            {{ $customer->transactions->count() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="customerId={{ $customer->id }}; editMode=true; customerData={ name: '{{ $customer->name }}', phone: '{{ $customer->phone }}', email: '{{ $customer->email }}', address: '{{ $customer->address }}', membership: '{{ $customer->membership }}' }; showModal=true" class="p-2 text-slate-400 hover:text-accent-500 transition-colors">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </button>
                                <a href="{{ route('admin.customers.show', $customer) }}" class="p-2 text-slate-400 hover:text-accent-500 transition-colors">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada data pelanggan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">{{ $customers->links() }}</div>
        @endif
    </div>

    <!-- Modal Add/Edit Customer -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showModal=false"></div>
        <div class="relative bg-white rounded-2xl p-6 max-w-lg w-full shadow-2xl dark:bg-navy-900"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-lg font-bold text-navy-900 dark:text-white mb-4" x-text="editMode ? 'Edit Pelanggan' : 'Tambah Pelanggan'"></h3>
            
            <!-- FIX: Gunakan x-bind:action untuk dynamic form action -->
            <form :action="editMode ? '{{ route('admin.customers.index') }}/' + customerId : '{{ route('admin.customers.store') }}'" 
                  method="POST" 
                  class="space-y-4">
                @csrf
                <!-- FIX: Gunakan x-bind untuk method PUT saat edit -->
                <input type="hidden" name="_method" :value="editMode ? 'PUT' : 'POST'">
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" :value="editMode ? customerData.name : ''" required 
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Telepon</label>
                        <input type="tel" name="phone" :value="editMode ? customerData.phone : ''" 
                               class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                        <input type="email" name="email" :value="editMode ? customerData.email : ''" 
                               class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat</label>
                    <textarea name="address" rows="2" 
                              class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">{{ old('address') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Membership</label>
                    <select name="membership" 
                            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        <option value="regular">Regular</option>
                        <option value="gold">Gold</option>
                        <option value="platinum">Platinum</option>
                    </select>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showModal=false" 
                            class="flex-1 rounded-lg border border-slate-200 py-2.5 text-sm text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300">Batal</button>
                    <button type="submit" 
                            class="flex-1 rounded-lg bg-accent-500 py-2.5 text-sm font-medium text-white hover:bg-accent-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection