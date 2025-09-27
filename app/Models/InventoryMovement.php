<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'product_batch_id',
        'flag',
        'qty',
        'unit',
        // diisi otomatis karena menggunkan relasi polyMorph
        // 'reference_type',
        // 'reference_id',
        'note',
    ];

    public function reference(){
        return $this->morphTo();
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function item(){
        return $this->belongsTo(Item::class);
    }
    public function productBatch(){
        return $this->belongsTo(ProductBatch::class);
    }
}
