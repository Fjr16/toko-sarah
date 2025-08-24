<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'company_name',
        'type',
        'contact_person',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'country',
        'postal_code',
        'tax_number',
        'bank_account',
        'bank_number',
        'status',
    ];

    protected static function booted()
    {
     static::creating(function ($supplier) {
        $id = (static::max('id') ?? 0) + 1;

        // SUP-001, SUP-012, SUP-123
        $supplier->code = 'SUP-' . str_pad($id, 3, '0', STR_PAD_LEFT);
    });  
    }
}
