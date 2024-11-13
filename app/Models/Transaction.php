<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'total_kotor',
        'diskon',
        'pajak',
        'total_bersih',
        'status',
    ];
}
