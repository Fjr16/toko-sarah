<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_category_id',
        'code',
        'name',
        'small_unit',
        'medium_unit',
        'big_unit',
        'base_price',
        'stok',
    ];
}
