<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $transaction->invoice_code }}</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body { 
            font-family: 'Courier New', monospace; 
            width: 58mm; 
            margin: 0 auto; 
            padding: 5px; 
            font-size: 10px;
            line-height: 1.3;
        }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 3px 0; }
        .line-double { border-bottom: 2px dashed #000; margin: 4px 0; }
        .item { display: flex; justify-content: space-between; margin: 2px 0; }
        .item-name { flex: 1; padding-right: 5px; word-wrap: break-word; word-break: break-word; }
        .item-qty { text-align: center; min-width: 30px; }
        .item-price { text-align: right; min-width: 50px; }
        .header { margin-bottom: 5px; }
        .store-name { font-size: 12px; font-weight: bold; margin-bottom: 2px; }
        .store-info { font-size: 9px; margin-bottom: 1px; }
        .invoice { font-size: 11px; font-weight: bold; margin: 3px 0; }
        .datetime { font-size: 9px; margin: 2px 0; }
        .total { font-size: 12px; font-weight: bold; margin-top: 3px; padding-top: 3px; border-top: 2px dashed #000; }
        .footer { margin-top: 5px; padding-top: 3px; border-top: 1px dashed #000; text-align: center; font-size: 9px; }
        
        @media print {
            body { width: 100%; padding: 0; }
            @page { margin: 0; size: 58mm auto; }
        }
    </style>
</head>
<body onload="window.print()">
    <!-- Store Header -->
    <div class="header text-center">
        <div class="store-name">{{ $storeName }}</div>
        @if($storeAddress)
        <div class="store-info">{{ $storeAddress }}</div>
        @endif
        @if($storePhone)
        <div class="store-info">Telp: {{ $storePhone }}</div>
        @endif
    </div>
    
    <div class="line-double"></div>
    
    <!-- Invoice & DateTime -->
    <div class="text-center">
        <div class="invoice">{{ $transaction->invoice_code }}</div>
        <div class="datetime">{{ $transaction->created_at->format('d/m/Y') }}</div>
        <div class="datetime">{{ $transaction->created_at->format('H:i:s') }}</div>
    </div>
    
    <div class="line"></div>
    
    <!-- Items List -->
    <div class="items">
        @foreach($transaction->items as $item)
        <div class="item">
            <span class="item-name">{{ $item->product->name }}</span>
            <span class="item-qty">{{ $item->qty }}x</span>
            <span class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
        @if($item->qty > 1)
        <div class="item" style="font-size: 8px; color: #666; margin-top: -2px;">
            <span style="padding-left: 20px;">{{ number_format($item->price, 0, ',', '.') }}</span>
        </div>
        @endif
        @endforeach
    </div>
    
    <div class="line"></div>
    
    <!-- Totals -->
    <div class="item">
        <span>Subtotal</span>
        <span>{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
    </div>
    
    @if($transaction->discount > 0)
    <div class="item">
        <span>Diskon</span>
        <span>- {{ number_format($transaction->discount, 0, ',', '.') }}</span>
    </div>
    @endif
    
    @if($transaction->tax > 0)
    <div class="item">
        <span>Pajak</span>
        <span>{{ number_format($transaction->tax, 0, ',', '.') }}</span>
    </div>
    @endif
    
    <div class="item total">
        <span>TOTAL</span>
        <span>{{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
    </div>
    
    <!-- Cash Payment Details -->
    @if($transaction->payment_method === 'cash')
    <div class="line"></div>
    <div class="item">
        <span>Tunai</span>
        <span>{{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
    </div>
    <div class="item">
        <span>Kembalian</span>
        <span>{{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
    </div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <div class="bold">{{ $receiptFooter }}</div>
        <div style="margin-top: 2px;">Barang yang dibeli tidak dapat</div>
        <div>ditukar/dikembalikan</div>
    </div>
</body>
</html>