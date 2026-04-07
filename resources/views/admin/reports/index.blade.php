@extends('layouts.app')

@section('content')

<style>
canvas{
    max-height:250px;
}
</style>

<div class="space-y-6"
x-data="{
dateFrom:'{{ $dateFrom }}',
dateTo:'{{ $dateTo }}',
groupBy:'{{ $groupBy }}'
}">

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

<div>
<h1 class="text-2xl font-bold text-navy-900 dark:text-white">
Laporan Penjualan
</h1>

<p class="text-sm text-slate-500 dark:text-slate-400">
Analisis performa toko Anda
</p>
</div>

<div class="flex gap-2">

<a href="{{ route('admin.reports.export.excel',['date_from'=>$dateFrom,'date_to'=>$dateTo]) }}"
class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">

<i data-lucide="file-spreadsheet" class="mr-2 h-4 w-4"></i>
Export Excel

</a>

<a href="{{ route('admin.reports.export.pdf',['date_from'=>$dateFrom,'date_to'=>$dateTo]) }}"
class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">

<i data-lucide="file-text" class="mr-2 h-4 w-4"></i>
Export PDF

</a>

</div>

</div>


<!-- Filter -->
<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">

<form action="{{ route('admin.reports.index') }}"
method="GET"
class="flex flex-wrap gap-4 items-end">

<div>

<label class="block text-sm mb-1">Dari Tanggal</label>

<input type="date"
name="date_from"
value="{{ $dateFrom }}"
x-model="dateFrom"
class="rounded-lg border px-4 py-2 text-sm">

</div>

<div>

<label class="block text-sm mb-1">Sampai Tanggal</label>

<input type="date"
name="date_to"
value="{{ $dateTo }}"
x-model="dateTo"
class="rounded-lg border px-4 py-2 text-sm">

</div>

<div>

<label class="block text-sm mb-1">Group By</label>

<select name="group_by"
x-model="groupBy"
class="rounded-lg border px-4 py-2 text-sm">

<option value="daily">Harian</option>
<option value="weekly">Mingguan</option>
<option value="monthly">Bulanan</option>

</select>

</div>

<div>

<label class="block text-sm mb-1">Metode Bayar</label>

<select name="payment_method"
class="rounded-lg border px-4 py-2 text-sm">

<option value="">Semua</option>

<option value="cash"
{{ request('payment_method')=='cash'?'selected':'' }}>
Tunai
</option>

<option value="qris"
{{ request('payment_method')=='qris'?'selected':'' }}>
QRIS
</option>

<option value="debit"
{{ request('payment_method')=='debit'?'selected':'' }}>
Debit
</option>

<option value="ewallet"
{{ request('payment_method')=='ewallet'?'selected':'' }}>
E-Wallet
</option>

</select>

</div>

<button type="submit"
class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white">

<i data-lucide="filter" class="mr-2 inline h-4 w-4"></i>
Filter

</button>

</form>

</div>


<!-- Stats -->
<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">

<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">
<p class="text-sm text-slate-500">Total Penjualan</p>

<h3 class="mt-2 text-2xl font-bold">
Rp {{ number_format($stats['total_sales'],0,',','.') }}
</h3>

</div>


<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">

<p class="text-sm text-slate-500">Total Transaksi</p>

<h3 class="mt-2 text-2xl font-bold">
{{ number_format($stats['total_transactions']) }}
</h3>

</div>


<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">

<p class="text-sm text-slate-500">Rata-rata/Transaksi</p>

<h3 class="mt-2 text-2xl font-bold">
Rp {{ number_format($stats['avg_transaction'],0,',','.') }}
</h3>

</div>


<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">

<p class="text-sm text-slate-500">Estimasi Profit</p>

<h3 class="mt-2 text-2xl font-bold text-green-600">
Rp {{ number_format($stats['total_profit'],0,',','.') }}
</h3>

</div>

</div>


<!-- Charts -->
<div class="grid gap-6 lg:grid-cols-3">


<!-- Sales Chart -->
<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900 lg:col-span-2">

<h3 class="text-lg font-bold mb-4">
Grafik Penjualan
</h3>

<div class="h-64 relative">
<canvas id="salesChart"></canvas>
</div>

</div>


