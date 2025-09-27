@extends('layouts.auth2.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4">
            <h4 class="m-0 p-0 mb-4">{{ $title ?? '' }}</h4>
            <form method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-0" id="form-filter">
                <div class="input-group" style="flex:1 1 150px;">
                    <input type="date" name="start_at" id="start_at" value="{{ request('start_at') }}" class="form-control form-control-md" >
                    <input type="date" name="end_at" id="end_at" value="{{ request('end_at') }}" class="form-control form-control-md">
                </div>

                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bx bx-search"></i>
                </button>
                <a id="btnReset" class="btn btn-sm btn-secondary">
                    <i class="bx bx-reset"></i>
                </a>
            </form>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
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
    {{ $dataTable->scripts() }}

    <script>
        $(function () {
            var table = $('#inventorymovement-table').DataTable();
            $('#form-filter').on('submit', function(e){
                e.preventDefault();
                if($('#start_at').val() && $('#end_at').val()){
                    table.ajax.reload();
                }else{
                    notify('error', 'Mohon isi rentang tanggal');
                    return;
                }
            });
            $('#btnReset').on('click', function(){
                $('#start_at').val('');
                $('#end_at').val('');
                table.ajax.reload();
            })
        })
    </script>
@endpush
