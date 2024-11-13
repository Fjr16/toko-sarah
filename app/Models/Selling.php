<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_id',
        'total_diskon',
        'total_kotor',
        'total_pajak',
        'total_bersih',
        'status',
    ];
}
