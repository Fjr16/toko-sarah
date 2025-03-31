@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4 d-flex justify-content-between align-items-center">
            <h4 class="m-0 p-0">Data {{ $title ?? '' }}</h4>
            <a href="{{ route('currency.create') }}" class="btn btn-sm btn-primary">+ Tambah {{ $title ?? '' }}</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="datatable">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama Mata Uang</th>
                            <th>Kode</th>
                            <th>Simbol</th>
                            <th>Pemisah Ribuan</th>
                            <th>Pemisah Desimal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->name ?? '' }}</td>
                            <td>{{ $item->code ?? '' }}</td>
                            <td>{{ $item->symbol ?? '' }}</td>
                            <td>{{ $item->thousand_separator ?? '' }}</td>
                            <td>{{ $item->decimal_separator ?? '' }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('currency.edit', encrypt($item->id)) }}" class="btn btn-icon btn-outline-warning me-1"><i class="bx bx-edit"></i></a>
                                    <button class="btn btn-icon btn-outline-danger me-1" type="button"  data-warning="Hapus Mata Uang" data-url="{{ route('currency.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
                                        <i class="bx bx-x-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
<x-modal-confirm-delete></x-modal-confirm-delete>
