@extends('layouts.auth.main')

@section('content')
{{-- @dd(session()->all()) --}}
    <div class="card">
        <div class="card-header mb-4 border-bottom d-flex justify-content-between">
            <h4 class="m-0 p-0">Penjualan</h4>
            <h4 class="m-0 p-0" id="totalPembayaran"></h4>
        </div>
        <div class="card-body">
            {{-- <form action="{{ route('sales.store') }}" method="POST">
                @csrf --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <input type="text" name="code" id="kode-produk" class="form-control" placeholder="Enter Product Code">
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
                        @if (empty(session('data')))
                            <tr>
                                <td colspan="8" class="text-center fst-italic small fw-bold">-- Belum Ada Produk Dalam Keranjang --</td>
                            </tr>  
                        @else    
                            @foreach (session()->get('data') as $item)
                            <tr>
                                <td>
                                    <form action="{{ route('cart.destroy', $item['id']) }}" method="POST" style="display:inline;">
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
                                <td>{{ number_format($item['harga_satuan']) }}</td>
                                <td>{{ $item['diskon'] }}</td>
                                <td class="text-end">{{ number_format($item['total_harga']) }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="row mt-4 mb-1">
                <div class="col-sm-9 text-end">
                </div>
                <div class="col-sm-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">items</span>
                        <input type="text" id="totalItems" class="form-control text-end" placeholder="0" disabled/>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-9 text-end">
                </div>
                <div class="col-sm-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">qty</span>
                        <input type="text" id="totalQty" class="form-control text-end" placeholder="0" disabled/>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-9 text-end">
                    Total Akhir
                </div>
                <div class="col-sm-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" name="total-akhir" id="totalAkhir" class="form-control text-end" placeholder="0.000" @readonly(true)/>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-9 text-end">
                    Total Diskon
                </div>
                <div class="col-sm-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" name="total-diskon" id="totalDiskon" class="form-control text-end" placeholder="0.000" readonly/>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-9 text-end">
                    Total Pembayaran
                </div>
                <div class="col-sm-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp. </span>
                        <input type="text" name="total-bayar" id="totalBayar" class="form-control text-end" placeholder="0.000" @readonly(true)/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9 text-end">
                    Metode Pembayaran
                </div>
                <div class="col-sm-3">
                    <select class="form-control form-control-sm text-end" id="metodePembayaran" name="metode_pembayaran">
                        <option value="tunai">Tunai</option>
                        <option value="kartu_kredit">Kartu Kredit</option>
                        <option value="e_wallet">E-Wallet</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12 mt-4 border-top">
                <div class="d-flex justify-content-center mt-4">
                    <button class="btn btn-md btn-danger me-2" type="button" data-warning="Kosongkan keranjang belanja ?" data-url="{{ route('cart.reset') }}" onclick="showModalDelete(this)"><i class="bx bx-reset"></i> Reset</button>
                    <button type="submit" class="btn btn-md btn-success"><i class="bx bx-check"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-modal-confirm-delete></x-modal-confirm-delete>

@section('script')
    <script>
        // add item to cart
        const barcodeInput = document.getElementById('kode-produk');
        const name = document.getElementById('nama-produk');
        const stok = document.getElementById('stok-produk');
        const harga = document.getElementById('harga-satuan');
        barcodeInput.addEventListener('change', function(e){
            const barcodeValue = this.value.trim();
            fetch(`/cart/store/${barcodeValue}`)
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

               fetch(`/cart/update/${encryptedId}`, {
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
        $(document).ready(function(){
            $('#kode-produk').focus();
            sumAll();
        });
        
        function sumAll(){
            // sum all data
            const data = @json(session('data'));
            const dataArr = Object.values(data);
            let totalQty = 0;
            let totalDiskon = 0;
            let totalAkhir = 0;
            if (dataArr.length !== 0) {
                dataArr.forEach(function (item){
                    totalQty += parseInt(item.jumlah);
                    totalDiskon += Number(item.diskon);
                    totalAkhir += Number(item.total_harga);
                });
            }
            $('#totalItems').val(dataArr.length);
            $('#totalQty').val(totalQty);
            $('#totalDiskon').val(formatterFunct(totalDiskon));
            $('#totalAkhir').val(formatterFunct(totalAkhir));
            const totalBayar = totalAkhir-totalDiskon;
            $('#totalBayar').val(formatterFunct(totalBayar));
            $('#totalPembayaran').text('Rp. ' + formatterFunct(totalBayar));

        }

        function formatterFunct(number){
            const formatted = number.toLocaleString('id-ID', {
                style: 'decimal',
                currency: 'IDR', // opsional
                currencyDisplay: 'symbol', // opsional
            });
            return formatted;
        }
    </script>
@endsection