<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        $idToUpdate = $request->customer_id ? $request->customer_id : null;
        $rules = [
            'name' => 'required|string|max:50',
            'email' => ['sometimes','nullable','email','max:50', Rule::unique('customers', 'email')->ignore($idToUpdate)],
            'phone' => 'string|required|max:20',
            'address' => 'string|nullable',
            'subdistrict' => 'string|nullable|max:50',
            'city' => 'string|nullable|max:50',
            'country' => 'string|nullable|max:100',
            'postal_code' => 'string|required|max:20',
            'nik' => ['required','string','max:20', Rule::unique('customers', 'nik')->ignore($idToUpdate)],
        ];
        if ($idToUpdate) {
            $rules['member_code'] = ['string','required', Rule::unique('customers', 'member_code')->ignore($idToUpdate)];
        }
        try {
            $data = $request->validate($rules);
            if(!$idToUpdate){

            }
            $item = $idToUpdate ? Customer::findOrFail($idToUpdate) : new Customer();
            $item->name = $data['name'];
            $item->email = $data['email'];
            $item->phone = $data['phone'];
            $item->address = $data['address'];
            $item->subdistrict = $data['subdistrict'];
            $item->city = $data['city'];
            $item->country = $data['country'];
            $item->postal_code = $data['postal_code'];
            $item->nik = $data['nik'];
            $item->member_code = $data['member_code'];
            // $item->email_verified_at = $data['email_verified_at'];
            $item->save();

            return redirect()->route('customer.index')->with('success', 'Data berhasil disimpan');
        } catch (Throwable $e) {
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