<!-- Payment Chart -->
<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">

<h3 class="text-lg font-bold mb-4">
Metode Pembayaran
</h3>

<div class="h-64 relative">
<canvas id="paymentChart"></canvas>
</div>

</div>


</div>



<!-- Top Products -->
<div class="rounded-2xl bg-white p-6 shadow-soft dark:bg-navy-900">

<h3 class="text-lg font-bold mb-4">
Produk Terlaris
</h3>

<div class="overflow-x-auto">

<table class="w-full">

<thead>

<tr class="text-xs text-slate-500 uppercase">

<th class="pb-3">Produk</th>
<th class="pb-3">Terjual</th>
<th class="pb-3">Revenue</th>

</tr>

</thead>

<tbody class="divide-y">

@foreach($topProducts as $product)

<tr>

<td class="py-3">

<div class="flex items-center gap-3">

<div class="h-10 w-10 rounded-lg bg-slate-100 overflow-hidden">

@if($product->image)

<img src="{{ asset('storage/'.$product->image) }}"
class="w-full h-full object-cover">

@else

<div class="flex items-center justify-center h-full">

<i data-lucide="package"></i>

</div>

@endif

</div>

<span class="text-sm font-medium">
{{ $product->name }}
</span>

</div>

</td>

<td class="py-3 text-sm">
{{ $product->total_sold }} pcs
</td>

<td class="py-3 text-sm font-bold text-blue-500">
Rp {{ number_format($product->total_revenue,0,',','.') }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>


<!-- Transactions -->
<div class="rounded-2xl bg-white shadow-soft overflow-hidden dark:bg-navy-900">

<div class="p-6 border-b">

<h3 class="text-lg font-bold">
Detail Transaksi
</h3>

</div>

<div class="overflow-x-auto">

<table class="w-full">

<thead class="bg-slate-50">

<tr>

<th class="px-6 py-3 text-left text-xs uppercase">Invoice</th>
<th class="px-6 py-3 text-left text-xs uppercase">Tanggal</th>
<th class="px-6 py-3 text-left text-xs uppercase">Kasir</th>
<th class="px-6 py-3 text-left text-xs uppercase">Customer</th>
<th class="px-6 py-3 text-left text-xs uppercase">Metode</th>
<th class="px-6 py-3 text-right text-xs uppercase">Total</th>

</tr>

</thead>

<tbody class="divide-y">

@foreach($transactions as $trx)

<tr>

<td class="px-6 py-4 font-mono text-blue-500">
{{ $trx->invoice_code }}
</td>

<td class="px-6 py-4 text-sm">
{{ $trx->created_at->format('d/m/Y H:i') }}
</td>

<td class="px-6 py-4 text-sm">
{{ $trx->user->name }}
</td>

<td class="px-6 py-4 text-sm">
{{ $trx->customer->name ?? 'Umum' }}
</td>

<td class="px-6 py-4 text-sm">
{{ ucfirst($trx->payment_method) }}
</td>

<td class="px-6 py-4 text-right font-bold">
Rp {{ number_format($trx->grand_total,0,',','.') }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

<div class="p-6 border-t">

{{ $transactions->links() }}

</div>

</div>


</div>


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded',function(){

lucide.createIcons();

new Chart(document.getElementById('salesChart'),{

type:'line',

data:{
labels:@json($chartData['labels']),
datasets:[
{
label:'Penjualan',
data:@json($chartData['sales']),
borderColor:'#2563EB',
backgroundColor:'rgba(37,99,235,0.1)',
fill:true,
tension:0.4
},
{
label:'Transaksi',
data:@json($chartData['transactions']),
borderColor:'#16A34A',
borderDash:[5,5],
yAxisID:'y1'
}
]
},

options:{
responsive:true,
maintainAspectRatio:false
}

});


new Chart(document.getElementById('paymentChart'),{

type:'doughnut',

data:{
labels:@json($paymentDistribution->pluck('method')),
datasets:[{
data:@json($paymentDistribution->pluck('total')),
backgroundColor:['#2563EB','#16A34A','#F59E0B','#8B5CF6']
}]
},

options:{
responsive:true,
maintainAspectRatio:false,
cutout:'70%'
}

});

});

</script>

@endpush

@endsection