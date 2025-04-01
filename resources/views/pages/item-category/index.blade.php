@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4 d-flex justify-content-between align-items-center">
            <h4 class="m-0 p-0">Data {{ $title ?? ''  }}</h4>
            <a href="{{ route('kategori/barang.create') }}" class="btn btn-sm btn-primary">+ Tambah {{ $title ?? '' }}</a>
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
                    <div class="table-responsive">
                        <table class="table table-hover datatable">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kategori</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td onclick="window.location.href='{{ route('kategori/barang.show', encrypt($item->id)) }}';" style="cursor: pointer;">{{ $loop->iteration }}</td>
                                    <td onclick="window.location.href='{{ route('kategori/barang.show', encrypt($item->id)) }}';" style="cursor: pointer;">{{ $item->name ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('kategori/barang.edit', encrypt($item->id)) }}" class="btn btn-icon btn-outline-warning mx-2"><i class="bx bx-edit"></i></a>
                                            
                                            <button class="btn btn-icon btn-outline-danger me-1" type="button" data-warning="Hapus kategori barang" data-url="{{ route('kategori/barang.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
                                                <i class="bx bx-trash"></i>
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
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead class="table-danger">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kategori</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trashed as $item)
                                <tr class="text-danger">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('kategori/barang.restore', encrypt($item->id)) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-warning me-1" type="submit">
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
