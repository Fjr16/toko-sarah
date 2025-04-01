<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
        return view('pages.item-category.create', [
            'title' => 'Kategori Produk',
            'menu' => 'item',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:item_categories,name',
        ]);


        ItemCategory::create($data);

        return redirect()->route('kategori/barang.index')->with('success', 'Berhasil Ditambahkan');
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
        return view('pages.item-category.edit', [
            'title' => 'Kategori Produk',
            'menu' => 'item',
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = ItemCategory::find(decrypt($id));
        $data = $request->validate([
            'name' => 'required|string|unique:item_categories,name,'. $item->id,
        ]);

        $item->update($data);

        return redirect()->route('kategori/barang.index')->with('success', 'Berhasil Diperbarui');
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
