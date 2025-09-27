<?php

namespace App\Http\Controllers;

use App\DataTables\InventoryMovementDataTable;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InventoryMovementController extends Controller
{
    public function index(InventoryMovementDataTable $dataTable){
        return $dataTable->render('pages.extras.inventory-movement.index',[
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

        // ->when(request()->get('start_at') && request('end_at'), function())


    }
}
