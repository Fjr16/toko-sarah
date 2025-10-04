<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'invoice_number',
        'purchase_date',
        'subtotal',
        'diskon',
        'tax',
        'other_cost',
        'grand_total',
        'purchase_status',
        'notes',
    ];

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
