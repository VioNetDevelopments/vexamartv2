<!-- Products Table -->
<div class="bg-white dark:bg-navy-900 rounded-2xl shadow-lg shadow-slate-200/50 dark:shadow-black/20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.6s;">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 dark:bg-navy-800/50">
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Produk</th>
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Kategori</th>
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">SKU</th>
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Harga</th>
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Stok</th>
                    <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($products as $product)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-xl bg-slate-100 dark:bg-navy-800 overflow-hidden flex-shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <i data-lucide="package" class="h-6 w-6 text-slate-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-navy-900 dark:text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($product->description, 30) ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm font-bold text-slate-600 dark:text-slate-300">{{ $product->sku }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-bold text-accent-600 dark:text-accent-400">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-500">Beli: Rp {{ number_format($product->buy_price, 0, ',', '.') }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-lg {{ $product->stock <= 0 ? 'text-danger' : ($product->stock <= $product->min_stock ? 'text-warning' : 'text-success') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->stock <= 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-danger/10 text-danger border border-danger/20">
                                    <i data-lucide="x-circle" class="w-3 h-3"></i>
                                    Habis
                                </span>
                            @elseif($product->stock <= $product->min_stock)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-warning/10 text-warning border border-warning/20 animate-pulse">
                                    <i data-lucide="alert-triangle" class="w-3 h-3"></i>
                                    Menipis
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-success/10 text-success border border-success/20">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    Aman
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.show', $product) }}" 
                                   class="p-2.5 rounded-xl bg-accent-50 dark:bg-accent-500/10 text-accent-500 hover:bg-accent-500 hover:text-white transition-all shadow-sm active:scale-95" 
                                   title="Detail">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>
                                <a href="{{ route('admin.stock.adjust', $product) }}" 
                                   class="p-2.5 rounded-xl bg-slate-50 dark:bg-navy-800 text-slate-500 hover:bg-navy-900 dark:hover:bg-white hover:text-white dark:hover:text-navy-900 transition-all shadow-sm active:scale-95" 
                                   title="Sesuaikan Stok">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-navy-800 flex items-center justify-center text-slate-200 dark:text-navy-700 mb-4 animate-bounce">
                                    <i data-lucide="package" class="w-10 h-10"></i>
                                </div>
                                <h4 class="text-lg font-bold text-navy-900 dark:text-white mb-1">Tidak Ada Produk</h4>
                                <p class="text-sm text-slate-500">Belum ada data produk yang tercatat.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Modern Pagination -->
    @if($products->hasPages())
    <div class="px-6 py-5 border-t border-slate-100 dark:border-white/5 bg-slate-50/30 dark:bg-navy-900/30">
        <div class="flex items-center justify-between">
            <!-- Info Text -->
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $products->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $products->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $products->total() }}</span> produk
            </div>
            
            <!-- Simple Prev/Next Buttons -->
            <div class="flex items-center gap-2">
                @if($products->onFirstPage())
                    <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            Previous
                        </span>
                    </button>
                @else
                    <a href="{{ $products->previousPageUrl() }}" 
                       class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold ajax-link">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            Previous
                        </span>
                    </a>
                @endif
                
                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" 
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