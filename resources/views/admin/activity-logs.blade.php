@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Activity Logs</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Riwayat aktivitas sistem</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-navy-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($logs ?? [] as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                            {{ $log->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="font-medium text-navy-900 dark:text-white">{{ $log->user->name ?? 'System' }}</span>
                            <span class="text-xs text-slate-500">({{ ucfirst($log->user->role ?? '-') }})</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent-500/10 text-accent-500">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                            {{ $log->description ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500 font-mono">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                            Belum ada activity log
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($logs) && $logs->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-white/5">
            {{ $links ?? '' }}
        </div>
        @endif
    </div>
</div>
@endsection