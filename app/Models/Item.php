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
        'big_unit',
        'medium_to_small',
        'big_to_medium',
        'default_cost',
        'margin',
        'default_price',
        'all_stok',
        'stok_alert',
        'image',
        'description',
        'status',
    ];

    public function itemCategory() {
        return $this->belongsTo(ItemCategory::class);
    }
    public function productBatchs(){
        return $this->hasMany(ProductBatch::class);
    }
}
