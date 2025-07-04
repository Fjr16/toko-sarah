@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Tambah Supplier</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('supplier.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="nama_supplier" class="form-label">Nama Supplier</label>
                        <input type="text" class="form-control form-control-md" id="nama_supplier" name="name" placeholder="Nama Supplier" value="{{ old('name') }}" required />
                    </div>
                    <div class="col-md mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control form-control-md" id="email" name="email" placeholder="supplier@gmail.com" value="{{ old('email') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="phone" class="form-label">HP / WA</label>
                        <input type="text" class="form-control form-control-md" id="phone" name="phone" placeholder="+62xx-xxxx-xxxx" value="{{ old('phone') }}" required />
                    </div>
                    <div class="col-md mb-3">
                        <label for="city" class="form-label">Kota</label>
                        <input type="text" class="form-control form-control-md" id="city" name="city" placeholder="Nama Kota" value="{{ old('city') }}" />
                    </div>
                    <div class="col-md mb-3">
                        <label for="country" class="form-label">Negara</label>
                        <input type="text" class="form-control form-control-md" id="country" name="country" placeholder="Nama Negara" value="{{ old('country') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control form-control-md" id="alamat" name="address" placeholder="alamat pelanggan" value="{{ old('address') }}"/>
                    </div>
                </div>
                <div class="col-md-12 mt-4 border-top">
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('supplier.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                        <button type="submit" class="btn btn-md btn-success"><i class="bx bx-file"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection