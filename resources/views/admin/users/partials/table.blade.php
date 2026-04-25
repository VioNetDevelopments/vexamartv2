<!-- Users Table -->
<div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.7s;">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 dark:bg-navy-800/50 border-b border-slate-100 dark:border-white/5">
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">User</th>
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Role</th>
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Login Terakhir</th>
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Dibuat</th>
                    <th class="px-6 py-5 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($users as $user)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-blue-500/30 flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 dark:text-white text-base truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($user->role === 'owner')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-lg shadow-purple-500/30">
                                    <i data-lucide="crown" class="w-3.5 h-3.5"></i>
                                    Owner
                                </span>
                            @elseif($user->role === 'admin')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/30">
                                    <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg shadow-green-500/30">
                                    <i data-lucide="monitor-smartphone" class="w-3.5 h-3.5"></i>
                                    Kasir
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-success/10 text-success border border-success/20">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $user->created_at->format('d M Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="p-2.5 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-500 hover:text-white transition-all shadow-sm active:scale-95" 
                                   title="Edit">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" id="delete-user-{{ $user->id }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                @click="confirmAction({
                                                    title: 'Tendang User ini, King?',
                                                    text: 'User {{ $user->name }} ini bakal ilang aksesnya, sikat King?',
                                                    formId: 'delete-user-{{ $user->id }}'
                                                })"
                                                class="p-2.5 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-500 hover:text-white transition-all shadow-sm active:scale-95" 
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
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-navy-800 flex items-center justify-center text-slate-200 dark:text-navy-700 mb-4 animate-bounce">
                                    <i data-lucide="users" class="w-10 h-10"></i>
                                </div>
                                <h4 class="text-lg font-bold text-navy-900 dark:text-white mb-1">Belum Ada User</h4>
                                <p class="text-sm text-slate-500">User akan muncul setelah mendaftar</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
    <div class="px-6 py-5 border-t border-slate-100 dark:border-white/5 bg-slate-50/30 dark:bg-navy-900/30">
        <div class="flex items-center justify-between">
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $users->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $users->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $users->total() }}</span> user
            </div>
            
            <div class="flex items-center gap-2">
                @if($users->onFirstPage())
                    <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            Previous
                        </span>
                    </button>
                @else
                    <a href="{{ $users->previousPageUrl() }}" 
                       class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold ajax-link">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            Previous
                        </span>
                    </a>
                @endif
                
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" 
                       class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold ajax-link">
                        <span class="flex items-center gap-1.5">
                            Next
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </span>
                    </a>
                @else
                    <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                        <span class="flex items-center gap-1.5">
                            Next
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>