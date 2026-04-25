@extends('layouts.customer')

@section('title', $product->name . ' - Detail Produk')

@section('content')
    <div class="min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-sm mb-8 animate-fade-in px-4">
                <a href="{{ route('customer.home') }}"
                    class="text-slate-500 hover:text-blue-600 transition-colors">Beranda</a>
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                <a href="{{ route('customer.home') }}#products" class="text-slate-500 hover:text-blue-600 transition-colors">Produk</a>
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
                <span class="text-blue-600 font-bold truncate">{{ $product->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
                <!-- Product Image -->
                <div class="animate-slide-in-left">
                    <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-2xl overflow-hidden sticky top-24">
                        <div class="aspect-square relative bg-gradient-to-br from-slate-100 to-slate-200 dark:from-navy-700 dark:to-navy-600">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i data-lucide="package" class="w-32 h-32 text-slate-400"></i>
                                </div>
                            @endif

                            <!-- Badges -->
                            <div class="absolute top-6 left-6 flex flex-col gap-3">
                                @if($activeFlashSale = $product->getActiveFlashSale())
                                    <div class="animate-pulse">
                                        <div class="px-4 py-2 bg-rose-600 text-white rounded-xl text-xs font-black shadow-lg shadow-rose-600/30 flex items-center gap-2 uppercase tracking-widest">
                                            <i data-lucide="zap" class="w-3.5 h-3.5 fill-white"></i>
                                            <span>FLASH SALE</span>
                                        </div>
                                    </div>
                                @elseif($product->discount > 0)
                                    <div class="px-4 py-2 bg-rose-500 text-white rounded-xl text-xs font-black shadow-lg shadow-rose-500/30 uppercase tracking-widest">
                                        DISKON {{ intval($product->discount) }}%
                                    </div>
                                @endif

                                @if($product->stock <= 0)
                                    <div class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-black shadow-lg uppercase tracking-widest">
                                        HABIS
                                    </div>
                                @endif
                            </div>

                            <div class="absolute top-6 right-6">
                                <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-slate-700 rounded-xl text-xs font-bold shadow-lg flex items-center gap-2">
                                    <i data-lucide="package" class="w-3.5 h-3.5 text-blue-600"></i>
                                    {{ $product->stock }} STOK
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="animate-slide-in-right">
                    <!-- Category -->
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 mb-4">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </span>

                    <!-- Product Name -->
                    <h1 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4">{{ $product->name }}</h1>

                    <!-- Rating -->
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-200 dark:border-white/10">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-yellow-400 fill-yellow-400' : 'text-slate-300' }}"></i>
                            @endfor
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ number_format($averageRating, 1) }} / 5.0</span>
                        <a href="#reviews" class="text-sm text-blue-600 hover:text-blue-700 font-bold">({{ $reviewsCount }} ulasan)</a>
                    </div>

                    <!-- Price -->
                    <div class="mb-8 pb-8 border-b border-slate-200 dark:border-white/10">
                        @if($activeFlashSale = $product->getActiveFlashSale())
                            @php $finalPrice = $product->sell_price * (1 - $activeFlashSale->discount_percentage / 100); @endphp
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-2xl text-slate-400 line-through">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</span>
                                <span class="px-3 py-1 rounded-lg bg-rose-600 text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-rose-600/20">HEMAT {{ round($activeFlashSale->discount_percentage) }}%</span>
                            </div>
                            <div class="flex items-baseline gap-2">
                                <p class="text-5xl font-black text-rose-600">Rp{{ number_format($finalPrice, 0, ',', '.') }}</p>
                                <span class="text-rose-400 font-bold text-sm uppercase tracking-wider animate-pulse">(FLASH SALE)</span>
                            </div>

                            {{-- FS Progress --}}
                            <div class="mt-6 p-4 bg-rose-50 rounded-2xl border border-rose-100 max-w-md">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="zap" class="w-4 h-4 text-rose-600 fill-rose-600"></i>
                                        <span class="text-[11px] font-black text-rose-600 uppercase tracking-widest">Penawaran Berakhir: {{ $activeFlashSale->time_remaining }}</span>
                                    </div>
                                    <span class="text-[11px] font-bold text-rose-400 uppercase tracking-tight">Sisa Stok: {{ $activeFlashSale->max_quantity - $activeFlashSale->sold_quantity }}</span>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-[10px] font-bold text-rose-900/60 uppercase">
                                        <span>Terjual {{ $activeFlashSale->sold_quantity }} / {{ $activeFlashSale->max_quantity }}</span>
                                        <span>{{ round($activeFlashSale->progress) }}%</span>
                                    </div>
                                    <div class="w-full h-2 bg-rose-200/50 rounded-full overflow-hidden">
                                        <div class="h-full bg-rose-500 rounded-full transition-all duration-1000" style="width: {{ $activeFlashSale->progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @elseif($product->discount > 0)
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-2xl text-slate-400 line-through">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</span>
                                <span class="px-3 py-1 rounded-lg bg-blue-600 text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-600/20">DISKON {{ intval($product->discount) }}%</span>
                            </div>
                            <p class="text-5xl font-black text-blue-600">Rp{{ number_format($product->sell_price * (1 - $product->discount / 100), 0, ',', '.') }}</p>
                        @else
                            <p class="text-5xl font-black text-slate-900 dark:text-white">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</p>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mb-8 pb-8 border-b border-slate-200 dark:border-white/10">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                            <span>Deskripsi Produk</span>
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ $product->description ?? 'Tidak ada deskripsi produk.' }}</p>
                    </div>

                    <!-- Add to Cart Form -->
                    <form action="{{ route('customer.cart.add', $product) }}" method="POST" class="space-y-6 mb-8">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Jumlah</label>
                            <div class="flex items-center gap-4">
                                <button type="button" onclick="decrementQty()" class="w-14 h-14 rounded-2xl border-2 border-slate-200 dark:border-white/10 flex items-center justify-center hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                                    <i data-lucide="minus" class="w-6 h-6"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" readonly
                                       class="w-24 h-14 rounded-2xl border-2 border-slate-200 dark:border-white/10 text-center text-2xl font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white">
                                <button type="button" onclick="incrementQty()" class="w-14 h-14 rounded-2xl border-2 border-slate-200 dark:border-white/10 flex items-center justify-center hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                                    <i data-lucide="plus" class="w-6 h-6"></i>
                                </button>
                                <span class="text-sm text-slate-500 ml-4">Maksimal: {{ $product->stock }}</span>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full py-5 text-white rounded-2xl font-black text-lg shadow-lg transition-all hover:-translate-y-1 flex items-center justify-center gap-3 active:scale-95 {{ $product->getActiveFlashSale() ? 'bg-rose-600 shadow-rose-600/30 hover:bg-rose-700' : 'bg-blue-600 shadow-blue-600/30 hover:bg-blue-700' }}">
                            <i data-lucide="{{ $product->getActiveFlashSale() ? 'zap' : 'shopping-bag' }}" class="w-6 h-6"></i>
                            <span>{{ $product->getActiveFlashSale() ? 'Beli Sekarang' : 'Tambah ke Keranjang' }}</span>
                        </button>

                        <a href="{{ route('customer.cart') }}" class="w-full py-5 border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-2xl font-bold text-lg hover:bg-slate-50 dark:hover:bg-navy-800 transition-all flex items-center justify-center gap-3">
                            <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                            <span>Lihat Keranjang Belanja</span>
                        </a>
                    </form>

                    <!-- Product Details -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-navy-800">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-lucide="package" class="w-5 h-5 text-blue-600"></i>
                                <span class="text-xs font-bold text-slate-500 uppercase">SKU</span>
                            </div>
                            <p class="font-mono font-bold text-slate-900 dark:text-white">{{ $product->sku ?? '-' }}</p>
                        </div>
                        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-navy-800">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-lucide="barcode" class="w-5 h-5 text-blue-600"></i>
                                <span class="text-xs font-bold text-slate-500 uppercase">Barcode</span>
                            </div>
                            <p class="font-mono font-bold text-slate-900 dark:text-white">{{ $product->barcode ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <section id="reviews" class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-2">Ulasan & Rating</h2>
                        <p class="text-slate-500 dark:text-slate-400">{{ $reviewsCount }} ulasan dari pembeli</p>
                    </div>
                    <button onclick="document.getElementById('reviewForm').classList.remove('hidden')" class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl font-bold hover:shadow-lg hover:shadow-blue-500/30 transition-all flex items-center gap-2">
                        <i data-lucide="star" class="w-5 h-5"></i>
                        <span>Tulis Ulasan</span>
                    </button>
                </div>

                <!-- Rating Summary -->
                <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-8 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="text-center">
                            <p class="text-7xl font-black gradient-text mb-4">{{ number_format($averageRating, 1) }}</p>
                            <div class="flex items-center justify-center gap-1 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="w-8 h-8 {{ $i <= round($averageRating) ? 'text-yellow-400 fill-yellow-400' : 'text-slate-300' }}"></i>
                                @endfor
                            </div>
                            <p class="text-slate-500 dark:text-slate-400">dari {{ $reviewsCount }} ulasan</p>
                        </div>

                        <div class="space-y-3">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $product->reviews()->where('rating', $i)->count();
                                    $percentage = $reviewsCount > 0 ? ($count / $reviewsCount) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300 w-16">{{ $i }} ★</span>
                                    <div class="flex-1 h-4 bg-slate-200 dark:bg-navy-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-500 w-12 text-right">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Review Form -->
                <div id="reviewForm" class="hidden bg-white dark:bg-navy-900 rounded-3xl shadow-xl p-8 mb-8">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">Tulis Ulasan</h3>
                    <form action="{{ route('customer.reviews.store', $product) }}" method="POST" class="space-y-6">
                        @csrf

                        @guest
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama</label>
                                    <input type="text" name="customer_name" required class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                                    <input type="email" name="customer_email" required class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white">
                                </div>
                            </div>
                        @endguest

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Rating</label>
                            <div class="flex gap-2" x-data="{ rating: 5 }">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" @click="rating = {{ $i }}" name="rating" value="{{ $i }}"
                                            class="w-14 h-14 rounded-xl flex items-center justify-center transition-all"
                                            :class="rating >= {{ $i }} ? 'bg-yellow-400 text-white' : 'bg-slate-200 dark:bg-navy-700 text-slate-400'">
                                        <i data-lucide="star" class="w-7 h-7"></i>
                                    </button>
                                @endfor
                                <input type="hidden" name="rating" :value="rating">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Komentar (Opsional)</label>
                            <textarea name="comment" rows="4" class="w-full rounded-xl border border-slate-200 dark:border-white/10 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 dark:bg-navy-800 dark:text-white resize-none" placeholder="Bagaimana pengalaman Anda dengan produk ini?"></textarea>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl font-bold hover:shadow-lg hover:shadow-blue-500/30 transition-all">Kirim Ulasan</button>
                            <button type="button" onclick="document.getElementById('reviewForm').classList.add('hidden')" class="px-8 py-4 border-2 border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 rounded-2xl font-bold hover:bg-slate-50 dark:hover:bg-navy-800 transition-all">Batal</button>
                        </div>
                    </form>
                </div>

                <!-- Reviews List -->
                <div class="space-y-4">
                    @forelse($product->reviews as $review)
                        <div class="bg-white dark:bg-navy-900 rounded-3xl shadow-lg p-6 animate-fade-in">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($review->customer_name ?? $review->user?->name ?? 'A', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white">{{ $review->customer_name ?? $review->user?->name ?? 'Anonim' }}</p>
                                        <p class="text-xs text-slate-500">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star" class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-slate-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-16 bg-white dark:bg-navy-900 rounded-3xl shadow-lg">
                            <i data-lucide="message-square" class="w-20 h-20 text-slate-300 mx-auto mb-6"></i>
                            <p class="text-slate-500 dark:text-slate-400 text-lg">Belum ada ulasan. Jadilah yang pertama!</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <section>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-8">Produk Terkait</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="group bg-white dark:bg-navy-900 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-2 card-hover">
                                <a href="{{ route('customer.products.show', ['product' => $relatedProduct, 'slug' => \Illuminate\Support\Str::slug($relatedProduct->name)]) }}" class="block">
                                    <div class="relative h-48 overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200 dark:from-navy-700 dark:to-navy-600">
                                        @if($relatedProduct->image)
                                            <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i data-lucide="package" class="w-16 h-16 text-slate-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                <div class="p-5">
                                    <a href="{{ route('customer.products.show', ['product' => $relatedProduct, 'slug' => \Illuminate\Support\Str::slug($relatedProduct->name)]) }}" class="block">
                                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 line-clamp-2 hover:text-blue-600 transition-colors">{{ $relatedProduct->name }}</h3>
                                    </a>
                                    <p class="text-lg font-black text-blue-600">Rp {{ number_format($relatedProduct->sell_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
        function incrementQty() {
            const input = document.getElementById('quantity');
            const max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decrementQty() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
        </script>
    @endpush
@endsection