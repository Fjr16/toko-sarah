<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'item_id',
        'product_batch_id',
        'jumlah',
        'satuan',
        'unit_price',
        'discount',
        'tax',
        'sub_total',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
