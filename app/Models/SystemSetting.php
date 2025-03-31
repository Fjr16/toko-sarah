<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'currency_position_default',
        'company_name',
        'company_email',
        'company_phone',
        'company_address',
        'notification_email',
    ];
}
