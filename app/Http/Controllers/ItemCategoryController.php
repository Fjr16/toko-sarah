<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ItemCategory::all();
        $trashed = ItemCategory::onlyTrashed()->get();
        return view('pages.item-category.index', [
            'title' => 'Kategori Produk',
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
        $status = Status::cases();
        return view('pages.item-category.create', [
            'title' => 'Tambah Kategori',
            'menu' => 'item',
            'status' => $status,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $idToUpdate = $request->category_id ?? null;
            $validators = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'status' => ['required', new Enum(Status::class)],
            ]);
            if($validators->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak valid: ' . $validators->errors()->first(),
                ]);
            }

            $item = $idToUpdate ? ItemCategory::findOrFail($idToUpdate) : new ItemCategory();
            $item->name = $request->name;
            $item->status = $request->status;
            $item->save();

            return response()->json([
                'status' => true,
                'message' => 'Proses Berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan: ' . $th->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $itemCategory = ItemCategory::find(decrypt($id));
        $data = Item::where('item_category_id', $itemCategory->id)->get();
        return view('pages.item-category.show', [
            'title' => 'Kategori Produk',
            'menu' => 'item',
            'itemCategory' => $itemCategory,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = ItemCategory::find(decrypt($id));
        $status = Status::cases();
        return view('pages.item-category.create', [
            'title' => 'Edit Kategori',
            'menu' => 'item',
            'item' => $item,
            'status' => $status,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = ItemCategory::findOrFail(decrypt($id));

            $item->delete();

            return back()->with('success', 'Berhasil Diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Menghapus Data : ' . $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Kategori tidak ditemukan : ' . $e->getMessage());
        }
    }

    public function restore($id) {
        try {
            $item = ItemCategory::onlyTrashed()->findOrFail(decrypt($id));
            $item->restore();
            return redirect()->route('kategori/barang.index')->with('success', 'Daqta berhasil dipulihkan');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Kategori tidak ditemukan : ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Memulihkan Data : ' . $e->getMessage());
        }

    }
}
