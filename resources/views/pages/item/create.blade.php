@extends('layouts.auth.main')

@push('styles')
<style>
    .upload-box {
      width: 200px;
      height: 200px;
      border: 2px dashed #ccc;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      overflow: hidden;
      background: #fafafa;
    }
    .upload-box img {
      max-width: 100%;
      max-height: 100%;
      display: none;
    }
    .upload-box span {
      color: #aaa;
      font-size: 14px;
    }
    input[type="file"] {
      display: none; /* sembunyikan bawaan browser */
    }
    </style>
@endpush
@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Tambah Produk</h4>
        </div>
        <div class="card-body">
            <form action="{{ route("barang.store") }}" method="POST" id="post-form">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-10">
                        <div class="mb-3">
                            <label for="kategori-barang" class="form-label">Kategori Barang <span class="text-danger">*</span></label>
                            <select class="form-select" id="kategori-barang" aria-label="Default select example" name="item_category_id" required>
                                <option selected disabled>-- Pilih Kategori --</option>
                                @foreach ($data as $item)
                                <option value="{{ $item->id }}" {{ old('item_category_id') == $item->id ? 'selected' : '' }}>{{ $item->name ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label for="nama-barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-md" id="nama-barang" name="name" placeholder="Input Nama Barang" value="{{ old('name') }}" required />
                            </div>
                            <div class="col-md-5">
                                <label for="kode-barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-md" id="kode-barang" name="code" placeholder="Input / scan kode barang disini" value="{{ old('code') }}" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="satuan-terkecil" class="form-label">Satuan Terkecil <span class="text-danger">*</span></label>
                                <input name="small_unit" class="form-control form-control-md" id="satuan-terkecil" placeholder="Input Satuan Terkecil" required></input>
                            </div>
                            <div class="col-md-5">
                                <label for="satuan-menengah" class="form-label">Satuan Menengah</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" placeholder="Input Satuan Menengah" id="satuan-menengah" name="medium_unit"/>
                                    <span class="input-group-text" id="get-satuan-sedang-awal">-</span>
                                    <input type="number" class="form-control" placeholder="nilai konversi ke satuan terkecil" name="medium_to_small"/>
                                    <span class="input-group-text" id="get-satuan-kecil">-</span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="satuan-terbesar" class="form-label">Satuan Terbesar</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" class="form-control" placeholder="Input Satuan Terbesar" id="satuan-terbesar" name="big_unit" />
                                    <span class="input-group-text" id="get-satuan-besar">-</span>
                                    <input type="number" class="form-control" placeholder="nilai konversi ke satuan menengah" name="big_to_medium"/>
                                    <span class="input-group-text" id="get-satuan-sedang-akhir">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex justify-content-center align-items-center">
                        <label for="fotoProduk" class="upload-box" id="uploadBox">
                            <span>+ Upload Foto</span>
                            <img id="previewImg" alt="Preview"/>
                        </label>
                        <input type="file" id="fotoProduk" name="image" accept="image/*">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-5">
                        <label for="cost" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            {{-- @if ($systemSetting?->currency_position_default == 'prefix') --}}
                            @if (setting('currency_position_default', 'prefix') == 'prefix')
                                <span class="input-group-text bg-dark text-white">Rp. </span>
                            @endif
                            <input type="text" class="form-control form-control-md price" id="cost" name="cost" placeholder="0,00" value="{{ old('cost', 0) }}" required />

                            @if (setting('currency_position_default', 'prefix') == 'suffix')
                                <span class="input-group-text bg-dark text-white">Rp. </span>
                            @endif
                            <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="margin" class="form-label">Margin (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-md" id="margin" name="margin" placeholder="0" min="0" value="{{ old('margin') ?? 0 }}" required />
                    </div>
                    <div class="col-md-5">
                        <label for="price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            @if (setting('currency_position_default', 'prefix') == 'prefix')
                                <span class="input-group-text bg-dark text-white">Rp. </span>
                            @endif

                            <input type="text" name="price" id="price" class="form-control form-control-md" placeholder="0,00" value="{{ old('price', 0)}}" readonly required />

                            @if (setting('currency_position_default', 'prefix') == 'suffix')
                                <span class="input-group-text bg-dark text-white">Rp. </span>
                            @endif
                            <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md">
                        <label for="stok-awal" class="form-label">Total stok / Stok awal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="stok" id="stok-awal" placeholder="0" min="0" class="form-control form-control-md" value="{{ old('stok') ?? 0 }}" required />
                            <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label for="stok_alert" class="form-label">Peringatan Stok <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="stok_alert" id="stok_alert" placeholder="0" min="0" class="form-control form-control-md" value="{{ old('stok_alert') ?? 0 }}" required />
                            <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" rows="4" name="description">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="col-md-12 mt-4 border-top">
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('barang.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                        <button type="submit" class="btn btn-md btn-success"><i class="bx bx-file"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('cost').addEventListener('keyup', function() {
            let cost = this.value.replace(/[^0-9]/g, '');
            let margin = document.getElementById('margin').value.replace(/[^0-9]/g, '');
            let price = parseInt(cost) * (parseInt(margin) / 100) + parseInt(cost);

            let harga = rupiahFormatter(price).replace(/[^0-9.]/g, '');
            document.getElementById('price').value = harga;
        });
        document.getElementById('margin').addEventListener('keyup', function() {
            let cost = document.getElementById('cost').value.replace(/[^0-9]/g, '');
            let margin = this.value.replace(/[^0-9]/g, '');
            let price = parseInt(cost) * (parseInt(margin) / 100) + parseInt(cost);

            let harga = rupiahFormatter(price).replace(/[^0-9.]/g, '');
            document.getElementById('price').value = harga;
            return this.value = parseInt(margin);
        });

        // format rupiah
        let inputPrice = document.querySelectorAll('.price');
        inputPrice.forEach(function(input) {
            input.addEventListener('keyup', function() {
                let val = rupiahFormatter(input.value.replace(/[^0-9]/g, ''));
                input.value = val.replace(/[^0-9.]/g, '');
            });
        });
    </script>
@endpush