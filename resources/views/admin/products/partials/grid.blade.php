        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="group bg-white dark:bg-navy-900 rounded-2xl overflow-hidden shadow-lg shadow-slate-200/50 dark:shadow-black/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <!-- Product Image -->
                    <div class="relative h-48 overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-navy-800 dark:to-navy-700 flex items-center justify-center">
                                <i data-lucide="package" class="w-16 h-16 text-slate-400"></i>
                            </div>
                        @endif
                        
                        <!-- Flash Sale Badge -->
                        @if($product->hasActiveFlashSale())
                            @php
                                $flashSale = $product->getActiveFlashSale();
                            @endphp
                            <div class="absolute bottom-4 left-4 right-4">
                                <div class="bg-gradient-to-r from-red-500 to-orange-500 text-white px-4 py-2 rounded-xl shadow-lg shadow-red-500/30 animate-pulse">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="zap" class="w-3 h-3"></i>
                                            <span>FLASH SALE</span>
                                        </div>
                                        <span>-{{ $flashSale->discount_percentage }}%</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Discount Badge Overlay (Regular) -->
                            @if($product->discount > 0)
                                <div class="absolute top-3 left-3 px-2.5 py-1 rounded-lg bg-gradient-to-r from-pink-500 to-rose-500 text-white text-xs font-bold shadow-lg shadow-pink-500/30 animate-pulse z-10">
                                    -{{ $product->discount }}%
                                </div>
                            @endif
                        @endif

                        <!-- Quick Actions -->
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="p-2.5 bg-white rounded-xl text-slate-700 hover:bg-accent-500 hover:text-white transition-all transform hover:scale-110">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="p-2.5 bg-white rounded-xl text-slate-700 hover:bg-accent-500 hover:text-white transition-all transform hover:scale-110">
                                <i data-lucide="edit" class="w-5 h-5"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="notify.confirm('Beneran mau hapus produk {{ $product->name }} ini? Nanti datanya ludes loh!', () => $el.closest('form').submit())" class="p-2.5 bg-white rounded-xl text-slate-700 hover:bg-danger hover:text-white transition-all transform hover:scale-110">
                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <!-- Category & Status -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-100 dark:bg-navy-800 text-slate-600 dark:text-slate-400">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                            @if(!$product->is_active)
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nonaktif</span>
                            @endif
                        </div>

                        <!-- Product Name -->
                        <h3 class="font-bold text-navy-900 dark:text-white text-base mb-1 line-clamp-2 leading-tight">{{ $product->name }}</h3>

                        <!-- Flash Sale Info (Below Name) -->
                        @if($product->hasActiveFlashSale())
                            @php
                                $flashSale = $product->getActiveFlashSale();
                                $flashPrice = $product->sell_price * (1 - $flashSale->discount_percentage / 100);
                            @endphp
                            <div class="mb-3 p-3 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 rounded-xl border border-red-200 dark:border-red-800">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="zap" class="w-4 h-4 text-red-600 animate-pulse"></i>
                                        <span class="text-xs font-black text-red-600 uppercase">Flash Sale</span>
                                    </div>
                                    <span class="text-[9px] font-bold text-red-600">Akhir {{ $flashSale->ends_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-slate-400 line-through">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                                    <span class="text-base font-black text-red-600">Rp {{ number_format($flashPrice, 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-2">
                                    <div class="flex items-center justify-between text-[9px] mb-1">
                                        <span class="text-slate-500">Terjual: {{ $flashSale->sold_quantity }}/{{ $flashSale->max_quantity }}</span>
                                        <span class="font-bold text-red-600">{{ round(($flashSale->sold_quantity / $flashSale->max_quantity) * 100) }}%</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-slate-200 dark:bg-navy-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-red-500 to-orange-500 rounded-full" 
                                             style="width: {{ ($flashSale->sold_quantity / $flashSale->max_quantity) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Regular Price (No Flash Sale) -->
                            <div class="flex items-end justify-between mb-3">
                                <div>
                                    @if($product->discount > 0)
                                        <p class="text-xs text-slate-400 line-through">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</p>
                                        <p class="text-lg font-black text-pink-600">
                                            Rp {{ number_format($product->sell_price * (1 - $product->discount / 100), 0, ',', '.') }}
                                        </p>
                                    @else
                                        <p class="text-lg font-black text-accent-600 dark:text-accent-400">
                                            Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Stock Indicator below name/flash sale -->
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-1.5 h-1.5 rounded-full {{ $product->stock > $product->min_stock ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}"></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $product->stock > $product->min_stock ? 'text-success' : ($product->stock > 0 ? 'text-warning' : 'text-danger') }}">
                                Stok: {{ $product->stock }}
                            </span>
                        </div>

                        <!-- Profit/Loss Info (Small) -->
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-white/5">
                            <span class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">Estimasi Profit</span>
                            @php
                                $finalPrice = $product->sell_price * (1 - $product->discount / 100);
                                $profit = $finalPrice - $product->buy_price;
                            @endphp
                            <span class="text-xs font-bold {{ $profit > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $profit > 0 ? '+' : '' }}Rp {{ number_format($profit, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Edit Button -->
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="mt-3 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:shadow-accent-500/30 transition-all">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                            <span>Edit</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-20">
                        <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-navy-800 flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="package" class="w-12 h-12 text-slate-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-navy-900 dark:text-white mb-2">Belum Ada Produk</h3>
                        <p class="text-slate-500 dark:text-slate-400 mb-6">Mulai tambahkan produk pertama Anda</p>
                        <a href="{{ route('admin.products.create') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-bold shadow-lg shadow-accent-500/30 hover:shadow-accent-500/50 transition-all">
                            <i data-lucide="plus-circle" class="w-5 h-5"></i>
                            <span>Tambah Produk</span>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="bg-white dark:bg-navy-900 rounded-2xl p-6 shadow-lg shadow-slate-200/50 dark:shadow-black/20 animate-fade-in-up mt-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    Menampilkan <span class="font-bold text-navy-900 dark:text-white">{{ $products->firstItem() }}</span> - <span class="font-bold text-navy-900 dark:text-white">{{ $products->lastItem() }}</span> dari <span class="font-bold text-navy-900 dark:text-white">{{ $products->total() }}</span> produk
                </div>

                <div class="flex items-center gap-2 ajax-pagination">
                    @if($products->onFirstPage())
                        <button disabled class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 cursor-not-allowed text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                Previous
                            </span>
                        </button>
                    @else
                        <a href="{{ $products->previousPageUrl() }}"
                           class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                Previous
                            </span>
                        </a>
                    @endif

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}"
                           class="px-4 py-2 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:bg-accent-500 hover:text-white hover:border-accent-500 hover:shadow-lg hover:shadow-accent-500/30 transition-all text-sm font-bold">
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
