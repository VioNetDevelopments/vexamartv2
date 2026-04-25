@forelse($flashSales as $sale)
    <tr class="group hover:bg-accent-50/50 dark:hover:bg-accent-900/5 transition-all flash-sale-row" 
        data-id="{{ $sale->id }}"
        data-starts="{{ $sale->starts_at->toISOString() }}"
        data-ends="{{ $sale->ends_at->toISOString() }}"
        data-active="{{ $sale->is_active ? '1' : '0' }}">
        <td class="px-6 py-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0">
                    @if($sale->product->image)
                        <img src="{{ asset('storage/' . $sale->product->image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="package" class="w-6 h-6 text-slate-400"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <p class="font-bold text-slate-900 dark:text-white">{{ $sale->product->name }}</p>
                    <p class="text-xs text-slate-500">{{ $sale->product->sku ?? '-' }}</p>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <span class="inline-flex items-center px-4 py-2 rounded-xl bg-gradient-to-r from-accent-500 to-blue-600 text-white text-sm font-black shadow-lg shadow-accent-500/30">
                -{{ $sale->discount_percentage }}%
            </span>
        </td>
        <td class="px-6 py-4">
            <div class="flex flex-col gap-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Terjual:</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ $sale->sold_quantity }}/{{ $sale->max_quantity }}</span>
                </div>
                <div class="w-32 h-2 bg-slate-200 dark:bg-navy-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-accent-500 to-blue-600 rounded-full" style="width: {{ ($sale->sold_quantity / $sale->max_quantity) * 100 }}%"></div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 periode-cell">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2 text-xs start-time-row {{ $sale->ends_at <= now() ? 'opacity-50' : '' }}">
                    <i data-lucide="play" class="w-3 h-3 start-icon {{ $sale->ends_at > now() ? 'text-green-500 animate-pulse' : 'text-slate-400' }}"></i>
                    <span class="text-slate-600 dark:text-slate-400 starts-at-text">{{ $sale->starts_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs end-time-row">
                    <i data-lucide="stop" class="w-3 h-3 end-icon {{ $sale->ends_at <= now() ? 'text-green-500' : 'text-red-500' }}"></i>
                    <span class="text-slate-600 dark:text-slate-400 ends-at-text">{{ $sale->ends_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 text-center status-cell">
            @if($sale->isRunning())
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-green-100 text-green-700 animate-pulse status-badge" data-status="active">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Berlangsung
                </span>
            @elseif($sale->starts_at > now())
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-blue-100 text-blue-700 status-badge" data-status="upcoming">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    Akan Datang
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black bg-slate-100 text-slate-600 status-badge" data-status="expired">
                    <i data-lucide="archive" class="w-3 h-3"></i>
                    Berakhir
                </span>
            @endif
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center justify-center gap-2">
                <a href="{{ route('admin.flash-sales.edit', $sale) }}" 
                   class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 hover:bg-blue-500 hover:text-white transition-all">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                </a>
                <form action="{{ route('admin.flash-sales.toggle', $sale) }}" method="POST" class="inline">
                    @csrf
                    <button type="button" 
                            onclick="notify.confirm('{{ $sale->is_active ? 'Beneran mau berhentiin diskonnya KING? Kasihan pembelinya loh.' : 'Mau aktifin diskonnya sekarang KING? Gas nih?' }}', () => this.closest('form').submit())"
                            class="p-2.5 rounded-xl {{ $sale->is_active ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white' : 'bg-green-50 text-green-600 hover:bg-green-500 hover:text-white' }} transition-all">
                        <i data-lucide="{{ $sale->is_active ? 'pause' : 'play' }}" class="w-4 h-4"></i>
                    </button>
                </form>
                <form action="{{ route('admin.flash-sales.destroy', $sale) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="notify.confirm('Beneran KING mau hapus flash sale ini? Kasihan produknya nggak jadi diskon loh.', () => this.closest('form').submit())" class="p-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center">
                <i data-lucide="zap-off" class="w-16 h-16 text-slate-300 mb-4"></i>
                <p class="text-slate-500">Belum ada flash sale</p>
            </div>
        </td>
    </tr>
@endforelse
