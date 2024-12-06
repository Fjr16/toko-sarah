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
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration ?? '-' }}</td>
                            <td>{{ $item->itemCategory->name ?? '-' }}</td>
                            <td>{{ $item->code ?? '-' }}</td>
                            <td>{{ $item->name ?? '-' }}</td>
                            <td>{{ $item->small_unit ?? '-' }}</td>
                            <td>{{ $item->medium_unit ?? '-' }}</td>
                            <td>{{ $item->big_unit ?? '-' }}</td>
                            <td>{{ number_format($item->base_price ?? 0) . ' /' . $item->small_unit ?? '-' }}</td>
                            <td>{{ $item->stok  . ' '. $item->small_unit }}</td>
                            <td><span class="badge bg-{{ $item->status === 'active' ? 'primary' : 'danger' }}">{{ $item->status ?? '' }}</span></td>
                            <td class="text-nowrap">
                                <div class="d-flex">
                                    <a href="{{ route('barang.edit', encrypt($item->id)) }}" class="btn btn-icon btn-outline-warning mx-2"><i class="bx bx-edit"></i></a>
                                    @if ($item->status === 'active')
                                        <button class="btn btn-icon btn-outline-danger me-1" type="button" data-value="deleted" data-name="status" data-warning="Hapus / disable barang" data-url="{{ route('barang.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
                                            <i class="bx bx-x-circle"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-icon btn-outline-success" type="button" data-value="active" data-name="status" data-warning="Aktivasi barang" data-url="{{ route('barang.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
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