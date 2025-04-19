@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4 d-flex justify-content-between align-items-center">
            <h4 class="m-0 p-0">Daftar Barang, Kategori <span class="text-primary">{{ $itemCategory->name ?? '-' }}</span></h4>
            <a href="{{ route('kategori/barang.index') }}" class="btn btn-sm btn-danger"><i class="bx bx-left-arrow"></i> Kembali</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="datatable">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Kategori</th>
                            <th>Produk</th>
                            <th>Harga Beli</th>
                            <th>Margin (%)</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration ?? '-' }}</td>
                            <td>{{ $item->itemCategory->name ?? '-' }}</td>
                            <td>
                                <span class="d-block">{{ $item->name ?? '-' }}</span>
                                <span class="badge bg-primary">
                                    <small>{{ $item->code ?? '-' }}</small>
                                </span>
                            </td>
                            <td>{{ number_format($item->cost, 0) . ' /' . $item->small_unit ?? '-' }}</td>
                            <td>{{ $item->margin ?? '-' }}</td>
                            <td>{{ number_format($item->price, 0) . ' /' . $item->small_unit ?? '-' }}</td>
                            <td>{{ $item->stok  . ' '. $item->small_unit }}</td>
                            <td class="text-nowrap">
                                <div class="d-flex">
                                    <a href="{{ route('barang.edit', encrypt($item->id)) }}" class="btn btn-icon btn-outline-warning"><i class="bx bx-edit"></i></a>
                                    <a href="{{ route('barang.show', encrypt($item->id)) }}" class="btn btn-icon btn-outline-primary mx-2"><i class="bx bxs-show"></i></a>
                                    <button class="btn btn-icon btn-outline-danger me-1" type="button" data-warning="Hapus Produk" data-url="{{ route('barang.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
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
    </div>
@endsection