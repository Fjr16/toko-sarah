<?php

namespace App\Http\Controllers;

use App\DataTables\PurchaseTempDataTable;
use App\Helpers\CustomHelpers;
use Exception;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\ItemCategory;
use App\Models\ProductBatch;
use App\Models\PurchaseTemp;
use App\Models\PurchaseTempDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TransactionController extends Controller
{
    public function create(PurchaseTempDataTable $dataTable)
    {
        $suppliers = Supplier::get();
        $itemCategories = ItemCategory::get();
        return $dataTable->render('pages.pembelian.create', [
            'title' => 'Pembelian',
            'menu' => 'Pembelian',
            'suppliers' => $suppliers,
            'itemCategories' => $itemCategories,
        ]);
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

    public function updateItem(Request $request, string $id)
    {
        $validators = Validator::make($request->all(), [
            'unit_price' => 'required',
            'qty' => 'required|integer|min:1',
            'discount' => 'required',
            'tax' => 'required'
        ]);

        if ($validators->fails()) {
            return response()->json([
                'status' => false,
                'message' => substr($validators->errors()->first(),0,150)
            ]);
        }
        try {
            $item = PurchaseTempDetail::findOrFail($id);
            $cleanPrice = CustomHelpers::cleanCurrency($request->unit_price) ?? 0;
            $cleanDisc = CustomHelpers::cleanCurrency($request->discount) ?? 0;
            $cleanTax = CustomHelpers::cleanCurrency($request->tax) ?? 0;
            $subTotal = ($cleanPrice * $request->qty) - $cleanDisc + $cleanTax;
            if ($subTotal < 0) {
                throw new Exception("Subtotal tidak valid, tidak boleh kecil dari 0");
            }
            $item->update([
                'unit_price' => $cleanPrice,
                'qty' => $request->qty,
                'discount' => $cleanDisc,
                'tax' => $cleanTax,
                'sub_total' => $subTotal,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil diperbarui',
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => substr($th->getMessage(), 0, 150),
            ]);
        }
    }

    public function destroyItem(string $id)
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

    public function resetCart(){
        try {
            DB::transaction(function(){
                $item = PurchaseTemp::first();
                PurchaseTempDetail::query()->delete();
                if ($item) {
                    $item->delete();
                }
            });
            return response()->json([
                'status' => true,
                'message' => 'Keranjang berhasil dikosongkan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengosongkan keranjang'
            ]);
        }
    }

    public function finishPurchase(Request $request)
    {
        DB::beginTransaction();
        try {
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

            return response()->json([
                'status' => true,
                'message' => 'Pembelian berhasil disimpan'
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => substr($th->getMessage(),0,150)
            ]);
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
