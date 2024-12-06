@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between border-bottom mb-4">
            <h4 class="m-0 p-0">Data User</h4>
            <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">+ Tambah User</a>
        </div>
        <div class="card-body">
            <div class="table-responsive text-wrap">
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name ?? '-' }}</td>
                                <td>{{ $item->username ?? '-' }}</td>
                                <td>{{ $item->role ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status === 'active' ? 'primary' : 'danger' }}">{{ $item->status ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('user.edit', encrypt($item->id)) }}" class="btn btn-icon btn-outline-warning mx-2"><i class="bx bx-edit"></i></a>
                                        @if ($item->status === 'active')
                                            <button class="btn btn-icon btn-outline-danger me-1" type="button" data-value="deleted" data-name="status" data-warning="Hapus / tangguhkan akun" data-url="{{ route('user.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
                                                <i class="bx bx-x-circle"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-icon btn-outline-success" type="button" data-value="active" data-name="status" data-warning="Aktivasi akun" data-url="{{ route('user.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
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

