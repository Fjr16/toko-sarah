<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


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
}
