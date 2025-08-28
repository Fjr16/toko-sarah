@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom d-flex justify-content-between">
            <div class="">
                <h4 class="m-0 p-0">{{ $title ?? '' }}</h4>
                <p class="text-primary small">{{ $item?->name ?? '-' }} / {{ $item?->code ?? '-' }}</p>
            </div>
            <div class="">
                <a href="{{ route('barang.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bxs-left-arrow"></i> Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th width="30%" class="fw-bold">Kode Produk</th>
                            <td>{{ $item->code ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Nama Produk</th>
                            <td>{{ $item->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Kategori</th>
                            <td>{{ $item->itemCategory->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Harga Beli</th>
                            <td>Rp. {{ number_format($item->default_cost, 0) }} <span class="badge bg-danger">{{ $item->small_unit ?? '' }}</span></td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Margin(%)</th>
                            <td>{{ $item->margin ?? '-' }} %</td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Harga Jual</th>
                            <td>Rp. {{ number_format($item->default_price, 0) }} <span class="badge bg-danger">{{ $item->small_unit ?? '' }}</span></td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Stok</th>
                            <td>{{ $item->all_stok ?? 0 }} {{ $item->small_unit ?? '' }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Peringatan Stok</th>
                            <td>{{ $item->stok_alert ?? 0 }} {{ $item->small_unit ?? '' }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Satuan Terkecil</th>
                            <td>{{ $item->small_unit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Satuan Menengah</th>
                            <td>{{ $item->medium_unit ?? '-' }} (x {{ $item->medium_to_small ?? '-' }} {{ $item->small_unit }})</td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Satuan Terbesar</th>
                            <td>{{ $item->big_unit ?? '-' }} (x {{ $item->big_to_medium ?? '-' }} {{ $item->medium_unit }})</td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Status</th>
                            <td><span class="badge bg-{{ $item->status == 'Active' ? 'primary' : 'danger' }}">{{ $item->status ?? '-' }}</span></td>
                        </tr>
                        <tr>
                            <th width="30%" class="fw-bold">Deskripsi</th>
                            <td>{{ $item->description ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-3 text-center">
                    <div class="border rounded p-2">
                        <img src="{{ $item?->image ? Storage::url($item->image) : '/images/no-image.png' }}"
                             alt="Product Image"
                             class="img-fluid rounded"
                             style="max-height: 200px; object-fit: contain;">
                        <p class="mt-2 text-muted">Gambar Produk</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection