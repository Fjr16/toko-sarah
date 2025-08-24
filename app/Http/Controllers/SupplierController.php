<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Enums\SupplierType;
use Exception;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Throwable;

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
        $types = SupplierType::cases();
        $status = Status::cases();
        return view('pages.supplier.create', [
            'title' => 'Tambah Supplier',
            'menu' => 'settings',
            'types' => $types,
            'status' => $status,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $idToUpdate = $request->supplier_id ? decrypt($request->supplier_id) : null;
        try {
            $data = $request->validate([
                'name' => ['required','string','max:100', Rule::unique('suppliers', 'name')->ignore($idToUpdate)],
                'company_name' => 'nullable|string|max:100',
                'type' => ['required', new Enum(SupplierType::class)],
                'contact_person' => 'nullable|string|max:50',
                'phone' => 'required|string|max:20',
                'email' => ['nullable','email','max:50', Rule::unique('suppliers', 'email')->ignore($idToUpdate)],
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:50',
                'province' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'tax_number' => ['nullable','string','max:50', Rule::unique('suppliers', 'tax_number')->ignore($idToUpdate)],
                'bank_account' => 'nullable|string|max:50',
                'bank_number' => 'nullable|string|max:50',
                'status' => ['required', new Enum(Status::class)],
            ]);
            $item = $idToUpdate ? Supplier::findOrFail($idToUpdate) : new Supplier();
            $item->name = $data['name'];
            $item->company_name = $data['company_name'];
            $item->type = $data['type'];
            $item->contact_person = $data['contact_person'];
            $item->phone = $data['phone'];
            $item->email = $data['email'];
            $item->address = $data['address'];
            $item->city = $data['city'];
            $item->province = $data['province'];
            $item->country = $data['country'];
            $item->postal_code = $data['postal_code'];
            $item->tax_number = $data['tax_number'];
            $item->bank_account = $data['bank_account'];
            $item->bank_number = $data['bank_number'];
            $item->status = $data['status'];

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
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Supplier::find(decrypt($id));
        $types = SupplierType::cases();
        $status = Status::cases();
        return view('pages.supplier.create', [
            'title' => 'Edit Supplier',
            'menu' => 'settings',
            'item' => $item,
            'status' => $status,
            'types' => $types,
        ]);
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
