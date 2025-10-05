<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    protected static function booted()
    {
        static::creating(function($transaction){
            $today = date('Y-m-d');

            $lastInv = static::whereDate('created_at', $today)
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

            $lastNumber = 0;
            if($lastInv){
                $lastNumber = (int) substr($lastInv->invoice_number, -3); 
            }
            $nextNumber = $lastNumber + 1;
            $user_id = str_pad(Auth::user()->id, 2, 0, STR_PAD_LEFT);
            $transaction->invoice_number = 'INV-PC/'.$user_id.'/'.date('Ymd').'/'.str_pad($nextNumber, 3, STR_PAD_LEFT);
        });
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
