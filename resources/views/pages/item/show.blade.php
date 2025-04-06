@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Detail {{ $title ?? '' }}</h4>
        </div>
        <div class="card-body">
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
                    <td>Rp. {{ number_format($item->cost, 2, $systemSetting?->currency?->decimal_separator, $systemSetting?->currency?->thousand_separator) }} / {{ $item->small_unit ?? '' }}</td>
                </tr>
                <tr>
                    <th width="30%" class="fw-bold">Harga Jual</th>
                    <td>Rp. {{ number_format($item->price, 2, $systemSetting?->currency?->decimal_separator, $systemSetting?->currency?->thousand_separator) }} / {{ $item->small_unit ?? '' }}</td>
                </tr>
                <tr>
                    <th width="30%" class="fw-bold">Stok</th>
                    <td>{{ $item->stok ?? 0 }} {{ $item->small_unit ?? '' }}</td>
                </tr>
                <tr>
                    <th width="30%" class="fw-bold">Peringatan Stok</th>
                    <td>{{ $item->stok_alert ?? 0 }} {{ $item->small_unit ?? '' }}</td>
                </tr>
                <tr>
                    <th width="30%" class="fw-bold">Pajak</th>
                    <td>{{ $item->tax ?? 0 }} %</td>
                </tr>
                <tr>
                    <th width="30%" class="fw-bold">Tipe Pajak</th>
                    <td>{{ $item->tax_type ?? '-' }}</td>
                </tr>
                <tr>
                    <th width="30%" class="fw-bold">Catatan</th>
                    <td>{!! $item->note ?? '-' !!}</td>
                </tr>
                <tr>
                    <th width="30%" class="fw-bold">Satuan Terkecil</th>
                    <td>{{ $item->small_unit ?? '-' }}</td>
                </tr>
                <tr>
                    <th width="30%" class="fw-bold">Satuan Menengah</th>
                    <td>{{ $item->medium_unit ?? '-' }} (x {{ $item->medium_to_small ?? 0 }} {{ $item->small_unit }})</td>
                </tr>
                <tr>
                    <th width="30%" class="fw-bold">Satuan Terbesar</th>
                    <td>{{ $item->big_unit ?? '-' }} (x {{ $item->big_to_medium ?? 0 }} {{ $item->medium_unit }})</td>
                </tr>
            </table>
        </div>
    </div>
@endsection