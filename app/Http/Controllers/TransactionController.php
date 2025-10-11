<?php

namespace App\Http\Controllers;

use App\DataTables\PurchaseTempDataTable;
use App\Helpers\CustomHelpers;
use Exception;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\ItemCategory;
use App\Models\ProductBatch;
use App\Models\PurchaseTemp;
use App\Models\PurchaseTempDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

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
    public function create(PurchaseTempDataTable $dataTable)
    {
        $produks = Item::all();
        $suppliers = Supplier::get();
        $itemCategories = ItemCategory::get();
        return $dataTable->render('pages.pembelian.create', [
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

    public function storeItem(Request $req){
        $validators = Validator::make($req->all(), [
            'item_id' => 'required|exists:items,id',
            'product_batch_id' => 'required|array',
            'product_batch_id.*' => 'nullable|exists:product_batches,id|required_without:batch_number.*',
            'batch_number' => 'required|array',
            'batch_number.*' => 'nullable|required_without:product_batch_id.*',
            'exp_date' => 'required|array',
            'exp_date.*' => 'required|date|after:today',
            'qty' => 'required|array',
            'qty.*' => 'required|integer|min:1',
            'unit_price'=>'required|array',
            'unit_price.*'=>'required',
            'discount'=>'required|array',
            'discount.*'=>'nullable',
            'tax'=>'required|array',
            'tax.*'=>'nullable',
        ],[
            'product_batch_id.*.required_without' => 'Pilih No batch produk yang ada atau buat batch baru',
            'batch_number.*.required_without' => 'Isi nomor batch jika tidak memilih batch yang ada',
        ]);
        if ($validators->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validators->errors()->first(),
            ]);
        }


        DB::beginTransaction();
        try {
            $purchaseTemp = PurchaseTemp::firstOrCreate([
                'user_id' => Auth::user()->id,
            ]);

            $data = $req->all();
            $itemId = $data['item_id'];
            foreach ($data['product_batch_id'] as $key => $batchId) {
                $qty = (int) $data['qty'][$key] ?? 0;
                $unitPrice = CustomHelpers::cleanCurrency($data['unit_price'][$key]) ?? 0;
                $disc = CustomHelpers::cleanCurrency($data['discount'][$key]) ?? 0;
                $tax = CustomHelpers::cleanCurrency($data['tax'][$key]) ?? 0;
                $subTotal = ($qty * $unitPrice) + $tax - $disc;
                $productBatchNumber = !empty($batchId) ? ProductBatch::whereKey($batchId)->value('batch_number') : null;
                if (!empty($batchId) && !$productBatchNumber) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Mohon pilih atau tambahkan nomor batch baru'
                    ]);
                }

                if($this->checkExistBatch($itemId, $batchId, $data['batch_number'][$key])){
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Nomor batch ' . (empty($batchId) ? $data['batch_number'][$key] : $productBatchNumber) . ' telah ditambahkan ke keranjang',
                    ]);
                }

                $purchaseDetail = new PurchaseTempDetail;
                $purchaseDetail->purchase_temp_id = $purchaseTemp->id;
                $purchaseDetail->item_id = $itemId;
                $purchaseDetail->product_batch_id = $batchId ?? null;
                $purchaseDetail->temp_batch_number = empty($batchId) ? $data['batch_number'][$key] : $productBatchNumber;
                $purchaseDetail->exp_date = Carbon::parse($data['exp_date'][$key])->format('Y-m-d');
                $purchaseDetail->qty = $qty;
                $purchaseDetail->unit_price = $unitPrice;
                $purchaseDetail->discount = $disc;
                $purchaseDetail->tax = $tax;
                $purchaseDetail->sub_total = $subTotal;
                $purchaseDetail->save();
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil tambah ke keranjang'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
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
                $productItem->all_stok = $productItem->all_stok + $itemSession['jumlah'];
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
        try {
            $item = PurchaseTempDetail::findOrFail($id);
            $item->delete();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil Hapus Produk'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => substr($th->getMessage(),0,150)
            ]);
        }
    }

    public function reset(){
        session()->put('data_pembelian', []);
        return back()->with('success', 'Berhasil Direset');
    }

    public function updatePriceItem(Request $request, $id){
        DB::beginTransaction();
        try {
            $item = Item::findOrFail(decrypt($id));
            $request['cost'] = CustomHelpers::cleanCurrency($request->cost);
            $request['price'] = CustomHelpers::cleanCurrency($request->price);
            $data = $request->validate([
                'cost' => 'required',
                'margin' => 'required',
                'price' => 'required',
            ]);
            $item->default_cost = $request->cost;
            $item->margin = $request->margin;
            $item->default_price = $request->price;
            $item->save();

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
                    'harga_satuan' => $item->default_cost,
                    'margin' => $item->margin,
                    'harga_jual' => $item->default_price,
                    'total_harga' => $item->default_cost * $findItem['jumlah'],
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


    private function checkExistBatch($itemId, $batchId = null, $batchNumber = null){
        if ($batchId) {
            return PurchaseTempDetail::where('item_id', $itemId)->where('product_batch_id', $batchId)->exists();
        }else if($batchNumber){
            return PurchaseTempDetail::where('item_id', $itemId)->where('temp_batch_number', $batchNumber)->exists();
        }
        return false;
    }
}
