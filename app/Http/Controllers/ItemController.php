<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItemController extends Controller
{
    protected $systemSetting;

    public function __construct()
    {
        $this->systemSetting = SystemSetting::first();
    }

    // clean currency format before submit to controller
    private function cleanFormat($val) {
        $value = preg_replace('/[^\d]/', '', $val); //mengambil angka saja 
        return $value;
    }

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
            'systemSetting' => $this->systemSetting,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = ItemCategory::get();
        return view('pages.item.create', [
            'title' => 'add-item',
            'menu' => 'item',
            'data' => $data,
            'systemSetting' => $this->systemSetting,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        DB::beginTransaction();
        try {
            $request['cost'] = $this->cleanFormat($request->cost);
            $request['price'] = $this->cleanFormat($request->price);
            $data = $request->all();
    
            Item::create($data);

            DB::commit();
            return redirect()->route('barang.index')->with('success', 'Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Menyimpan Data: ' . $e->getMessage())->withInput();
            DB::rollBack();
        }
        
    }

    public function storeAndAddToCart(ItemRequest $request){
        DB::beginTransaction();
        try {
            $request['cost'] = $this->cleanFormat($request->cost);
            $request['price'] = $this->cleanFormat($request->price);
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
            'systemSetting' => $this->systemSetting,
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
            'systemSetting' => $this->systemSetting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::find(decrypt($id));
        $request['cost'] = $this->cleanFormat($request->cost);
        $request['price'] = $this->cleanFormat($request->price);
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
