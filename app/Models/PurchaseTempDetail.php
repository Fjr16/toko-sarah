<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTempDetail extends Model
{
    use HasFactory;

    public function purchaseTemp(){
        return $this->belongsTo(PurchaseTemp::class);
    }
}
