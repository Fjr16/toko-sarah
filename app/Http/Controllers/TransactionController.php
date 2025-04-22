<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{

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
        $itemCategories = ItemCategory::get();
        $systemSetting = SystemSetting::first();
        return view('pages.pembelian.create', [
            'title' => 'Pembelian',
            'menu' => 'Pembelian',
            'produks' => $produks,
            'suppliers' => $suppliers,
            'itemCategories' => $itemCategories,
            'systemSetting' => $systemSetting,
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
    public function store($id)
    {
        try {
            $dataSession = session()->get('data_pembelian');
            $item = Item::findOrFail($id);
            $findItem = $this->findItem($item->id);
            if ($findItem) {
                // untuk mendapatkan key asli, case misal terdapat array dengan key 0,1,2 ketika array key 1
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
                    'harga_satuan' => $item->cost,
                    'margin' => $item->margin,
                    'harga_jual' => $item->price,
                    'stok' => $item->stok,
                    'total_harga' => $item->cost * $jumlahItem,
                ];
                session()->put('data_pembelian', $dataSession);
            }else{
                session()->push('data_pembelian', [
                    'id' => $item->id,
                    'barcode' => $item->code,
                    'name' => $item->name,
                    'jumlah' => 1,
                    'satuan' => $item->small_unit,
                    'harga_satuan' => $item->cost,
                    'margin' => $item->margin,
                    'harga_jual' => $item->price,
                    'stok' => $item->stok,
                    'total_harga' => $item->cost * 1,
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

    private function generateRandomId() {
        $date = now()->format('YmdHis');
        $randomUniqueString = strtoupper(Str::random(6));
        return 'PRC-' . $date . '-' . $randomUniqueString;
    }
    /**
     * Display the specified resource.
     */
    public function saveOnTable(Request $request)
    {
        DB::beginTransaction();
        try {
            $tranId = $this->generateRandomId();
            $dataTran = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'purchase_date' => 'required|date',
                'subtotal' => 'required|numeric|max_digits:10',
                'diskon' => 'required|numeric|max_digits:10',
                'tax' => 'required|numeric|max_digits:10',
                'other_cost' => 'required|numeric|max_digits:10',
                'grand_total' => 'required|numeric|max_digits:10',
                'status' => 'required|in:pending,ordered,completed',
                'payment_method' => 'required',
            ]);
            $dataTran['transaction_code'] = $tranId;
    
            $item = Transaction::create($dataTran);
            $dataSession = session()->get('data_pembelian');
            foreach ($dataSession as $itemSession) {
                $item->transactionDetails()->create([
                    // 'transaction_id' => $item->id,
                    'item_id' => $itemSession['id'],
                    'jumlah' => $itemSession['jumlah'],
                    'satuan' => $itemSession['satuan'],
                    'unit_price' => $itemSession['harga_satuan'],
                    'total' => $itemSession['total_harga'],
                ]);
                $productItem = Item::findOrFail($itemSession['id']);
                $productItem->stok = $productItem->stok + $itemSession['jumlah'];
                $productItem->save();
            }
            DB::commit();

            session()->put('data_pembelian', []);
            return redirect()->route('pembelian.create')->with('success', 'Berhasil menyimpan data');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: '.$e->getMessage());
            DB::rollBack();
        } catch (ValidationException $e) {
            return back()->with('error', 'Gagal menyimpan data: '.$e->getMessage());
            DB::rollBack();
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Gagal menyimpan data: '.$e->getMessage());
            DB::rollBack();
        } catch (QueryException $qe){
            return back()->with('error', 'Terjadi Kesalahan Database:'. $qe->getMessage());
            DB::rollBack();
        }
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
                    'harga_satuan' => $findItem['harga_satuan'],
                    'margin' => $findItem['margin'],
                    'harga_jual' => $findItem['harga_jual'],
                    'total_harga' => $findItem['harga_satuan'] * $qty,
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

    public function updatePriceItem(Request $request, $id){
        DB::beginTransaction();
        try {
            $item = Item::findOrFail(decrypt($id));
            $request['cost'] = $this->cleanFormat($request->cost);
            $request['price'] = $this->cleanFormat($request->price);
            $data = $request->validate([
                'cost' => 'required',
                'margin' => 'required',
                'price' => 'required',
            ]);
            $item->update($data);

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
                    'jumlah' => $findItem['jumlah'],
                    'satuan' => $findItem['satuan'],
                    'harga_satuan' => $item->cost,
                    'margin' => $item->margin,
                    'harga_jual' => $item->price,
                    'total_harga' => $item->cost * $findItem['jumlah'],
                ];
                session()->put('data_pembelian', $dataSession);
            }else{
                DB::rollBack();
                return back()->with('error', 'Data tidak ditemukan Pada Keranjang');
            }
            
            DB::commit();
            return back()->with('success', 'Berhasil memperbarui data');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data, coba lagi : '.$e->getMessage());
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data, coba lagi : '.$e->getMessage());
        }
    }
}
