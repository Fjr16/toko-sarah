<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTempDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_temp_id',
        'item_id',
        'product_batch_id',
        'temp_batch_number',
        'exp_date',
        'qty',
        'unit_price',
        'discount',
        'tax',
        'sub_total',
    ];

    public function purchaseTemp(){
        return $this->belongsTo(PurchaseTemp::class);
    }
    public function item(){
        return $this->belongsTo(Item::class);
    }
    public function productBatch(){
        return $this->belongsTo(ProductBatch::class);
    }
}
