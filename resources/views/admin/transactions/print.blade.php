<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $transaction->invoice_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Courier New', monospace; 
            width: 58mm; 
            margin: 0 auto; 
            padding: 10px;
            font-size: 11px;
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
        .item { display: flex; justify-content: space-between; margin: 3px 0; }
        .qr-code { margin: 10px auto; text-align: center; }
        .qr-code img { width: 100px; height: 100px; }
        @media print {
            body { width: 100%; }
            @page { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h3>{{ \App\Models\Setting::get('store_name', 'VexaMart') }}</h3>
        <p>{{ \App\Models\Setting::get('store_address', '') }}</p>
        <p>Telp: {{ \App\Models\Setting::get('store_phone', '') }}</p>
        <div class="line"></div>
    </div>

    <p class="text-center"><strong>{{ $transaction->invoice_code }}</strong></p>
    <p class="text-center">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
    <p class="text-center">Kasir: {{ $transaction->user->name }}</p>
    
    <div class="line"></div>

    @foreach($transaction->items as $item)
    <div class="item">
        <span class="item-name">{{ $item->product->name }}</span>
        <span class="item-qty">{{ $item->qty }}x</span>
        <span class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
    </div>
    @endforeach

    <div class="line"></div>

    <div class="item bold">
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
    <div class="item bold" style="font-size: 13px;">
        <span>TOTAL</span>
        <span>{{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
    </div>

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

    <div class="line"></div>
    
    <!-- QR Code -->
    <div class="qr-code">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($transaction->invoice_code) }}" 
             alt="QR Code">
        <p style="font-size: 9px; margin-top: 3px;">Scan untuk detail transaksi</p>
    </div>
    
    <div class="line"></div>
    <div class="text-center">
        <p>{{ \App\Models\Setting::get('receipt_footer', 'Terima kasih atas kunjungan Anda!') }}</p>
    </div>
</body>
</html>