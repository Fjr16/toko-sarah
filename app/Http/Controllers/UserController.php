<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::all();
        return view('pages.user.index', [
            'title' => 'Management User',
            'menu' => 'Management User',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.user.create', [
            'title' => 'Management User',
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
            'title' => 'Management User',
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
    public function destroy(Request $request, string $id)
    {
        $this->validate($request, [
            'status' => 'required|in:deleted,active'
        ]);
        $item = User::find(decrypt($id));
        $item->update([
            'status' => $request->status,
        ]);
        
        return back()->with('success', 'Berhasil Diperbarui');
    }
}
