@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent">
                            Manajemen Pelanggan
                        </h1>
                        <span class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full">
                            {{ $stats['total'] }} Pelanggan
                        </span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">Kelola data pelanggan dan program loyalitas</p>
                </div>

                <button onclick="openModal('create')" 
                        class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-medium shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-300 hover:-translate-y-0.5">
                    <i data-lucide="user-plus" class="w-5 h-5 group-hover:rotate-90 transition-transform"></i>
                    <span>Tambah Pelanggan</span>
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center">
                            <i data-lucide="users" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Pelanggan</p>
                    <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $stats['total'] }}</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <i data-lucide="award" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Regular</p>
                    <h3 class="text-2xl font-bold text-blue-600">{{ $stats['regular'] }}</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center">
                            <i data-lucide="star" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Gold Member</p>
                    <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['gold'] }}</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                            <i data-lucide="crown" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Platinum Member</p>
                    <h3 class="text-2xl font-bold text-purple-600">{{ $stats['platinum'] }}</h3>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg">
                <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, atau email..."
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                    </div>
                    <select name="membership" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        <option value="">Semua Membership</option>
                        <option value="regular" {{ request('membership') == 'regular' ? 'selected' : '' }}>Regular</option>
                        <option value="gold" {{ request('membership') == 'gold' ? 'selected' : '' }}>Gold</option>
                        <option value="platinum" {{ request('membership') == 'platinum' ? 'selected' : '' }}>Platinum</option>
                    </select>
                    <button type="submit" class="rounded-xl bg-accent-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-accent-600 transition-colors">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.customers.index') }}" class="rounded-xl border border-slate-200 dark:border-white/10 px-5 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Customers Table -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-navy-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Pelanggan</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Kontak</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Membership</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Poin</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Transaksi</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white font-semibold">
                                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-navy-900 dark:text-white">{{ $customer->name }}</p>
                                                <p class="text-xs text-slate-500">Member sejak {{ $customer->created_at->format('M Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $customer->phone ?: '-' }}</p>
                                        <p class="text-xs text-slate-400">{{ $customer->email ?: '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($customer->membership === 'platinum')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                                <i data-lucide="crown" class="w-3 h-3 mr-1"></i>Platinum
                                            </span>
                                        @elseif($customer->membership === 'gold')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                <i data-lucide="star" class="w-3 h-3 mr-1"></i>Gold
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                                Regular
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-accent-600 dark:text-accent-400">{{ number_format($customer->loyalty_points) }}</span>
                                        <span class="text-xs text-slate-500">poin</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600 dark:text-slate-300">{{ $customer->transactions_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.customers.show', $customer) }}" 
                                               class="p-2 text-slate-400 hover:text-accent-500 transition-colors" title="Lihat Detail">
                                                <i data-lucide="eye" class="h-4 w-4"></i>
                                            </a>
                                            <button @click="openModal('edit', {{ json_encode($customer->only(['id', 'name', 'phone', 'email', 'address', 'membership'])) }})" 
                                                    class="p-2 text-slate-400 hover:text-accent-500 transition-colors" title="Edit">
                                                <i data-lucide="edit" class="h-4 w-4"></i>
                                            </button>
                                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-danger transition-colors" title="Hapus">
                                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <i data-lucide="users" class="h-16 w-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                                        <p class="text-slate-500 dark:text-slate-400">Belum ada data pelanggan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($customers->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Create/Edit Customer -->
    <div id="customerModal" x-data="{ 
            open: false, 
            mode: 'create', 
            customerId: null,
            customerData: { name: '', phone: '', email: '', address: '', membership: 'regular' },
            openModal(mode, data = {}) {
                this.mode = mode;
                if (mode === 'edit') {
                    this.customerId = data.id;
                    this.customerData = { ...data };
                } else {
                    this.customerId = null;
                    this.customerData = { name: '', phone: '', email: '', address: '', membership: 'regular' };
                }
                this.open = true;
            }
         }" 
         x-show="open" x-cloak
         @open-customer-modal.window="openModal($event.detail.mode, $event.detail.data)"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative bg-white dark:bg-navy-900 rounded-2xl p-6 max-w-lg w-full shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-navy-900 dark:text-white" x-text="mode === 'create' ? 'Tambah Pelanggan' : 'Edit Pelanggan'"></h3>
                <button @click="open = false" class="p-2 hover:bg-slate-100 dark:hover:bg-navy-800 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-slate-500"></i>
                </button>
            </div>

            <form :action="mode === 'create' ? '{{ route('admin.customers.store') }}' : '{{ route('admin.customers.index') }}/' + customerId" 
                  method="POST" class="space-y-4">
                @csrf
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" x-model="customerData.name" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Telepon</label>
                        <input type="tel" name="phone" x-model="customerData.phone" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                        <input type="email" name="email" x-model="customerData.email" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Alamat</label>
                    <textarea name="address" x-model="customerData.address" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Membership</label>
                    <select name="membership" x-model="customerData.membership" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        <option value="regular">Regular</option>
                        <option value="gold">Gold</option>
                        <option value="platinum">Platinum</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="open = false" class="flex-1 px-4 py-2.5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-xl font-medium hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-accent-500 text-white rounded-xl font-medium hover:bg-accent-600 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
        function openModal(mode, data = {}) {
            window.dispatchEvent(new CustomEvent('open-customer-modal', { 
                detail: { mode, data } 
            }));
        }

        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
        </script>
    @endpush

    @push('styles')
        <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.6s ease-out forwards; opacity: 0; }
        .animate-fade-in-down { animation: fade-in-down 0.6s ease-out forwards; }
        [x-cloak] { display: none !important; }
        </style>
    @endpush
@endsection