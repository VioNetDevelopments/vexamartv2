<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $invoice_code
 * @property numeric $amount
 * @property string $status
 * @property array<array-key, mixed>|null $qr_data
 * @property string|null $payment_gateway_ref
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment whereInvoiceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment wherePaymentGatewayRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment whereQrData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QrisPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QrisPayment extends Model
{
    protected $fillable = [
        'invoice_code', 'amount', 'status', 'qr_data', 
        'payment_gateway_ref', 'paid_at', 'expired_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'qr_data' => 'array',
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'invoice_code', 'invoice_code');
    }
}