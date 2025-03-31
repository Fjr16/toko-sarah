<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::all();
        $trashed = User::onlyTrashed()->get();
        return view('pages.user.index', [
            'title' => 'User',
            'menu' => 'Management User',
            'data' => $data,
            'trashed' => $trashed,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.user.create', [
            'title' => 'User',
            'menu' => 'Management User',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            // 'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', Password::defaults()],
            'role' => 'required|in:administrator,cashier,manager',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            // 'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'Berhasil ditambahkan');
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
        $item = User::find(decrypt($id));
        return view('pages.user.edit', [
            'title' => 'User',
            'menu' => 'Management User',
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();    
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable',
            'role' => 'required|in:administrator,cashier,manager',
        ]);
            
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
        ];
        
        if ($request->password != null) {
            $request->validate([
                'password' => ['required', Password::defaults()]
            ]);
            $data['password'] = Hash::make($request->password);
        }
        $item = User::find(decrypt($id));
        $item->update($data);
        
        return redirect()->route('user.index')->with('success', 'Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $item = User::findOrFail(decrypt($id));
            $item->delete();
            return back()->with('success', 'Data berhasil dihapus');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function restore($id) 
    {
        try {
            $item = User::onlyTrashed()->findOrFail(decrypt($id));
            if ($item) {
                $item->restore();
                return back()->with('success', 'Data berhasil dipulihkan');
            } else {
                return back()->with('error', 'Data tidak ditemukan');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }
}
