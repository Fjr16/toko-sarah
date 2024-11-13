<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'selling_id',
        'item_id',
        'code',
        'jumlah',
        'diskon',
        'pajak',
        'total_harga',
    ];
}
