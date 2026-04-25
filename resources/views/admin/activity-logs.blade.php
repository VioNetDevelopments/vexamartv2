@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-navy-950 dark:via-navy-900 dark:to-navy-950 p-6">
        <div class="relative max-w-7xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between animate-fade-in-down">
                <div>
                    <h1
                        class="text-3xl font-bold text-navy-900 dark:text-white">
                        Activity Logs
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">Audit trail semua aktivitas sistem</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg animate-fade-in-up"
                style="animation-delay: 0.1s;">
                <form action="{{ route('admin.activity-logs') }}" method="GET" class="flex flex-wrap gap-4">
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                    <select name="user_id"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 dark:border-white/10 dark:bg-navy-800 dark:text-white">
                        <option value="">Semua User</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="rounded-xl bg-accent-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-accent-600 transition-colors">
                        <i data-lucide="filter" class="inline h-4 w-4 mr-2"></i>Filter
                    </button>
                </form>
            </div>

            <!-- Activity Logs Table -->
            <div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg overflow-hidden animate-fade-in-up"
                style="animation-delay: 0.2s;">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-navy-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">User</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Action</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">Description
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="h-8 w-8 rounded-full bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white text-xs font-semibold">
                                                {{ strtoupper(substr($log->user->name ?? 'U', 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-navy-900 dark:text-white">
                                                    {{ $log->user->name ?? 'System' }}</p>
                                                <p class="text-xs text-slate-500 capitalize">{{ $log->user->role ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-accent-100 text-accent-700 dark:bg-accent-900/30 dark:text-accent-400">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                        {{ $log->description ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-mono text-slate-500">
                                        {{ $log->ip_address ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <i data-lucide="activity"
                                            class="h-16 w-16 text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
                                        <p class="text-slate-500 dark:text-slate-400">Belum ada activity log</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($logs->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5">
                        {{ $logs->links() }}
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