<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProvider extends Model
{
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'account_name',
        'logo',
        'is_active',
        'sort_order'
    ];
}
