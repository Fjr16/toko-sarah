<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'supplier_id',
        'purchase_date',
        'subtotal',
        'diskon',
        'tax',
        'other_cost',
        'grand_total',
        'status',
        'payment_method',
    ];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
