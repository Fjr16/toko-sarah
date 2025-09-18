@extends('layouts.auth2.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">{{ $title ?? '-' }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('stok/barang.store') }}" method="POST" id="post-form">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="product-select" class="form-label">Produk <span class="text-danger">*</span></label>
                        <select class="form-control form-control-md" id="product-select" aria-label="Default select example" name="item_id" required></select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="list_batch" class="form-label">List Nomor Batch</label>
                    <div class="card">
                        <div class="card-body">
                            <div class="table table-responsive">
                                <table class="table" id="list-no-batch">
                                    <thead>
                                        <th>Aksi</th>
                                        <th>Nomor Batch</th>
                                        <th>Expire Date</th>
                                        <th>Jumlah Stok</th>
                                        <th>Harga Beli</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-7">
                        <label for="batch_number" class="form-label">Nomor Batch <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-md" id="batch_number" name="batch_number" placeholder="Input Nomor Batch" value="{{ old('batch_number') }}" required />
                    </div>
                    <div class="col-md-5">
                        <label for="exp_date" class="form-label">Expire Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-md" id="exp_date" name="exp_date" value="{{ old('exp_date') }}" required />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md">
                        <label for="total_stok" class="form-label">Total Stok Sekarang</label>
                        <div class="input-group">
                            <input class="form-control form-control-md" id="total_stok" value="" required disabled></input>
                            <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                        </div>
                    </div>
                    <div class="col-md">
                        <label for="stock" class="form-label">Stok ditambahkan<span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input name="stock" class="form-control form-control-md" id="stock" placeholder="Jumlah stok yang ditambahkan" required></input>
                            <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                     <div class="col-md">
                        <label for="unit_cost" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            @if (setting('currency_position_default', 'prefix') == 'prefix')
                                <span class="input-group-text bg-dark text-white">Rp. </span>
                            @endif
                            <input type="text" class="form-control form-control-md price" id="unit_cost" name="unit_cost" placeholder="0,00" value="{{ old('unit_cost') }}" required />

                            @if (setting('currency_position_default', 'prefix') == 'suffix')
                                <span class="input-group-text bg-dark text-white">Rp. </span>
                            @endif
                            <span class="input-group-text get-satuan-kecil bg-primary text-white">/</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-4 border-top">
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('barang.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                        <button type="submit" class="btn btn-md btn-success"><i class="bx bx-file"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // format rupiah
        let inputPrice = document.querySelectorAll('.price');
        inputPrice.forEach(function(input) {
            input.addEventListener('keyup', function() {
                let val = rupiahFormatter(input.value.replace(/[^0-9]/g, ''));
                input.value = val.replace(/[^0-9.]/g, '');
            });
        });

        let satuan = document.querySelectorAll('.get-satuan-kecil');
        var table, productId;
        $(document).ready(function(){
            $('#product-select').on('change', function(){
                productId = $(this).val();

                $.get(`{{ url('product/show/by-id/${productId}') }}`, function(res){
                    if (res && res.status) {
                        if (!res.data) {
                            notify('error','Data Not Found');
                        }

                        const data = res.data;
                        $('#total_stok').val(data.all_stok);
                        $('#unit_cost').val(rupiahFormatter(data.default_cost).replace(/[^0-9.]/g, ''));

                        satuan.forEach(function(element){
                            $(element).html('/'+data.small_unit);
                        });

                        table.ajax.reload();
                    }else{
                        notify('error', res.message ?? 'Data Not Found');
                    }
                })
            });

            table = $('#list-no-batch').DataTable({
                processing:true,
                serverSide:true,
                searching:false,
                paginate:false,
                ajax:{
                    url:"{{ route('product/get/data.batch') }}",
                    data:function(d){
                        d.product_id = productId
                    }
                },
                columns:[
                    { data: 'action', name: 'action', orderable:false, searchable:false },
                    { data: 'batch_number', name: 'batch_number' },
                    { data: 'exp_date', name: 'exp_date' },
                    { data: 'stock', name: 'stock' },
                    { data: 'unit_cost', name: 'unit_cost' }
                ],
                order:[[0,'2']]
            });

            // submit form
            var form = document.getElementById('post-form');
            $(form).on('submit', function(){
                $(this).preventDefault();
                const formData = new FormData(form);
                const url = "{{ route('stok/barang.store') }}";

                $.ajax({
                    url:url,
                    type:'POST',
                    data:formData,
                    processData:false,
                    contentType:false,
                    // dataType:'json',
                    success:function(res){
                        if (res.status) {
                            notify('success', res.message);
                            form.reset();
                        }else{
                            console.log(res.message);
                            notify('error', res.message.slice(0,150));
                        }
                    },
                    error:function(xhr){
                        console.log(xhr.responseText());
                        notify('error', xhr.responseText().slice(0,150));
                    }
                });
            });

        });

        function useBatch(batchId){
            $.ajax({
                url:"{{ url('product/get/item/batch') }}/" + batchId,
                type:"GET",
                success:function(res){
                    if (res.status) {
                        const data = res.data;
                        $('#batch_number').val(data.batch_number);
                        $('#exp_date').val(data.exp_date);
                        $('#unit_cost').val(data.unit_cost);
                    }else{
                        console.loh(res.message);
                        notify('error', res.message)
                    }
                },
                error:function(xhr){
                    console.log(xhr.responseText());
                    notify('error', xhr.responseText());
                }
            });
        }

    </script>
@endpush
