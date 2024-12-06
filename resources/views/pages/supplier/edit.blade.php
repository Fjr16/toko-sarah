@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Edit Supplier</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('supplier.update', encrypt($item->id)) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="nama-supplier" class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control form-control-md" id="nama-supplier" name="name" placeholder="Input nama supplier disini" value="{{ old('name', $item->name ?? '') }}" required />
                        </div>
                    </div>
                    <div class="col-md-12 mt-4 border-top">
                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('supplier.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                            <button type="submit" class="btn btn-md btn-success"><i class="bx bx-file"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection