<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InventoryMovementController extends Controller
{
    public function index(){
        return view('pages.extras.inventory-movement.index', [
            'title' => 'Inventory Movement',
            'menu' => 'extras'
        ]);
    }

    public function getTable(){
        return response()->json([
            'draw' => 0,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
        ]);
        $query = InventoryMovement::query()
        ->when(request()->get('product_id'), function($prodId, $q){
            $q->where('item_id', $prodId);
        })
        ->when(request()->get('batch_number'), function($batchNo, $q){
            $q->where('product_batch_id', $batchNo);
        });
        // ->when(request()->get('start_at') && request('end_at'), function())

        return DataTables::of($query)
        ->rawColumns()
        ->make(true);
    }
}
