@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4 d-flex justify-content-between align-items-center">
            <h4 class="m-0 p-0">Riwayat Penjualan</h4>
            <a href="" class="btn btn-sm btn-primary"><i class="bx bx-printer"></i> Cetak laporan</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="datatable">
                    <thead>
                        {{-- <tr class="table-secondary">
                            <th>
                                <select name="status_filter" id="status_filter" class="form-select form-select-sm">
                                    <option value="paid">Paid</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="filter no nota"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="filter"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="filter"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="filter"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="filter"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="filter"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="filter"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="filter"></th>
                            <th><a href="" class="btn btn-sm btn-primary"><i class="bx bx-filter"></i></a></th>
                        </tr> --}}
                        <tr>
                            <th>Status</th>
                            <th>No Nota</th>
                            <th>Kasir</th>
                            {{-- <th>Items</th> --}}
                            {{-- <th>Total Items</th> --}}
                            <th>Subtotal</th>
                            <th>Total Diskon</th>
                            <th>Grand Total</th>
                            <th>Metode Bayar</th>
                            <th>Jumlah Bayar</th>
                            <th>Kembalian</th>
                            <th>Tanggal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td><span class="badge bg-{{ $item->status === 'paid' ? 'success' : ($item->status === 'unpaid' ? 'warning' : 'secondary') }}">{{ $item->status ?? '' }}</span></td>
                            <td onclick="detailPenjualan('{{ encrypt($item->id) }}')" style="cursor: pointer;">
                                {{ $item->selling_id ?? '-' }}
                            </td>
                            <td>{{ $item->detail->name ?? '-' }}</td>
                            {{-- <td>{{ $item->items ?? '-' }}</td> --}}
                            {{-- <td>{{ $item->total_item ?? '-' }}</td> --}}
                            <td class="text-nowrap">{{ $item->total_kotor ? 'Rp. ' . number_format($item->total_kotor) : '-' }}</td>
                            <td class="text-nowrap">{{ $item->total_diskon ? 'Rp. ' . number_format($item->total_diskon) : '-'}}</td>
                            <td class="text-nowrap">{{ $item->total_bersih ? 'Rp. ' . number_format($item->total_bersih) : '-' }}</td>
                            <td>{{ $item->metode_bayar ?? '-' }}</td>
                            <td class="text-nowrap">{{ $item->jumlah_bayar ? 'Rp. ' . number_format($item->jumlah_bayar) : '-' }}</td>
                            <td class="text-nowrap">{{ $item->kembalian ? 'Rp. ' . number_format($item->kembalian) : '-' }}</td>
                            <td class="text-nowrap">{{ $item->created_at->format('d/M/Y') }}</td>
                            <td class="text-nowrap">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a href="" class="dropdown-item"><i class="bx bx-edit-alt me-1"></i>Edit</a>
                                        <form action="" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"><i class="bx bx-trash me-1"></i>Delete</button>
                                        </form>
                                        <a href="" class="dropdown-item"><i class="bx bx-printer me-1"></i>Nota</a>
                                    </div>
                                  </div>
                                <div class="d-flex">
                    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="modalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            
          </div>
        </div>
      </div>
@endsection
<x-modal-confirm-delete></x-modal-confirm-delete>

@section('script')
    <script>
            async function detailPenjualan(idPenjualan) {
                try {
                    const response = await fetch('/sales/riwayat/detail/' + idPenjualan);
                    if (!response.ok) {
                    throw new Error(response.statusText);
                    }
                    const data = await response.text();
                    const modalContent = document.querySelector('#modal-detail .modal-content');
                    modalContent.innerHTML = data;
                    $('#modal-detail').modal('show');
                } catch (error) {
                    console.error(error);
                }
            }
    </script>
@endsection
