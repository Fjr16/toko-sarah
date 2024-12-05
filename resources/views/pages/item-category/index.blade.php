@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4 d-flex justify-content-between align-items-center">
            <h4 class="m-0 p-0">Data Kategori</h4>
            <a href="{{ route('kategori/barang.create') }}" class="btn btn-sm btn-primary">+ Tambah Kategori</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr onclick="window.location.href='{{ route('kategori/barang.show', encrypt($item->id)) }}';" style="cursor: :pointer;">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status == 'active' ? 'primary' : 'danger' }}">{{ $item->status ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('kategori/barang.edit', encrypt($item->id)) }}" class="btn btn-sm btn-warning me-1"><i class="bx bx-edit"></i></a>
                                    <form action="{{ route('kategori/barang.destroy', encrypt($item->id)) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" name="status" value="deleted" type="submit"><i class="bx bx-x-circle"></i></button>
                                        <button class="btn btn-sm btn-success" name="status" value="active" type="submit"><i class="bx bx-check-circle"></i></button>
                                    </form>
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