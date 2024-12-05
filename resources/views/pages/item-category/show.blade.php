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
                    <thead>
                        <tr>
                            <th>#</th>
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
                            <td>{{ $item->code ?? '-' }}</td>
                            <td>{{ $item->name ?? '-' }}</td>
                            <td>{{ $item->small_unit ?? '-' }}</td>
                            <td>{{ $item->medium_unit ?? '-' }}</td>
                            <td>{{ $item->big_unit ?? '-' }}</td>
                            <td>{{ ((int) $item->base_price ?? 0) . ' /' . ($item->small_unit ?? '-') }}</td>
                            <td>{{ ($item->stok ?? 0) . ' ' . $item->small_unit ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $item->status === 'active' ? 'primary' : 'danger' }}">
                                    {{ $item->status ?? '-' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex">
                                    <a href="{{ route('barang.edit', encrypt($item->id)) }}" class="btn btn-sm btn-warning me-1"><i class="bx bx-edit"></i></a>
                                    <form action="{{ route('barang.destroy', encrypt($item->id)) }}" method="POST">
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