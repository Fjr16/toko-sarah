@extends('layouts.auth2.main')

@push('styles')
<style>
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
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="card shadow-sm mb-3 border-0">
                <div class="card-header bg-light">
                    <h5 class="fw-bold mb-0 text-uppercase text-dark">
                        {{ 'Pilih Produk' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        {{-- <label class="form-label fw-semibold">Pilih Produk</label> --}}
                        <select name="product_id" id="product-select" style="width: 100%"></select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-8">
            <div class="card shadow-sm mb-3 border-0">
                <div class="card-header bg-light">
                    <h5 class="fw-bold mb-0 text-uppercase text-dark">
                        {{ 'Pilih Batch' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <p>----- Tidak ada batch ----</p>
                        {{-- <label class="form-label fw-semibold">Pilih Produk</label>
                        <select name="product_id" id="product-select" style="width: 100%"></select> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-sm-6 col-md-9">
            <div class="card shadow-sm mb-3 border-0">
                <div class="card-header bg-light">
                    <h5 class="fw-bold mb-0 text-uppercase text-dark">
                        {{'Keranjang'}}
                        <i class="bi bi-cart"></i>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card shadow-sm mb-3 border-1" style="background-color:#bde1f8e1">
                <div class="card-header" style="background-color:rgba(18, 137, 255, 0.671)">
                    <h5 class="fw-bold mb-0 text-uppercase text-white">
                        {{'Ringkasan'}}
                        <i class="bi bi-file-post"></i>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <div class="card mt-3 shadow-sm">
        <div class="card-body py-2">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
            <small>
                Total Items: <span class="fw-bold" id="summaryTotalItem">0</span>
            </small>
            <small class="fw-bold text-success">
                Grand Total: <span class="fs-6" id="summaryTotalAkhir">Rp0</span>
            </small>
            </div>

            <div class="row g-2 align-items-center">
                <div class="col-6 col-md-4">
                    <div class="input-group input-group-sm">
                    <span class="input-group-text">Tipe Bayar</span>
                        <select name="payment_type" id="payment_type" class="form-control form-control-sm">
                            <option value="" selected>Tunai</option>
                            <option value="">E-wallet</option>
                            <option value="">QRIS</option>
                        </select>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="input-group input-group-sm">
                    <span class="input-group-text">Jumlah Bayar</span>
                    {{-- <input type="text" id="invoice_tax" class="form-control text-end price" placeholder="0" value="{{ number_format($item?->tax,0,'','.') }}" oninput="this.value = this.value.replace(/[^0-9]/g,''), updateGrandTotal()"> --}}
                    <input type="text" id="invoice_tax" class="form-control text-end price" placeholder="0" oninput="this.value = this.value.replace(/[^0-9]/g,''), updateGrandTotal()">
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="input-group input-group-sm">
                    <span class="input-group-text">Kembalian</span>
                    {{-- <input type="text" id="invoice_discount" class="form-control text-end price" placeholder="0" value="{{ number_format($item?->diskon,0,'','.') }}" oninput="this.value = this.value.replace(/[^0-9]/g,''), updateGrandTotal()"> --}}
                    <input type="text" id="invoice_discount" class="form-control text-end price" placeholder="0" oninput="this.value = this.value.replace(/[^0-9]/g,''), updateGrandTotal()">
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
                    {{-- <button type="button" class="btn btn-secondary btn-sm flex-fill flex-sm-grow-0" onclick="storeAll('{{ $stts::draft->value }}')"> --}}
                    <button type="button" class="btn btn-secondary btn-sm flex-fill flex-sm-grow-0">
                        <i class="bi bi-file-earmark-text"></i>
                        <span class="ms-1">Simpan Draft</span>
                    </button>
                    {{-- <button type="button" class="btn btn-primary btn-sm flex-fill flex-sm-grow-0" onclick="storeAll('{{ $stts::finish->value }}')"> --}}
                    <button type="button" class="btn btn-primary btn-sm flex-fill flex-sm-grow-0">
                        <i class="bx bx-check"></i>
                        <span class="ms-1">Pembelian Selesai</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
