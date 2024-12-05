@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Edit Barang</h4>
        </div>
        <div class="card-body">
            <form action="{{ route("barang.update", encrypt($item->id)) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="kategori-barang" class="form-label">Kategori Barang</label>
                            <select class="form-select form-control" id="kategori-barang" aria-label="Default select example" name="item_category_id" required>
                              <option selected disabled>-- Pilih Kategori --</option>
                              @foreach ($data as $cat)
                                <option value="{{ $cat->id }}" {{ old('item_category_id', $item->item_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name ?? '-' }}</option>
                              @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kode-barang" class="form-label">Kode Barang</label>
                            <input type="text" class="form-control form-control-md" id="kode-barang" name="code" placeholder="Input / scan kode barang disini" value="{{ old('code', $item->code) }}" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama-barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control form-control-md" id="nama-barang" name="name" placeholder="Input Nama Barang" value="{{ old('name', $item->name) }}" required />
                        </div>
                        <div class="mb-3">
                            <label for="harga-awal" class="form-label">Harga Awal</label>
                            <div class="input-group">
                                <input type="mumber" name="base_harga" id="harga-awal" value="{{ old('base_price', (int) $item->base_price ?? 0) }}" class="form-control form-control-md" placeholder="Input Harga Awal Barang" value="{{ old('base_price', $item->base_price) }}" required />
                                <span class="input-group-text get-satuan-kecil">/{{ $item->small_unit ?? '' }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="stok-awal" class="form-label">Stok Awal</label>
                            <div class="input-group">
                                <input type="mumber" name="stok" id="stok-awal" value="{{ old('stok', $item->stok ?? 0) }}" class="form-control form-control-md" placeholder="Input Stok Awal Barang" value="{{ old('stok', $item->stok ?? '') }}" required />
                                <span class="input-group-text get-satuan-kecil">/{{ $item->small_unit ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="satuan-terkecil" class="form-label">Satuan Terkecil</label>
                            <input name="small_unit" class="form-control form-control-md" id="satuan-terkecil" placeholder="Input Satuan Terkecil" value="{{ old('small_unit', $item->small_unit) }}" required></input>
                        </div>
                        <div class="mb-3">
                            <label for="satuan-menengah" class="form-label">Satuan Menengah</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" placeholder="Input Satuan Menengah" id="satuan-menengah" value="{{ old('medium_unit', $item->medium_unit ?? '') }}" name="medium_unit" />
                                <span class="input-group-text" id="get-satuan-sedang-awal">{{ $item->medium_unit ? '1 ' . $item->medium_unit : '-' }}</span>
                                <input type="number" class="form-control" placeholder="nilai konversi ke satuan terkecil" name="medium_to_small" value="{{ old('medium_to_small', $item->medium_to_small ?? '') }}"/>
                                <span class="input-group-text" id="get-satuan-kecil">{{ $item->small_unit ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="satuan-terbesar" class="form-label">Satuan Terbesar</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" placeholder="Input Satuan Terbesar" id="satuan-terbesar" name="big_unit" value="{{ old('big_unit', $item->big_unit ?? '') }}"/>
                                <span class="input-group-text" id="get-satuan-besar">{{ $item->big_unit ? '1 ' . $item->big_unit : '-' }}</span>
                                <input type="mumber" class="form-control" placeholder="nilai konversi ke satuan menengah" name="big_to_medium" value="{{ old('big_to_medium', $item->big_to_medium ?? '') }}"/>
                                <span class="input-group-text" id="get-satuan-sedang-akhir">{{ $item->medium_unit ?? '-' }}</span>
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


@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function(){
      var satuanTerkecil = document.getElementById('satuan-terkecil');
      var satuanSedang = document.getElementById('satuan-menengah');
      var satuanTerbesar = document.getElementById('satuan-terbesar');
  
      var setSatuanKecil = document.getElementById('get-satuan-kecil');
      var setSatuanKecilClass = document.querySelectorAll('.get-satuan-kecil');
      var setSatuanSedang1 = document.getElementById('get-satuan-sedang-awal');
      var setSatuanSedang2 = document.getElementById('get-satuan-sedang-akhir');
      var setSatuanBesar = document.getElementById('get-satuan-besar');
  
  
      satuanTerkecil.addEventListener('keyup', function(){
          setSatuanKecil.textContent = satuanTerkecil.value;
          setSatuanKecilClass.forEach(element => {
            element.textContent = '/' + satuanTerkecil.value;
          });
      });
      satuanTerbesar.addEventListener('keyup', function(){
          setSatuanBesar.textContent = '1 ' + satuanTerbesar.value + ' =';
      });
     
      satuanSedang.addEventListener('keyup', function(){
        setSatuanSedang1.textContent = '1 ' + satuanSedang.value + ' =';
        setSatuanSedang2.textContent = satuanSedang.value;
      });
    })
  </script>
@endsection