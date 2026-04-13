@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <!-- Animated Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center shadow-lg shadow-accent-500/20">
                                <i data-lucide="users" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-black bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent tracking-tight">
                                    Manajemen Pelanggan
                                </h1>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Kelola data pelanggan dan program loyalitas</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                            {{ $stats['total'] }} Pelanggan
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Pelanggan -->
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center shadow-lg shadow-slate-500/30">
                                <i data-lucide="users" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Pelanggan</p>
                        <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $stats['total'] }}</h3>
                    </div>
                </div>

                <!-- Regular -->
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <i data-lucide="user" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Regular</p>
                        <h3 class="text-2xl font-bold text-blue-600">{{ $stats['regular'] }}</h3>
                    </div>
                </div>

                <!-- Gold Member -->
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg shadow-yellow-500/30">
                                <i data-lucide="star" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Gold Member</p>
                        <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['gold'] }}</h3>
                    </div>
                </div>

                <!-- Platinum Member -->
                <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <i data-lucide="crown" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Platinum Member</p>
                        <h3 class="text-2xl font-bold text-purple-600">{{ $stats['platinum'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up overflow-visible relative z-20" style="animation-delay: 0.5s;">
                <form id="customerFilterForm" class="flex flex-wrap gap-4">
                    @csrf
                    <div class="flex-1 min-w-64">
                        <div class="relative group">
                            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari nama, telepon, atau email..."
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                        </div>
                    </div>
                    
                    <!-- Membership Dropdown -->
                    <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center justify-between min-w-[180px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                            <span id="membershipText">{{ request('membership') ? (request('membership') == 'regular' ? 'Regular' : (request('membership') == 'gold' ? 'Gold' : 'Platinum')) : 'Semua Membership' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                        </button>
                        <div x-show="open" x-transition 
                             class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                             style="top: 100%;">
                            <button type="button" @click="selectMembership('', 'Semua Membership'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ !request('membership') ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Semua Membership
                            </button>
                            <button type="button" @click="selectMembership('regular', 'Regular'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('membership') == 'regular' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Regular
                            </button>
                            <button type="button" @click="selectMembership('gold', 'Gold'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('membership') == 'gold' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Gold
                            </button>
                            <button type="button" @click="selectMembership('platinum', 'Platinum'); open = false"
                                    class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('membership') == 'platinum' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                                Platinum
                            </button>
                        </div>
                        <input type="hidden" name="membership" id="membershipInput" value="{{ request('membership') }}">
                    </div>

                    <button type="button" onclick="filterCustomers()" class="bg-accent-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-accent-600 shadow-lg shadow-accent-500/30 transition-all active:scale-95">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <button type="button" onclick="resetFilter()" class="flex items-center justify-center px-4 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 transition-all">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            <!-- Customers Table -->
            <div id="customerTableWrapper">
                @include('admin.customers.partials.table')
            </div>

        </div>
    </div>

    <x-alert-modal />

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        initCustomerAjax();
        
        @if(session('success'))
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('alert-modal', {
                    detail: {
                        type: 'success',
                        title: 'Berhasil!',
                        message: '{{ session('success') }}'
                    }
                }));
            }, 100);
        @endif
        
        @if(session('error'))
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('alert-modal', {
                    detail: {
                        type: 'error',
                        title: 'Gagal!',
                        message: '{{ session('error') }}'
                    }
                }));
            }, 100);
        @endif
    });

    function initCustomerAjax() {
        const tableWrapper = document.getElementById('customerTableWrapper');
        if (!tableWrapper) return;

        tableWrapper.addEventListener('click', function(event) {
            const link = event.target.closest('a.ajax-link');
            if (!link) return;
            
            event.preventDefault();
            const url = new URL(link.href, window.location.origin);
            fetchCustomerTable(url.searchParams.toString());
        });

        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let debounceTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    filterCustomers();
                }, 500);
            });
        }
    }

    function selectMembership(value, text) {
        document.getElementById('membershipInput').value = value;
        document.getElementById('membershipText').textContent = text;
        filterCustomers();
    }

    function filterCustomers() {
        const search = document.getElementById('searchInput').value;
        const membership = document.getElementById('membershipInput').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (membership) params.append('membership', membership);
        
        fetchCustomerTable(params.toString());
    }

    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('membershipInput').value = '';
        document.getElementById('membershipText').textContent = 'Semua Membership';
        fetchCustomerTable('');
    }

    async function fetchCustomerTable(queryString) {
        const tableWrapper = document.getElementById('customerTableWrapper');
        if (!tableWrapper) return;

        tableWrapper.style.opacity = '0.5';
        tableWrapper.style.pointerEvents = 'none';
        
        try {
            const response = await fetch('{{ route('admin.customers.index') }}?' + queryString, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                    'Content-Type': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const tableContent = doc.querySelector('#customerTableWrapper');
            
            if (tableContent) {
                tableWrapper.innerHTML = tableContent.innerHTML;
                lucide.createIcons();
                initCustomerAjax();
            }
        } catch (error) {
            console.error('Error loading customer data:', error);
            showAlert('error', 'Error', 'Terjadi kesalahan saat memuat data');
        } finally {
            tableWrapper.style.opacity = '1';
            tableWrapper.style.pointerEvents = 'auto';
        }
    }

    function deleteCustomer(id) {
        window.dispatchEvent(new CustomEvent('alert-modal', {
            detail: {
                type: 'confirm',
                title: 'Hapus Pelanggan',
                message: 'Yakin ingin menghapus pelanggan ini? Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.',
                confirmText: 'Ya, Hapus',
                cancelText: 'Batalkan',
                onConfirm: () => {
                    fetch(`/admin/customers/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.dispatchEvent(new CustomEvent('alert-modal', {
                                detail: {
                                    type: 'success',
                                    title: 'Berhasil!',
                                    message: 'Pelanggan berhasil dihapus'
                                }
                            }));
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            window.dispatchEvent(new CustomEvent('alert-modal', {
                                detail: {
                                    type: 'error',
                                    title: 'Gagal!',
                                    message: data.message || 'Gagal menghapus pelanggan'
                                }
                            }));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.dispatchEvent(new CustomEvent('alert-modal', {
                            detail: {
                                type: 'error',
                                title: 'Error',
                                message: 'Terjadi kesalahan saat menghapus pelanggan'
                            }
                        }));
                    });
                }
            }
        }));
    }

    function showAlert(type, title, message, callbacks = {}) {
        window.dispatchEvent(new CustomEvent('custom-alert', {
            detail: {
                type: type,
                title: title,
                message: message,
                onConfirm: callbacks.onConfirm || null,
                onCancel: callbacks.onCancel || null
            }
        }));
    }
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
    </style>
    @endpush
@endsection