<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $transaction->invoice_code }}</title>
    <style>
        @page {
            margin: 0;
            size: 58mm auto;
        }
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Courier New', Courier, monospace;
        }
        body { 
            width: 58mm; 
            margin: 0 auto; 
            padding: 10px 5px; 
            font-size: 10px;
            color: #000;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .upper { text-transform: uppercase; }
        
        .header { margin-bottom: 8px; }
        .store-name { font-size: 13px; font-weight: 900; margin-bottom: 2px; }
        .store-info { font-size: 9px; margin-bottom: 1px; line-height: 1.1; }
        
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .divider-double { border-top: 1px double #000; margin: 6px 0; }
        
        .info-row { display: flex; justify-content: space-between; margin-bottom: 1px; font-size: 9px; }
        
        .items-table { width: 100%; border-collapse: collapse; margin: 5px 0; }
        .item-row td { padding: 1px 0; vertical-align: top; }
        .item-name { font-weight: bold; display: block; margin-bottom: 1px; }
        .item-details { display: flex; justify-content: space-between; font-size: 9px; }
        
        .total-section { margin-top: 5px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 2px; }
        .grand-total { font-size: 12px; font-weight: bold; border-top: 1px dashed #000; padding-top: 4px; margin-top: 4px; }
        
        .payment-info { margin-top: 8px; font-size: 9px; }
        .footer { margin-top: 12px; font-size: 9px; line-height: 1.2; }
        
        .qr-code { margin: 10px auto; width: 60px; height: 60px; }
        .qr-code img { width: 100%; height: 100%; }

        @media print {
            body { width: 58mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <!-- Store Header -->
    <div class="header text-center">
        <div class="store-name upper">{{ $storeName }}</div>
        @if($storeAddress)
            <div class="store-info">{{ $storeAddress }}</div>
        @endif
        @if($storePhone)
            <div class="store-info">TELP: {{ $storePhone }}</div>
        @endif
    </div>

    <div class="divider-double"></div>

    <!-- Transaction Info -->
    <div class="info-row">
        <span>No: {{ $transaction->invoice_code }}</span>
        <span>Kasir: {{ strtoupper($transaction->user->name) }}</span>
    </div>
    <div class="info-row">
        <span>Tgl: {{ $transaction->created_at->format('d.m.y H:i') }}</span>
        @if($transaction->customer)
            <span>Plgn: {{ strtoupper($transaction->customer->name) }}</span>
        @endif
    </div>

    <div class="divider"></div>

    <!-- Items List -->
    <table class="items-table">
        @foreach($transaction->items as $item)
            <tr class="item-row">
                <td colspan="2">
                    <span class="item-name">{{ strtoupper($item->product->name) }}</span>
                    <div class="item-details">
                        <span>{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <!-- Summary -->
    <div class="total-section">
        <div class="total-row">
            <span>TOTAL ITEM: {{ $transaction->items->sum('qty') }}</span>
            <span>{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>
        
        @if($transaction->discount > 0)
            <div class="total-row">
                <span>DISKON</span>
                <span>-{{ number_format($transaction->discount, 0, ',', '.') }}</span>
            </div>
        @endif

        @if($transaction->tax > 0)
            <div class="total-row">
                <span>PPN</span>
                <span>{{ number_format($transaction->tax, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="total-row grand-total">
            <span>GRAND TOTAL</span>
            <span>RP {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Payment -->
    <div class="payment-info">
        <div class="total-row">
            <span>{{ strtoupper($transaction->payment_method) }}</span>
            <span>{{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
        </div>
        @if($transaction->payment_method === 'cash')
            <div class="total-row">
                <span>KEMBALI</span>
                <span>{{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    <div class="divider"></div>

    <!-- Footer -->
    <div class="footer text-center">
        <div class="bold">{{ strtoupper($receiptFooter) }}</div>
        <div style="margin-top: 4px;">TERIMA KASIH ATAS KUNJUNGAN ANDA</div>
        <div style="font-size: 8px; margin-top: 4px;">BARANG YANG SUDAH DIBELI TIDAK DAPAT<br>DITUKAR ATAU DIKEMBALIKAN</div>
    </div>

    <!-- QR Code for Online Tracking (Mock) -->
    <div class="qr-code text-center">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('customer.receipt', $transaction->invoice_code)) }}" alt="QR Code">
    </div>
    <div class="text-center" style="font-size: 7px; margin-top: -5px;">Scan untuk struk digital</div>
    
    <div class="divider" style="margin-top: 10px;"></div>
    <div class="text-center" style="font-size: 7px;">{{ now()->format('d/m/Y H:i:s') }}</div>

</body>
</html>