<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTemp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'subtotal',
        'diskon',
        'tax',
        'other_cost',
        'grand_total',
        // 'temp_status',
    ];

    public function purchaseTempDetails(){
        return $this->hasMany(PurchaseTempDetail::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
}
