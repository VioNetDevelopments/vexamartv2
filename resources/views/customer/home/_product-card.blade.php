@php
    $dummyRating = round(4 + (($product->id * 7) % 10) / 10, 1);
    
    // Check for Flash Sale
    $activeFlashSale = $product->getActiveFlashSale();
    
    if ($activeFlashSale) {
        $finalPrice = $product->sell_price * (1 - $activeFlashSale->discount_percentage / 100);
        $discountLabel = '-' . round($activeFlashSale->discount_percentage) . '%';
        $stockLabel = 'Sisa ' . ($activeFlashSale->max_quantity - $activeFlashSale->sold_quantity);
    } else {
        $finalPrice = $product->discount > 0
            ? $product->sell_price * (1 - $product->discount / 100)
            : $product->sell_price;
        $discountLabel = $product->discount > 0 ? '-' . intval($product->discount) . '%' : null;
        $stockLabel = null;
    }

    $productJson = [
        'id'            => $product->id,
        'name'          => $product->name,
        'image'         => $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image)) : '',
        'description'   => $product->description ?? 'Belum ada deskripsi untuk produk ini.',
        'price'         => $product->sell_price,
        'finalPrice'    => round($finalPrice),
        'discount'      => $activeFlashSale ? $activeFlashSale->discount_percentage : ($product->discount ?? 0),
        'stock'         => $product->stock,
        'category'      => $product->category->name ?? 'Umum',
        'rating'        => $dummyRating,
        'isFlashSale'   => $activeFlashSale ? true : false,
        'sold'          => $activeFlashSale ? $activeFlashSale->sold_quantity : 0,
        'max'           => $activeFlashSale ? $activeFlashSale->max_quantity : 0,
        'progress'      => $activeFlashSale ? round($activeFlashSale->progress) : 0,
    ];
@endphp

<div class="product-card cursor-pointer group bg-white border border-slate-200 rounded-[1.5rem] overflow-hidden flex flex-col h-full shadow-sm hover:shadow-xl transition-all duration-300" @click='openProduct(@json($productJson))'>
    {{-- Image Section (Matches Screenshot 1) --}}
    <div class="relative aspect-square overflow-hidden bg-slate-50">
        @if($product->image)
            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="w-full h-full flex items-center justify-center bg-slate-100">
                <i data-lucide="package" class="w-12 h-12 text-slate-200"></i>
            </div>
        @endif

        {{-- Badges --}}
        @if($discountLabel)
            <div class="absolute top-3 left-3 px-2 py-1 bg-rose-500 text-white text-[10px] font-bold rounded-lg shadow-sm">
                {{ $discountLabel }}
            </div>
        @endif
        @if($activeFlashSale)
            <div class="absolute top-3 right-3 px-2 py-1 bg-rose-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg shadow-sm flex items-center gap-1">
                <i data-lucide="zap" class="w-3 h-3 fill-white"></i> FLASH SALE
            </div>
        @elseif($stockLabel)
            <div class="absolute top-3 right-3 px-2 py-1 bg-amber-500 text-white text-[10px] font-bold rounded-lg shadow-sm">
                {{ $stockLabel }}
            </div>
        @endif

        {{-- Hover Button --}}
        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <div class="px-5 py-2.5 bg-white text-blue-600 text-[12px] font-black rounded-full shadow-xl flex items-center gap-2 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                <i data-lucide="eye" class="w-4 h-4"></i>
                Lihat Detail
            </div>
        </div>
    </div>

    {{-- Content Section (Matches Screenshot 1) --}}
    <div class="p-5 flex-1 flex flex-col">
        <div class="flex items-center gap-2 mb-1.5">
            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">{{ $productJson['category'] }}</span>
            @if($activeFlashSale)
                <span class="text-[9px] font-semibold text-rose-500 uppercase tracking-tighter">&bull; {{ $stockLabel }}</span>
            @endif
        </div>
        
        <h3 class="text-[14px] font-bold text-slate-800 line-clamp-2 mb-3 leading-snug">{{ $product->name }}</h3>
        
        <div class="flex items-center gap-1.5 mb-5">
            <i data-lucide="star" class="w-3.5 h-3.5 text-amber-400 fill-amber-400"></i>
            <span class="text-[11px] font-bold text-slate-400">{{ $dummyRating }}</span>
        </div>

        <div class="mt-auto flex items-end justify-between">
            <div class="flex flex-col">
                @if($product->discount > 0 || $activeFlashSale)
                    <span class="text-[10px] text-slate-300 line-through font-bold mb-1 leading-none">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</span>
                    <span class="text-[17px] font-black {{ $activeFlashSale ? 'text-rose-600' : 'text-blue-600' }} leading-none">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                @else
                    <span class="text-[17px] font-black text-slate-900 leading-none">Rp{{ number_format($product->sell_price, 0, ',', '.') }}</span>
                @endif
            </div>
            <button @click.stop="quickAdd({{ $product->id }})" class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/30 hover:bg-blue-700 active:scale-95 transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </button>
        </div>
    </div>
</div>