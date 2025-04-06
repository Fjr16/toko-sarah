@extends('layouts.auth.main')

@section('content')
{{-- @dd(session()->all()) --}}
    <div class="card">
        <div class="card-header mb-4 border-bottom d-flex justify-content-between">
            <h4 class="m-0 p-0">{{ $title ?? 'Pembelian' }}</h4>
            <h4 class="m-0 p-0 fw-bold fst-italic totalAkhir"></h4>
        </div>
        <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-4">
                        <label for="defaultInput" class="form-label">Tgl. Pembelian</label>
                        <input id="defaultInput" class="form-control" name="tanggal_pembelian" type="date" value="{{ date('Y-m-d') }}"/>
                    </div>
                    <div class="col-sm-4">
                        <label for="defaultInput" class="form-label">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-select">
                            @foreach ($suppliers as $sup)
                                @if ($sup->id === old('supplier_id'))
                                    <option value="{{ $sup->id }}" selected>{{ $sup->name ?? '-' }}</option>
                                @else
                                    <option value="{{ $sup->id }}" selected>{{ $sup->name ?? '-' }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="defaultInput" class="form-label">Status Bayar</label>
                        <select name="tipe_bayar" id="tipe_bayar" class="form-select">
                            <option value="Lunas">Lunas</option>
                            <option value="Hutang">Hutang</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3 border-top pt-4">
                    <h5>Detail Pembelian</h5>
                    <div class="col-md-12">
                        <input type="text" name="code" id="kode-produk" class="form-control" placeholder="Enter Product Code">
                    </div>
                </div>
                <div class="row mb-4 px-3">
                    <button class="btn btn-sm btn-dark text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#newProduct" aria-expanded="false" aria-controls="collapseExample">
                        <i class="bx bx-plus"></i> New Product
                    </button>
                </div>
                <div class="collapse mb-4" id="newProduct">
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
                                <div class="col-md-6">
                                    <label for="cost" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        @if ($systemSetting?->currency_position_default == 'prefix')
                                            <span class="input-group-text bg-dark text-white">{{ $systemSetting?->currency?->symbol ?? null }}</span>                                
                                        @endif
                                        <input type="text" class="form-control form-control-md price" id="cost" name="cost" placeholder="0,00" value="{{ old('cost') }}" onkeyup="number_format('{{ $systemSetting?->currency?->thousand_separator ?? null }}', '{{ $systemSetting?->currency?->decimal_separator ?? null }}', this)" required />
                                        @if ($systemSetting?->currency_position_default == 'suffix')
                                            <span class="input-group-text bg-dark text-white">{{ $systemSetting?->currency?->symbol ?? null }}</span>                                
                                        @endif
                                        <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        @if ($systemSetting?->currency_position_default == 'prefix')
                                            <span class="input-group-text bg-dark text-white">{{ $systemSetting?->currency?->symbol ?? null }}</span>                                
                                        @endif
                                        <input type="text" name="price" id="price" class="form-control form-control-md price" placeholder="0,00" value="{{ old('price')}}" onkeyup="number_format('{{ $systemSetting?->currency?->thousand_separator ?? null }}', '{{ $systemSetting?->currency?->decimal_separator ?? null }}', this)" required />
                                        @if ($systemSetting?->currency_position_default == 'suffix')
                                            <span class="input-group-text bg-dark text-white">{{ $systemSetting?->currency?->symbol ?? null }}</span>                                
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

            <div class="table-responsive">
                <table class="table">
                    <thead class="table-secondary">
                        <tr>
                            <th>Aksi</th>
                            <th>Barcode</th>
                            <th>Nama Produk</th>
                            <th>qty</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Diskon</th>
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
                                <td>{{ $item['barcode'] }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>
                                    <input type="number" class="form-control" name="jumlah" id="jumlah" value="{{ $item['jumlah'] }}" data-encrypt-id="{{ encrypt($item['id']) }}" readonly ondblclick="enableForm(this)">
                                </td>
                                <td>{{ $item['satuan'] }}</td>
                                <td>{{ number_format($item['harga_satuan'], 2, $systemSetting?->currency?->decimal_separator, $systemSetting?->currency?->thousand_separator) }}</td>
                                <td>{{ $item['diskon'] }}</td>
                                <td class="text-end">{{ number_format($item['total_harga'], 2, $systemSetting?->currency?->decimal_separator, $systemSetting?->currency?->thousand_separator) }}</td>
                            </tr>
                            @endforeach
                        @endif
                        <tr class="fw-bold table-secondary fst-italic">
                            <td colspan="7">Subtotal</td>
                            <td class="text-end subtotal">{{ $systemSetting?->currency?->symbol }} -</td>
                        </tr>
                        <tr class="fw-bold table-secondary fst-italic">
                            <td colspan="7">Diskon</td>
                            <td class="text-end totalDiskon">{{ $systemSetting?->currency?->symbol }} -</td>
                        </tr>
                        <tr class="fw-bold table-secondary fst-italic">
                            <td colspan="7">Total Akhir</td>
                            <td class="text-end totalAkhir">{{ $systemSetting?->currency?->symbol }} -</td>
                        </tr>
                        <tr class="fw-bold table-secondary fst-italic">
                            <td colspan="7">Pembayaran</td>
                            <td colspan="2" class="text-end d-block px-0">
                                <select class="form-control form-control-md text-end mb-1" id="metodePembayaran" name="metode_pembayaran">
                                    <option value="tunai">Tunai</option>
                                    <option value="kartu kredit">Kartu Kredit</option>
                                    <option value="e-wallet">E-Wallet</option>
                                </select>
                                <div class="input-group input-group-md">
                                    @if ($systemSetting?->currency_position_default == 'prefix')
                                        <span class="input-group-text bg-primary text-white">{{ $systemSetting?->currency?->symbol }} </span>
                                    @endif
                                    <input type="text" class="form-control text-end" placeholder="0,00" onkeyup="jumBayar(this)" required/>

                                    @if ($systemSetting?->currency_position_default == 'suffix')
                                        <span class="input-group-text bg-primary text-white">{{ $systemSetting?->currency?->symbol }} </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr class="fw-bold table-danger fst-italic">
                            <td colspan="7">Kembalian</td>
                            <td class="fst-italic text-end">
                                <h4 class="mb-0 kembalian">
                                   -
                                </h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-12 mt-4 ">
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
                                    <td>Supplier : <span>-</span></td>
                                    <td>Jam : <span>{{ date('H:i') }}</span></td>
                                </tr>
                                <tr>
                                    <td>Status Bayar : <span>Lunas</span></td>
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
                                        <td>{{ number_format($item['harga_satuan'], 2, $systemSetting?->currency?->decimal_separator, $systemSetting?->currency?->thousand_separator) }}</td>
                                        {{-- <td>{{ $item['total_harga'] }}</td> --}}
                                        <td class="text-end">{{ number_format($item['total_harga'], 2, $systemSetting?->currency?->decimal_separator, $systemSetting?->currency?->thousand_separator) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3">Subtotal</th>
                                    <th class="subtotal"></th>
                                </tr>
                                <tr>
                                    <th colspan="3">Items</th>
                                    <th class="totalItems">-</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Total Items</th>
                                    <th class="totalQty">-</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Diskon</th>
                                    <th class="totalDiskon">-</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="fw-bold">Total</th>
                                    <th class="fw-bold totalAkhir">{{ $systemSetting?->currency?->symbol }} -</th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <input type="hidden" name="tipe_bayar" required>
                                        Tipe Bayar
                                    </th>
                                    <th id="tipeBayar">-</th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <input type="hidden" name="jumlah_bayar" required>
                                        Jumlah bayar
                                    </th>
                                    <th id="jmlBayar">{{ $systemSetting?->currency?->symbol }} -</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="fw-bold text-uppercase fst-italic">Kembalian</th>
                                    <th class="kembalian">{{ $systemSetting?->currency?->symbol }} -</th>
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
            console.log(barcodeValue);
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
        let symbol, position, thouSeparator, decSeparator;
        $(document).ready(function(){
            $('#kode-produk').focus();
            $('#tipeBayar').text($('#metodePembayaran').val());
            $('input[name="tipe_bayar"]').val($('#metodePembayaran').val());
            const setting = @json($systemSetting);
            symbol = setting.currency.symbol;
            position = setting.currency_position_default;
            thouSeparator = setting.currency.thousand_separator;
            decSeparator = setting.currency.decimal_separator;
            sumAll();
        });
        
        let totalAkhir = 0;
        function sumAll(){
            // sum all data
            const data = @json(session('data_pembelian'));
            const dataArr = Object.values(data);
            let totalQty = 0;
            let totalDiskon = 0;
            if (dataArr.length !== 0) {
                dataArr.forEach(function (item){
                    totalQty += parseInt(item.jumlah);
                    totalDiskon += Number(item.diskon);
                    totalAkhir += Number(item.total_harga);
                });
            }
            // $('#totalItems').val(dataArr.length);
            $('.totalItems').text(dataArr.length);
            $('.totalQty').text(totalQty);
            $('.totalDiskon').text(reformatCurrency(totalDiskon.toFixed(2)));
            $('.subtotal').text(reformatCurrency(totalAkhir.toFixed(2)));
            const totalBayar = totalAkhir-totalDiskon;
            $('.totalAkhir').text(reformatCurrency(totalBayar.toFixed(2)));
        }
    </script>
    <script>
        $('#modalLong').keypress(function (e){
            if (e.key === 'Enter') {
                $(this).find('form').submit();
            }
        })
        function jumBayar(element){
            let value = element.value.replace(new RegExp(`[^\\d${decSeparator}]`, 'g'), '');   //"\d" sama dengan [0-9], "^" artinya bukan, dan decimal separator diambil dari variabel
            element.value = formatRupiah(value,thouSeparator,decSeparator);

            let formatAsli = element.value.replace(new RegExp(`[^\\d${decSeparator}]`, 'g'), '').replace(decSeparator, '.');

            let kembalian = formatAsli - totalAkhir;
            $('.kembalian').text(reformatCurrency(kembalian.toFixed(2)));
            $('#jmlBayar').text(reformatCurrency(formatAsli));
            $('input[name="jumlah_bayar"]').val(formatAsli);

        }
        
        $('#metodePembayaran').change(function(){
            $('#tipeBayar').text(this.value);
            $('input[name="tipe_bayar"]').val(this.value);
        });

        function reformatCurrency(value) {
            return (position == 'prefix' ? symbol + ' ' : '') + (value.replace(/\B(?=(\d{3})+(?!\d))/g, thouSeparator).replace('.', decSeparator)) + (position == 'suffix' ? ' ' + symbol : '');
        }

    </script>
@endpush