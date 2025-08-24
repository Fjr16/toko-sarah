<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
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
        $genders = Gender::cases();
        return view('pages.customer.create',[
            'title' => 'Tambah Pelanggan',
            'menu' => 'settings',
            'genders' => $genders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $idToUpdate = $request->customer_id ? decrypt($request->customer_id) : null;
        $rules = [
            'name' => 'required|string|max:50',
            'email' => ['sometimes','nullable','email','max:50', Rule::unique('customers', 'email')->ignore($idToUpdate)],
            'phone' => 'string|required|max:20',
            'gender' => ['required', new Enum(Gender::class)],
            'address' => 'string|nullable',
            'subdistrict' => 'string|nullable|max:50',
            'city' => 'string|nullable|max:50',
            'province' => 'string|nullable|max:100',
            'country' => 'string|nullable|max:100',
            'postal_code' => 'string|required|max:20',
            'nik' => ['required','string','max:20', Rule::unique('customers', 'nik')->ignore($idToUpdate)],
        ];
        // if ($idToUpdate) {
        //     $rules['member_code'] = ['string','required', Rule::unique('customers', 'member_code')->ignore($idToUpdate)];
        // }
        try {
            $data = $request->validate($rules);
            $item = $idToUpdate ? Customer::findOrFail($idToUpdate) : new Customer();
            $item->name = $data['name'];
            $item->email = $data['email'];
            $item->phone = $data['phone'];
            $item->gender = $data['gender'];
            $item->address = $data['address'];
            $item->subdistrict = $data['subdistrict'];
            $item->city = $data['city'];
            $item->province = $data['province'];
            $item->country = $data['country'];
            $item->postal_code = $data['postal_code'];
            $item->nik = $data['nik'];
            // $item->member_code = $data['member_code'];
            // $item->email_verified_at = $data['email_verified_at'];
            if($item->save()){
                return response()->json([
                    'status' => true,
                    'message' => 'Proses Berhasil',
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Proses gagal, Mohon coba lagi dalam beberapa saat',
                ]);
            }

        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
            // return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $item = Customer::findOrFail(decrypt($id));
            $genders = Gender::cases();
            return view('pages.customer.create',[
                'title' => 'Edit Pelanggan',
                'menu' => 'settings',
                'item' => $item,
                'genders' => $genders,
            ]);
        } catch (Exception $e) {
            return redirect()->route('customer.index')->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('customer.index')->with('error', $e->getMessage());
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
