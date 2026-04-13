<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan PDF</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; }
        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 4px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 8px; font-size: 12px; }
        th { background: #f4f4f4; }
        .text-right { text-align: right; }
        .small { font-size: 11px; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan</h1>
        <p>Periode: {{ $dateFrom }} - {{ $dateTo }}</p>
        <p class="small">Dicetak pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
                <tr>
                    <td>{{ $trx->invoice_code }}</td>
                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ optional($trx->user)->name ?? 'Unknown' }}</td>
                    <td>{{ optional($trx->customer)->name ?? 'Umum' }}</td>
                    <td class="text-right">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($trx->payment_method) }}</td>
                    <td>{{ ucfirst($trx->payment_status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada transaksi untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
