<div class="card bg-label-primary">
    <form id="product-form">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-10">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <select class="form-select form-control" id="kategori-barang" aria-label="Default select example" name="item_category_id" required>
                            <option selected disabled>-- Pilih Kategori --</option>
                            @foreach ($itemCategories as $cat)
                                <option value="{{ $cat->id }}" {{ old('item_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name ?? '-' }}</option>
                            @endforeach
                            </select>
                        </div>
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
                        <div class="col-md-4">
                            <label for="satuan-terkecil" class="form-label">Satuan Terkecil <span class="text-danger">*</span></label>
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
                </div>
                <div class="col-md-2 d-flex justify-content-center align-items-center pt-0">
                    <label for="fotoProduk" class="upload-box" id="uploadBox">
                        <span id="uploadText" style="display:block;">+ Upload Foto</span>
                        <img id="previewImg" alt="Preview" style="display:none;"/>
                    </label>
                    <input type="file" id="fotoProduk" name="image" accept="image/*" style="display: none;">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-5">
                    <label for="cost" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                    <div class="input-group">
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
                    <label for="stok-awal" class="form-label">Total stok / Stok Awal <span class="text-danger">*</span></label>
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
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" rows="4" name="description">{{ old('description') }}</textarea>
            </div>
            <div class="col-md-12 mt-4 border-top">
                <div class="d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-sm btn-success" id="product_submit"><i class="bx bx-file"></i> Save & add To Cart</button>
                </div>
            </div>
        </div>
    </form>
</div>