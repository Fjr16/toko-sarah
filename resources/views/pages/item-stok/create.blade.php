@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Tambah Produk</h4>
        </div>
        <div class="card-body">
            <form action="{{ route("barang.store") }}" method="POST" id="post-form">
                @csrf
                <div class="row mb-3">
                    <label for="kategori-barang" class="form-label">Kategori Barang <span class="text-danger">*</span></label>
                    <select class="form-select form-control" id="kategori-barang" aria-label="Default select example" name="item_category_id" required>
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
                    <div class="col-md">
                        <label for="satuan-terkecil" class="form-label">Satuan Terkecil <span class="text-danger">*</span></label>
                        <input name="small_unit" class="form-control form-control-md" id="satuan-terkecil" placeholder="Input Satuan Terkecil" required></input>
                    </div>
                    <div class="col-md">
                        <label for="satuan-menengah" class="form-label">Satuan Menengah</label>
                        <div class="input-group input-group-merge">
                            <input type="text" class="form-control" placeholder="Input Satuan Menengah" id="satuan-menengah" name="medium_unit"/>
                            <span class="input-group-text" id="get-satuan-sedang-awal">-</span>
                            <input type="number" class="form-control" placeholder="nilai konversi ke satuan terkecil" name="medium_to_small"/>
                            <span class="input-group-text" id="get-satuan-kecil">-</span>
                        </div>
                    </div>
                    <div class="col-md">
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
                    <div class="col-md-5">
                        <label for="cost" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            @if ($systemSetting?->currency_position_default == 'prefix')
                                <span class="input-group-text bg-dark text-white">Rp. </span>                                
                            @endif
                            <input type="text" class="form-control form-control-md price" id="cost" name="cost" placeholder="0,00" value="{{ old('cost', 0) }}" required />

                            @if ($systemSetting?->currency_position_default == 'suffix')
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
                            @if ($systemSetting?->currency_position_default == 'prefix')
                                <span class="input-group-text bg-dark text-white">Rp. </span>                                
                            @endif
                            
                            <input type="text" name="price" id="price" class="form-control form-control-md" placeholder="0,00" value="{{ old('price', 0)}}" readonly required />

                            @if ($systemSetting?->currency_position_default == 'suffix')
                                <span class="input-group-text bg-dark text-white">Rp. </span>                                
                            @endif
                            <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md">
                        <label for="stok-awal" class="form-label">Stok Awal <span class="text-danger">*</span></label>
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
                {{-- <div class="row mb-3">
                    <div class="col-md">
                        <label for="tax" class="form-label">Pajak (%) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control form-control-md" id="tax" min="0" name="tax" value="{{ old('tax') ?? 0 }}" required/>
                            <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label for="tax_type" class="form-label">Tipe Pajak <span class="text-danger">*</span></label>
                        <select class="form-select form-control" id="tax_type" aria-label="Default select example" name="tax_type" required>
                            <option value="none" {{ old('tax_type') ? '' : 'selected' }}>None</option>
                            <option value="inclusive" {{ old('tax_type') == 'inclusive' ? 'selected' : '' }}>Inclusive</option>
                            <option value="exclusive" {{ old('tax_type') == 'exclusive' ? 'selected' : '' }}>Exclusive</option>
                        </select>
                    </div>
                </div> --}}
                <div class="row mb-3">
                    <label for="note" class="form-label">Catatan</label>
                    <textarea class="form-control" id="note" rows="4" name="note">{{ old('note') }}</textarea>
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