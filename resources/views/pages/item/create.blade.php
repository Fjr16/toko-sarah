@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Tambah Produk</h4>
        </div>
        <div class="card-body">
            <form action="{{ route("barang.store") }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="kategori-barang" class="form-label">Kategori Barang</label>
                            <select class="form-select form-control" id="kategori-barang" aria-label="Default select example" name="item_category_id" required>
                              <option selected disabled>-- Pilih Kategori --</option>
                              @foreach ($data as $item)
                                <option value="{{ $item->id }}" {{ old('item_category_id') == $item->id ? 'selected' : '' }}>{{ $item->name ?? '-' }}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kode-barang" class="form-label">Kode Barang</label>
                            <input type="text" class="form-control form-control-md" id="kode-barang" name="code" placeholder="Input / scan kode barang disini" value="{{ old('code') }}" required />
                        </div>
                        <div class="mb-3">
                            <label for="nama-barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control form-control-md" id="nama-barang" name="name" placeholder="Input Nama Barang" value="{{ old('name') }}" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="satuan-terkecil" class="form-label">Satuan Terkecil</label>
                            <input name="small_unit" class="form-control form-control-md" id="satuan-terkecil" placeholder="Input Satuan Terkecil" required></input>
                        </div>
                        <div class="col-md-4">
                            <label for="satuan-menengah" class="form-label">Satuan Menengah</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" placeholder="Input Satuan Menengah" id="satuan-menengah" name="medium_unit"/>
                                <span class="input-group-text" id="get-satuan-sedang-awal">-</span>
                                <input type="number" class="form-control" placeholder="nilai konversi ke satuan terkecil" name="medium_to_small"/>
                                <span class="input-group-text" id="get-satuan-kecil">-</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="satuan-terbesar" class="form-label">Satuan Terbesar</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" placeholder="Input Satuan Terbesar" id="satuan-terbesar" name="big_unit" />
                                <span class="input-group-text" id="get-satuan-besar">-</span>
                                <input type="mumber" class="form-control" placeholder="nilai konversi ke satuan menengah" name="big_to_medium"/>
                                <span class="input-group-text" id="get-satuan-sedang-akhir">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="harga-awal" class="form-label">Harga Awal</label>
                            <div class="input-group">
                                <input type="mumber" name="base_price" id="harga-awal" placeholder="0" class="form-control form-control-md" placeholder="Input Harga Awal Barang" value="{{ old('base_price') }}" required />
                                <span class="input-group-text get-satuan-kecil">/</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="stok-awal" class="form-label">Stok Awal</label>
                            <div class="input-group">
                                <input type="mumber" name="stok" id="stok-awal" placeholder="0" class="form-control form-control-md" placeholder="Input Stok Awal Barang" value="{{ old('stok') }}" required />
                                <span class="input-group-text get-satuan-kecil">/</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4 border-top">
                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('barang.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                            <button type="submit" class="btn btn-md btn-success"><i class="bx bx-file"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection