<?php

namespace App\Models;

use App\Models\ItemCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_category_id',
        'code',
        'name',
        'small_unit',
        'medium_unit',
        'medium_to_small',
        'big_unit',
        'big_to_medium',
        'cost',
        'margin',
        'price',
        'stok',
        'stok_alert',
        // 'tax',
        // 'tax_type',
        'note',
    ];

    public function itemCategory() {
        return $this->belongsTo(ItemCategory::class);
    }
}
