<?php

namespace App\Helpers;

class CustomHelpers {
    public static function cleanCurrency($val){
        if (empty($val)) {
            return null;
        }
        $cleaned = preg_replace('/[^d]/', '', $val);
        return $cleaned;
    }
}