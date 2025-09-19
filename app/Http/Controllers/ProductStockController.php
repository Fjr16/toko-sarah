<?php

namespace App\Http\Controllers;

use App\Enums\InventoryFlag;
use App\Helpers\CustomHelpers;
use App\Models\Item;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $selectedProduct = $filterProductId ? Item::where('id', $filterProductId)->first() : null;
        return view('pages.item-stok.index', [
            'title' => 'Data Stok Produk',
            'menu' => 'stok',
            'data' => $data,
            'selectedProduct' => $selectedProduct,
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
            $helpers = new CustomHelpers;

            $item = ProductBatch::where('batch_number', $req->batch_number)->where('item_id', $req->item_id)->first()
                    ? ProductBatch::where('batch_number', $req->batch_number)->where('item_id', $req->item_id)->first()
                    : new ProductBatch;


            $item->batch_number = $req->batch_number;
            $item->item_id = $req->item_id;
            $item->exp_date = $req->exp_date;
            $item->stock = $item->stock
                            ? $item->stock + $req->stock
                            : $req->stock;
            $item->unit_cost = $helpers->cleanCurrency($req->unit_cost);
            $item->save();

            $totalStokCurrent = $item->product->all_stok;
            $item->product->update([
                'all_stok' => $totalStokCurrent + $item->stock
            ]);

            // pencatatan inventory movements
            $dataToStore = [
                'user_id' => Auth::user()->id,
                'item_id' => $item->product->id,
                'product_batch_id' => $item->id,
                'flag' => InventoryFlag::in->value,
                'qty' => $item->stock,
                'unit' => $item->product->small_unit,
                'note' => '-'
            ];
            $res = $helpers->logInventoryMovements($item, $dataToStore);
            if ($res['status'] == false) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => substr($res['message'], 0, 150),
                ]);
            }

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
