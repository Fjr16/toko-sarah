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

    /* Presisi tinggi antar kolom */
    #batchWrapper .form-label {
    font-size: 0.75rem;
    margin-bottom: 2px;
    }

    #batchWrapper .form-control {
    padding: 0.35rem 0.5rem;
    font-size: 0.875rem;
    }

    #batchWrapper .btn {
    padding: 0.35rem 0.55rem;
    }

    #batchWrapper .batch-row {
    border-bottom: 1px solid #eaeaea;
    padding-bottom: 4px;
    }

    /* Hover efek baris */
    #batchWrapper .batch-row:hover {
    background-color: #f8f9fa;
    border-radius: 6px;
    transition: background 0.2s ease-in-out;
    }
    .is-disabled {
        background: rgba(0,0,0,.20);
        opacity: .8;                 /* terlihat redup */
        filter: brightness(.75) contrast(.95) saturate(.8);
        pointer-events: none;         /* cegah interaksi mouse */
        user-select: none;            /* cegah seleksi teks */
        cursor: not-allowed;          /* hint visual */
    }
</style>
@endpush
@section('content')
    <div class="card shadow-sm mb-3 border-0">
        <div class="card-header bg-light">
            <h5 class="fw-bold mb-0 text-uppercase text-dark">
            {{ $title ?? 'Pembelian' }}
            <i class="bi bi-cart-plus"></i>
            </h5>
        </div>

        <div class="card-body">
            {{-- Tombol tambah produk baru --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-sm btn-dark text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#newProduct" aria-expanded="false" aria-controls="collapseExample">
                <i class="bx bx-plus"></i> New Product
            </button>
            </div>

            {{-- Form Produk Baru --}}
            <div class="collapse mb-4" id="newProduct">
            @include('pages.pembelian.partials.newProduct')
            </div>

            {{-- Pilih Produk --}}
            <div class="mb-3">
            <label class="form-label fw-semibold">Pilih Produk</label>
                {{-- <div id="product-select" style="width: 100%"></div> --}}
                <select name="product_id" id="product-select" style="width: 100%"></select>
            </div>

            {{-- === BAGIAN MULTIPLE BATCH === --}}
            <div id="wrapperMultipleBatch" class="border rounded p-3 mb-3 is-disabled" aria-disabled="true">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-uppercase mb-0">Batch Produk</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addBatchRow">
                    <i class="bi bi-plus-circle"></i> Tambah Batch
                    </button>
                </div>

                {{-- Header visual agar sejajar seperti tabel --}}
                <div class="d-none d-lg-flex fw-semibold text-secondary small border-bottom pb-2 mb-2">
                    <div class="col-2">Batch Number</div>
                    <div class="col-2">Exp. Date</div>
                    <div class="col-1 text-end">Qty</div>
                    <div class="col-2 text-end">* Harga Satuan</div>
                    <div class="col-1 text-end">- Diskon</div>
                    <div class="col-1 text-end">+ Pajak</div>
                    <div class="col-2 text-end">= SubTotal</div>
                    <div class="col-1 text-center">Aksi</div>
                </div>

                <div id="batchWrapper">
                    {{-- Contoh Row --}}
                    <div class="row g-2 batch-row align-items-end mb-2">
                        <input type="hidden" name="product_batch_id[]">
                        <div class="col-12 col-md-3 col-lg-2">
                            <label class="form-label d-lg-none fw-semibold">Batch Number</label>
                            <select class="form-select form-select-sm batch-select" name="batch_number[]" style="width: 100%;"></select>
                        </div>

                        <div class="col-6 col-md-3 col-lg-2">
                            <label class="form-label d-lg-none fw-semibold">Exp. Date</label>
                            <input type="date" class="form-control form-control-sm" name="exp_date[]">
                        </div>

                        <div class="col-6 col-md-2 col-lg-1">
                            <label class="form-label d-lg-none fw-semibold">Qty</label>
                            <input type="number" class="form-control form-control-sm text-end" name="qty[]" placeholder="0" min="1">
                        </div>

                        <div class="col-6 col-md-3 col-lg-2">
                            <label class="form-label d-lg-none fw-semibold">Harga Beli</label>
                            <input type="text" class="form-control form-control-sm text-end" name="unit_price[]"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="0">
                        </div>

                        <div class="col-6 col-md-2 col-lg-1">
                            <label class="form-label d-lg-none fw-semibold">Diskon</label>
                            <input type="text" class="form-control form-control-sm text-end" name="discount[]"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="0">
                        </div>

                        <div class="col-6 col-md-2 col-lg-1">
                            <label class="form-label d-lg-none fw-semibold">Pajak</label>
                            <input type="text" class="form-control form-control-sm text-end" name="tax[]"
                            oninput="this.value=this.value.replace(/[^0-9]/g,'')" placeholder="0">
                        </div>

                        <div class="col-6 col-md-2 col-lg-2">
                            <label class="form-label d-lg-none fw-semibold">Sub Total</label>
                            <input type="text" class="form-control form-control-sm text-end" name="sub_total[]" placeholder="0"
                            disabled>
                        </div>

                        <div class="col-12 col-md-1 d-flex align-items-end justify-content-center">
                            <button type="button" class="btn btn-sm btn-outline-danger removeBatchRow">
                            <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Tambah ke Keranjang --}}
            <div class="text-end">
            <button class="btn btn-success px-4" onclick="addToCart()">
                <i class="bi bi-cart-plus"></i> Tambahkan ke Keranjang
            </button>
            </div>
        </div>
    </div>


    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-uppercase text-dark">Keranjang <i class="bi bi-cart4"></i></h5>
            <h5 class="fw-bold mb-0 text-success totalAkhir"></h5>
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-8">
                    <label for="defaultInput" class="form-label">Supplier</label>
                    <select id="supplier_id" class="form-select">
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
                    <input class="form-control" name="tanggal_pembelian" id="tanggal_pembelian" type="date" value="{{ date('Y-m-d') }}"/>
                </div>
            </div>
            <div class="row mb-4">
                {{ $dataTable->table([],true) }}
            </div>
        </div>
    </div>

@endsection
@section('footer')
    <div class="card mt-3 shadow-sm">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
            <small>
                Total Item: <span class="fw-bold" id="summaryTotalItem">0</span> |
                Subtotal: <span class="fw-bold text-primary" id="summarySubtotal">Rp0</span>
            </small>
            <small class="fw-bold text-success">
                Grand Total: <span class="fs-6" id="summaryTotalAkhir">Rp0</span>
            </small>
            </div>

            <div class="row g-2 align-items-center">
                <div class="col-6 col-md-4">
                    <div class="input-group input-group-sm">
                    <span class="input-group-text">Pajak</span>
                    <input type="text" id="invoice_tax" class="form-control text-end price" placeholder="0" value="{{ number_format($item?->tax,0,'','.') }}" oninput="this.value = this.value.replace(/[^0-9]/g,''), updateGrandTotal()">
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="input-group input-group-sm">
                    <span class="input-group-text">Diskon</span>
                    <input type="text" id="invoice_discount" class="form-control text-end price" placeholder="0" value="{{ number_format($item?->diskon,0,'','.') }}" oninput="this.value = this.value.replace(/[^0-9]/g,''), updateGrandTotal()">
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="input-group input-group-sm">
                    <span class="input-group-text">Biaya Lain</span>
                    <input type="text" id="invoice_other" class="form-control text-end price" placeholder="0" value="{{ number_format($item?->other_cost,0,'','.') }}" oninput="this.value = this.value.replace(/[^0-9]/g,''), updateGrandTotal()">
                    </div>
                </div>
            </div>

            <hr class="my-2">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-sm-auto">
                    <button type="button" class="btn btn-outline-danger btn-sm w-100"
                        data-warning="Kosongkan keranjang?"
                        data-url="{{ route('pembelian.reset') }}"
                        onclick="showModalDelete(this)">
                    <i class="bx bx-reset"></i>
                    <span class="d-none d-sm-inline">Kosongkan Keranjang</span>
                    <span class="d-sm-none">Kosongkan</span>
                </button>
                </div>
                <div class="col-12 col-sm d-flex gap-2 justify-content-sm-end">
                    <button type="button" class="btn btn-secondary btn-sm flex-fill flex-sm-grow-0" onclick="storeAll('{{ $stts::draft->value }}')">
                        <i class="bi bi-file-earmark-text"></i>
                        <span class="ms-1">Simpan Draft</span>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm flex-fill flex-sm-grow-0" onclick="storeAll('{{ $stts::finish->value }}')">
                        <i class="bx bx-check"></i>
                        <span class="ms-1">Pembelian Selesai</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-modal-confirm-delete></x-modal-confirm-delete>


@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        $(document).ready(function(){
            const table = window.LaravelDataTables['purchasetemp-table'];
            table.on('xhr.dt', function(){
                const json = table.ajax.json();
                if(json?.summary){
                    updateSummaryPurchase(json.summary, table);
                }
            })
        });

        function updateSummaryPurchase(data, table){
            $(table.column(5).footer()).text(data.total_qty);
            $(table.column(6).footer()).text(data.subtotal);
            $(table.column(7).footer()).text(data.discount);
            $(table.column(8).footer()).text(data.tax);
            $(table.column(9).footer()).text(data.total);
            $('#summaryTotalItem').text(data.total_rows);
            $('#summarySubtotal').text(data.total);
            updateGrandTotal();
        }

        function updateGrandTotal(){
            const totalClean = reverseFormatRupiah($('#summarySubtotal').text());
            const pajakInvoice = toNum(($('#invoice_tax').val().replace(/[^0-9]/g,'')));
            const discInvoice = toNum(($('#invoice_discount').val().replace(/[^0-9]/g,'')));
            const otherCost = toNum(($('#invoice_other').val().replace(/[^0-9]/g,'')));

            const grandTot = totalClean+pajakInvoice+otherCost-discInvoice;
            $('#summaryTotalAkhir').text(rupiahFormatter(grandTot));
        }

    </script>

    <script>
        let onedited = null;
        const sttsAvailable = @json($stts::cases());
        const sttsDraft = "{{ $stts::draft->value }}";
        const sttsFinished = "{{ $stts::finish->value }}";

        async function addToCart() {
            const productId = $('#product-select').val();
            const batchId = $('input[name="product_batch_id[]"]').map(function (){return this.value.trim()}).get();
            const batch = $('select[name="batch_number[]"]').map(function (){return this.value.trim()}).get();
            const exp_date = $('input[name="exp_date[]"]').map(function (){return this.value.trim()}).get();
            const qty = $('input[name="qty[]"]').map(function (){return this.value.trim()}).get();
            const unit_price = $('input[name="unit_price[]"]').map(function (){return this.value.trim()}).get();
            const discount = $('input[name="discount[]"]').map(function (){return this.value.trim()}).get();
            const tax = $('input[name="tax[]"]').map(function (){return this.value.trim()}).get();

            if (!productId) {
                notify('error', 'Pilih produk terlebih dahulu');
                return;
            }

            try {
                const res = await fetch('/pembelian/store/item', {
                    method : 'POST',
                    headers:{
                        'X-CSRF-TOKEN' : "{{ csrf_token() }}",
                        'Accept' : 'application/json',
                        'Content-Type' : 'application/json',
                    },
                    body:JSON.stringify({
                        item_id : productId,
                        product_batch_id : batchId,
                        batch_number : batch,
                        exp_date : exp_date,
                        qty:qty,
                        unit_price:unit_price,
                        discount:discount,
                        tax:tax,
                    })
                });

                if (!res.ok) {
                    const errorText = await res.statusText;
                    throw new Error(errorText || 'Gagal Hapus Data');
                }

                const result = await res.json();

                if (!result || result.status == false) {
                    notify('error', result.message || 'Gagal, terjadi kesalahan sistem');
                    return;
                }
                window.LaravelDataTables['purchasetemp-table'].ajax.reload();
                resetBatchSelect();
                notify('success', result.message || 'Sukses, ditambahkan ke keranjang');
            } catch (error) {
                console.log('Error: ', error.message);
                notify('error', error.message.slice(0,150) ?? 'terjadi Kesalahan sistem');
            }
        }
        async function removeItem(purchaseTempDetailId){
            try {
                const url = '/pembelian/destroy/'+purchaseTempDetailId;
                const res = await fetch(url, {
                    method : 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN':"{{ csrf_token() }}"
                    }
                });
                if (!res.ok) {
                    const errorText = await res.statusText;
                    throw new Error(errorText || 'Gagal Hapus Data');
                }
                const result = await res.json();

                const data = result.data ?? [];
                window.LaravelDataTables['purchasetemp-table'].ajax.reload();

                notify('success', result.message ?? 'Success');
            } catch (error) {
                console.log('Error: ', error.message);
                notify('error', error.message.slice(0,150) ?? 'Terjadi Kesalahan');
            }
        }
        async function editItem(purchaseTempDetailId) {
            const table = window.LaravelDataTables['purchasetemp-table'];

            if (onedited && onedited !== purchaseTempDetailId) {
               table.row('#'+onedited).invalidate().draw(false);
            }

            onedited = purchaseTempDetailId;

            var currentPrice = 0,currentQty = 0,currentDisc = 0,currentTax = 0, productSatuan = '';
            try {
                const res = await fetch('/purchase/temp/detail/byId/' + purchaseTempDetailId);
                if (!res.ok) {
                    const errorText = await res.statusText;
                    throw new Error(errorText || "Terjadi kesalahan sistem, mohon coba lagi beberapa saat");
                }

                const result = await res.json();
                if (result.status) {
                    const data = result.data ?? [];

                    currentPrice = data.unit_price ?? 0;
                    currentQty = data.qty ?? 0;
                    currentDisc = data.discount ?? 0;
                    currentTax = data.tax ?? 0;
                    productSatuan = data.small_unit ?? '';
                }
            } catch (error) {
                onedited = null;
                console.log(error.message)
                notify('error', error.message.slice(0,150) ?? 'Terjadi kesalahan sistem');
            }

            const row = '#' + purchaseTempDetailId;

            const actionIndex  = table.column('.action_table').index();
            const unitPriceIndex  = table.column('.unit_price_table').index();
            const qtyIndex  = table.column('.qty_table').index();
            const discIndex  = table.column('.discount_table').index();
            const taxIndex  = table.column('.tax_table').index();

            const colAct = table.cell(row, actionIndex).node();
            const colUnitPrice = table.cell(row, unitPriceIndex).node();
            const colQty = table.cell(row, qtyIndex).node();
            const colDisc = table.cell(row, discIndex).node();
            const colTax = table.cell(row, taxIndex).node();

            const btnSimpan = `<button onclick="updateItem(${purchaseTempDetailId})" class="text-success border-0 bg-transparent p-0"><i class="bx bx-save fs-4"></i></button>`;
            const btnBtl =`<button onclick="window.LaravelDataTables['purchasetemp-table'].row('#${purchaseTempDetailId}').invalidate().draw(false)" class="text-danger border-0 bg-transparent p-0"><i class="bx bx-exit fs-4"></i></button>`;

            const inputUnitPrice = `<input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="${currentPrice}" placeholder="0" name="unit_price_edit" id="unit_price_edit_${purchaseTempDetailId}" class="form-control form-control-sm">`;
            const inputQty = `
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="qty_edit" id="qty_edit_${purchaseTempDetailId}" value="${currentQty}">
                    <span class="input-group-text bg-primary text-white">${productSatuan}</span>
                </div>`;
            const inputDisc = `<input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="${currentDisc}" name="discount_edit" id="discount_edit_${purchaseTempDetailId}" class="form-control form-control-sm">`;
            const inputTax = `<input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="${currentTax}" name="tax_edit" id="tax_edit_${purchaseTempDetailId}" class="form-control form-control-sm">`;

            $(colAct).html(btnBtl + btnSimpan);
            $(colUnitPrice).html(inputUnitPrice);
            $(colQty).html(inputQty);
            $(colDisc).html(inputDisc);
            $(colTax).html(inputTax);
        }
        async function updateItem(purchaseTempDetailId) {
            const table = window.LaravelDataTables['purchasetemp-table'];
            const row = table.row('#' + purchaseTempDetailId).node();
            try {
                const res = await fetch('/pembelian/update/item/'+purchaseTempDetailId, {
                    method:'PUT',
                    headers: {
                        'X-CSRF-TOKEN':"{{ csrf_token() }}",
                        'Accept' : 'application/json',
                        'Content-Type' : 'application/json'
                    },
                    body:JSON.stringify({
                        unit_price : $(row).find('#unit_price_edit_'+purchaseTempDetailId).val() ?? 0,
                        qty : $(row).find('#qty_edit_'+purchaseTempDetailId).val() ?? 0,
                        discount : $(row).find('#discount_edit_'+purchaseTempDetailId).val() ?? 0,
                        tax : $(row).find('#tax_edit_'+purchaseTempDetailId).val() ?? 0,
                    })
                });

                if (!res.ok) {
                    const errorText = await res.statusText;
                    throw new Error(errorText || "Gagal update data");
                }

                const result = await res.json();
                if (result.status) {
                    table.ajax.reload(null,false);
                    notify('success', result.message);
                }else{
                    notify('error', result.message);
                }
            } catch (error) {
                console.log(error.message);
                notify('error', error.message.slice(0,150) ?? 'Terjadi kesalahan, gagal update data');
            }
        }
        async function storeAll(status) {
            if(sttsAvailable.includes(status) == false) return notify('error', 'Status Tidak Dikenali');
            const supp_id = $('#supplier_id').val() ?? null;
            const purc_date = $('#tanggal_pembelian').val() ?? null;
            const inv_tax = reverseFormatRupiah($('#invoice_tax').val());
            const inv_disc = reverseFormatRupiah($('#invoice_discount').val());
            const inv_other = reverseFormatRupiah($('#invoice_other').val());
            const subTot = reverseFormatRupiah($('#summarySubtotal').text());
            const grandTot = reverseFormatRupiah($('#summaryTotalAkhir').text());

            const { isConfirmed, value:notes } =  await Swal.fire({
                title: 'Catatan',
                input:"textarea",
                inputPlaceholder:"Tuliskan catatan untuk pembelian ini, jika ada",
                theme: 'auto',
                icon:'warning',
                showConfirmButton:true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batalkan',
                showCancelButton:true,
                reverseButtons:true
            });

            if (!isConfirmed) return;

            try {
                const res = await fetch('/pembelian/save/all', {
                    method : 'POST',
                    headers: {
                        'X-CSRF-TOKEN' : "{{ csrf_token() }}",
                        'Accept' : 'application/json',
                        'Content-Type' : 'application/json'
                    },
                    body:JSON.stringify({
                        status : status,
                        purchase_date : purc_date,
                        supplier_id : supp_id,
                        tax : inv_tax,
                        diskon : inv_disc,
                        other_cost : inv_other,
                        subtotal:subTot,
                        grand_total:grandTot,
                        notes : notes ?? null
                    })
                });

                if (!res.ok) {
                    const errorText = res.statusText;
                    throw new Error(errorText || "Gagal simpan data");
                }

                const result = await res.json();
                if (result.status) {
                    if (result.type && result.type === sttsFinished) {
                        $('#summarySubtotal').text('Rp. 0');
                        $('#invoice_tax').val('0');
                        $('#invoice_discount').val('0');
                        $('#invoice_other').val('0');
                    }
                    window.LaravelDataTables['purchasetemp-table'].ajax.reload();
                    notify('success', result.message);
                }else{
                    notify('error', result.message || 'Gagal simpan data');
                }

            } catch (error) {
                console.log(error.message)
                notify('error', error.message || 'Terjadi Kesalahan');
            }
        }
    </script>

    {{-- scripts modified batch --}}
    <script>
        // === Fungsi inisialisasi Select2 Hybrid (manual + existing) ===
        function initSelect2(element) {
            element.select2({
                theme: 'bootstrap-5',
                placeholder: 'Tambah atau pilih batch...',
                minimumInputLength: 1,
                tags: true, // bisa input manual
                ajax: {
                    url: '/product/get/batch/select',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        const productId = $('#product-select').val(); // optional: batasi batch per produk
                        return {
                            keyword: params.term,
                            product_id: productId
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(row => ({
                                id: row.id,
                                text: row.text,
                                exp_date: row.exp_date,
                                cost: row.cost,
                            }))
                        };
                    },
                    cache: false
                },
                createTag: (params) => {
                    const term = (params.term || '').trim();
                    if(!term) return null;
                    const exists = element.find('option').toArray()
                                    .some(opt => $(opt).text().trim().toLowerCase() === term.toLowerCase());
                    return exists ? null : {id:term, text:term, newTag:true};
                },
                insertTag: (data,tag) => {data.unshift(tag);},
                templateResult:item => {
                    if (item.loading) return item.text;
                    return item.newTag
                    ? $(`<span>Tambah batch baru: <strong>${item.text}</strong></span>`)
                    : $(`<span>${item.text}</span>`);
                }
            });

            // Isi otomatis exp_date, harga, dll bila pilih batch existing
            element.on('select2:select', function (e) {
                const picked = (e.params.data.text || '').trim().toLowerCase();
                $(this).find('option[data-select2-tag="true"]').each(function(){
                    const t = (this.text || '').trim().toLowerCase();
                    if (t !== picked) $(this).remove(); // sisakan hanya yang terpilih
                });

                const data = e.params.data;
                const row = $(this).closest('.batch-row');

                if (data.newTag) {
                    row.find('[name="qty[]"],[name="product_batch_id[]"],[name="exp_date[]"], [name="unit_price[]"], [name="sub_total[]"]').val('');
                }else{
                    if (data.id) row.find('[name="product_batch_id[]"]').val(data.id);
                    if (data.exp_date) row.find('[name="exp_date[]"]').val(data.exp_date);
                    if (data.cost){
                        row.find('[name="qty[]"]').val(1);
                        row.find('[name="unit_price[]"]').val(data.cost);
                        row.find('[name="sub_total[]"]').val(rupiahFormatter(data.cost));
                    }
                }
            });
        }
        function resetBatchSelect() {
            $('#batchWrapper .batch-select').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).find('option').remove();
                $(this).val('');
                initSelect2($(this));

            });
            $('.batch-row').each(function(){
                $(this).find('[name="product_batch_id[]"], [name="exp_date[]"], [name="qty[]"], [name="unit_price[]"], [name="discount[]"], [name="sub_total[]"], [name="tax[]"]').val('');
            });
        }
        $(document).ready(function() {
            // saat produk dipilih
            $('#product-select').on('select2:select', function(e) {
                const data = e.params.data;
                if (data.text) {
                    $('#wrapperMultipleBatch').removeClass('is-disabled');
                    $('#wrapperMultipleBatch').attr('aria-disabled', false);
                }else{
                    $('#wrapperMultipleBatch').addClass('is-disabled');
                    $('#wrapperMultipleBatch').attr('aria-disabled', true);
                }
                $(this).find('option').remove();
                const newOption = new Option(data.text, data.id, true, true);
                $(this).append(newOption).trigger('change.select2');

                // reset semua batch row agar relevan dengan produk baru
                resetBatchSelect();
            });
            // end saat produk dipilih

            // === Inisialisasi row pertama ===
            initSelect2($('.batch-select'));

            // === Tambah row baru ===
            $('#addBatchRow').on('click', function() {
                const newRow = $('.batch-row:first').clone(false,false);
                newRow.find('input[type="text"], input[type="number"], input[type="date"], input[type="hidden"]').val('');
                newRow.find('.select2, .select2-container').remove(); // hapus select lama
                newRow.find('select .batch-select').remove(); // hapus select lama

                const newInput = $('<select class="form-select form-select-sm batch-select" name="batch_number[]" style="width: 100%;"></select>');
                newRow.find('.col-12.col-md-3.col-lg-2').first().html(newInput);
                $('#batchWrapper').append(newRow.hide().fadeIn(150));
                initSelect2(newRow.find('.batch-select'));
            });

            // === Hapus row ===
            $(document).on('click', '.removeBatchRow', function() {
                if ($('.batch-row').length > 1) {
                    $(this).closest('.batch-row').fadeOut(150, function() {
                        $(this).remove();
                    });
                } else {
                    notify('warning', 'Minimal 1 batch harus ada!');
                }
            });

            // hitung sub total per batch
            $('#batchWrapper').on('input change',
                '[name="qty[]"], [name="unit_price[]"], [name="discount[]"], [name="tax[]"]',
                function(){
                    const row = $(this).closest('.batch-row');
                    const qty = toNum(row.find('[name="qty[]"]').val());
                    const unit_price = toNum(row.find('[name="unit_price[]"]').val());
                    const discount = toNum(row.find('[name="discount[]"]').val());
                    const tax = toNum(row.find('[name="tax[]"]').val());

                    let subTotal = toNum((qty * unit_price) + tax - discount);
                    row.find('[name="sub_total[]"]').val(rupiahFormatter(subTotal));
                }
            );
        });
    </script>

     <script>
        // format rupiah pada event oninput
        let inputPrice = document.querySelectorAll('.price');
        inputPrice.forEach(function(input) {
            input.addEventListener('keyup', function() {
                let val = rupiahFormatter(input.value.replace(/[^0-9]/g, ''));
                input.value = val.replace(/[^0-9.]/g, '');
            });
        });
    </script>

@endpush
