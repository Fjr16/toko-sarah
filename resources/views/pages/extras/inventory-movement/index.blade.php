@extends('layouts.auth2.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4">
            <h4 class="m-0 p-0 mb-4">{{ $title ?? '' }}</h4>
            <form action="{{ route('stok/barang.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-0">
                <select name="product_id" id="product-select" class="form-control form-control-md" style="flex:1 1 200px;"></select>
                <input type="text" name="batch_number" value="{{ request('batch_number') }}" class="form-control form-control-md" placeholder="No Batch" style="flex:1 1 150px;">
                <div class="input-group" style="flex:1 1 150px;">
                    <input type="date" name="start_at" value="{{ request('start_at') }}" class="form-control form-control-md" >
                    <input type="date" name="end_at" value="{{ request('end_at') }}" class="form-control form-control-md">
                </div>

                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bx bx-search"></i>
                </button>
                <a href="{{ route('stok/barang.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bx bx-reset"></i>
                </a>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="data-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>No. Batch</th>
                            <th>Tgl Exp</th>
                            <th>Jumlah</th>
                            <th>Flag Movement</th>
                            <th>Modul</th>
                            <th>Catatan</th>
                            <th>Dibuat Oleh</th>
                            <th>Dibuat Pada</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="mt-3">
                {{-- {{ $data->appends(request()->all()->links()) }} --}}
            </div>
        </div>
    </div>
    <div class="fab-wrapper">
        <div class="fab-container" id="fabMenu">
          <a href="{{ route('stok/barang.create') }}" class="fab-btn fab-main">
            <i class="bx bx-book-add" style="font-size:22px;"></i>
          </a>
          <button class="fab-btn fab-primary" onclick="toggleFab()">
            <i id="fabIcon" class="bx bx-expand"></i>
          </button>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var table;
        $(document).ready(function(){
            table = $('#data-table').DataTable({
                processing:true,
                serverSide:true,
                ajax:{
                    url:"{{ route('inventory/movement.getTable') }}",
                    data:{

                    }
                },
                columns:[
                    {data:'', name:'', 'defaultContent':'-'},
                ]
            })
        })
    </script>
@endpush
