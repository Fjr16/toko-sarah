<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Support\Arr;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (session('data_pembelian')) {
            session()->put('data_pembelian', session('data_pembelian'));
        }else{
            session()->put('data_pembelian', []);
        }

        $produks = Item::all();
        $suppliers = Supplier::get();
        $itemCategories = ItemCategory::where('status', 'active')->get();
        return view('pages.pembelian.create', [
            'title' => 'Pembelian',
            'menu' => 'Pembelian',
            'produks' => $produks,
            'suppliers' => $suppliers,
            'itemCategories' => $itemCategories,
        ]);
    }


    private function findItem($id) {
        $data = session()->get('data_pembelian');
        $find = Arr::first($data, function($item) use ($id){
            return $item['id'] === $id;
        });
        return $find;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store($barcode)
    {
        try {
            $dataSession = session()->get('data_pembelian');
            $item = Item::where('code', $barcode)->firstOrFail();
            $findItem = $this->findItem($item->id);
            if ($findItem) {
                // untuk mendapatkan key asli, case misal terdapat array dengan key 0,1,2 ketikda array key 1
                // dihapus maka array 0,2. disini ketika index dicari maka index yang dikembalikan sesuai dengan index 0,2 bukan 0,1
                $index = key(array_filter($dataSession, function($itemSession) use ($item) {
                    return $itemSession['id'] === $item->id;
                }));
                $jumlahItem = $dataSession[$index]['jumlah']+1;
                $dataSession[$index] = [
                    'id' => $item->id,
                    'barcode' => $item->code,
                    'name' => $item->name,
                    'jumlah' => $jumlahItem,
                    'satuan' => $item->small_unit,
                    'harga_satuan' => (int) $item->base_price,
                    'diskon' => 0,
                    'total_harga' => (int) $item->base_price * $jumlahItem,
                ];
                session()->put('data_pembelian', $dataSession);
            }else{
                session()->push('data_pembelian', [
                    'id' => $item->id,
                    'barcode' => $item->code,
                    'name' => $item->name,
                    'jumlah' => 1,
                    'satuan' => $item->small_unit,
                    'harga_satuan' => (int) $item->base_price,
                    'diskon' => 0,
                    'total_harga' => (int) $item->base_price * 1,
                ]);
            }
            session()->flash('success', 'Berhasil Ditambahkan Keranjang');
            return response()->json([
                'status_code' => 200,
                'message' => 'Data Berhasil Ditemukan',
            ]);
        } catch (ModelNotFoundException $mn){
            session()->flash('error', 'Produk Tidak Ditemukan');
            return response()->json([
                'status_code' => 404,
                'message' => 'Produk Tidak Ditemukan',
            ]);
        } catch (QueryException $qe){
            session()->flash('error', 'Terjadi Kesalahan Database');
            return response()->json([
                'status_code' => 500,
                'message' => 'Kesalahan Database',
            ], 500);
        } catch (Exception $e) {
            session()->flash('error', 'Terjadi Kesalahan Sistem');
            return response()->json([
                'status_code' => 500,
                'message' => 'Kesalahan Sistem',
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $qty = $request->jumlah;
            $dataSession = session()->get('data_pembelian');
            $findItem = $this->findItem(decrypt($id));
            if ($findItem) {
                $index = key(array_filter($dataSession, function ($itemSession) use ($findItem){
                    return $itemSession['id'] == $findItem['id'];
                }));
                $dataSession[$index] = [
                    'id' => $findItem['id'],
                    'barcode' => $findItem['barcode'],
                    'name' => $findItem['name'],
                    'jumlah' => $qty,
                    'satuan' => $findItem['satuan'],
                    'harga_satuan' => (int) $findItem['harga_satuan'],
                    'diskon' => 0,
                    'total_harga' => (int) $findItem['harga_satuan'] * $qty,
                ];
                session()->put('data_pembelian', $dataSession);
                session()->flash('success', 'Berhasil memperbarui data');
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Data Berhasil diperbarui',
                ]);
            }else{
                session()->flash('error', 'Data tidak ditemukan');
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Data tidak ditemukan pada keranjang',
                ]);
            }
        } catch (Exception $e) {
            session()->flash('error', 'Kesalahan Sistem');
            return response()->json([
                'status_code' => 500,
                'message' => 'Kesalahan Sistem',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = session()->get('data_pembelian');
        $newData = array_filter($data, function ($item) use ($id){
            return $item['id'] != $id;
        });

        session()->put('data_pembelian', $newData);
        return back()->with('success', 'Berhasil Dihapus');
    }

    public function reset(){
        session()->put('data_pembelian', []);
        return back()->with('success', 'Berhasil Direset');
    }
}
