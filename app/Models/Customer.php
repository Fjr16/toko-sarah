<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'address',
        'subdistrict',
        'city',
        'province',
        'country',
        'postal_code',
        'nik',
        'member_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function($cust){
            $cust->member_code = 'CUST' . now()->format('ymdHis') . $cust->id;
            $cust->save();
        });
    }
}
