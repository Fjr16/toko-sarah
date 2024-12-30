@extends('layouts.auth.main')

@section('content')
@dd(session()->all())
    <div class="card">
        <div class="card-header border-bottom mb-4 d-flex justify-content-between align-items-center">
            <h4 class="m-0 p-0">Data Supplier</h4>
            <a href="{{ route('supplier.create') }}" class="btn btn-sm btn-primary">+ Tambah Supplier</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Supplier</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status == 'active' ? 'primary' : 'danger' }}">{{ $item->status ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('supplier.edit', encrypt($item->id)) }}" class="btn btn-icon btn-outline-warning me-1"><i class="bx bx-edit"></i></a>
                                    @if ($item->status === 'active')
                                        <button class="btn btn-icon btn-outline-danger me-1" type="button" data-value="deleted" data-name="status" data-warning="Hapus / disable supplier" data-url="{{ route('supplier.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
                                            <i class="bx bx-x-circle"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-icon btn-outline-success" type="button" data-value="active" data-name="status" data-warning="Aktivasi supplier" data-url="{{ route('supplier.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
                                            <i class="bx bx-check-circle"></i>
                                        </button>
                                    @endif
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
