@extends('layouts.auth2.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4">
            <h4 class="m-0 p-0 mb-4">{{ $title ?? '' }}</h4>
            {{-- form filter stok --}}
            <form action="{{ route('stok/barang.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-0">
                <select name="product_id" id="product-select" class="form-control form-control-md" style="flex:1 1 200px;"></select>
                <input type="text" name="batch_number" value="{{ request('batch_number') }}" class="form-control form-control-md" placeholder="No Batch" style="flex:1 1 150px;">
                <input type="date" name="exp_date" value="{{ request('exp_date') }}" class="form-control form-control-md" style="flex:1 1 150px;">

                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bx bx-search"></i>
                </button>
                <a href="{{ route('stok/barang.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bx bx-reset"></i>
                </a>
            </form>
            {{-- end form filter stok --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th>No Batch</th>
                            <th>Tanggal Expire</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration ?? '-' }}</td>
                            <td>
                                <span class="d-block">{{ $item->product->name ?? '-' }}</span>
                                <span class="badge bg-primary">
                                    <small>{{ $item->product->code ?? '-' }}</small>
                                </span>
                            </td>
                            <td>{{ $item->batch_number ?? '-' }}</td>
                            <td>{{ $item->exp_date ?? '-' }}</td>
                            <td>{{ $item->stock ?? '-' }}</td>
                            <td>Rp. {{ number_format($item->unit_cost, 0) . ' /' . $item->product->small_unit ?? '-' }}</td>
                        </tr>
                        @empty
                         <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination dengan filter tetap -->
            <div class="mt-3">
                {{ $data->appends(request()->all())->links() }}
            </div>
        </div>
    </div>

    <div class="fab-wrapper">
        <div class="fab-container" id="fabMenu">
          <a href="{{ route('stok/barang.create') }}" class="fab-btn fab-main">
            <i class="bx bx-book-add" style="font-size:22px;"></i>
          </a>
          <button class="fab-btn fab-primary" onclick="toggleFab()">
            <i id="fabIcon" class="bx bx-expand"></i>
          </button>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let selectedProd = @json($selectedProduct);
        if (selectedProd) {
            let option = new Option("["+selectedProd.code+"] " + selectedProd.name, selectedProd.id, true, true);
            $('#product-select').append(option).trigger('change');
        }
    </script>
@endpush
<x-modal-confirm-delete></x-modal-confirm-delete>
