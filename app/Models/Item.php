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
        'medium_to_small',
        'big_unit',
        'big_to_medium',
        'base_price',
        'stok',
        'status',
    ];

    public function itemCategory() {
        return $this->belongsTo(ItemCategory::class);
    }
}
