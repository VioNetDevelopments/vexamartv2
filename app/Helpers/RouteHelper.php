<?php

namespace App\Helpers;

class RouteHelper
{
    public static function isActive($patterns)
    {
        if (!is_array($patterns)) $patterns = [$patterns];
        
        foreach ($patterns as $pattern) {
            if (request()->is($pattern) || request()->routeIs($pattern)) {
                return true;
            }
        }
        return false;
    }
}