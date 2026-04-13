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
                                Manajemen User
                            </h1>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Kelola akses dan peran pengguna sistem</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                        {{ $stats['total'] }} User
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Cards - SAME DESIGN AS STOCK PAGE -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Total User -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center shadow-lg shadow-slate-500/30">
                            <i data-lucide="users" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total User</p>
                    <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $stats['total'] }}</h3>
                </div>
            </div>

            <!-- Owner -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <i data-lucide="crown" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Owner</p>
                    <h3 class="text-2xl font-bold text-purple-600">{{ $stats['owner'] }}</h3>
                </div>
            </div>

            <!-- Admin -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i data-lucide="shield" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Admin</p>
                    <h3 class="text-2xl font-bold text-blue-600">{{ $stats['admin'] }}</h3>
                </div>
            </div>

            <!-- Kasir -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.4s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-500/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/30">
                            <i data-lucide="monitor-smartphone" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Kasir</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ $stats['cashier'] }}</h3>
                </div>
            </div>

            <!-- Aktif -->
            <div class="group relative bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-500 hover:-translate-y-2 overflow-hidden animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-success/10 to-transparent rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center shadow-lg shadow-success/30">
                            <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Aktif</p>
                    <h3 class="text-2xl font-bold text-success">{{ $stats['active'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Search & Filter - SAME DESIGN AS STOCK PAGE -->
        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up overflow-visible relative z-20" style="animation-delay: 0.6s;">
            <form id="userFilterForm" class="flex flex-wrap gap-4">
                @csrf
                <div class="flex-1 min-w-64">
                    <div class="relative group">
                        <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent-500 transition-colors"></i>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 focus:ring-4 focus:ring-accent-500/10 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all">
                    </div>
                </div>
                
                <!-- Role Dropdown -->
                <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                    <button type="button" @click="open = !open" @click.away="open = false"
                            class="flex items-center justify-between min-w-[160px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                        <span id="roleText">{{ request('role') ? (request('role') == 'owner' ? 'Owner' : (request('role') == 'admin' ? 'Admin' : 'Kasir')) : 'Semua Role' }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-transition 
                         class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                         style="top: 100%;">
                        <button type="button" @click="selectRole('', 'Semua Role'); open = false"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ !request('role') ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                            Semua Role
                        </button>
                        <button type="button" @click="selectRole('owner', 'Owner'); open = false"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('role') == 'owner' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                            Owner
                        </button>
                        <button type="button" @click="selectRole('admin', 'Admin'); open = false"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('role') == 'admin' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                            Admin
                        </button>
                        <button type="button" @click="selectRole('cashier', 'Kasir'); open = false"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('role') == 'cashier' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                            Kasir
                        </button>
                    </div>
                    <input type="hidden" name="role" id="roleInput" value="{{ request('role') }}">
                </div>

                <!-- Status Dropdown -->
                <div class="relative" x-data="{ open: false }" style="z-index: 50;">
                    <button type="button" @click="open = !open" @click.away="open = false"
                            class="flex items-center justify-between min-w-[160px] pl-4 pr-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold focus:border-accent-500 dark:border-white/10 dark:bg-navy-800 dark:text-white transition-all hover:bg-white">
                        <span id="statusText">{{ request('status') !== null ? (request('status') == '1' ? 'Aktif' : 'Nonaktif') : 'Semua Status' }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-transition 
                         class="absolute z-50 mt-2 w-full rounded-2xl bg-white dark:bg-navy-900 p-2 shadow-2xl border border-slate-100 dark:border-white/10 max-h-64 overflow-y-auto"
                         style="top: 100%;">
                        <button type="button" @click="selectStatus('', 'Semua Status'); open = false"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('status') === null ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                            Semua Status
                        </button>
                        <button type="button" @click="selectStatus('1', 'Aktif'); open = false"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('status') == '1' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                            Aktif
                        </button>
                        <button type="button" @click="selectStatus('0', 'Nonaktif'); open = false"
                                class="w-full text-left px-4 py-2 text-sm rounded-lg transition-colors hover:bg-accent-50 dark:hover:bg-accent-900/20 {{ request('status') === '0' ? 'bg-accent-500 text-white' : 'text-slate-600 dark:text-slate-300' }}">
                            Nonaktif
                        </button>
                    </div>
                    <input type="hidden" name="status" id="statusInput" value="{{ request('status') }}">
                </div>

                <button type="button" onclick="filterUsers()" class="bg-accent-500 text-white rounded-xl px-5 py-2.5 text-sm font-black uppercase tracking-widest hover:bg-accent-600 shadow-lg shadow-accent-500/30 transition-all active:scale-95">
                    <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                </button>
                <button type="button" onclick="resetFilter()" class="flex items-center justify-center px-4 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-white/10 dark:text-slate-400 transition-all">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

        <!-- Users Table -->
        <div id="userTableWrapper">
            @include('admin.users.partials.table')
        </div>

    </div>
</div>

<x-alert-modal />

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    initUserAjax();
    
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

function initUserAjax() {
    const tableWrapper = document.getElementById('userTableWrapper');
    if (!tableWrapper) return;

    tableWrapper.addEventListener('click', function(event) {
        const link = event.target.closest('a.ajax-link');
        if (!link) return;
        
        event.preventDefault();
        const url = new URL(link.href, window.location.origin);
        fetchUserTable(url.searchParams.toString());
    });

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterUsers();
            }, 500);
        });
    }
}

