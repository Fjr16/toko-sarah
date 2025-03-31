<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Currency::all();
        return view('pages.mata-uang.index', [
            'title' => 'Mata Uang',
            'menu' => 'settings',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.mata-uang.create', [
            'title' => 'Mata Uang',
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
                'name' => 'required|string|max:50',
                'code' => 'required|string|max:20|unique:currencies,code',
                'symbol' => 'required|string|max:5|unique:currencies,symbol',
                'thousand_separator' => 'required|string|max:5',
                'decimal_separator' => 'required|string|max:5',
            ]);

            Currency::create($data);

            return redirect()->route('currency.index')->with('success', 'Mata Uang berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (ValidationException $e) { 
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $item = Currency::findOrFail(decrypt($id));
            return view('pages.mata-uang.edit', [
                'title' => 'Mata Uang',
                'menu' => 'settings',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('currency.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('currency.index')->with('error', 'Data Tidak Ditemukan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:50',
                'code' => 'required|string|max:20|unique:currencies,code,' . decrypt($id),
                'symbol' => 'required|string|max:5|unique:currencies,symbol,' . decrypt($id),
                'thousand_separator' => 'required|string|max:5',
                'decimal_separator' => 'required|string|max:5',
            ]);

            $item = Currency::findOrFail(decrypt($id));

            $item->update($data);

            return redirect()->route('currency.index')->with('success', 'Mata Uang berhasil diubah');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('currency.index')->with('error', 'Data Tidak Ditemukan: ' . $e->getMessage())->withInput();
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = Currency::findOrFail(decrypt($id));
            $item->delete();

            return redirect()->route('currency.index')->with('success', 'Mata Uang berhasil dihapus');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('currency.index')->with('error', 'Data Tidak Ditemukan: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->route('currency.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
