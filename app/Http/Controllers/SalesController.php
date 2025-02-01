<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Selling;
use App\Models\SellingDetail;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SalesController extends Controller
{

    private function generateSellingId() {
        $idCs = str_pad(Auth::user()->id, 2, '0', STR_PAD_LEFT);
        $currentDate = now();
        $nextOrderNumber = (Selling::whereDate('created_at', $currentDate->format('Y-m-d'))->count() ?? 0) + 1;
        $kodeOrder = 'CS/' . $idCs . '/' . $currentDate->format('d/m/Y') . '/' . str_pad($nextOrderNumber, 3, '0', STR_PAD_LEFT);
        return $kodeOrder;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // session()->forget('data');
        if (session('data')) {
            session()->put('data', session('data'));
        }else{
            session()->put('data', []);
        }

        $produks = Item::all();
        return view('pages.sales.create', [
            'title' => 'Penjualan',
            'menu' => 'Penjualan',
            'produks' => $produks,
        ]);
    }

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
                        'selling_id' => $this->generateSellingId(),
                        'user_id' => auth()->user()->id,
                        'member_id' => auth()->user()->id,
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
