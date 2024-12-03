<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Item::all();
        return view('pages.item.index', [
            'title' => 'Item',
            'menu' => 'item',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = ItemCategory::all();
        return view('pages.item.create', [
            'title' => 'Item',
            'menu' => 'item',
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'code' => 'required',
            'name' => 'required|string',
            'small_unit' => 'required|string',
            'medium_unit' => 'mullable|string',
            'medium_to_small' => 'required_with:medium_unit',
            'big_unit' => 'mullable|string',
            'big_to_medium' => 'required_with:big_unit',
            'base_price' => 'nullable',
            'stok' => 'nullable',
        ]);


        Item::create($data);

        return redirect()->route('barang.index')->with('success', 'Berhasil Ditambahkan');
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
        $data = ItemCategory::all();
        $item = Item::find(decrypt($id));
        return view('pages.item.edit', [
            'title' => 'Item',
            'menu' => 'item',
            'item' => $item,
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::find(decrypt($id));
        $data = $request->validate([
            'item_category_id' => 'required|exists:item_categories,id',
            'code' => 'required',
            'name' => 'required|string',
            'small_unit' => 'required|string',
            'medium_unit' => 'mullable|string',
            'medium_to_small' => 'required_with:medium_unit',
            'big_unit' => 'mullable|string',
            'big_to_medium' => 'required_with:big_unit',
            'base_price' => 'nullable',
            'stok' => 'nullable',
        ]);

        $item->update($data);

        return redirect()->route('barang.index')->with('success', 'Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::find(decrypt($id));
        $item->update([
            'status' => 'deleted',
        ]);

        return back()->with('success', 'Berhasil Diperbarui');
    }
}
