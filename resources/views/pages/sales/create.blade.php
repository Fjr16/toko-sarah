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
    <div class="row g-4">
        <div class="col-sm-6 col-md-8">
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
        <div class="col-sm-6 col-md-4">
            <div class="card shadow-sm mb-3 border-0">
                <div class="card-header bg-light">
                    <h5 class="fw-bold mb-0 text-uppercase text-dark">
                        {{ 'Batch List' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        {{-- <label class="form-label fw-semibold">Pilih Produk</label>
                        <select name="product_id" id="product-select" style="width: 100%"></select> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
