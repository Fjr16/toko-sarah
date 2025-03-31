@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between border-bottom mb-4">
            <h4 class="m-0 p-0">Data User</h4>
            <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">+ Tambah User</a>
        </div>
        <div class="card-body">

            <div class="nav-align-top nav-tabs-shadow">
                <ul class="nav nav-pills nav-tabs" role="tablist">
                  <li class="nav-item">
                    <button
                      type="button"
                      class="nav-link active"
                      role="tab"
                      data-bs-toggle="tab"
                      data-bs-target="#navs-top-home"
                      aria-controls="navs-top-home"
                      aria-selected="true">
                      Data {{ $title ?? '' }}
                    </button>
                  </li>
                  <li class="nav-item">
                    <button
                      type="button"
                      class="nav-link"
                      role="tab"
                      data-bs-toggle="tab"
                      data-bs-target="#navs-top-profile"
                      aria-controls="navs-top-profile"
                      aria-selected="false">
                      Restore {{ $title ?? '' }}
                    </button>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                    <div class="table-responsive text-wrap">
                        <table class="table datatable">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Username</th>
                                    <th>Role</th>
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
                                            <div class="d-flex">
                                                <a href="{{ route('user.edit', encrypt($item->id)) }}" class="btn btn-icon btn-outline-warning mx-2"><i class="bx bx-edit"></i></a>
                                                <button class="btn btn-icon btn-outline-danger me-1" type="button" data-warning="Hapus / tangguhkan akun" data-url="{{ route('user.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
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
                  <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                    <div class="table-responsive text-wrap">
                        <table class="table datatable">
                            <thead class="table-danger">
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trashed as $item)
                                    <tr class="text-danger">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name ?? '-' }}</td>
                                        <td>{{ $item->username ?? '-' }}</td>
                                        <td>{{ $item->role ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('user.restore', encrypt($item->id)) }}" class="m-0" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-warning" type="submit">
                                                    <i class='bx bx-refresh'></i>
                                                    Restore
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                  </div>
                </div>
            </div>

            
        </div>
    </div>
@endsection
<x-modal-confirm-delete></x-modal-confirm-delete>

