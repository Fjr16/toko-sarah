@extends('layouts.auth.main')

@section('content')
{{-- @dd(session()->all()) --}}
    <div class="card">
        <div class="card-header mb-4 border-bottom d-flex justify-content-between">
            <h4 class="m-0 p-0">Penjualan</h4>
            <h4 class="m-0 p-0 fw-bold fst-italic totalAkhir"></h4>
        </div>
        <div class="card-body">
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
                        {{-- <tr class="fw-bold table-secondary">
                            <td colspan="7">Items</td>
                            <td class="text-end totalItems">-</td>
                        </tr>
                        <tr class="fw-bold table-secondary">
                            <td colspan="7">Total Items</td>
                            <td class="text-end totalQty">-</td>
                        </tr> --}}
                        <tr class="fw-bold table-secondary fst-italic">
                            <td colspan="7">Subtotal</td>
                            <td class="text-end subtotal">Rp. -</td>
                        </tr>
                        <tr class="fw-bold table-secondary fst-italic">
                            <td colspan="7">Diskon</td>
                            <td class="text-end totalDiskon">Rp. -</td>
                        </tr>
                        <tr class="fw-bold table-secondary fst-italic">
                            <td colspan="7">Total Akhir</td>
                            <td class="text-end totalAkhir">Rp. -</td>
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
                                    <span class="input-group-text">Rp. </span>
                                    <input type="text" id="jumlahBayar" class="form-control text-end bg-secondary text-white" placeholder="0"/>
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
                    <button class="btn btn-md btn-danger me-2" type="button" data-warning="Kosongkan keranjang belanja ?" data-url="{{ route('cart.reset') }}" onclick="showModalDelete(this)"><i class="bx bx-reset"></i> Reset</button>
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
              <h5 class="modal-title" id="modalLongTitle">Konfirmasi Penjualan</h5>
              {{-- <p class="small my-0 py-0 text-uppercase">Order ID : <span class="fw-bold">4910487129047124</span></p> --}}
              <p class="small my-0 py-0 text-uppercase">Order ID : <span class="fw-bold">-</span></p>
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
                                    <td>Order : <span>{{ auth()->user()->name ?? '' }}</span></td>
                                    <td>Jam : <span>{{ date('H:i') }}</span></td>
                                </tr>
                                <tr>
                                    <td>Kasir : <span>{{ auth()->user()->name ?? '' }}</span></td>
                                    <td>Nama Order : <span>Pelanggan</span></td>
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
                                @foreach (session()->get('data') as $item)
                                    <tr>
                                        <td>{{ $item['name'] ?? '' }}</td>
                                        <td>{{ $item['jumlah'] }}</td>
                                        <td>{{ $item['harga_satuan'] }}</td>
                                        <td>{{ $item['total_harga'] }}</td>
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
                                    <th class="fw-bold totalAkhir">Rp. -</th>
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
                                    <th id="jmlBayar">Rp. -</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="fw-bold text-uppercase fst-italic">Kembalian</th>
                                    <th class="kembalian">Rp. -</th>
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
            $('#tipeBayar').text($('#metodePembayaran').val());
            $('input[name="tipe_bayar"]').val($('#metodePembayaran').val());
            sumAll();
        });
        
        let totalAkhir = 0;
        function sumAll(){
            // sum all data
            const data = @json(session('data'));
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
            $('.totalDiskon').text('Rp. ' + formatterFunct(totalDiskon));
            $('.subtotal').text('Rp. ' + formatterFunct(totalAkhir));
            const totalBayar = totalAkhir-totalDiskon;
            $('.totalAkhir').text('Rp. ' + formatterFunct(totalBayar));

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
    <script>
        $('#modalLong').keypress(function (e){
            if (e.key === 'Enter') {
                $(this).find('form').submit();
            }
        })
        $('#jumlahBayar').keyup(function (){
            this.value = formatRupiah(this.value);
            let formatAsli = this.value.replace(/[^,\d]/g, '').toString();
            $('#jmlBayar').text('Rp. ' + formatterFunct(parseInt(formatAsli)));
            const kembalian = parseInt(formatAsli) - totalAkhir;
            $('.kembalian').text('Rp. ' + formatterFunct(parseInt(kembalian)))
            $('input[name="jumlah_bayar"]').val(parseInt(formatAsli));
        });
        $('#metodePembayaran').change(function(){
            $('#tipeBayar').text(this.value);
            $('input[name="tipe_bayar"]').val(this.value);
        })
    </script>
@endpush