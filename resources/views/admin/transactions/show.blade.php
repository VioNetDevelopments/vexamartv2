@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.transactions.index') }}" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                <i data-lucide="arrow-left" class="h-5 w-5 text-slate-500"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-navy-900 dark:text-white">Detail Transaksi</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-mono">{{ $transaction->invoice_code }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.transactions.print', $transaction) }}" target="_blank" class="inline-flex items-center rounded-lg bg-accent-500 px-4 py-2 text-sm font-medium text-white hover:bg-accent-600">
                <i data-lucide="printer" class="mr-2 h-4 w-4"></i> Cetak Struk
            </a>
        </div>
    </div>

    <!-- Transaction Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Tanggal</p>
            <p class="font-bold text-navy-900 dark:text-white">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Kasir</p>
            <p class="font-bold text-navy-900 dark:text-white">{{ $transaction->user->name }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Customer</p>
            <p class="font-bold text-navy-900 dark:text-white">{{ $transaction->customer->name ?? 'Umum' }}</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
            <p class="text-sm text-slate-500">Metode Bayar</p>
            <p class="font-bold text-accent-500">{{ ucfirst($transaction->payment_method) }}</p>
        </div>
    </div>

    <!-- Items Table -->
    <div class="rounded-2xl bg-white shadow-soft overflow-hidden dark:bg-navy-900 dark:border dark:border-white/5">
        <div class="p-6 border-b border-slate-100 dark:border-white/5">
            <h3 class="font-bold text-navy-900 dark:text-white">Item Pembelian</h3>
        </div>
        <table class="w-full">
            <thead class="bg-slate-50 dark:bg-navy-800/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @foreach($transaction->items as $item)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-slate-100 dark:bg-navy-800 overflow-hidden">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/'.$item->product->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full"><i data-lucide="package" class="h-5 w-5 text-slate-400"></i></div>
                                @endif
                            </div>
                            <span class="font-medium text-navy-900 dark:text-white">{{ $item->product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm">{{ $item->qty }}</td>
                    <td class="px-6 py-4 text-right font-bold text-navy-900 dark:text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Payment Summary -->
    <div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 dark:border dark:border-white/5">
        <div class="flex justify-end">
            <div class="w-full max-w-sm space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Subtotal</span>
                    <span class="text-navy-900 dark:text-white">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($transaction->discount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Diskon</span>
                    <span class="text-danger">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaction->tax > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Pajak</span>
                    <span class="text-navy-900 dark:text-white">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-bold pt-3 border-t border-slate-100 dark:border-white/5">
                    <span class="text-navy-900 dark:text-white">Total</span>
                    <span class="text-accent-500">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                </div>
                @if($transaction->payment_method === 'cash')
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Dibayar</span>
                    <span class="text-navy-900 dark:text-white">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Kembalian</span>
                    <span class="text-success font-bold">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection