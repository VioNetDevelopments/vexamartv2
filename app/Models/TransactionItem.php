<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $transaction_id
 * @property int $product_id
 * @property int $qty
 * @property numeric $price
 * @property numeric $subtotal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Transaction|null $transaction
 * @method static \Database\Factories\TransactionItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransactionItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'product_id', 'qty', 'price', 'subtotal'];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'qty' => 'integer',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}