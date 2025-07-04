<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Supplier::all();
        $trashed = Supplier::onlyTrashed()->get();
        return view('pages.supplier.index', [
            'title' => 'Supplier',
            'menu' => 'settings',
            'data' => $data,
            'trashed' => $trashed,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.supplier.create', [
            'title' => 'Supplier',
            'menu' => 'settings',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'string|required|unique:suppliers,name',
                'email' => 'string|email|nullable',
                'phone' => 'string|required',
                'city' => 'string|nullable',
                'country' => 'string|nullable',
                'address' => 'string|nullable',
            ]);
            if ($data['email']) {
                $request->validate([
                    'email' => 'unique:suppliers,email',
                ]);
            }
            Supplier::create($data);
            return redirect()->route('supplier.index')->with('success', 'Data berhasil disimpan');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (ModelNotFoundException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (ValidationException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Supplier::find(decrypt($id));
        return view('pages.supplier.edit', [
            'title' => 'Supplier',
            'menu' => 'settings',
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $item = Supplier::findOrFail(decrypt($id));
            $data = $request->validate([
                'name' => 'string|required|unique:suppliers,name,'. $item->id,
                'email' => 'string|email|nullable',
                'phone' => 'string|required',
                'city' => 'string|nullable',
                'country' => 'string|nullable',
                'address' => 'string|nullable',
            ]);
            if ($data['email']) {
                $request->validate([
                    'email' => 'unique:suppliers,email,'.decrypt($id),
                ]);
            }
            $item->update($data);
            return redirect()->route('supplier.index')->with('success', 'Data berhasil diperbarui');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (ModelNotFoundException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (ValidationException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = Supplier::findOrFail(decrypt($id));
            $item->delete();
            return back()->with('success', 'Data berhasil dihapus');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function restore(string $id)
    {
        try {
            $item = Supplier::withTrashed()->findOrFail(decrypt($id));
            $item->restore();
            return back()->with('success', 'Data berhasil direstore');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
