<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="bg-slate-50 dark:bg-navy-800/50 border-b border-slate-100 dark:border-white/5">
                <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">No. Invoice</th>
                <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Waktu Transaksi</th>
                <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Customer</th>
                <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Item</th>
                <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Pembayaran</th>
                <th class="px-6 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Total Bayar</th>
                <th class="px-6 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 dark:divide-white/5">
            @forelse($transactions as $trx)
            <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                <td class="px-6 py-5 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-accent-500 group-hover:scale-150 transition-transform"></div>
                        <span class="font-mono text-sm font-black text-navy-900 dark:text-white leading-none">#{{ $trx->invoice_code }}</span>
                    </div>
                </td>
                <td class="px-6 py-5 whitespace-nowrap">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-navy-900 dark:text-white">{{ $trx->created_at->format('d M Y') }}</span>
                        <span class="text-[10px] font-medium text-slate-500">{{ $trx->created_at->format('H:i') }} WIB</span>
                    </div>
                </td>
                <td class="px-6 py-5 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center text-slate-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $trx->customer->name ?? 'Umum' }}</span>
                    </div>
                </td>
                <td class="px-6 py-5 whitespace-nowrap">
                    @php
                        $itemCount = $trx->items ? $trx->items->sum('qty') : 0;
                    @endphp
                    <span class="px-3 py-1 rounded-lg bg-slate-100 dark:bg-navy-800 text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                        {{ $itemCount }} Item
                    </span>
                </td>
                <td class="px-6 py-5 whitespace-nowrap">
                    @php
                        $badgeClasses = [
                            'cash' => 'bg-success/10 text-success border border-success/20',
                            'qris' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800',
                            'debit' => 'bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800',
                            'ewallet' => 'bg-warning/10 text-warning border border-warning/20'
                        ];
                        $icons = [
                            'cash' => 'banknote',
                            'qris' => 'qr-code',
                            'debit' => 'credit-card',
                            'ewallet' => 'wallet'
                        ];
                        $method = $trx->payment_method;
                        $class = $badgeClasses[$method] ?? $badgeClasses['cash'];
                        $icon = $icons[$method] ?? $icons['cash'];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $class }}">
                        <i data-lucide="{{ $icon }}" class="w-3 h-3"></i>
                        {{ ucfirst($method) }}
                    </span>
                </td>
                <td class="px-6 py-5 text-right whitespace-nowrap">
                    <span class="text-base font-black text-navy-900 dark:text-white">Rp{{ number_format($trx->grand_total, 0, ',', '.') }}</span>
                </td>
                <td class="px-6 py-5 text-right whitespace-nowrap">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.transactions.show', $trx) }}" 
                           class="p-2.5 rounded-xl bg-accent-50 dark:bg-accent-500/10 text-accent-500 hover:bg-accent-500 hover:text-white transition-all shadow-sm active:scale-95" 
                           title="Detail">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                        </a>
                        <a href="{{ route('admin.transactions.print', $trx) }}" target="_blank" 
                           class="p-2.5 rounded-xl bg-slate-50 dark:bg-navy-800 text-slate-500 hover:bg-navy-900 dark:hover:bg-white hover:text-white dark:hover:text-navy-900 transition-all shadow-sm active:scale-95" 
                           title="Cetak">
                            <i data-lucide="printer" class="h-4 w-4"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-20 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-navy-800 flex items-center justify-center text-slate-200 dark:text-navy-700 mb-4 animate-bounce">
                            <i data-lucide="receipt" class="w-10 h-10"></i>
                        </div>
                        <h4 class="text-lg font-bold text-navy-900 dark:text-white mb-1">Riwayat Kosong</h4>
                        <p class="text-sm text-slate-500">Belum ada transaksi yang tercatat dalam periode ini.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modern Pagination -->
@if($transactions->hasPages())
<div class="px-6 py-5 border-t border-slate-100 dark:border-white/5 bg-slate-50/30 dark:bg-navy-900/30">
    <div class="flex items-center justify-between">
        <div class="text-sm text-slate-600 dark:text-slate-400">
            Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $transactions->total() }}</span> hasil
        </div>
        <div class="flex items-center gap-2">
            @if($transactions->onFirstPage())
                <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        Previous
                    </span>
                </button>
            @else
                <button @click="changePage('{{ $transactions->previousPageUrl() }}')" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        Previous
                    </span>
                </button>
            @endif
            
            @if($transactions->hasMorePages())
                <button @click="changePage('{{ $transactions->nextPageUrl() }}')" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                    <span class="flex items-center gap-1.5">
                        Next
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </span>
                </button>
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
