<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = ['product_id', 'user_id', 'type', 'qty', 'reason', 'stock_before', 'stock_after'];
    protected $casts = ['qty' => 'integer', 'stock_before' => 'integer', 'stock_after' => 'integer'];

    public function product() { return $this->belongsTo(Product::class); }
    public function user() { return $this->belongsTo(User::class); }
}