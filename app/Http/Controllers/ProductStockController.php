<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductStockController extends Controller
{
    public function index(Request $req){
        $query = ProductBatch::with('product');

        $filterProductId = $req->get('product_id');
        $filterNoBatch = $req->get('batch_number');
        $filterExpDate = $req->get('exp_date');

        $query->when($filterProductId, function($q) use ($filterProductId){
            $q->where('item_id', $filterProductId);
        })
        ->when($filterNoBatch, function($q) use ($filterNoBatch){
            $q->where('batch_number', $filterNoBatch);
        })
        ->when($filterExpDate, function($q) use ($filterExpDate){
            $q->whereDate('exp_date', $filterExpDate);
        });
        $data = $query->paginate(10);
        $products = Item::all();
        return view('pages.item-stok.index', [
            'title' => 'Data Stok Produk',
            'menu' => 'stok',
            'data' => $data,
            'products' => $products,
        ]);
    }

    public function create(){
        return view('pages.item-stok.create', [
            'title' => 'Adjustment Stok Produk',
            'menu' => 'stok',
        ]);
    }

    public function store(Request $req){
        $validators = Validator::make($req->all(), [
            'item_id' => 'required|exists:items,id',
            'batch_number' => 'required',
            'exp_date' => 'required',
            'stock' => 'required',
            'unit_cost' => 'required'
        ]);

        if ($validators->fails()) {
            return response()->json([
                'status' => false,
                'message' => substr($validators->errors()->first(), 0, 150)
            ]);
        }
        try {
            DB::beginTransaction();
            $item = ProductBatch::updateOrCreate(
                [
                    'batch_number' => $req->batch_number,
                    'item_id' => $req->item_id
                ],
                [
                    'exp_date' => $req->exp_date,
                    'stock' => $req->stock,
                    'unit_cost' => $req->unit_cost,
                ]
            );

            $stokCurrent = $item->product->all_stok;
            $item->product->update([
                'all_stok' => $stokCurrent + $item->stock
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Proses Berhasil',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => substr($th->getMessage(),0,150),
            ]);
        }

    }
}
