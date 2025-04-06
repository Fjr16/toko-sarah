@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Edit {{ $title ?? '' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route("barang.update", encrypt($item->id)) }}" method="POST">
                @csrf
                @method('PUT')
                    <div class="row mb-3">
                        <label for="kategori-barang" class="form-label">Kategori Barang <span class="text-danger">*</span></label>
                        <select class="form-select form-control" id="kategori-barang" aria-label="Default select example" name="item_category_id" required>
                            <option selected disabled>-- Pilih Kategori --</option>
                            @foreach ($data as $cat)
                            <option value="{{ $cat->id }}" {{ old('item_category_id', $item->item_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name ?? '-' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-7">
                            <label for="nama-barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-md" id="nama-barang" name="name" placeholder="Input Nama Barang" value="{{ old('name', $item->name) }}" required />
                        </div>
                        <div class="col-md-5">
                            <label for="kode-barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-md" id="kode-barang" name="code" placeholder="Input / scan kode barang disini" value="{{ old('code', $item->code) }}" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md">
                            <label for="satuan-terkecil" class="form-label">Satuan Terkecil <span class="text-danger">*</span></label>
                            <input name="small_unit" class="form-control form-control-md" id="satuan-terkecil" placeholder="Input Satuan Terkecil" value="{{ old('small_unit', $item->small_unit) }}" required></input>
                        </div>
                        <div class="col-md">
                            <label for="satuan-menengah" class="form-label">Satuan Menengah</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" placeholder="Input Satuan Menengah" id="satuan-menengah" value="{{ old('medium_unit', $item->medium_unit ?? '') }}" name="medium_unit" />
                                <span class="input-group-text" id="get-satuan-sedang-awal">{{ $item->medium_unit ? '1 ' . $item->medium_unit : '-' }}</span>
                                <input type="number" class="form-control" placeholder="nilai konversi ke satuan terkecil" name="medium_to_small" value="{{ old('medium_to_small', $item->medium_to_small ?? '') }}"/>
                                <span class="input-group-text" id="get-satuan-kecil">{{ $item->small_unit ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md">
                            <label for="satuan-terbesar" class="form-label">Satuan Terbesar</label>
                            <div class="input-group input-group-merge">
                                <input type="text" class="form-control" placeholder="Input Satuan Terbesar" id="satuan-terbesar" name="big_unit" value="{{ old('big_unit', $item->big_unit ?? '') }}"/>
                                <span class="input-group-text" id="get-satuan-besar">{{ $item->big_unit ? '1 ' . $item->big_unit : '-' }}</span>
                                <input type="mumber" class="form-control" placeholder="nilai konversi ke satuan menengah" name="big_to_medium" value="{{ old('big_to_medium', $item->big_to_medium ?? '') }}"/>
                                <span class="input-group-text" id="get-satuan-sedang-akhir">{{ $item->medium_unit ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cost" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                            <div class="input-group">
                                @if ($systemSetting?->currency_position_default == 'prefix')
                                    <span class="input-group-text bg-dark text-white">{{ $systemSetting?->currency?->symbol ?? null }}</span>                                
                                @endif
                                <input type="text" name="cost" id="cost" value="{{ old('cost', number_format($item->cost, 2, $systemSetting?->currency?->decimal_separator, $systemSetting?->currency?->thousand_separator)) }}" class="form-control form-control-md" placeholder="0" onkeyup="number_format('{{ $systemSetting?->currency?->thousand_separator ?? null }}', '{{ $systemSetting?->currency?->decimal_separator ?? null }}', this)" required />

                                @if ($systemSetting?->currency_position_default == 'suffix')
                                    <span class="input-group-text bg-dark text-white">{{ $systemSetting?->currency?->symbol ?? null }}</span>                                
                                @endif
                                <span class="input-group-text get-satuan-kecil bg-primary text-white">/{{ $item->small_unit ?? '' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                @if ($systemSetting?->currency_position_default == 'prefix')
                                    <span class="input-group-text bg-dark text-white">{{ $systemSetting?->currency?->symbol ?? null }}</span>                                
                                @endif
                                <input type="text" name="price" id="price" value="{{ old('price',number_format($item->price, 2, $systemSetting?->currency?->decimal_separator, $systemSetting?->currency?->thousand_separator)) }}" class="form-control form-control-md" placeholder="0" onkeyup="number_format('{{ $systemSetting?->currency?->thousand_separator ?? null }}', '{{ $systemSetting?->currency?->decimal_separator ?? null }}', this)" required />

                                @if ($systemSetting?->currency_position_default == 'suffix')
                                    <span class="input-group-text bg-dark text-white">{{ $systemSetting?->currency?->symbol ?? null }}</span>                                
                                @endif
                                <span class="input-group-text get-satuan-kecil bg-primary text-white">/{{ $item->small_unit ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md">
                            <label for="stok-awal" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="mumber" name="stok" id="stok-awal" value="{{ old('stok', $item->stok ?? 0) }}" class="form-control form-control-md" placeholder="0" required />
                                <span class="input-group-text get-satuan-kecil">/{{ $item->small_unit ?? '' }}</span>
                            </div>
                        </div>
                        <div class="col-md">
                            <label for="stok_alert" class="form-label">Peringatan Stok <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="mumber" name="stok_alert" id="stok_alert" class="form-control form-control-md" placeholder="0" value="{{ old('stok_alert', $item->stok_alert ?? 0) }}" required />
                                <span class="input-group-text get-satuan-kecil">/ {{ $item->small_unit ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md">
                            <label for="tax" class="form-label">Pajak (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-md" id="tax" name="tax" placeholder="0" value="{{ old('tax', (int) $item->tax) }}"/>
                                <span class="input-group-text get-satuan-kecil">/ {{ $item->small_unit ?? '' }}</span>
                            </div>
                        </div>
                        <div class="col-md">
                            <label for="tax_type" class="form-label">Tipe Pajak <span class="text-danger">*</span></label>
                            <select class="form-select form-control" id="tax_type" aria-label="Default select example" name="tax_type">
                                <option value="none" {{ old('tax_type', $item->tax_type) == 'none' ? 'selected' : '' }}>None</option>
                                <option value="inclusive" {{ old('tax_type', $item->tax_type) == 'inclusive' ? 'selected' : '' }}>Inclusive</option>
                                <option value="exclusive" {{ old('tax_type',$item->tax_type) == 'exclusive' ? 'selected' : '' }}>Exclusive</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="note" class="form-label">Catatan</label>
                        <textarea class="form-control" id="note" rows="4" name="note">{{ old('note', $item->note ?? '') }}</textarea>
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


{{-- @push('scripts')
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
@endpush --}}