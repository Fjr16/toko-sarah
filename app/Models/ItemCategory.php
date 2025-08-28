<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function($prodCategory){
            $id = (static::max('id') ?? 0) + 1;

            $prodCategory->code = 'PRC-' . str_pad($id, 3, '0', STR_PAD_LEFT);
        });
    }

    public function items(){
        return $this->hasMany(Item::class);
    }
}
