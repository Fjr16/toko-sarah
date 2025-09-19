<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'batch_number',
        'exp_date',
        'stock',
        'unit_cost',
    ];

    protected $cast = [
        'exp_date' => 'date',
    ];

    public function product(){
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    // untuk pencatatan log adjustment stok
    public function inventoryMovements(){
        return $this->morphMany(InventoryMovement::class, 'reference');
    }
}
