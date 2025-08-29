<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_logo',
        'company_email',
        'company_address',
        'company_phone',
        'currency_code',
        'currency_symbol',
        'currency_position_default',
        'decimal_separator',
        'thousand_separator',
        'notification_email',
        'language',
    ];
}
