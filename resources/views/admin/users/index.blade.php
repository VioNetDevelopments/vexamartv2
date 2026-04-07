@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 animate-fade-in-down">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1
                            class="text-3xl font-bold bg-gradient-to-r from-navy-900 to-accent-600 dark:from-white dark:to-accent-400 bg-clip-text text-transparent">
                            Manajemen User
                        </h1>
                        <span
                            class="px-3 py-1 bg-accent-500/10 text-accent-600 dark:text-accent-400 text-xs font-semibold rounded-full">
                            {{ $stats['total'] }} User
                        </span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400">Kelola akses user dan role</p>
                </div>

                <a href="{{ route('admin.users.create') }}"
                    class="group flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-medium shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all duration-300 hover:-translate-y-0.5">
                    <i data-lucide="user-plus" class="w-5 h-5 group-hover:rotate-90 transition-transform"></i>
                    <span>Tambah User</span>
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center">
                            <i data-lucide="users" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total User</p>
                    <h3 class="text-2xl font-bold text-navy-900 dark:text-white">{{ $stats['total'] }}</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                            <i data-lucide="crown" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Owner</p>
                    <h3 class="text-2xl font-bold text-purple-600">{{ $stats['owner'] }}</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center">
                            <i data-lucide="shield" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Admin</p>
                    <h3 class="text-2xl font-bold text-accent-600">{{ $stats['admin'] }}</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                            <i data-lucide="cashier" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Kasir</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ $stats['cashier'] }}</h3>
                </div>

                <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                    style="animation-delay: 0.5s;">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-success to-success/80 flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Aktif</p>
                    <h3 class="text-2xl font-bold text-success">{{ $stats['active'] }}</h3>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                style="animation-delay: 0.6s;">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <div class="relative">
                            <i data-lucide="search"
                                class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama atau email..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        </div>
                    </div>
                    <select name="role"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        <option value="">Semua Role</option>
                        <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Kasir</option>
                    </select>
                    <select name="status"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <button type="submit"
                        class="rounded-xl bg-accent-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-accent-600 transition-colors">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                        class="rounded-xl border border-slate-200 dark:border-white/10 px-5 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-navy-800 transition-colors">
                        Reset
                    </a>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up"
                style="animation-delay: 0.7s;">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-navy-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">User</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Last Login</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Created</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white font-semibold">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-navy-900 dark:text-white">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->role === 'owner')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                                <i data-lucide="crown" class="w-3 h-3 mr-1"></i>Owner
                                            </span>
                                        @elseif($user->role === 'admin')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-accent-100 text-accent-700 dark:bg-accent-900/30 dark:text-accent-400">
                                                <i data-lucide="shield" class="w-3 h-3 mr-1"></i>Admin
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                                <i data-lucide="cashier" class="w-3 h-3 mr-1"></i>Kasir
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_active)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-success/10 text-success">
                                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                                <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="p-2 text-slate-400 hover:text-accent-500 transition-colors" title="Edit">
                                                <i data-lucide="edit" class="h-4 w-4"></i>
                                            </a>
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-success transition-colors"
                                                    title="Toggle Status">
                                                    <i data-lucide="toggle-left" class="h-4 w-4"></i>
                                                </button>
                                            </form>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-slate-400 hover:text-danger transition-colors"
                                                        title="Hapus">
                                                        <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <i data-lucide="users"
                                            class="h-16 w-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                                        <p class="text-slate-500 dark:text-slate-400">Belum ada data user</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @keyframes fade-in-up {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fade-in-down {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fade-in-up {
                animation: fade-in-up 0.6s ease-out forwards;
                opacity: 0;
            }

            .animate-fade-in-down {
                animation: fade-in-down 0.6s ease-out forwards;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                lucide.createIcons();
            });
        </script>
    @endpush
@endsection