<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    use HasFactory;

    protected $fillable = [
        'selling_id',
        'user_id',
        // 'member_id',
        // 'nama_pelanggan',
        'total_diskon',
        'total_kotor',
        // 'total_pajak',
        'total_bersih',
        'items',
        'total_item',
        'metode_bayar',
        'jumlah_bayar',
        'kembalian',
        'status',
    ];


    public function sellingDetails() {
        return $this->hasMany(SellingDetail::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
