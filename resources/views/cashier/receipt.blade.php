<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $transaction->invoice_code }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            width: 58mm; 
            margin: 0 auto; 
            padding: 15px 10px; 
            font-size: 11px;
            color: #1e293b;
        }
        .header { text-align: center; margin-bottom: 15px; }
        .store-name { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
        .store-info { font-size: 9px; color: #64748b; line-height: 1.4; }
        .divider { border-bottom: 2px dashed #e2e8f0; margin: 10px 0; }
        .invoice { font-size: 12px; font-weight: 600; color: #2563eb; text-align: center; margin: 8px 0; }
        .datetime { font-size: 9px; color: #64748b; text-align: center; margin-bottom: 10px; }
        .items { margin: 10px 0; }
        .item { display: flex; justify-content: space-between; margin: 4px 0; font-size: 10px; }
        .item-name { flex: 1; color: #334155; }
        .item-qty { margin: 0 5px; color: #64748b; }
        .item-price { text-align: right; color: #334155; font-weight: 600; }
        .total-section { margin-top: 10px; }
        .total-row { display: flex; justify-content: space-between; margin: 3px 0; font-size: 10px; }
        .total-label { color: #64748b; }
        .total-value { font-weight: 600; color: #334155; }
        .grand-total { 
            display: flex; 
            justify-content: space-between; 
            margin-top: 8px; 
            padding-top: 8px; 
            border-top: 2px solid #0f172a;
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }
        .footer { text-align: center; margin-top: 15px; padding-top: 10px; border-top: 2px dashed #e2e8f0; }
        .thank-you { font-size: 12px; font-weight: 600; color: #0f172a; margin-bottom: 3px; }
        .footer-text { font-size: 9px; color: #64748b; }
        @media print {
            body { width: 100%; }
            @page { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="store-name">{{ config('app.name') }}</div>
        <div class="store-info">
            {{ config('app.name') }}<br>
            Jl. Teknologi No. 12<br>
            Telp: 0812-3456-7890
        </div>
    </div>
    <div class="divider"></div>
    <div class="invoice">{{ $transaction->invoice_code }}</div>
    <div class="datetime">{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
    <div class="divider"></div>
    <div class="items">
        @foreach($transaction->items as $item)
        <div class="item">
            <span class="item-name">{{ $item->product->name }}</span>
            <span class="item-qty">x{{ $item->qty }}</span>
            <span class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
        @endforeach
    </div>
    <div class="divider"></div>
    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Subtotal</span>
            <span class="total-value">{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>
        @if($transaction->discount > 0)
        <div class="total-row">
            <span class="total-label">Diskon</span>
            <span class="total-value">-{{ number_format($transaction->discount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="grand-total">
            <span>TOTAL</span>
            <span>{{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
        </div>
        @if($transaction->payment_method === 'cash')
        <div class="total-row" style="margin-top: 8px;">
            <span class="total-label">Tunai</span>
            <span class="total-value">{{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">Kembalian</span>
            <span class="total-value" style="color: #16a34a;">{{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>
        @endif
    </div>
    <div class="divider"></div>
    <div class="footer">
        <div class="thank-you">Terima Kasih!</div>
        <div class="footer-text">Barang yang sudah dibeli<br>tidak dapat ditukar</div>
    </div>
</body>
</html>