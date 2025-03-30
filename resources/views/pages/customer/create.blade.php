@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Tambah {{ $title ?? '' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control form-control-md" id="nama_pelanggan" name="name" placeholder="Nama Pelanggan" value="{{ old('name') }}" required />
                    </div>
                    <div class="col-md mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control form-control-md" id="email" name="email" placeholder="customer@gmail.com" value="{{ old('email') }}"/>
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
                        <a href="{{ route('customer.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                        <button type="submit" class="btn btn-md btn-success"><i class="bx bx-file"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection