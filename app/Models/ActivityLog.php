<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description', 'ip_address', 'user_agent', 'properties'];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $description = null, $properties = null)
    {
        if (auth()->check()) {
            return self::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'description' => $description,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'properties' => $properties,
            ]);
        }
        return null;
    }
}