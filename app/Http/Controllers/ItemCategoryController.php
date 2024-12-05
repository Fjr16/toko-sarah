<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ItemCategory::all();
        return view('pages.item-category.index', [
            'title' => 'Item Category',
            'menu' => 'item',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.item-category.create', [
            'title' => 'Item Category',
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
            'title' => 'Item Category',
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
            'title' => 'Item Category',
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
    public function destroy(Request $request, string $id)
    {
        $this->validate($request, [
            'status' => 'in:active,deleted',
        ]);
        $item = ItemCategory::find(decrypt($id));

        $item->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Berhasil Diperbarui');
    }
}
