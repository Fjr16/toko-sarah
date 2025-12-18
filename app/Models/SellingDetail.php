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
        'product_batch_id',
        'qty',
        'unit',
        'price',
        'sub_total',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }
    public function productBatch() {
        return $this->belongsTo(ProductBatch::class);
    }
    public function selling() {
        return $this->belongsTo(Selling::class);
    }
}
