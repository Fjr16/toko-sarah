<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Supplier::all();
        return view('pages.supplier.index', [
            'title' => 'Supplier',
            'menu' => 'Supplier',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.supplier.create', [
            'title' => 'Supplier',
            'menu' => 'Supplier',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:suppliers,name',
        ]);


        Supplier::create($data);

        return redirect()->route('supplier.index')->with('success', 'Berhasil Ditambahkan');
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
        $item = Supplier::find(decrypt($id));
        return view('pages.supplier.edit', [
            'title' => 'Supplier',
            'menu' => 'Supplier',
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Supplier::find(decrypt($id));
        $data = $request->validate([
            'name' => 'required|string|unique:suppliers,name,'. $item->id,
        ]);

        $item->update($data);

        return redirect()->route('supplier.index')->with('success', 'Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        $this->validate($request, [
            'status' => 'in:active,deleted',
        ]);
        $item = Supplier::find(decrypt($id));

        $item->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Berhasil Diperbarui');
    }
}
