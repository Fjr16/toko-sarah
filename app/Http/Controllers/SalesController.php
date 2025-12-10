<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Helpers\CustomHelpers;
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
        return view('pages.sales.create', [
            'title' => 'Penjualan',
            'menu' => 'Penjualan',
            'produks' => $produks,
            'paymentMethods' => $paymentMethods,
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

    // public function getProduct(Request $r){
    //     $keyword = request('keyword');
    //     $data = Item::where('code', $keyword)
    //             ->orWhere('name', 'like', '%{$keyword}%')
    //             ->limit(10)->get([
    //                 'id', 'code', 'name', 'small_unit'
    //             ]);
    //     return response()->json($data);

    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $dataSession = Session::get('data');
            if (!empty($dataSession)) {
                $subtotal = array_sum(array_column($dataSession, 'total_harga'));
                $items = count($dataSession);
                $totalItems = array_sum(array_column($dataSession, 'jumlah'));
                $totalDiskon = array_sum(array_column($dataSession, 'diskon'));
                $totalAkhir = $subtotal - $totalDiskon;
                $tipeBayar = $request->tipe_bayar;
                $request->validate([
                    'jumlah_bayar' => 'required|gte:' . $totalAkhir,
                    'tipe_bayar' => 'required|string',
                ], [
                    'jumlah_bayar.required' => 'Jumlah Bayar tidak Boleh Kosong',
                    'jumlah_bayar.gte' => 'Jumlah bayar tidak mencukupi',
                    'tipe_bayar.required' => 'Tipe bayar tidak valid',
                ]);
                $jumlahBayar = $request->jumlah_bayar;
                $kembalian = $jumlahBayar - $totalAkhir;
                if ($subtotal > 0 || $totalItems > 0 || $totalAkhir > 0 || $kembalian >= 0) {
                    $item = Selling::create([
                        'user_id' => auth()->user()->id,
                        'customer_id' => auth()->user()->id,
                        'sale_date' => auth()->user()->id,
                        'total_diskon' => $totalDiskon,
                        'total_kotor' => $subtotal,
                        'total_bersih' => $totalAkhir,
                        'items' => $items,
                        'total_item' => $totalItems,
                        'metode_bayar' => $tipeBayar,
                        'jumlah_bayar' => $jumlahBayar,
                        'kembalian' => $kembalian,
                        'status' => 'paid',
                    ]);

                    foreach ($dataSession as $key => $detail) {
                        SellingDetail::create([
                            'selling_id' => $item->id,
                            'item_id' => $detail['id'],
                            'product_barcode' => $detail['barcode'],
                            'product_name' => $detail['name'],
                            'product_jumlah' => $detail['jumlah'],
                            'product_satuan' => $detail['satuan'],
                            'product_harga' => $detail['harga_satuan'],
                            'product_sub_total' => $detail['total_harga'],
                            'product_diskon' => $detail['diskon'],
                        ]);
                    }

                    DB::commit();
                    session()->forget('data');
                    return back()->with('success', 'Berhasil Disimpan');
                }else{
                    DB::rollBack();
                    return back()->with('error', 'Terdapat data produk yang tidak valid pada keranjang');
                }
            }else{
                DB::rollBack();
                return back()->with('error', 'Keranjang Anda masih kosong');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        } catch (ModelNotFoundException $mn) {
            DB::rollBack();
            return back()->with('error', $mn->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('pages.sales.invoice', [
            'title' => 'Invoice',
            'menu' => 'Invoice',
        ]);
    }
    public function detail(String $id)
    {
        $item = Selling::find(decrypt($id));
        return view('pages.sales.show', [
            'item' => $item,
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

    /**
     * Remove the specified resource from storage.
     */
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
