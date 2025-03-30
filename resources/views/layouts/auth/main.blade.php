<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>SPK | {{ $title }}</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/boxicons.css') }}" />

    {{-- Flat Picker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- Flat Picker --}}

    {{-- css Leaflet untuk maps --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/leaflet/leaflet.css') }}">
    {{-- Leaflet --}}

    <style>
        /* .menu-link:hover {
            color: #161515 !important;
        } */
        .btn-success {
            background-color: #49a141 !important;
        }

        #example_filter {
            margin-bottom: 10px !important;
        }
        .multi-line-text {
            white-space: pre-wrap;
            word-break: break-word;
        }
        .ck-editor__editable[role="textbox"] {
            /* editing area */
            min-height: 200px;
        }

        .ck-content .image {
            /* block images */
            max-width: 80%;
            margin: 20px auto;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
            /* Untuk Firefox */
        }

        /* table rme */
        .outer-wrapper {
            width: 100% !important;
            border: 1px solid #49a141;
            border-radius: 4px;
            box-shadow: 0px 0px 3px #49a141;
            max-height: fit-content;
        }

        .table-wrapper {

            overflow-y: scroll;
            /* overflow-x: scroll; */
            height: fit-content;
            max-height: 66.4vh;
            margin-top: 22px;
            margin: 15px;
            padding-bottom: 20px;
        }
        /* /table rme */
    </style>

    {{-- dinamis css --}}
    @yield('css-added')

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Datatables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.css">

    <!-- Helpers -->
    <script src="{{ asset('/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/assets/js/config.js') }}"></script>
</head>

<body>

    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.auth.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('layouts.auth.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                            {{-- alert --}}
                            @if (session()->has('success'))
                            <div class="alert alert-success d-flex mb-4" role="alert">
                                <span class="alert-icon rounded-circle"><i class='bx bxs-badge-check'></i></span>
                                <div class="d-flex flex-column ps-1">
                                <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">Transaction Successfully !!</h6>
                                <span>{{ session('success') }} !</span>
                                </div>
                            </div>
                            @endif
                            @if (session()->has('errors'))
                            <div class="alert alert-danger d-flex mb-4" role="alert">
                                <span class="alert-icon rounded-circle"><i class='bx bxs-badge-x'></i></span>
                                <div class="d-flex flex-column ps-1">
                                <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">Transaction Errors !!</h6>
                                <span>{{ session('errors') }} !</span>
                                </div>
                            </div>
                            @endif
                            @if (session()->has('error'))
                            <div class="alert alert-danger d-flex mb-4" role="alert">
                                <span class="alert-icon rounded-circle"><i class='bx bxs-badge-x'></i></span>
                                <div class="d-flex flex-column ps-1">
                                <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">Transaction Errors !!</h6>
                                <span>{{ session('error') }} !</span>
                                </div>
                            </div>
                            @endif
                            {{-- end alert --}}
                        @yield('content')
                    </div>
                    <!-- / Content -->


                    <!-- Footer -->
                    @include('layouts.auth.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- / Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
                $(this).remove();
            });
        }, 2000);
    </script>

    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/jquery/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('/assets/js/dashboards-analytics.js') }}"></script>

    {{-- Datatables --}}
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script>
        $('#datatable').DataTable();
        $('.datatable').DataTable();
    </script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('/assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        $('.select2').select2({
            placeholder : 'Pilih',
        });
        $('.select2-w-placeholder').select2({
            placeholder : "Pilih Dignosa Sesuai kode ICD 10",
            allowClear : true
        });
    </script>
    {{-- get jumlah in multiple input --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#tanggal-lahir", {
            dateFormat: "Y-m-d", // Format tanggal MySQL
            maxDate: "today", // Batasan maksimum tanggal yang dapat dipilih
            // defaultDate: "01-01-1990" // Tanggal default jika input kosong
        });
    </script>

    {{-- start expand collapse table --}}
    {{-- penggunaan pada fitur export laporan penunjang pk  --}}
    <script>
        $(document).ready(function() {
            // Sembunyikan rincian tambahan saat halaman dimuat
            $('.details-row').hide();

            // Tambahkan event click pada tombol toggle-details
            $('.toggle-details').click(function() {
                // Temukan baris detail terkait
                var detailsRow = $(this).closest('tr').next('.details-row');

                // Toggle tampilan rincian tambahan
                detailsRow.toggle();
            });
        });
    </script>
    {{-- end expand collapse table --}}    

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
        // function untuk membuat input dengan format rupiah
        function formatRupiah(angka){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);
                
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
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

    @yield('script')

</body>

</html>
