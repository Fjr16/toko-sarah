<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\ItemCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Item::all();
        return view('pages.item.index', [
            'title' => 'Item',
            'menu' => 'item',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = ItemCategory::where('status', 'active')->get();
        return view('pages.item.create', [
            'title' => 'Item',
            'menu' => 'item',
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        $data = $request->all();

        Item::create($data);

        return redirect()->route('barang.index')->with('success', 'Berhasil Ditambahkan');
    }

    public function storeAndAddToCart(ItemRequest $request){
        DB::beginTransaction();
        try {
            $data = $request->all();
            if ($item = Item::create($data)) {
                $req = Request::create(route('pembelian.store', $item->code), 'GET');
                $res = app()->handle($req);
                $message = json_decode($res->getContent(), true)['message'];
                if ($res->getStatusCode() === 200) {
                    DB::commit();
                    return redirect()->route('pembelian.create')->with('success', $message);
                }else{
                    DB::rollBack();
                    return redirect()->route('pembelian.create')->with('error', $message);
                }
            }else{
                DB::rollBack();
                return back()->with('error', 'Gagal Menyimpan Data, coba lagi');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status_code' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = ItemCategory::all();
        $item = Item::find(decrypt($id));
        return view('pages.item.edit', [
            'title' => 'Item',
            'menu' => 'item',
            'item' => $item,
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::find(decrypt($id));
        $data = $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required|string|unique:items,name,' . $item->id,
            'small_unit' => 'required|string',
            'medium_unit' => 'nullable|string',
            'medium_to_small' => 'required_with:medium_unit',
            'big_unit' => 'nullable|string',
            'big_to_medium' => 'required_with:big_unit',
            'base_price' => 'nullable',
            'stok' => 'nullable',
        ]);

        $item->update($data);

        return redirect()->route('barang.index')->with('success', 'Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $this->validate($request, [
            'status' => 'in:active,deleted',
        ]);
        $item = Item::find(decrypt($id));
        $item->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Berhasil Diperbarui');
    }
}
