@extends('layouts.auth.main')

@section('content')
{{-- @dd(session()->all()) --}}
    <div class="card mb-4">
        <div class="card-header mb-4 border-bottom d-flex justify-content-between">
            <h4 class="m-0 p-0">{{ $title ?? 'Pembelian' }}</h4>
            <h4 class="m-0 p-0 fw-bold fst-italic totalAkhir"></h4>
        </div>
        <div class="card-body border-bottom pb-2">
            <div class="row mb-3 px-3">
                <button class="btn btn-sm btn-dark text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#newProduct" aria-expanded="false" aria-controls="collapseExample">
                    <i class="bx bx-plus"></i> New Product
                </button>
            </div>
            <div class="collapse mb-3" id="newProduct">
                <div class="card bg-label-primary">
                    <form action="{{ route('item/store/add/to.cart') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-3">
                            <label for="kategori-barang" class="form-label col-form-label col-sm-2">Kategori Barang <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <select class="form-select form-control" id="kategori-barang" aria-label="Default select example" name="item_category_id" required>
                                <option selected disabled>-- Pilih Kategori --</option>
                                @foreach ($itemCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('item_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name ?? '-' }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama-barang" class="form-label col-form-label col-sm-2">Nama Barang <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control form-control-md" id="nama-barang" name="name" placeholder="Input Nama Barang" value="{{ old('name') }}" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="kode-barang" class="form-label col-form-label col-sm-2">Kode Barang <span class="text-danger">*</span></label>
                            <div class="col-sm-10">
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
                        <div class="row mb-3">
                            <label for="note" class="form-label">Catatan</label>
                            <textarea class="form-control" id="note" rows="4" name="note">{{ old('note') }}</textarea>
                        </div>
                        <div class="col-md-12 mt-4 border-top">
                            <div class="d-flex justify-content-center mt-4">
                                <button type="submit" class="btn btn-sm btn-success"><i class="bx bx-file"></i> Save & add To Cart</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="row mb-4">
                <div class="input-group">
                    <span class="input-group-text bg-label-primary" id="kode-produk-label"><i class="bx bx-search"></i></span>
                    <input type="text" name="code" id="kode-produk" class="form-control" placeholder="Enter Product Code">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-8">
                    <label for="defaultInput" class="form-label">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-select" onchange="updateSupplier(this.options[this.selectedIndex].text)">
                        @foreach ($suppliers as $sup)
                            @if ($sup->id === old('supplier_id'))
                                <option value="{{ $sup->id }}" selected>{{ $sup->name ?? '-' }}</option>
                            @else
                                <option value="{{ $sup->id }}">{{ $sup->name ?? '-' }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="defaultInput" class="form-label">Tgl. Pembelian</label>
                    <input id="defaultInput" class="form-control" name="tanggal_pembelian" type="date" value="{{ date('Y-m-d') }}"/>
                </div>
            </div>
            <div class="row mb-4">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-secondary">
                            <tr>
                                <th>Aksi</th>
                                <th>Produk</th>
                                <th>Harga Beli + margin (%)</th>
                                {{-- <th>Margin (%)</th> --}}
                                <th>Harga Jual</th>
                                <th>qty</th>
                                <th class="text-end">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (empty(session('data_pembelian')))
                                <tr>
                                    <td colspan="8" class="text-center fst-italic small fw-bold">-- Belum Ada Produk Dalam Keranjang --</td>
                                </tr>  
                            @else    
                                @foreach (session()->get('data_pembelian') as $item)
                                <tr>
                                    <td>
                                        <form action="{{ route('pembelian.destroy', $item['id']) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-icon btn-xs btn-danger"><i class="bx bx-x"></i></button>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="d-block">{{ $item['name'] }}</span>
                                        <span class="badge bg-primary">
                                            <small class="text-start">
                                                kode : {{ $item['barcode'] }} |
                                                Stok : {{ $item['stok'] ?? '0' }} {{ $item['satuan'] }}
                                            </small>
                                        </span>
                                    </td>
                                    <td>
                                        Rp {{ number_format($item['harga_satuan'], 0) }}
                                        +
                                        {{ $item['margin'] ?? 0 }} %
                                        <button type="button" class="btn btn-icon text-warning" onclick="openModalUpdatePrice('{{ encrypt($item['id']) }}', '{{ $item['name'] }}', {{ $item['harga_satuan'] }}, {{ $item['margin'] ?? 0 }}, {{ $item['harga_jual'] ?? 0 }})">
                                            <i class="bx bx-edit"></i>
                                          </button>
                                        {{-- <a class="" href=""><i class="bx bx-edit"></i></a> --}}
                                        {{-- <div class="input-group">
                                            <input type="text" class="form-control price" id="harga-satuan_{{ $loop->iteration }}" name="harga_satuan[]" value="{{ number_format($item['harga_satuan'], 0) }}"/>
                                            <span class="input-group-text">+</span>
                                            <input type="number" class="form-control" value="{{ old('margin', $item['margin'] ?? 0) }}" name="margin" id="margin" placeholder="0"/>
                                        </div> --}}
                                    </td>
                                    {{-- <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control" value="{{ old('margin', $item['margin'] ?? 0) }}" name="margin" id="margin" placeholder="0"/>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </td> --}}
                                    <td class="text-nowrap">
                                        Rp {{ number_format($item['harga_jual'], 0) }}
                                        {{-- <input type="text" class="form-control" value="" id="harga_jual" required disabled/> --}}
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="jumlah" id="jumlah" value="{{ $item['jumlah'] }}" data-encrypt-id="{{ encrypt($item['id']) }}" readonly ondblclick="enableForm(this)">
                                            <span class="input-group-text bg-primary text-white">{{ $item['satuan'] }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end text-nowrap">Rp {{ number_format($item['total_harga'], 0) }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row justify-content-md-end mb-4">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>+ Subtotal</th>
                                <td class="text-end subtotal">Rp. 0</td>
                            </tr>
                            <tr>
                                <th>+ Pajak</th>
                                <td class="pajak p-2">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input id="pajak" class="form-control price text-end" name="invoice_tax" type="text" value="{{ old('invoice_tax', 0) }}" onchange="totalBayar()"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>- Diskon</th>
                                <td class="p-2">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input id="discount_invoice" class="form-control price text-end" name="discount_invoice" type="text" value="{{ old('discount_invoice', 0) }}" onchange="totalBayar()"/>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>+ Biaya Lainnya</th>
                                <td class="biaya-lainnya p-2">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input id="biaya_lainnya" class="form-control price text-end" name="biaya_lainnya" type="text" value="{{ old('biaya_lainnya', 0) }}" onchange="totalBayar()"/>
                                    </div>
                                </td>
                            </tr>
                            <tr class="table-dark">
                                <th>= Total Akhir</th>
                                <td class="text-end totalAkhir">Rp. 0</td>
                            </tr>
                            <tr>
                                <th></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Status <span class="text-danger">*</span></th>
                                <td class="text-end">
                                    <select name="status" id="status" class="form-select" onchange="updateStatus(this.value)">
                                        <option value="pending">Pending</option>
                                        <option value="ordered">Ordered</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran <span class="text-danger">*</span></th>
                                <td class="text-end">
                                    <select name="payment_method" id="payment_method" onchange="updatePaymentMethod(this.value)" class="form-select">
                                        <option value="Tunai">Tunai</option>
                                        <option value="Kartu Kredit">Kartu Kredit</option>
                                        <option value="Transfer Bank">Transfer Bank</option>
                                        <option value="E-wallet">E-wallet</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Jumlah Bayar <span class="text-danger">*</span></th>
                                <td class="text-end">
                                    <div class="input-group input-group-md">
                                        @if ($systemSetting?->currency_position_default == 'prefix')
                                            <span class="input-group-text bg-primary text-white">Rp. </span>
                                        @endif
                                        <input type="text" class="form-control text-end" id="amount_paid" name="amount_paid" placeholder="0,00" onkeyup="jumBayar(this)" required/>
                
                                        @if ($systemSetting?->currency_position_default == 'suffix')
                                            <span class="input-group-text bg-primary text-white">Rp. </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            {{-- <div class="row mb-4"> --}}
                {{-- <div class="col-md-4">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select" onchange="updateStatus(this.value)">
                        <option value="pending">Pending</option>
                        <option value="ordered">Ordered</option>
                        <option value="completed">Completed</option>
                    </select>
                </div> --}}
                {{-- <div class="col-md-4">
                    <label for="payment_method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="payment_method" id="payment_method" onchange="updatePaymentMethod(this.value)" class="form-select">
                        <option value="Tunai">Tunai</option>
                        <option value="Kartu Kredit">Kartu Kredit</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="E-wallet">E-wallet</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div> --}}
                {{-- <div class="col-md-4">
                    <label for="amount_paid" class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                    <div class="input-group input-group-md">
                        @if ($systemSetting?->currency_position_default == 'prefix')
                            <span class="input-group-text bg-primary text-white">Rp. </span>
                        @endif
                        <input type="text" class="form-control text-end" id="amount_paid" name="amount_paid" placeholder="0,00" onkeyup="jumBayar(this)" required/>

                        @if ($systemSetting?->currency_position_default == 'suffix')
                            <span class="input-group-text bg-primary text-white">Rp. </span>
                        @endif
                    </div>
                </div> --}}
            {{-- </div> --}}

            

            <div class="border-top mt-4 ">
                <div class="d-flex justify-content-center mt-4">
                    <button class="btn btn-md btn-danger me-2" type="button" data-warning="Kosongkan keranjang belanja ?" data-url="{{ route('pembelian.reset') }}" onclick="showModalDelete(this)"><i class="bx bx-reset"></i> Reset</button>
                    <button type="button" class="btn btn-md btn-success" data-bs-toggle="modal" data-bs-target="#modalLong"><i class="bx bx-check"></i> Checkout</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal with long content -->
      <!-- Modal -->
      <div class="modal fade" id="modalLong" tabindex="-1" aria-labelledby="modalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header border-bottom d-block">
              <h5 class="modal-title" id="modalLongTitle">Konfirmasi Pembelian</h5>
              {{-- <p class="small my-0 py-0 text-uppercase">Order ID : <span class="fw-bold">4910487129047124</span></p> --}}
              <p class="small my-0 py-0 text-uppercase">Transaction ID : <span class="fw-bold">-</span></p>
            </div>
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    {{-- <td>No. Nota : <span>CS/103/241231/0121</span></td> --}}
                                    <td>No. Nota : <span>-</span></td>
                                    <td>Tanggal : <span>{{ date('d/m/Y') }}</span></td>
                                </tr>
                                <tr>
                                    <td>Supplier : <span id="namaSupplier">-</span></td>
                                    <td>Jam : <span>{{ date('H:i') }}</span></td>
                                </tr>
                                <tr>
                                    <td>Status Bayar : <span class="status_bayar text-white">-</span></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                           
                            <tbody>
                                @foreach (session()->get('data_pembelian') as $item)
                                    <tr>
                                        <td>{{ $item['name'] ?? '' }}</td>
                                        <td>{{ $item['jumlah'] }}</td>
                                        {{-- <td>{{ $item['harga_satuan'] }}</td> --}}
                                        <td>Rp. {{ number_format($item['harga_satuan'], 0) }}</td>
                                        {{-- <td>{{ $item['total_harga'] }}</td> --}}
                                        <td>Rp. {{ number_format($item['total_harga'], 0) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3">Subtotal</th>
                                    <th class="subtotal">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Items</th>
                                    <th class="totalItems">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Total Qty</th>
                                    <th class="totalQty">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Pajak</th>
                                    <th class="totalPajak">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Biaya Lainnya</th>
                                    <th class="biayaLainnya">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Diskon</th>
                                    <th class="totalDiskon">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="fw-bold">Total</th>
                                    <th class="fw-bold totalAkhir">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <input type="hidden" name="metode_pembayaran" required>
                                        Metode Pembayaran
                                    </th>
                                    <th id="metodePembayaran">-</th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <input type="hidden" name="jumlah_bayar" required>
                                        Jumlah bayar
                                    </th>
                                    <th id="jmlBayar">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="fw-bold text-uppercase fst-italic">Kembalian</th>
                                    <th class="kembalian">Rp. 0</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-outline-primary">Lanjutkan</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    <!-- End Modal with long content -->

    {{-- modal update harga beli dan margin --}}
    <div class="modal fade" id="smallModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modal-title"></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                            <label for="new_cost" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-md price" id="new_cost" name="cost" placeholder="0,00" value="0" required />
                    </div>    
                    <div class="row">
                        <label for="new_margin" class="form-label">Margin (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-md" id="new_margin" name="margin" placeholder="0" min="0" value="0" required />
                    </div>
                    <div class="row">
                        <label for="new_price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                        <input type="text" name="price" id="new_price" class="form-control form-control-md" placeholder="0,00" value="0" readonly required />
                        <div id="info-harga"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
          </div>
        </div>
    </div>
    {{-- end modal update harga beli dan margin --}}

@endsection
<x-modal-confirm-delete></x-modal-confirm-delete>


@push('scripts')
    <script>
        // add item to cart
        const barcodeInput = document.getElementById('kode-produk');
        const name = document.getElementById('nama-produk');
        const stok = document.getElementById('stok-produk');
        const harga = document.getElementById('harga-satuan');
        barcodeInput.addEventListener('change', function(e){
            const barcodeValue = this.value.trim();
            // console.log(barcodeValue);
            fetch(`/pembelian/store/${barcodeValue}`)
            .then(response => response.json())
            .then(res => {
                if (res.status_code === 200) {
                    window.location.reload();
                } else {
                    name.value = null;
                    stok.value = null;
                    harga.value = null;
                    console.log(res.message);
                }
            })
            .catch(error => console.error('Error: ', error, window.location.reload()));
            barcodeInput.value = null;
        });
    </script>
    <script>
        // enable form
        function enableForm(element){
            $(element).attr('readonly', false);
            element.addEventListener('change', function(e){
               const encryptedId = element.dataset.encryptId;
               const bodyReq = {
                jumlah : element.value,
               };

               fetch(`/pembelian/update/${encryptedId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type' : 'application/json',
                        'Accept' : 'application/json',
                        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify(bodyReq)
               })
               .then(response => response.json())
               .then(res => {
                if (res.status_code === 200) {
                    window.location.reload();
                } else {
                    $(e).attr('readonly', true);
                    console.log(res.message);
                }
               })
               .catch(error => console.error('Error: ', error));
            });
        }
        // setting global variabel
        // let symbol, position, thouSeparator, decSeparator;
        $(document).ready(function(){
            $('#kode-produk').focus();

            const selectSupplier = document.getElementById('supplier_id');
            updateSupplier(selectSupplier.options[selectSupplier.selectedIndex].text);
            updatePaymentMethod($('#payment_method').val());
            updateStatus($('#status').val());
            // const setting = @json($systemSetting);
            // symbol = setting.currency.symbol;
            // position = setting.currency_position_default;
            // thouSeparator = setting.currency.thousand_separator;
            // decSeparator = setting.currency.decimal_separator;
            sumAll();
        });
        
        let totalAkhir = 0;
        let totalDiskon = 0;
        let biayaLainnya = 0;
        let totalPajak = 0;
        let total_bayar = 0;
        function sumAll(){
            // sum all data
            const data = @json(session('data_pembelian'));
            const dataArr = Object.values(data);
            let totalQty = 0;
            if (dataArr.length !== 0) {
                dataArr.forEach(function (item){
                    totalQty += parseInt(item.jumlah);
                    totalAkhir += parseInt(item.total_harga);
                });
            }
            $('.totalItems').text(dataArr.length);
            $('.totalQty').text(totalQty);
            $('.subtotal').text(rupiahFormatter(totalAkhir));

            totalBayar();
        }

        function totalBayar(){
            totalDiskon = parseInt($('#discount_invoice').val().replace(/[^0-9]/g, ''));
            totalPajak = parseInt($('#pajak').val().replace(/[^0-9]/g, ''));
            biayaLainnya = parseInt($('#biaya_lainnya').val().replace(/[^0-9]/g, ''));
            $('.totalDiskon').text(rupiahFormatter(totalDiskon));
            $('.totalPajak').text(rupiahFormatter(totalPajak));
            $('.biayaLainnya').text(rupiahFormatter(biayaLainnya));

            total_bayar = totalAkhir + totalPajak + biayaLainnya - totalDiskon;
            $('.totalAkhir').text(rupiahFormatter(total_bayar));
        }
    </script>
    <script>
        $('#modalLong').keypress(function (e){
            if (e.key === 'Enter') {
                $(this).find('form').submit();
            }
        })
        function jumBayar(element){
            let value = rupiahFormatter(element.value.replace(/[^0-9]/g, ''));   //"\d" sama dengan [0-9], "^" artinya bukan, dan decimal separator diambil dari variabel
            element.value = value.replace(/[^0-9.]/g, '');

            let formatAsli = value.replace(/[^0-9]/g, '');

            let kembalian = formatAsli - total_bayar;
            $('.kembalian').text(rupiahFormatter(kembalian));
            $('#jmlBayar').text(value);
            $('input[name="jumlah_bayar"]').val(formatAsli);
        }
        
        function updateSupplier(value){
            $('#namaSupplier').text(value);
        }
        function updatePaymentMethod(value){
            $('#metodePembayaran').text(value);
            $('input[name="metode_pembayaran"]').val(value);
        }

        function updateStatus(value){
            $('.status_bayar').text(value);
            $('.status_bayar').removeClass('badge bg-warning');
            $('.status_bayar').removeClass('badge bg-primary');
            $('.status_bayar').removeClass('badge bg-success');
            $('.status_bayar').removeClass('badge bg-danger');
            if (value === 'pending') {
                $('.status_bayar').addClass('badge bg-warning');
            } else if(value === 'ordered'){
                $('.status_bayar').addClass('badge bg-primary');
            } else if(value === 'completed'){
                $('.status_bayar').addClass('badge bg-success');
            }else{
                $('.status_bayar').addClass('badge bg-danger');
            }
        }

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

        function openModalUpdatePrice(id, name, cost, margin, price){
            const currentPrice = parseInt(price);
            $('#smallModal #modal-title').text(name);
            $('#smallModal #new_cost').val(rupiahFormatter(cost).replace(/[^0-9.]/g, ''));
            $('#smallModal #new_margin').val(margin);
            $('#smallModal #new_price').val(rupiahFormatter(price).replace(/[^0-9.]/g, ''));
            $('#smallModal form').attr('action', '{{ route("update/price.item", "") }}' + "/" + id);
            $('#smallModal').modal('show');
            
            $('#smallModal #new_cost').keyup(function(){
                const newCost = this.value.replace(/[^0-9]/g, '');
                const newMargin = $('#smallModal #new_margin').val().replace(/[^0-9]/g, '');
                const newPrice = parseInt(newCost) * (parseInt(newMargin) / 100) + parseInt(newCost);

                const newHarga = rupiahFormatter(newPrice).replace(/[^0-9.]/g, '');
                $('#smallModal #new_price').val(newHarga);

                if (newPrice > currentPrice) {
                    $('#smallModal #info-harga').attr('class', 'text-warning').html(`<i class="bx bx-up-arrow-alt"></i> Harga naik sebesar: ${rupiahFormatter(newPrice-currentPrice)}`);
                } else if (newPrice < currentPrice) {
                    $('#smallModal #info-harga').attr('class', 'text-danger').html(`<i class="bx bx-up-arrow-alt"></i> Harga turun sebesar: ${rupiahFormatter(currentPrice-newPrice)}`);
                } else if (newPrice === currentPrice) {
                    $('#smallModal #info-harga').attr('class', 'text-success').html(`<i class="bx bx-check"></i> Harga tidak berubah`);
                }else{
                    $('#smallModal #info-harga').html(`Harga Tidak Valid`);
                }
            });
            $('#smallModal #new_margin').keyup(function(){
                const newCost = $('#smallModal #new_cost').val().replace(/[^0-9]/g, '');
                const newMargin = this.value.replace(/[^0-9]/g, '');
                const newPrice = parseInt(newCost) * (parseInt(newMargin) / 100) + parseInt(newCost);

                const newHarga = rupiahFormatter(newPrice).replace(/[^0-9.]/g, '');
                $('#smallModal #new_price').val(newHarga);

                if (newPrice > currentPrice) {
                    $('#smallModal #info-harga').attr('class', 'text-warning').html(`<i class="bx bx-up-arrow-alt"></i> Harga naik sebesar: ${rupiahFormatter(newPrice-currentPrice)}`);
                } else if (newPrice < currentPrice) {
                    $('#smallModal #info-harga').attr('class', 'text-danger').html(`<i class="bx bx-up-arrow-alt"></i> Harga turun sebesar: ${rupiahFormatter(currentPrice-newPrice)}`);
                } else if (newPrice === currentPrice) {
                    $('#smallModal #info-harga').attr('class', 'text-success').html(`<i class="bx bx-check"></i> Harga tidak berubah`);
                }else{
                    $('#smallModal #info-harga').html(`Harga Tidak Valid`);
                }
                return this.value = parseInt(newMargin);
            });
           
        }

    </script>
@endpush