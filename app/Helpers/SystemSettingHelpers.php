<?php

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    function setting($column, $default = null){
        $settings = Cache::rememberForever('system_settings', function(){
            return SystemSetting::first();
        });

        return $settings?->{$column} ?? $default;
    }
}