function selectRole(value, text) {
    document.getElementById('roleInput').value = value;
    document.getElementById('roleText').textContent = text;
    filterUsers();
}

function selectStatus(value, text) {
    document.getElementById('statusInput').value = value;
    document.getElementById('statusText').textContent = text;
    filterUsers();
}

function filterUsers() {
    const search = document.getElementById('searchInput').value;
    const role = document.getElementById('roleInput').value;
    const status = document.getElementById('statusInput').value;
    
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (role) params.append('role', role);
    if (status !== '') params.append('status', status);
    
    fetchUserTable(params.toString());
}

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('roleInput').value = '';
    document.getElementById('statusInput').value = '';
    document.getElementById('roleText').textContent = 'Semua Role';
    document.getElementById('statusText').textContent = 'Semua Status';
    fetchUserTable('');
}

async function fetchUserTable(queryString) {
    const tableWrapper = document.getElementById('userTableWrapper');
    if (!tableWrapper) return;

    tableWrapper.style.opacity = '0.5';
    tableWrapper.style.pointerEvents = 'none';
    
    try {
        const response = await fetch('{{ route('admin.users.index') }}?' + queryString, {
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
        const tableContent = doc.querySelector('#userTableWrapper');
        
        if (tableContent) {
            tableWrapper.innerHTML = tableContent.innerHTML;
            lucide.createIcons();
            initUserAjax();
        }
    } catch (error) {
        console.error('Error loading user data:', error);
        window.dispatchEvent(new CustomEvent('alert-modal', {
            detail: {
                type: 'error',
                title: 'Error',
                message: 'Terjadi kesalahan saat memuat data'
            }
        }));
    } finally {
        tableWrapper.style.opacity = '1';
        tableWrapper.style.pointerEvents = 'auto';
    }
}

function deleteUser(id) {
    window.dispatchEvent(new CustomEvent('alert-modal', {
        detail: {
            type: 'confirm',
            title: 'Hapus User',
            message: 'Yakin ingin menghapus user ini?',
            confirmText: 'Ya, Hapus',
            cancelText: 'Batalkan',
            onConfirm: () => {
                fetch(`/admin/users/${id}`, {
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
                                message: 'User berhasil dihapus'
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
                                message: data.message || 'Gagal menghapus user'
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
                            message: 'Terjadi kesalahan saat menghapus user'
                        }
                    }));
                });
            }
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