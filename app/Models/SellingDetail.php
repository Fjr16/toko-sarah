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
        'product_barcode',
        'product_name',
        'product_jumlah',
        'product_satuan',
        'product_harga',
        'product_sub_total',
        'product_diskon',
        // 'product_pajak',
    ];

    public function item() {
        return $this->belongsTo(Item::class);
    }
    public function selling() {
        return $this->belongsTo(Selling::class);
    }
}
