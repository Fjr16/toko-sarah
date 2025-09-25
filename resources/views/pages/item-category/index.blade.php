@extends('layouts.auth2.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4 align-items-center">
            <h4 class="m-0 p-0">{{ $title ?? ''  }}</h4>
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
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr onclick="window.location.href='{{ route('kategori/barang.show', encrypt($item->id)) }}';" style="cursor: pointer;">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->code ?? '-' }}</td>
                                    <td>{{ $item->name ?? '-' }}</td>
                                    <td><span class="badge bg-{{ $item->status == 'In Active' ? 'danger' : 'info' }}">{{ $item->status ?? '-' }}</span></td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('kategori/barang.edit', encrypt($item->id)) }}" class="btn btn-icon btn-outline-warning mx-2" onclick="event.stopPropagation();"><i class="bx bx-edit"></i></a>

                                            <button class="btn btn-icon btn-outline-danger me-1" type="button" data-warning="Hapus kategori barang" data-url="{{ route('kategori/barang.destroy', encrypt($item->id)) }}" onclick="event.stopPropagation(); showModalDelete(this)">
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
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trashed as $item)
                                <tr class="text-danger">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->code ?? '-' }}</td>
                                    <td>{{ $item->name ?? '-' }}</td>
                                    <td><span class="badge bg-{{ $item->status == 'in active' ? 'danger' : 'info' }}">{{ $item->status ?? '-' }}</span></td>
                                    <td>
                                        <form action="{{ route('kategori/barang.restore', encrypt($item->id)) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-warning d-flex align-items-center justify-content-center gap-1" type="submit">
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
    <div class="fab-wrapper">
        <div class="fab-container" id="fabMenu">
          <a href="{{ route('kategori/barang.create') }}" class="fab-btn fab-main">
            <i class="bx bx-book-add" style="font-size:22px;"></i>
          </a>
          <button class="fab-btn fab-primary" onclick="toggleFab()">
            <i id="fabIcon" class="bx bx-expand"></i>
          </button>
        </div>
    </div>
@endsection
<x-modal-confirm-delete></x-modal-confirm-delete>
