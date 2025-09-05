@extends('layouts.auth2.main')

@section('content')
    {{-- <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Selamat Datang {{ Auth::user()->role ?? '' }}</h4>
        </div>
        <div class="card-body">
            <form action="" method="">
                <div class="row"></div>
            </form>
        </div>
    </div> --}}
    <div class="row g-3">
    <div class="col-12 col-md-8">
      <div class="row g-3">
        @foreach(range(1,8) as $i)
          <div class="col-6 col-lg-4">
            <div class="product-card p-3 h-100 d-flex flex-column">
              <div class="fw-semibold">Produk {{ $i }}</div>
              <div class="text-body-secondary small mb-3">SKU00{{ $i }}</div>
              <div class="mt-auto d-flex justify-content-between align-items-end">
                <span class="fw-bold">Rp10.000</span>
                <button class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i></button>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm">
        <div class="card-header">Ringkasan</div>
        <div class="card-body">
          <div class="d-flex justify-content-between"><span>Sub‑Total</span><strong id="sumSub">Rp0</strong></div>
          <div class="d-flex justify-content-between"><span>Diskon</span><strong id="sumDis">Rp0</strong></div>
          <hr>
          <div class="d-flex justify-content-between fs-5"><span>Grand Total</span><strong id="sumGrand">Rp0</strong></div>
        </div>
      </div>
    </div>
  </div>
@endsection