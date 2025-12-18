<?php

namespace App\Http\Controllers;

use App\Enums\InventoryFlag;
use App\Enums\PaymentMethod;
use App\Enums\PurchaseStatus;
use App\Helpers\CustomHelpers;
use App\Models\Customer;
use App\Models\Item;
use App\Models\ProductBatch;
use App\Models\Selling;
use App\Models\SellingDetail;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    public function getDataTable(){
        if (session('data')) {
            session()->put('data', session('data'));
        }else{
            session()->put('data', []);
        }
        $data = session('data');

        $subTotalSum = array_sum(Arr::pluck($data, 'subtotal'));
        $qtySum = array_sum(Arr::pluck($data, 'jumlah'));
        $itemsCount = count($data);

        return DataTables::of($data)
        ->addColumn('action', function($row){
            $url = route('sales/destroy/on.cart', $row['id']);
            $btnDelete = '<button type="button" class="btn btn-sm btn-danger btn-icon me-2" data-url="'.$url.'" onclick="deleteProdOnCart(this)"><i class="bi bi-trash"></i></button>';
            $btnEdit = '<button type="button" class="btn btn-sm btn-warning btn-icon" onclick="editOnCart(this)" id="btn_edit_'.$row['id'].'"><i class="bi bi-pencil"></i></button>';
            $btnUpdate = '<button type="button" class="btn btn-sm btn-success btn-icon" onclick="updateOnCart(this)" id="btn_update_'.$row['id'].'" hidden><i class="bi bi-check-circle"></i></button>';
            return $btnDelete . $btnEdit . $btnUpdate;
        })
        ->editColumn('harga', function($row){
            return CustomHelpers::formatterRupiah($row['harga']);
        })
        ->editColumn('subtotal', function($row){
            return CustomHelpers::formatterRupiah($row['subtotal']);
        })
        ->with([
            'summary' => [
                'subTotalSum' => CustomHelpers::formatterRupiah($subTotalSum),
                'qtySum' => $qtySum,
                'itemsCount' => $itemsCount
            ],
        ])
        ->toJson();
    }

    public function create()
    {
        if (session('data')) {
            session()->put('data', session('data'));
        }else{
            session()->put('data', []);
        }

        $produks = Item::all();
        $paymentMethods = PaymentMethod::cases();
        $saleStatus = PurchaseStatus::cases();
        return view('pages.sales.create', [
            'title' => 'Penjualan',
            'menu' => 'Penjualan',
            'produks' => $produks,
            'paymentMethods' => $paymentMethods,
            'saleStatus' => $saleStatus,
        ]);
    }

    public function addToCart(Request $req){
        $validators = Validator::make($req->all(), [
            'batch_id' => 'required|exists:product_batches,id',
            'qty' => 'required|numeric|min:1'
        ]);
        if ($validators->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validators->errors()->first()
            ]);
        }

        try {
            $dataSession = session()->get('data');
            $item = ProductBatch::where('id', $req->batch_id)->firstOrFail();
            $findItem = $this->findItem($item->id);
            if ($findItem) {
                // untuk mendapatkan key asli, case misal terdapat array dengan key 0,1,2 ketikda array key 1
                // dihapus maka array 0,2. disini ketika index dicari maka index yang dikembalikan sesuai dengan index 0,2 bukan 0,1
                $index = key(array_filter($dataSession, function($itemSession) use ($item) {
                    return $itemSession['id'] === $item->id;
                }));
                $jumlahItem = $dataSession[$index]['jumlah']+$req->qty;
                $dataSession[$index] = [
                    'id' => $item->id,
                    'product_name' => $item->product->name,
                    'product_code' => $item->product->code,
                    'batch_number' => $item->batch_number,
                    'jumlah' => $jumlahItem,
                    'satuan' => $item->product->small_unit,
                    'harga' => (int) $item->product->default_price,
                    'subtotal' => (int) $item->product->default_price * $jumlahItem,
                ];
                session()->put('data', $dataSession);
            }else{
                session()->push('data', [
                    'id' => $item->id,
                    'product_name' => $item->product->name,
                    'product_code' => $item->product->code,
                    'batch_number' => $item->batch_number,
                    'jumlah' => $req->qty,
                    'satuan' => $item->product->small_unit,
                    'harga' => (int) $item->product->default_price,
                    'subtotal' => (int) $item->product->default_price * $req->qty,
                ]);
            }
            // session()->flash('success', 'Berhasil Ditambahkan Keranjang');
            return response()->json([
                'status' => true,
                'message' => 'Berhasil ditambahkan',
            ]);
        } catch (\Throwable $th){
            // session()->flash('error', 'Produk Tidak Ditemukan');
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function destroyOnCart($id){
        try {
            $item = ProductBatch::findOrFail($id);
            $dataSession = session('data', []);
            $key = array_search($item->id, array_column($dataSession, 'id'), true);

            unset($dataSession[$key]);
            session(['data' => array_values($dataSession)]);
            return response()->json([
                'status' => true,
                'message' => 'Berhasil dihapus pada keranjang',
            ]);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $validators = Validator::make($req->all(), [
            'customer_id' => 'nullable',
            'payment_method' => ['required', new Enum(PaymentMethod::class)],
            'total_amount' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0|gte:total_amount',
            'change_due' => 'required|numeric|min:0',
            'sale_status' => 'required|string|max:50',
            'note' => 'nullable',
            'additional_cost_name' => 'nullable|string|max:255',
            'additional_cost' => 'nullable|numeric|min:0',
            'sale_date' => 'required|date_format:Y-m-d',
        ]);

        if($validators->fails()){
            return response()->json([
                'status' => false,
                'message' => $validators->errors()->first()
            ]);
        }

        $cust = Customer::find($req->customer_id);
        if(!$cust && $req->customer_id != 'umum'){
            return response()->json([
                'status' => false,
                'message' => 'Member tidak ditemukan'
            ]);
        }

        $products = Session::get('data');
        if(empty($products)){
            return response()->json([
                'status' => false,
                'message' => 'Keranjang masih kosong'
            ]);
        }

        DB::beginTransaction();
        try {
            $sale = new Selling;
            $sale->user_id = auth()->user()->id;
            $sale->customer_id = $req->customer_id == 'umum' ? null : $req->customer_id;
            $sale->sale_date = $req->sale_date ?? now()->format('Y-m-d');
            $sale->total_amount = $req->total_amount ?? 0;
            $sale->payment_method = $req->payment_method ?? PaymentMethod::tunai->value;
            $sale->amount_paid = $req->amount_paid ?? 0;
            $sale->change_due = $req->change_due ?? 0;
            $sale->sale_status = $req->sale_status ?? PurchaseStatus::finish->value;
            $sale->note = $req->note ?? null;
            $sale->additional_cost_name = $req->additional_cost_name ?? null;
            $sale->additional_cost = $req->additional_cost;
            $sale->save();

            foreach ($products as $prod) {
                $batch = ProductBatch::findOrFail($prod['id']);
                if(!$batch->product) throw new Exception('Produk dengan batch ' . $batch->batch_number . ' tidak ditemukan');
                if ($batch->stock < $prod['jumlah']) throw new Exception('Stok tidak mencukupi untuk produk ' . $batch->product->name . ' pada batch ' . $batch->batch_number);
                if($batch->exp_date < now()->format('Y-m-d')) throw new Exception('Produk ' . $batch->product->name . ' pada batch ' . $batch->batch_number . ' sudah kadaluarsa');

                if ($sale->sale_status == PurchaseStatus::finish->value) {
                    // kurangi stok
                    $batch->stock -= $prod['jumlah'];
                    $batch->save();

                    // catat inventory movement
                    $batch->inventoryMovements()->create([
                        'user_id' => auth()->user()->id,
                        'item_id' => $batch->item_id,
                        'product_batch_id' => $batch->id,
                        'flag' => InventoryFlag::out->value,
                        'qty' => $prod['jumlah'],
                        'unit' => $prod['satuan'],
                        'note' => 'Penjualan melalui transaksi #' . $sale->invoice_number,
                    ]);
                }
                $detail = new SellingDetail;
                $detail->selling_id = $sale->id;
                $detail->item_id = $batch->item_id;
                $detail->product_batch_id = $prod['id'];
                $detail->qty = $prod['jumlah'];
                $detail->unit = $prod['satuan'];
                $detail->price = $prod['harga'];
                $detail->sub_total = $prod['subtotal'];
                $detail->save();
            }

            DB::commit();
            session()->forget('data');

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil disimpan',
            ]);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Selling $sale)
    {
        return view('pages.sales.invoice', [
            'title' => 'Invoice',
            'menu' => 'Invoice',
        ]);
    }

    /**
     * untuk riwwayat penjualan
     */
    public function index()
    {
        $data = Selling::all();
        return view('pages.sales.index', [
            'title' => 'Riwayat Penjualan',
            'menu' => 'Riwayat',
            'data' => $data,
        ]);

    }

    public function finishSales(Request $req){
        $validators = Validator::make($req->all(), [
            'customer_id' => 'nullable',
            'payment_method' => ['required', new Enum(PaymentMethod::class)],
            'total_amount' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'change_due' => 'required|numeric|min:0',
            'sale_status' => 'required|string|max:50',
            'note' => 'nullable',
            'additional_cost_name' => 'nullable|string|max:255',
            'additional_cost' => 'nullable|numeric|min:0',
            'sale_date' => 'required|date_format:Y-m-d',
        ]);

        if($validators->fails()){
            return response()->json([
                'status' => false,
                'message' => $validators->errors()->first()
            ]);
        }

        $cust = Customer::find($req->customer_id);
        if(!$cust && $req->customer_id != 'umum'){
            return response()->json([
                'status' => false,
                'message' => 'Member tidak ditemukan'
            ]);
        }

        $products = Session::get('data');
        if(empty($products)){
            return response()->json([
                'status' => false,
                'message' => 'Keranjang masih kosong'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateOnCart(Request $req)
    {
        $validators = Validator::make($req->all(), [
            'batch_id' => 'required|exists:product_batches,id',
            'qty' => 'required|numeric|min:1'
        ]);
        if ($validators->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validators->errors()->first()
            ]);
        }

        try {
            $dataSession = session()->get('data');
            $item = ProductBatch::where('id', $req->batch_id)->firstOrFail();
            $findItem = $this->findItem($item->id);
            if ($findItem) {
                $index = key(array_filter($dataSession, function($itemSession) use ($item) {
                    return $itemSession['id'] === $item->id;
                }));
                $dataSession[$index] = [
                    'id' => $item->id,
                    'product_name' => $item->product->name,
                    'product_code' => $item->product->code,
                    'batch_number' => $item->batch_number,
                    'jumlah' => $req->qty,
                    'satuan' => $item->product->small_unit,
                    'harga' => (int) $item->product->default_price,
                    'subtotal' => (int) $item->product->default_price * $req->qty,
                ];
                session()->put('data', $dataSession);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Ditemukan',
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Berhasil Diperbarui',
            ]);
        } catch (\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }

    }
    public function resetCart(Request $req)
    {
        $req->session()->put('data', []);
        return response()->noContent();
    }



    private function findItem($id) {
        $data = session()->get('data');
        $find = Arr::first($data, function($item) use ($id){
            return $item['id'] === $id;
        });
        return $find;
    }
}
