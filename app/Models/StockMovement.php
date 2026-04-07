<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property string $type
 * @property int $qty
 * @property string|null $reason
 * @property int $stock_before
 * @property int $stock_after
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereStockAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereStockBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockMovement whereUserId($value)
 * @mixin \Eloquent
 */
class StockMovement extends Model
{
    protected $fillable = ['product_id', 'user_id', 'type', 'qty', 'reason', 'stock_before', 'stock_after'];
    protected $casts = ['qty' => 'integer', 'stock_before' => 'integer', 'stock_after' => 'integer'];

    public function product() { return $this->belongsTo(Product::class); }
    public function user() { return $this->belongsTo(User::class); }
}