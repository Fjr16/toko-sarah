@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Edit {{ $title ?? '' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('currency.update', encrypt($item->id)) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Mata Uang</label>
                            <input type="text" class="form-control form-control-md" id="name" name="name" placeholder="Rupiah" value="{{ old('name', $item->name ?? '') }}" required />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="code" class="form-label">Kode</label>
                            <input type="text" class="form-control form-control-md" id="code" name="code" placeholder="IDR" value="{{ old('code', $item->code ?? '') }}" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="symbol" class="form-label">Simbol</label>
                            <input type="text" class="form-control form-control-md" id="symbol" name="symbol" placeholder="Rp." value="{{ old('symbol', $item->symbol ?? '') }}" required />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="thousand_separator" class="form-label">Pemisah Ribuan</label>
                            <input type="text" class="form-control form-control-md" id="thousand_separator" name="thousand_separator" placeholder="." value="{{ old('thousand_separator', $item->thousand_separator ?? '') }}" required />
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="mb-3">
                            <label for="decimal_separator" class="form-label">Pemisah Desimal</label>
                            <input type="text" class="form-control form-control-md" id="decimal_separator" name="decimal_separator" placeholder="," value="{{ old('decimal_separator', $item->decimal_separator ?? '') }}" required />
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-4 border-top">
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('currency.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                        <button type="submit" class="btn btn-md btn-success"><i class="bx bx-file"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection