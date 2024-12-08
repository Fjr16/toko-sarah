@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Penjualan</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="kode-produk">Barcode</label>
                        <input type="text" name="code" id="kode-produk" class="form-control" placeholder="scan barcode">
                    </div>
                    <div class="col-md-2">
                        <label for="nama-produk">Nama Produk</label>
                        <input type="text" id="nama-produk" class="form-control" placeholder="Nama Produk" disabled>
                    </div>
                    <div class="col-md-2">
                        <label for="stok-produk">Stok</label>
                        <input type="number" id="stok-produk" class="form-control" placeholder="0" disabled>
                    </div>
                    <div class="col-md-2">
                        <label for="harga-satuan">Harga Satuan</label>
                        <input type="text" id="harga-satuan" class="form-control" placeholder="Rp. 0" disabled>
                    </div>
                    <div class="col-md-2">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" value="1">
                    </div>
                    <div class="col-md-2">
                        <label for="diskon">Diskon (%)</label>
                        <input type="number" class="form-control" id="diskon" name="diskon" min="0" value="0">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
            </form>

            <h5 class="mt-4">Daftar Transaksi</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach($keranjang as $item)
                        <tr>
                            <td>{{ $item->produk->nama }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->jumlah * $item->produk->harga, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('penjualan.remove', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach --}}
                        <tr>
                            <td>nama</td>
                            <td>5</td>
                            <td>Rp 50.000</td>
                            <td>Rp 20.000</td>
                            <td>
                                {{-- <form action="{{ route('penjualan.remove', $item->id) }}" method="POST" style="display:inline;"> --}}
                                <form action="" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                </tbody>
            </table>

            <h5>Total Pembayaran: Rp {{ number_format($total, 0, ',', '.') }}</h5>

            <div class="form-group">
                <label for="metodePembayaran">Metode Pembayaran</label>
                <select class="form-control" id="metodePembayaran" name="metode_pembayaran">
                    <option value="tunai">Tunai</option>
                    <option value="kartu_kredit">Kartu Kredit</option>
                    <option value="e_wallet">E-Wallet</option>
                </select>
            </div>

            <button class="btn btn-success">Simpan Transaksi</button>
            <button class="btn btn-secondary">Batal</button>
        </div>
    </div>
@endsection