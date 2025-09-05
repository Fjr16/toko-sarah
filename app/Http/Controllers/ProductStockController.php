<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ProductBatch;
use Illuminate\Http\Request;

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
}
