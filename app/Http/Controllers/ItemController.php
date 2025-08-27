<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Helpers\CustomHelpers;
use Exception;
use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Item::all();
        $trashed = Item::onlyTrashed()->get();
        return view('pages.item.index', [
            'title' => 'Produk',
            'menu' => 'item',
            'data' => $data,
            'trashed' => $trashed,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = ItemCategory::get();
        return view('pages.item.create', [
            'title' => 'Tambah Produk',
            'menu' => 'item',
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        $idToUpdate = $request->item_id ?? null;
        $validators = Validator::make($request->all(), [
            'code' => ['required', Rule::unique('items', 'code')->ignore($idToUpdate)],
        ]);
        if($validators->fails()){
            return response()->json([
                'status' => false,
                'message' => $validators->errors()->first()
            ]);
        }

        try {
            DB::beginTransaction();
            $item = $idToUpdate ? Item::findOrFail($idToUpdate) : new Item();
            $request['cost'] = CustomHelpers::cleanCurrency($request->cost);
            $request['price'] = CustomHelpers::cleanCurrency($request->price);
            $item->item_category_id = $request->item_category_id;
            $item->code = $request->code;
            $item->name = $request->name;
            $item->small_unit = $request->small_unit;
            $item->medium_unit = $request->medium_unit ?? null;
            $item->big_unit = $request->big_unit ?? null;
            $item->medium_to_small = $request->medium_to_small ?? null;
            $item->big_to_medium = $request->big_to_medium ?? null;
            $item->default_cost = $request->cost;
            $item->margin = $request->margin;
            $item->default_price = $request->price;
            $item->all_stok = $request->stok;
            $item->stok_alert = $request->stok_alert;
            if ($request->hasFile('image')) {
                if($idToUpdate && $item->image){
                    Storage::disk('public')->delete($item->image);
                }
                $item->image = $request->file('image')->store('product', 'public');
            }
            $item->description = $request->description ?? null;
            if (!$idToUpdate) {
                $item->status = Status::active;
            }
            $item->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Proses Berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan: ' . $th->getMessage()
            ]);
            DB::rollBack();
        }

    }

    public function storeAndAddToCart(ItemRequest $request){
        DB::beginTransaction();
        try {
            $request['cost'] = CustomHelpers::cleanCurrency($request->cost);
            $request['price'] = CustomHelpers::cleanCurrency($request->price);
            $data = $request->all();

            if ($item = Item::create($data)) {
                $req = Request::create(route('pembelian.store', $item->id), 'GET');
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
        $item = Item::find(decrypt($id));
        return view('pages.item.show', [
            'title' => 'Produk',
            'menu' => 'item',
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = ItemCategory::all();
        $item = Item::find(decrypt($id));
        return view('pages.item.edit', [
            'title' => 'Produk',
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
        $request['cost'] = CustomHelpers::cleanCurrency($request->cost);
        $request['price'] = CustomHelpers::cleanCurrency($request->price);
        $data = $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required|string|unique:items,name,' . $item->id,
            'small_unit' => 'required|string',
            'medium_unit' => 'nullable|string',
            'medium_to_small' => 'required_with:medium_unit',
            'big_unit' => 'nullable|string',
            'big_to_medium' => 'required_with:big_unit',
            'cost' => 'required',
            'margin' => 'required',
            'price' => 'required',
            'stok' => 'required|integer',
            'stok_alert' => 'required|integer',
            // 'tax' => 'required|integer',
            // 'tax_type' => 'required|in:exclusive,inclusive,none',
            'note' => 'nullable|string',
        ]);

        $item->update($data);

        return redirect()->route('barang.index')->with('success', 'Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = Item::findOrFail(decrypt($id));
            $item->delete();

            return back()->with('success', 'Berhasil Menghapus Data');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal Menghapus Data : ' . $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Data tidak ditemukan : ' . $e->getMessage());
        }
    }

    public function restore(string $id)
    {
        try {
            $item = Item::withTrashed()->findOrFail(decrypt($id));
            $item->restore();

            return back()->with('success', 'Berhasil Memulihkan Data');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Memulihkan Data : ' . $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Data tidak ditemukan : ' . $e->getMessage());
        }
    }
}
