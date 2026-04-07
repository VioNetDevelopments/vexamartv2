<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $dateFrom;
    protected $dateTo;

    public function __construct($dateFrom, $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        return Transaction::with(['user', 'customer', 'items.product'])
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->where('payment_status', 'paid')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Tanggal',
            'Kasir',
            'Pelanggan',
            'Total Item',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Grand Total',
            'Metode Bayar',
            'Status',
            'Kembalian',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->invoice_code,
            $transaction->created_at->format('d/m/Y H:i'),
            $transaction->user->name,
            $transaction->customer->name ?? 'Umum',
            $transaction->total_item,
            $transaction->subtotal,
            $transaction->discount,
            $transaction->tax,
            $transaction->grand_total,
            ucfirst($transaction->payment_method),
            ucfirst($transaction->payment_status),
            $transaction->change_amount,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['color' => ['rgb' => '1E293B'], 'fillType' => 'solid']],
        ];
    }
}