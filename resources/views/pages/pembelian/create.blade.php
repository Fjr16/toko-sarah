@extends('layouts.auth2.main')

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
    }
    .upload-box span {
      color: #aaa;
      font-size: 14px;
    }
    .card.border-bottom-0 {
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .card.border-top-0 {
        border-top-left-radius: 0 !important;
        border-top-right-radius: 0 !important;
        margin-top: -1px; /* hilangkan garis ganda */
    }
</style>
@endpush
@section('content')
    <div class="card shadow-sm mb-2 border-bottom-0 rounded-bottom-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <h5 class="fw-bold mb-0 text-uppercase text-dark">{{ $title ?? 'Pembelian' }}</h5>
        <h5 class="fw-bold mb-0 text-success totalAkhir"></h5>
        </div>
        <div class="card-body pb-1">
            <div class="row mb-3 px-3">
                <button class="btn btn-sm btn-dark text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#newProduct" aria-expanded="false" aria-controls="collapseExample">
                    <i class="bx bx-plus"></i> New Product
                </button>
            </div>
            <div class="collapse" id="newProduct">
                @include('pages.pembelian.partials.newProduct')
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-top-0 rounded-top-0">
        <div class="card-body">
            <div id="product-select" style="width: 100%"></div>

            <div class="row my-2">
                <div class="col-md-8">
                    <label for="defaultInput" class="form-label">Supplier</label>
                    <select id="supplier_id" class="form-select" onchange="updateSupplier(this)">
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
                    <input class="form-control" name="tanggal_pembelian" id="tanggal_pembelian" type="date" value="{{ date('Y-m-d') }}" onchange="updatePurchaseDate(this.value)"/>
                </div>
            </div>
            <div class="row mb-4">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
    <!-- Modal with long content -->
      <!-- Modal -->
      {{-- <div class="modal fade" id="modalLong" tabindex="-1" aria-labelledby="modalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header border-bottom d-block">
              <h5 class="modal-title" id="modalLongTitle">Konfirmasi Pembelian</h5>
              <p class="small my-0 py-0 text-uppercase">Transaction ID : <span class="fw-bold">-</span></p>
            </div>
            <form action="{{ route('pembelian/save.all') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Tanggal :
                                        <span id="purchase_date">-</span>
                                        <input type="hidden" name="purchase_date" required>
                                    </td>
                                    <td>Supplier :
                                        <span id="namaSupplier">-</span>
                                        <input type="hidden" name="supplier_id" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jam : <span>{{ date('H:i') }}</span></td>
                                    <td>
                                        Status Bayar : <span class="status_bayar text-white">-</span>
                                        <input type="hidden" name="status" required>
                                    </td>
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
                                        <td>Rp. {{ number_format($item['harga_satuan'], 0) }}</td>
                                        <td>Rp. {{ number_format($item['total_harga'], 0) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3">
                                        <input type="hidden" name="subtotal" required>
                                        Subtotal
                                    </th>
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
                                    <th colspan="3">
                                        <input type="hidden" name="tax" required>
                                        Pajak
                                    </th>
                                    <th class="totalPajak">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <input type="hidden" name="other_cost" required>
                                        Biaya Lainnya
                                    </th>
                                    <th class="biayaLainnya">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <input type="hidden" name="diskon" required>
                                        Diskon
                                    </th>
                                    <th class="totalDiskon">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="fw-bold">
                                        <input type="hidden" name="grand_total" required>
                                        Total
                                    </th>
                                    <th class="fw-bold totalAkhir">Rp. 0</th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <input type="hidden" name="payment_method" required>
                                        Metode Pembayaran
                                    </th>
                                    <th id="metodePembayaran">-</th>
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
      </div> --}}
    <!-- End Modal with long content -->

    {{-- modal update harga beli dan margin --}}
    {{-- <div class="modal fade" id="smallModal" tabindex="-1" aria-hidden="true">
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
    </div> --}}
    {{-- end modal update harga beli dan margin --}}

@endsection
@section('footer')
    <div class="card mt-3 shadow-sm">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
            <small>
                Total Item: <span class="fw-bold totalItem">0</span> |
                Subtotal: <span class="fw-bold subtotal text-primary">Rp0</span> |
                Diskon: <span class="fw-bold totalDiskon text-danger">Rp0</span>
            </small>
            <small class="fw-bold text-success">
                Grand Total: <span class="totalAkhir fs-6">Rp0</span>
            </small>
            </div>

            <div class="row g-2 align-items-center">
            <div class="col-6 col-md-3">
                <div class="input-group input-group-sm">
                <span class="input-group-text">Pajak</span>
                <input type="text" id="invoice_tax" class="form-control text-end price" value="0" onchange="updateInvoiceSummary()">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="input-group input-group-sm">
                <span class="input-group-text">Diskon</span>
                <input type="text" id="invoice_discount" class="form-control text-end price" value="0" onchange="updateInvoiceSummary()">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="input-group input-group-sm">
                <span class="input-group-text">Biaya Lain</span>
                <input type="text" id="invoice_other" class="form-control text-end price" value="0" onchange="updateInvoiceSummary()">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select id="purchase_status" class="form-select form-select-sm" onchange="updateInvoiceSummary()">
                <option value="draft">Draft</option>
                <option value="ordered">Ordered</option>
                <option value="completed">Completed</option>
                </select>
            </div>
            </div>

            <hr class="my-2">

            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-outline-danger btn-sm"
                        data-warning="Kosongkan keranjang?"
                        data-url="{{ route('pembelian.reset') }}"
                        onclick="showModalDelete(this)">
                    <i class="bx bx-reset"></i> Reset
                </button>

                <button type="button" class="btn btn-success btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalLong">
                    <i class="bx bx-check"></i> Checkout
                </button>
            </div>
        </div>
    </div>
@endsection
<x-modal-confirm-delete></x-modal-confirm-delete>


@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        var table = $('#purchasetemp-table').DataTable();
        async function removeItem(pruchaseTempId){
            try {
                const url = 'pembelian/destroy/'+pruchaseTempId;
                const res = await fetch(url, {method : 'DELETE'});
                if (!res.ok) {
                    throw new Error(`${res.text}`);
                }
                const result = await res.json();

                const data = result.data ?? [];
                console.log(result.message);
                console.log(result.data);

                notify('success', result.message ?? 'Success');

            } catch (msg) {
                console.log(msg);
                notify('error', msg.slice(0,150) ?? 'Terjadi Kesalahan');
            }
        }
    </script>

    {{-- <script>
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
            const selectSupplier = document.getElementById('supplier_id');
            updateSupplier(selectSupplier);
            updatePurchaseDate($('#tanggal_pembelian').val());
            updatePaymentMethod($('#payment_method').val());
            updateStatus($('#status').val());
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
            $('input[name="subtotal"]').val(totalAkhir);

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
            $('input[name="diskon"]').val(totalDiskon);
            $('input[name="tax"]').val(totalPajak);
            $('input[name="other_cost"]').val(biayaLainnya);
            $('input[name="grand_total"]').val(total_bayar);
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

            $('input[name="jumlah_bayar"]').val(formatAsli);
        }

        function updateSupplier(selectSupplier){
            $('#namaSupplier').text(selectSupplier.options[selectSupplier.selectedIndex].text);
            $('input[name="supplier_id"]').val(selectSupplier.value);
        }
        function updatePurchaseDate(value){
            $('#purchase_date').text(value);
            $('input[name="purchase_date"]').val(value);
        }
        function updatePaymentMethod(value){
            $('#metodePembayaran').text(value);
            $('input[name="payment_method"]').val(value);
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
            $('input[name="status"]').val(value);
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

    </script> --}}

    <script>
        // preview image
        const inputImage = document.getElementById('fotoProduk');
        const previewImg = document.getElementById('previewImg');
        const uploadText = document.getElementById('uploadText');

        inputImage.addEventListener('change', function(){
            // validation image
            const maxSize = 200 * 1024; //max 200 kb
            const allowedExtensions = ['image/png', 'image/jpeg', 'image/webp']

            const file = this.files[0];
            if (!file) return;

            const isImage = file.type.startsWith('image/');
            const isTrueEks = allowedExtensions.includes(file.type);
            const isTrueSize = file.size <= maxSize;

            if (isImage && isTrueEks && isTrueSize) {
                const reader = new FileReader();
                reader.onload = function(e){
                    previewImg.src = e.target.result;
                }
                reader.readAsDataURL(file);
                uploadText.style.display='none';
                previewImg.style.display = 'block';
            }else{
                this.value = '';
                previewImg.src = '';
                uploadText.style.display='block';
                previewImg.style.display = 'none';

                let message;
                const messageImage = isImage == false ? 'File bukan image !' : '';
                const messageEks = isTrueEks == false ? 'Ekstensi yang diterima hanya jpg, png, dan webp !' : '';
                const messageSize = isTrueSize == false ? 'Ukuran file tidak lebih dari 200 KB !' : '';
                if (isImage == false && isTrueEks == false && isTrueSize == false) {
                    message = 'pastikan file berupa gambar dengan ekstensi jpeg, png atau webp berukuran maksimal : 200 KB !';
                }else{
                    message = (messageImage ?? '') + (messageEks ? '<br>' + messageEks : '') + (messageSize ?  '<br>' + messageSize : '');
                }
                notify('error', message);
            }
        });

        // submit form
        document.getElementById('product_submit').addEventListener('click', async function () {
            const form = document.getElementById('product-form');
            const formData = new FormData(form);
            const url = "{{ route('item/store/add/to.cart') }}";

            let res = await fetch(url, {
                method : 'POST',
                headers: {
                    'X-CSRF-TOKEN' : "{{ csrf_token() }}"
                },
                body: formData,
            });

            if (!res.ok) {
                notify('error', res.status);
            }

            let result = await res.json();

            if (result.status) {
                notify('success', result.message);
                form.reset();
                location.reload();
            }else{
                console.log(result.message);
                notify('error', result.message.slice(0,150));
            }
        });

        // event ketika value select2 produk diganti, maka simpan data pada keranjang
        $('#product-select').on('change', async function(){
            try {
                const res = await fetch(`/pembelian/store/item/${this.value}`);
                if (!res.ok) {
                    throw new Error(`${res.text}`);
                }
                const result = await res.json();

                if (res.status) {
                    table.ajax.reload(null, false);
                }else{
                    name.value = null;
                    stok.value = null;
                    harga.value = null;
                    console.log(res.message);
                    notify('error', res.message ?? 'Terjadi Kesalahan');
                }
            } catch (error) {
                console.error('Error: ', error);
                notify('error', error.slice(0,150));
            }
        });
    </script>

@endpush
