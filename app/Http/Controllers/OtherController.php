<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Item;
use App\Models\ProductBatch;
use App\Models\PurchaseTempDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Facades\DataTables;

class OtherController extends Controller
{
    public function searchProduct(Request $request)
    {
        $search = $request->get('search');
        $products = Item::where('name', 'LIKE', "%$search%")
            ->orWhere('code', 'LIKE', "%$search%")
            ->get();

        return response()->json($products);
    }

    public function searchProductByCode($code)
    {
        try {
            $item = Item::where('id', $code)->first();
            return response()->json($item);
        } catch (\Exception $e) {
            return response()->json('error', $e->getMessage());
        } catch (ModelNotFoundException $e){
            return response()->json('error', $e->getMessage());
        }
    }

    public function showDetailProductById($productId){
        try {
            $item = Item::where('id', $productId)->first();

            return response()->json([
                'status' => true,
                'message'=> 'success',
                'data'=> $item
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => substr($e->getMessage(), 0,150),
                'data'=> null
            ]);
        }
    }

    public function getDataBatch(){
        $productId = request()->get('product_id');

        $data = ProductBatch::query()
        ->where('item_id', $productId);

        return DataTables::of($data)
        ->addColumn('action', function($row){
            if ($row) {
                return '<button class="btn btn-sm btn-primary" type="button" onclick="useBatch('.$row->id.')">Gunakan</button>';
            }
        })
        ->editColumn('exp_date', function($row){
            if ($row && $row->exp_date) {
                return Carbon::parse($row->exp_date)->format('d F Y');
            }
        })
        ->editColumn('unit_cost', function($row){
            if ($row && $row->unit_cost) {
                return 'Rp. ' . number_format($row->unit_cost);
            }
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function getItemBatch($batch_id) {
        try {
            $item = ProductBatch::where('id',$batch_id)->first();
            return response()->json([
                'status' => (bool) $item,
                'message' => $item ? 'success' : 'No batch Tidak dikenali',
                'data' => $item
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => substr($th->getMessage(),0,150),
                'data' => null
            ]);
        }
    }

    public function getBatchSelect() {
        $keyword = request()->get('keyword');
        $productId = request()->get('product_id');

        $dataBatch = ProductBatch::query()
        ->with(['product:id,default_price,margin'])
        ->where('item_id', $productId)
        ->when($keyword,function ($q, $key) {
            $q->where('batch_number', 'LIKE', "%{$key}%");
        })
        ->limit(10)
        ->get(['item_id','batch_number', 'exp_date', 'unit_cost', 'id']);

        $results = $dataBatch->map(function($item){
            return [
                'id' => $item->id,
                'text' => $item->batch_number,
                'exp_date' => $item->exp_date,
                'cost' => number_format($item->unit_cost,0,null,''),
            ];
        });

        return response()->json($results);
    }

    public function getTempDetailById($tempId){
        $tempDetail = PurchaseTempDetail::query()
        ->leftJoin('items as product', 'purchase_temp_details.item_id', '=', 'product.id')
        ->where('purchase_temp_details.id', $tempId)
        ->select(
            'purchase_temp_details.item_id',
            'purchase_temp_details.unit_price',
            'purchase_temp_details.qty',
            'purchase_temp_details.discount',
            'purchase_temp_details.tax',
            'product.small_unit'
        )
        ->first();

        $tempDetail['unit_price'] = number_format($tempDetail['unit_price'],0,null,'');
        $tempDetail['discount'] = number_format($tempDetail['discount'],0,null,'');
        $tempDetail['tax'] = number_format($tempDetail['tax'],0,null,'');

        if (!$tempDetail) {
            return response()->json([
                'status' => false,
                'data' => []
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $tempDetail
        ]);
    }
}
