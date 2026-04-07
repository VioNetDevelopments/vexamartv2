<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class ActivityLogHelper
{
    public static function log($action, $description = null, $properties = null)
    {
        return ActivityLog::log($action, $description, $properties);
    }
}