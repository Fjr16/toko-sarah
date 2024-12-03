@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4 d-flex justify-content-between align-items-center">
            <h4 class="m-0 p-0">Data Barang</h4>
            <a href="{{ route('barang.create') }}" class="btn btn-sm btn-primary">+ Tambah Barang</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kategori</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Satuan Terkecil</th>
                            <th>Satuan Menengah</th>
                            <th>Satuan Terbesar</th>
                            <th>Harga Awal</th>
                            <th>Stok</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->itemCategory->name ?? '-' }}</td>
                            <td>{{ $item->code ?? '-' }}</td>
                            <td>{{ $item->name ?? '-' }}</td>
                            <td>{{ $item->small_unit ?? '-' }}</td>
                            <td>{{ $item->medium_unit ?? '-' }}</td>
                            <td>{{ $item->big_unit ?? '-' }}</td>
                            <td>{{ $item->base_price ?? '-' }}</td>
                            <td>{{ $item->stok ?? '-' }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('barang.edit', encrypt($item->id)) }}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                                    <form action="{{ route('barang.destroy', encrypt($item->id)) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit"><i class="bx bx-trash"></i></button>
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