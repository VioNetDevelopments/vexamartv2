@extends('layouts.app')

@section('content')

<style>
canvas{
    max-height:250px;
}
</style>

<div class="space-y-6">

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
<div>
<h1 class="text-2xl font-bold text-navy-900 dark:text-white">Dashboard</h1>
<p class="text-sm text-slate-500 dark:text-slate-400">
Selamat datang, {{ auth()->user()->name }}!
</p>
</div>
</div>


<!-- Stats -->
<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-slate-500">Penjualan Hari Ini</p>
<h3 class="mt-2 text-2xl font-bold">
Rp {{ number_format($stats['today_sales'] ?? 0, 0, ',', '.') }}
</h3>
</div>
<div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50">
<i data-lucide="dollar-sign" class="h-6 w-6"></i>
</div>
</div>
</div>

<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-slate-500">Total Transaksi</p>
<h3 class="mt-2 text-2xl font-bold">
{{ $stats['today_transactions'] ?? 0 }}
</h3>
</div>
<div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-50">
<i data-lucide="shopping-cart"></i>
</div>
</div>
</div>

<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-slate-500">Stok Kritis</p>
<h3 class="mt-2 text-2xl font-bold text-red-500">
{{ $stats['low_stock_products'] ?? 0 }}
</h3>
</div>
<div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50">
<i data-lucide="alert-circle"></i>
</div>
</div>
</div>

<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">
<div class="flex items-center justify-between">
<div>
<p class="text-sm text-slate-500">Total Produk</p>
<h3 class="mt-2 text-2xl font-bold">
{{ $stats['total_products'] ?? 0 }}
</h3>
</div>
<div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-50">
<i data-lucide="package"></i>
</div>
</div>
</div>

</div>


<!-- Charts -->
<div class="grid gap-6 lg:grid-cols-3">

<div class="rounded-2xl bg-white p-6 shadow-soft lg:col-span-2 dark:bg-navy-900">
<h3 class="text-lg font-bold mb-4">Grafik Penjualan (7 Hari)</h3>

<div class="h-64 relative">
<canvas id="salesChart"></canvas>
</div>

</div>


<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">
<h3 class="text-lg font-bold mb-4">Metode Pembayaran</h3>

<div class="h-64 relative">
<canvas id="paymentChart"></canvas>
</div>

</div>

</div>


<!-- Produk Terlaris -->
<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">

<h3 class="text-lg font-bold mb-4">Produk Terlaris</h3>

<div class="space-y-4">

@forelse($topProducts as $product)

<div class="flex items-center gap-3">

<div class="h-12 w-12 rounded-lg bg-slate-100 overflow-hidden">

@if($product->image)
<img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
@else
<div class="flex items-center justify-center h-full">
<i data-lucide="package"></i>
</div>
@endif

</div>

<div class="flex-1">
<p class="text-sm font-medium">{{ $product->name }}</p>
<p class="text-xs text-slate-500">
Terjual: {{ $product->total_sold }} pcs
</p>
</div>

<span class="text-sm font-bold text-blue-500">
{{ $product->total_sold }}
</span>

</div>

@empty
<p class="text-center text-slate-500">Belum ada data</p>
@endforelse

</div>

</div>


<!-- Recent Transactions -->
<div class="rounded-2xl bg-white shadow-soft overflow-hidden dark:bg-navy-900">

<div class="p-6 border-b">
<h3 class="text-lg font-bold">Transaksi Terbaru</h3>
</div>

<div class="overflow-x-auto">

<table class="w-full">

<thead class="bg-slate-50">

<tr>
<th class="px-6 py-3 text-left text-xs uppercase">Invoice</th>
<th class="px-6 py-3 text-left text-xs uppercase">Tanggal</th>
<th class="px-6 py-3 text-left text-xs uppercase">Kasir</th>
<th class="px-6 py-3 text-left text-xs uppercase">Total</th>
<th class="px-6 py-3 text-left text-xs uppercase">Status</th>
</tr>

</thead>

<tbody class="divide-y">

@forelse($recentTransactions as $transaction)

<tr class="hover:bg-slate-50">

<td class="px-6 py-4 font-mono text-blue-500">
{{ $transaction->invoice_code }}
</td>

<td class="px-6 py-4 text-sm">
{{ $transaction->created_at->format('d M Y, H:i') }}
</td>

<td class="px-6 py-4 text-sm">
{{ $transaction->user->name }}
</td>

<td class="px-6 py-4 font-bold">
Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
</td>

<td class="px-6 py-4">
<span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded">
{{ ucfirst($transaction->payment_status) }}
</span>
</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center py-6 text-slate-500">
Belum ada transaksi
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</div>


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function(){

lucide.createIcons();

/* SAFE DATA (anti undefined) */

const salesLabels = @json($salesData['labels'] ?? []);
const salesValues = @json($salesData['data'] ?? []);

const paymentLabels = @json($paymentData['labels'] ?? []);
const paymentCounts = @json($paymentData['counts'] ?? []);


/* SALES CHART */

if(document.getElementById('salesChart')){

new Chart(document.getElementById('salesChart'),{

type:'line',

data:{
labels:salesLabels,
datasets:[{
label:'Penjualan',
data:salesValues,
borderColor:'#2563EB',
backgroundColor:'rgba(37,99,235,0.1)',
fill:true,
tension:0.4
}]
},

options:{
responsive:true,
maintainAspectRatio:false
}

});

}


/* PAYMENT CHART */

if(document.getElementById('paymentChart')){

new Chart(document.getElementById('paymentChart'),{

type:'doughnut',

data:{
labels:paymentLabels,
datasets:[{
data:paymentCounts,
backgroundColor:[
'#2563EB',
'#16A34A',
'#F59E0B',
'#8B5CF6'
]
}]
},

options:{
responsive:true,
maintainAspectRatio:false,
cutout:'70%'
}

});

}

});

</script>

@endpush

@endsection