<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Selling extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'invoice_number',
        'sale_date',
        'total_amount',
        'payment_method',
        'amount_paid',
        'change_due', //kembalian
        'sale_status',
        'note',
        'additional_cost_name',
        'additional_cost',
    ];


    protected static function booted(){
        static::creating(function($sale){
            DB::transaction(function() use ($sale){
                $today = date('Y-m-d');

                $lastInv = static::whereDate('created_at', $today)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

                $lastNumber = 0;
                if ($lastInv) {
                    $lastNumber = (int) substr($lastInv->invoice_number, -3);
                }
                $nextNumber = $lastNumber + 1;
                $user_id = str_pad(Auth::user()->id,2,'0',STR_PAD_LEFT);

                $sale->invoice_number = 'INV-SL/' . $user_id . '/' . date('Ymd') . '/' . str_pad($nextNumber, 3, STR_PAD_LEFT);
            });
        });
    }

    public function sellingDetails() {
        return $this->hasMany(SellingDetail::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
