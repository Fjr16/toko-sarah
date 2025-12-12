@extends('layouts.auth2.main')

@push('styles')
<style>
    .card.shadow-flat{
        border:1px solid #e5e7eb;
        border-radius:12px;
        box-shadow:0 1px 2px rgba(0,0,0,.04);
    }
    .card .hd{
        padding:.65rem .9rem;
        border-bottom:1px solid #e5e7eb;
        background:#f1f5f9;
        border-radius:12px 12px 0 0;
        font-weight:700;
        text-transform:uppercase;
        color:#111827;
        display:flex;
        align-items:center;
        gap:.5rem;
    }
    .card .bd{
        padding:.4rem;
    }
    .input, .select{
        width:100%;
    }
    .table-cart th,.table-cart td{
        vertical-align:middle;
    }
    .table-cart tfoot th{
        background:#f8fafc;
        color:black;
    }
    .right{
        text-align:right;
    }
    .summary-sticky{
        position:sticky;
        top:1rem;
    }
    .footer-sticky{
        position:sticky;
        bottom:0;
        z-index:1020;
    }
    .gap-8{
        gap:.5rem;
    }
    .badge-soft{
        padding:.2rem .5rem;
        border-radius:6px;
        background:#ecfeff;
        color:#0369a1;
        font-weight:700;
    }
    th.fit, td.fit { white-space: nowrap; width: 1%; }
    .font-monospace { font-variant-numeric: tabular-nums; }
</style>
@endpush

@section('content')

    {{-- ROW: PILIH PRODUK + PANEL BATCH --}}
    <div class="row g-3 mb-3">
        {{-- PILIH PRODUK --}}
        <div class="col-sm-6 col-md-5">
        <div class="card shadow-flat">
            <div class="hd">Pilih Produk</div>
            <div class="bd">
            <select id="product-select" class="form-select" data-placeholder="Search / scan barcode" style="width:100%"></select>
            </div>
        </div>
        </div>

        {{-- PANEL BATCH CEPAT --}}
        @include('pages.sales.partials.panel-batch')
    </div>

    {{-- ROW: KERANJANG + RINGKASAN --}}
    @include('pages.sales.partials.cart-summary')

    {{-- Modal Konfirmasi Penjualan (versi rapi) --}}
    <div class="modal fade" id="modalConfirmSales" tabindex="-1" aria-labelledby="confirmSalesTitle" aria-describedby="confirmSalesDesc" aria-hidden="true" style="z-index:1100;">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="dialog">
        <div class="modal-content">

        {{-- Header --}}
        <div class="modal-header border-bottom-0">
            <div class="w-100">
            <div class="d-flex align-items-start justify-content-between">
                <div>
                <h5 class="modal-title mb-1" id="confirmSalesTitle">Konfirmasi Penjualan</h5>
                <p id="confirmSalesDesc" class="text-muted small mb-2">Periksa item & pembayaran sebelum menyelesaikan transaksi.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            {{-- Info ringkas --}}
            <div class="row g-2 mt-2">
                <div class="col-6 col-md-3">
                    <div class="small text-muted text-uppercase">Kasir</div>
                    <div class="fw-semibold">{{ strtoupper(auth()->user()->name ?? '-') }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-muted text-uppercase">Pelanggan / Member</div>
                    <div class="fw-semibold" id="uiNamaPemesan">-</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-muted text-uppercase">Tanggal Penjualan</div>
                    <input type="date" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}" name="sale_date" id="sale_date">
                </div>
                <div class="col-6 col-md-3">
                    <div class="small text-muted text-uppercase">Status Penjualan</div>
                    <select name="sale_status" id="sale_status" class="form-select form-select-sm">
                        @foreach ($saleStatus as $stts)
                            <option value="{{ $stts->value }}" @selected(old('sale_status', \App\Enums\PurchaseStatus::finish->value) === $stts->value)>{{ $stts->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            </div>
        </div>

        <form data-url="{{ route('sales.finish') }}" method="POST" id="confirmSalesForm" novalidate>
            @csrf
            <div class="modal-body pt-0">
                <div class="row g-3">
                    <input type="hidden" id="uiNamaPemesanValue">
                    {{-- Kiri: Tabel Item --}}
                    <div class="col-lg-8">
                        <div class="table-responsive border rounded">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                    <th style="width:40%">Produk</th>
                                    <th class="text-nowrap">No Batch</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="confirm-item-list">
                                </tbody>
                            </table>
                        </div>
                        {{-- Info jumlah item di bawah tabel --}}
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="small text-muted">
                            <span class="me-3">Items: <span class="fw-semibold totalItems" id="totalItems"></span></span>
                            <span>Total Qty: <span class="fw-semibold totalQty font-monospace" id="totalQty"></span></span>
                            </div>
                            <div class="small text-muted">* Periksa kembali nama produk & batch</div>
                        </div>
                    </div>

                    {{-- Kanan: Ringkasan & Pembayaran --}}
                    <div class="col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Ringkasan Pembayaran</h6>

                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="font-monospace subtotal" aria-live="polite" id="subTotalConfirm"></span>
                                </div>

                                <div class="d-flex justify-content-between mb-3" id="otherAdditionalCost"></div>

                                <div class="d-flex justify-content-between border-top pt-2 mb-3">
                                    <span class="fw-bold text-uppercase">Total</span>
                                    <span class="fw-bold font-monospace totalAkhir" aria-live="polite" id="totalAkhirConfirm"></span>
                                </div>

                                {{-- Tipe Bayar --}}
                                <div class="mb-2">
                                    <label class="form-label small text-muted">Tipe Bayar</label>
                                    <div class="d-flex flex-wrap gap-2" aria-label="Pilih tipe bayar">
                                        @foreach ($paymentMethods as $index => $pm)
                                            <input type="radio" class="btn-check" name="tipe_bayar_view"
                                                id="pay_{{ $pm->value }}" data-dipilih="{{ $pm->value }}" autocomplete="off"
                                                @if($index === 0) checked @endif>

                                            <label class="btn btn-outline-secondary px-3 py-1"
                                                for="pay_{{ $pm->value }}">
                                                {{ $pm->label() }}
                                            </label>
                                        @endforeach
                                    </div>

                                    <input type="hidden" name="tipe_bayar" id="tipe_bayar"
                                        value="{{ \App\Enums\PaymentMethod::tunai->value }}" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small text-muted">Catatan (opsional)</label>
                                    <textarea class="form-control" rows="1" id="note" name="note" placeholder="Tambahkan catatan untuk transaksi ini"></textarea>
                                </div>

                                {{-- Jumlah Bayar --}}
                                <div class="mb-2">
                                    <label for="jumlah_bayar_view" class="form-label small text-muted">Jumlah Bayar</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input id="amount_paid" type="text" oninput="this.value = numberFormatter(reverseFormatRupiah(this.value))" class="form-control text-end font-monospace" placeholder="0" aria-describedby="helpBayar">
                                    </div>
                                    <div id="helpBayar" class="form-text">Masukkan nominal yang diterima.</div>
                                </div>

                                <div class="d-flex justify-content-between mt-2">
                                    <span class="fw-semibold text-uppercase">Kembalian</span>
                                    <span class="fw-bold font-monospace" id="change_due" aria-live="polite">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> {{-- /row --}}
            </div>

            {{-- Footer --}}
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSubmitConfirm" onclick="finishSale()" disabled>
                    <span class="spinner-border spinner-border-sm me-2 d-none" id="btnSubmitSpinner" aria-hidden="true"></span>
                    Lanjutkan
                </button>
            </div>
        </form>
        </div>
    </div>
    </div>

@endsection

@section('footer')
    <div class="card mt-3 shadow-flat footer-sticky">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
            <small class="text-muted">Total Items: <b id="summaryTotalItemBottom">0</b></small>
            <small class="fw-bold text-success">Grand Total: <span id="grandTotalBottom">Rp0</span></small>
            </div>

            <div class="row g-2 align-items-center">
            <!-- tombol 1: full width di mobile, auto di sm+ -->
            <div class="col-12 col-sm-auto d-grid">
                <button type="button" class="btn btn-outline-danger btn-sm" id="resetCart" onclick="resetCart()">⟲ Kosongkan Keranjang</button>
            </div>

            <!-- tombol 2: full width di mobile, ke kanan di sm+ -->
            <div class="col-12 col-sm d-grid d-sm-flex justify-content-sm-end">
                <button type="button" class="btn btn-primary btn-sm" onclick="showModalConfirm()">✔ Penjualan Selesai</button>
            </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('change', '#add-cost-name', function(){
            const namaTambahan = this.value;
            const jumlahTambahan = rupiahFormatter($('#add-cost-amount').val() ?? 0);
            if (!namaTambahan && !jumlahTambahan) {
                return;
            }

            $('#otherAdditionalCost').html(`
                <span class="text-muted" id="additional_cost_name">${namaTambahan}</span>
                <span class="font-monospace" id="additional_cost">${jumlahTambahan}</span>
            `);
        })
        $(document).on('change', '#add-cost-amount', function(){
            const namaTambahan = $('#add-cost-name').val();
            const jumlahTambahan = rupiahFormatter(this.value);
            if (!namaTambahan && !jumlahTambahan) {
                return;
            }

            $('#otherAdditionalCost').html(`
                <span class="text-muted" id="additional_cost_name">${(namaTambahan == '' || namaTambahan == null) ? '-' : namaTambahan}</span>
                <span class="font-monospace" id="additional_cost">${jumlahTambahan}</span>
            `);
        });

        $(document).on('change', 'input[name="tipe_bayar_view"]', function(){
            const selectedVal = this.dataset.dipilih;
            $('#tipe_bayar').val(selectedVal);
        });

        $('#amount_paid').on('input', function(){
            let totalBelanja = reverseFormatRupiah($('#summaryTotalAkhir').text() ?? '0');
            if (totalBelanja === 0) {
                totalBelanja = reverseFormatRupiah($('#sum-subtotal').text());
            }
            const val = reverseFormatRupiah(this.value);
            const kembalian = val - totalBelanja;
            if(val < totalBelanja){
                $('#change_due').text('Rp 0');
                $('#btnSubmitConfirm').prop('disabled', true);
                return;
            }
            $('#change_due').text(rupiahFormatter(kembalian));
            $('#btnSubmitConfirm').prop('disabled', false);
        });


        async function resetCart() {
            const res = await fetch("{{ route('sales.reset.cart') }}", {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            if (res.ok) {
                notify('success', 'Keranjang berhasil dikosongkan');
            } else {
                notify('error', 'Gagal ' + res.status);
            }
            $('#cart-table').DataTable().ajax.reload();
        }

        function showModalConfirm() {
            const rows = cartTable.rows().data().toArray();
            const tbody = document.getElementById('confirm-item-list');

            if(!tbody) return;

            if(!rows.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Belum ada item di keranjang.
                        </td>
                    </tr>
                `;
                return;
            }else{
                tbody.innerHTML = rows.map(item => `
                    <tr>
                        <td class="text-truncate" style="max-width: 260px;">
                            ${item.product_name ?? '-'}
                            ${item.satuan ? `<span class="badge rounded-pill text-bg-primary ms-1">${item.satuan}</span>` : ''}
                        </td>
                        <td class="text-muted">${item.batch_number ?? '-'}</td>
                        <td class="text-end font-monospace">${item.jumlah ?? '0'}</td>
                        <td class="text-end font-monospace">
                            ${item.harga ? item.harga : 'Rp -'}
                        </td>
                        <td class="text-end fw-semibold font-monospace">
                            ${item.subtotal ? item.subtotal : 'Rp -'}
                        </td>
                    </tr>
                `).join('');
            }

            const selectCustomer = $('#customer_id').select2('data');
            const custValue = (selectCustomer[0]?.id) || '';
            const custText = (selectCustomer[0]?.text) || '';
            $('#uiNamaPemesan').text(custText.toUpperCase());
            $('#uiNamaPemesanValue').val(custValue);

            $('#amount_paid').val('');
            $('#change_due').text('Rp 0');
            $('#btnSubmitConfirm').prop('disabled', true);

            $('#modalConfirmSales').modal('show');
        }

        async function finishSale(){
            const form = document.getElementById('confirmSalesForm');
            const url = form.dataset.url;
            const totalAmount = reverseFormatRupiah($('#totalAkhirConfirm').text())
            const amountPaid = reverseFormatRupiah($('#amount_paid').val())
            const changeDue = reverseFormatRupiah($('#change_due').text())
            const payload = {
                'customer_id' : $('#uiNamaPemesanValue').val(),
                'payment_method' : $('#tipe_bayar').val(),
                'total_amount' : totalAmount,
                'amount_paid' : amountPaid,
                'change_due' : changeDue,
                'sale_status' : $('#sale_status').val(),
                'note' : $('#note').val(),
                'additional_cost_name' : $('#additional_cost_name').text(),
                'additional_cost' : reverseFormatRupiah($('#additional_cost').text()),
                'sale_date' : $('#sale_date').val(),
            }

            console.log(payload);
            // return;
            try {
                const res = await fetch(url, {
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN' : "{{ csrf_token() }}",
                        'Content-Type' : 'application/json',
                        'Accept': 'application/json'
                    },
                    body:{payload}
                });

                if (!res.ok) {
                    throw new Error(await res.statusText || 'Terjadi Kesalahan');
                }

                const result = await res.json();

                if (!result && result.status === false) {
                    notify('success', result.message.slice(0,150));
                    return;
                }

                // cartTable.ajax.reload();
                notify('success', result.message.slice(0,150));
            } catch (error) {
                console.log(error.message);
                notify('error', error.message.slice(0,150));
            }
        }

        // shortcut create
        (function(){
            const modal     = $('#modalConfirmSales');
            const form      = $('#confirmSalesForm');
            const amountIn  = $('#amount_paid'); // input tampilan
            const submitBtn = $('#btnSubmitConfirm');
            const otherCostName = $('#add-cost-name');
            const otherCostInput = $('#add-cost-amount');
            const btnResetCart = $('#resetCart');

            // Helper: abaikan jika user sedang mengetik di input/textarea/select/contenteditable
            const isTypingInField = (ev) => {
                const tag = (ev.target.tagName || '').toLowerCase();
                const editable = ev.target.isContentEditable;
                return ['input','textarea','select','button'].includes(tag) || editable;
            };

            // Fokus otomatis ke jumlah bayar ketika modal tampil
            modal.on('shown.bs.modal', function(){
                // kecilkan delay agar pasti fokus setelah animasi
                setTimeout(() => amountIn.trigger('focus').select(), 50);
            });

            // ENTER = submit saat modal terbuka
            document.addEventListener('keydown', (ev) => {
                    if (ev.key !== 'Enter') return;

                    const isModalShown = modal.is(':visible');
                    if (!isModalShown) return;              // hanya saat modal terbuka
                    if (isTypingInField(ev)) {
                    // Kalau Enter di dalam input jumlah bayar, tetap boleh submit:
                    if (ev.target.id !== 'jumlah_bayar_view') return;
                }

                ev.preventDefault();
                if (!submitBtn.prop('disabled')) {
                    finishSale();
                }
            });

            // F10 = buka modal konfirmasi
            document.addEventListener('keydown', (ev) => {
                if (ev.key === 'F10') {
                    ev.preventDefault();
                    const isModalShown = modal.is(':visible');
                    if (!isModalShown) {
                        // Pastikan ada item, dll. (opsional: validasi sebelum buka)
                        const itemsCount = Number($('.totalItems').text() || 0);
                        if (itemsCount > 0) {
                            showModalConfirm();
                        }
                    }
                }
            });

            // F2 = fokus ke jumlah bayar (saat modal terbuka)
            document.addEventListener('keydown', (ev) => {
                if (ev.key === 'F2') {
                    const isModalShown = modal.is(':visible');
                    if (isModalShown) {
                        ev.preventDefault();
                        amountIn.trigger('focus').select();
                    }
                }
            });

            // ESC = tutup modal (biarkan default Bootstrap menangani), tapi contoh manual:
            document.addEventListener('keydown', (ev) => {
                if (ev.key === 'Escape' && modal.is(':visible')) {
                    ev.preventDefault(); // opsional
                    modal.modal('hide');
                }
            });

            // f2 = biaya lain dan f3 = cari pelanggan dan f4 = tambah pelanggann untuk focus ke input biaya lainnya dan f8 reset keranjang
            document.addEventListener('keydown', (ev) => {
                if (ev.key === 'F2' && !modal.is(':visible')) {
                    ev.preventDefault(); // opsional
                    otherCostName.trigger('focus').select();
                }
                if (ev.key === 'F3' && !modal.is(':visible')) {
                    ev.preventDefault(); // opsional
                    $('#customer_id').trigger('select').select2('open');
                }
                if (ev.key === 'F4' && !modal.is(':visible')) {
                    ev.preventDefault(); // opsional
                    $('#btnAddCustomer').trigger('click');
                }
                if (ev.key === 'F8' && !modal.is(':visible')) {
                    btnResetCart.trigger('click');
                }
            });

        })();
    </script>
@endpush
