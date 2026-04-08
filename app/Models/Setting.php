<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];
    
    public $timestamps = true;

    /**
     * Get setting value with caching
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value and clear cache
     */
    public static function set($key, $value)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            self::create(['key' => $key, 'value' => $value]);
        }
        
        // Clear caches
        Cache::forget("setting_{$key}");
        Cache::forget('settings_all');
        
        return true;
    }
    
    /**
     * Get all settings as array
     */
    public static function allAsArray()
    {
        return Cache::remember('settings_all', 3600, function () {
            return self::all()->pluck('value', 'key')->toArray();
        });
    }
    
    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::forget('settings_all');
        self::all()->each(function ($setting) {
            Cache::forget("setting_{$setting->key}");
        });
    }
}