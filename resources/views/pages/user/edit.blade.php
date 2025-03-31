@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Edit {{ $title ?? '' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('user.update', encrypt($item->id)) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="mb-3">
                        <label for="useri-wisata" class="form-label">Nama Pengguna</label>
                        <input type="text" class="form-control form-control-md" id="nama-user" name="name" placeholder="Nama User" value="{{ old('name', $item->name) }}" required />
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control form-control-md" id="username" name="username" placeholder="Masukkan Username Anda" value="{{ old('username', $item->username) }}" required />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control form-control-md" id="password" name="password" placeholder="Perbarui Password"/>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select form-select-md">
                            <option value="administrator" {{ old('role', $item->role) == 'administrator' ? 'selected' : '' }}>Administrator</option>
                            <option value="cashier" {{ old('role', $item->role) == 'cashier' ? 'selected' : '' }}>Kasir</option>
                            <option value="manager" {{ old('role', $item->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-4 border-top">
                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('user.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                            <button type="submit" class="btn btn-md btn-success"><i class="bx bx-file"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection