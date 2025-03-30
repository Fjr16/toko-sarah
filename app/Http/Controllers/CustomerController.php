<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Customer::all();
        $trashed = Customer::onlyTrashed()->get();
        return view('pages.customer.index',[
            'title' => 'Pelanggan',
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
        return view('pages.customer.create',[
            'title' => 'Pelanggan',
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
                'name' => 'string|required',
                'email' => 'string|email|nullable',
                'phone' => 'string|required',
                'city' => 'string|nullable',
                'country' => 'string|nullable',
                'address' => 'string|nullable',
            ]);
            if ($data['email']) {
                $request->validate([
                    'email' => 'unique:customers,email',
                ]);
            }
            Customer::create($data);
            return redirect()->route('customer.index')->with('success', 'Data berhasil disimpan');
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
        try {
            $item = Customer::findOrFail(decrypt($id));
            return view('pages.customer.edit',[
                'title' => 'Pelanggan',
                'menu' => 'settings',
                'item' => $item,
            ]);
        } catch (Exception $e) {
            return redirect()->route('customer.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('customer.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $item = Customer::findOrFail(decrypt($id));
            $data = $request->validate([
                'name' => 'string|required',
                'email' => 'string|email|nullable',
                'phone' => 'string|required',
                'city' => 'string|nullable',
                'country' => 'string|nullable',
                'address' => 'string|nullable',
            ]);
            if ($data['email']) {
                $request->validate([
                    'email' => 'unique:customers,email,'.decrypt($id),
                ]);
            }
            $item->update($data);
            return redirect()->route('customer.index')->with('success', 'Data berhasil diperbarui');
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
            $item = Customer::findOrFail(decrypt($id));
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
            $item = Customer::withTrashed()->findOrFail(decrypt($id));
            $item->restore();
            return back()->with('success', 'Data berhasil direstore');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
