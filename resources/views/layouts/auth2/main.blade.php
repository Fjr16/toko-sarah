<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ setting('company_name', 'Company Name') }} | {{ $title }}</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  @stack('styles')
  @include('layouts.auth2.templateStyle')

  {{-- custom --}}
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/boxicons.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/select2/select2.css') }}" /> --}}
    {{-- Flat Picker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- notyf --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <!-- Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">
    {{-- select2 css --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .btn-success {
            background-color: #49a141 !important;
        }

        /* blok kontent */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Warna gelap dengan transparansi */
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .overlay-content {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Wrapper */
        .fab-wrapper {
            position: fixed;
            bottom: 65px;
            right: 30px;
            z-index: 1050;
        }

        /* Container */
        .fab-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        /* Style umum button */
        .fab-btn {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
            border: none;
            outline: none;
            box-shadow: 0 5px 12px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        /* Hover animasi */
        .fab-btn:hover {
        transform: translateY(-3px) scale(1.08);
        /* box-shadow: 0 8px 20px rgba(0,0,0,0.25); */
        box-shadow: 0 8px 20px rgba(0, 85, 255, 0.372);
        }

        /* Ripple effect */
        .fab-btn::after {
        content: "";
        position: absolute;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
        }

        .fab-btn:active::after {
        width: 200%;
        height: 200%;
        }

        /* Warna khusus */
        .fab-main {
        background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .fab-light {
        background: linear-gradient(135deg, #7b7b7b, #777777);
        }

        .fab-dark {
        background: linear-gradient(135deg, #232526, #414345);
        }

        .fab-warning {
        background: linear-gradient(135deg, #f7971e, #ffd200);
        }

        .fab-success {
        background: linear-gradient(135deg, #56ab2f, #a8e063);
        }

        .fab-primary {
        background: linear-gradient(135deg, #031d4b, #337dfb);
        }

        /* Default: sembunyi semua tombol selain .fab-main */
        .fab-container a {
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.3s ease;
        }

        /* Saat container open → tampilkan tombol */
        .fab-container.open a {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        }

        /* Animasi muncul satu-satu */
        .fab-container.open a:nth-child(1) {
        transition-delay: 0.05s;
        }
        .fab-container.open a:nth-child(2) {
        transition-delay: 0.1s;
        }

        /* menghilangkan panah atas bawah di input number */
        .form-control::-webkit-outer-spin-button,
        .form-control::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        .form-control[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="pos-layout">
<div id="app" class="pos-wrapper">
    @include('layouts.auth2.sidebar')

    @include('layouts.auth2.topbar')


  {{-- Main content --}}
  <main class="pos-content p-3">
    <div class="container-fluid">
      {{-- Flash messages --}}
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @yield('content')
    </div>
  </main>

  {{-- Checkout bar (sticky bottom) --}}
  <footer class="pos-checkout bg-body p-2">
    <div class="container-fluid">
      <div class="row g-2 align-items-center">
        <div class="col-md">
          <div class="d-flex gap-3 flex-wrap">
            <div>Total Item: <span id="summaryItems" class="fw-semibold">0</span></div>
            <div>Sub‑Total: <span id="summarySubtotal" class="fw-bold">Rp0</span></div>
            <div>Diskon: <span id="summaryDiscount" class="fw-semibold">Rp0</span></div>
            <div>Grand Total: <span id="summaryGrand" class="fw-bold text-primary">Rp0</span></div>
          </div>
        </div>
        <div class="col-md-auto">
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" id="btnHold"><i class="bi bi-pause-circle me-1"></i>Hold</button>
            <button type="button" class="btn btn-outline-secondary" id="btnDiscount"><i class="bi bi-percent me-1"></i>Diskon</button>
            <button type="button" class="btn btn-primary" id="btnPay"><i class="bi bi-credit-card me-1"></i>Bayar</button>
          </div>
        </div>
      </div>
    </div>
  </footer>
</div>

{{-- Offcanvas Cart --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="cartOffcanvasLabel">Keranjang</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    <div class="scroll-y flex-grow-1">
      {{-- Example cart item --}}
      {{-- Loop your cart items here --}}
      {{-- @foreach($cart as $item) --}}
      <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
        <div>
          <div class="fw-semibold">Contoh Produk</div>
          <div class="small text-body-secondary">x1 • Rp10.000</div>
        </div>
        <div class="btn-group" role="group" aria-label="Quantity">
          <button class="btn btn-sm btn-outline-secondary">-</button>
          <button class="btn btn-sm btn-light" disabled>1</button>
          <button class="btn btn-sm btn-outline-secondary">+</button>
        </div>
      </div>
      {{-- @endforeach --}}
    </div>

    <div class="mt-3">
      <button class="btn w-100 btn-danger"><i class="bi bi-trash me-1"></i>Kosongkan</button>
    </div>
  </div>
</div>

{{-- Generic Modal Placeholder --}}
<div class="modal fade" id="posModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @stack('modal-body')
      </div>
      <div class="modal-footer">
        @stack('modal-footer')
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>


{{-- custom --}}
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
                $(this).remove();
            });
        }, 2000);
    </script>
    <script src="{{ asset('/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/jquery/jquery.blockUI.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @include('layouts.auth2.scriptTemplate')


    {{-- Datatables --}}
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script>
        $('#datatable').DataTable();
        $('.datatable').DataTable();
    </script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('/assets/vendor/libs/select2/select2.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />


    {{-- notyf --}}
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    {{-- btn fab melayang --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- select2 produk ajax --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script>
        // search produk manual by name or code
        $('#product-select').select2({
            theme: 'bootstrap-5',
            placeholder : 'Search or scan any barcode',
            ajax : {
                url : '/product/search',
                dataType : 'json',
                delay : 250,
                data : function(params){
                    return {
                        search : params.term,
                    }
                },
                processResults : function(data){
                    return {
                        results : data.map(item => {
                            return {
                                id : item.id,   //menjadi value pada select
                                text : '['+item.code+'] ' + item.name,
                            }
                        })
                    }
                },
                cache : true    //respon pencarian ajax akan disimpan pada chace, jika dilakukan pencarian dengan keyword yang sama maka tidak memanggi ulang ajax melainkan diambil dari chace
            },
            minimumInputLength : 1,     //pencarian baru akan dilakukan jika terdapat 1 character pada form input select
        });
        setTimeout(() => {
            $('#product-select').select2('open');
        }, 300); // Delay kecil untuk pastikan inisialisasi selesai

        // end search produk  by code
    </script>
    {{-- end select2 produk ajax --}}

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#tanggal-lahir", {
            dateFormat: "Y-m-d", // Format tanggal MySQL
            maxDate: "today", // Batasan maksimum tanggal yang dapat dipilih
            // defaultDate: "01-01-1990" // Tanggal default jika input kosong
        });
    </script>

    <script>
        // {{-- new alert --}}
        function alertShow(status, message, elementID){
            const contentAlert = `
            <div class="alert alert-danger d-flex" role="alert">
                <span class="alert-icon rounded-circle"><i class='bx bxs-x-circle' style="font-size: 40px"></i></span>
                <div class="d-flex flex-column ps-1">
                    <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">${status}</h6>
                    <span>${message}</span>
                </div>
            </div>`;

            $(elementID).html(contentAlert);

            $(".alert").fadeTo(4000, 0).slideUp(1000, function() {
                $(this).remove();
            });
            window.scrollTo(0, 0);
        }

        // notyf alert
        const notif = new Notyf({
            duration:2000,
            position: {
                x:'right',
                y:'bottom',
            },
            types: [
                {
                    type: 'warning',
                    background: 'orange',
                    icon: {
                        className: 'material-icons',
                        tagName: 'i',
                        text: 'warning'
                    }
                },
                {
                    type: 'error',
                    background: 'indianred',
                    duration: 3000,
                    dismissible: true
                },
            ]
        });

        function notify(type, message){
            notif.open({
                type:type,
                message:message,
            });
        }
    </script>

    {{-- modal konfirmasi delete dinamis --}}
    <script>
        function showModalDelete(element) {
            const url = $(element).data('url');
            const name = $(element).data('name');
            const value = $(element).data('value');
            const aksi = $(element).data('warning');

            const modal = $('#modalDelete');

            const formDelete = modal.find('form');
            formDelete.attr('action', url);

            const elementWarning = modal.find('#data_aksi');
            elementWarning.text(aksi);

            const btnSubmit = formDelete.find('button');
            btnSubmit.attr('name', name);
            btnSubmit.attr('value', value);

            modal.modal('show');
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
        var satuanTerkecil = document.getElementById('satuan-terkecil');
        var satuanSedang = document.getElementById('satuan-menengah');
        var satuanTerbesar = document.getElementById('satuan-terbesar');

        var setSatuanKecil = document.getElementById('get-satuan-kecil');
        var setSatuanKecilClass = document.querySelectorAll('.get-satuan-kecil');
        var setSatuanSedang1 = document.getElementById('get-satuan-sedang-awal');
        var setSatuanSedang2 = document.getElementById('get-satuan-sedang-akhir');
        var setSatuanBesar = document.getElementById('get-satuan-besar');


        satuanTerkecil.addEventListener('keyup', function(){
            setSatuanKecil.textContent = satuanTerkecil.value;
            setSatuanKecilClass.forEach(element => {
                element.textContent = '/' + satuanTerkecil.value;
            });
        });
        satuanTerbesar.addEventListener('keyup', function(){
            setSatuanBesar.textContent = '1 ' + satuanTerbesar.value + ' =';
        });

        satuanSedang.addEventListener('keyup', function(){
            setSatuanSedang1.textContent = '1 ' + satuanSedang.value + ' =';
            setSatuanSedang2.textContent = satuanSedang.value;
        });
        })
    </script>
    <script>
        function rupiahFormatter(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
        }
        function numberFormatter(value) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            }).format(value);
        }

        function toggleFab() {
             let fabMenu = document.getElementById('fabMenu');
            let fabIcon = document.getElementById('fabIcon');

            fabMenu.classList.toggle('open');

            if (fabMenu.classList.contains('open')) {
                fabIcon.classList.remove('bx-expand');
                fabIcon.classList.add('bx-exit-fullscreen');
            } else {
                fabIcon.classList.remove('bx-exit-fullscreen');
                fabIcon.classList.add('bx-expand');
            }
        }
    </script>

@stack('scripts')
</body>
</html>

{{-- =========================
Cara pakai di Laravel:
1) Simpan file ini sebagai: resources/views/layouts/pos.blade.php
2) Buat view anak, contoh:

@extends('layouts.pos')
@section('title','Transaksi Baru')
@section('content')
  <div class="row g-3">
    <div class="col-12 col-md-8">
      <div class="row g-3">
        @foreach(range(1,8) as $i)
          <div class="col-6 col-lg-4">
            <div class="product-card p-3 h-100 d-flex flex-column">
              <div class="fw-semibold">Produk {{ $i }}</div>
              <div class="text-body-secondary small mb-3">SKU00{{ $i }}</div>
              <div class="mt-auto d-flex justify-content-between align-items-end">
                <span class="fw-bold">{{ format_rupiah(10000*$i ?? 0) ?? 'Rp10.000' }}</span>
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

3) (Opsional) Buat helper format_rupiah() sendiri atau gunakan window.formatIDR di JS.
========================= --}}
