@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header border-bottom mb-4 d-flex justify-content-between align-items-center">
            <h4 class="m-0 p-0">Data {{ $title ?? '' }}</h4>
        </div>
        <div class="card-body">
            <div class="nav-align-top nav-tabs-shadow">
                <ul class="nav nav-pills nav-tabs" role="tablist">
                  <li class="nav-item">
                    <button
                      type="button"
                      class="nav-link active"
                      role="tab"
                      data-bs-toggle="tab"
                      data-bs-target="#navs-top-home"
                      aria-controls="navs-top-home"
                      aria-selected="true">
                      Data {{ $title ?? '' }}
                    </button>
                  </li>
                  <li class="nav-item">
                    <button
                      type="button"
                      class="nav-link"
                      role="tab"
                      data-bs-toggle="tab"
                      data-bs-target="#navs-top-profile"
                      aria-controls="navs-top-profile"
                      aria-selected="false">
                      Restore {{ $title ?? '' }}
                    </button>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap" id="datatable">
                            <thead class="table-primary">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>NPWP / Identitas Pajak</th>
                                    <th>Rekening Bank</th>
                                    <th>Contact Person</th>
                                    <th>Telp / HP</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->code ?? '-' }}</td>
                                    <td>{{ $item->name ?? '-' }}</td>
                                    <td>{{ $item->type ?? '-' }}</td>
                                    <td>{{ $item->tax_number ?? '-' }}</td>
                                    <td>
                                        {{ 'Nama rek: ' . $item->bank_account ?? '-' }} <br>
                                        {{ 'No Rek: ' . $item->bank_number ?? '-' }}
                                    </td>
                                    <td>{{ $item->contact_person ?? '-' }}</td>
                                    <td>{{ $item->phone ?? '-' }}</td>
                                    <td>{{ $item->email ?? '-' }}</td>
                                    <td>
                                        {{ 
                                            ($item->address ?? '-') . ', ' .
                                            ($item->city ?? '-') . ', ' . 
                                            ($item->province ?? '-') . ', ' . 
                                            ($item->country ?? '-') . ', ' . 
                                            ($item->postal_code ?? '-')
                                        }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('supplier.edit', encrypt($item->id)) }}" class="btn btn-icon btn-outline-warning me-1"><i class="bx bx-edit"></i></a>
                                            <button class="btn btn-icon btn-outline-danger me-1" type="button" data-warning="Hapus supplier" data-url="{{ route('supplier.destroy', encrypt($item->id)) }}" onclick="showModalDelete(this)">
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
                  <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table datatable text-nowrap">
                            <thead class="table-danger">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>NPWP / Identitas Pajak</th>
                                    <th>Rekening Bank</th>
                                    <th>Contact Person</th>
                                    <th>Telp / HP</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trashed as $item)
                                <tr class="text-danger">
                                    <td>{{ $item->code ?? '-' }}</td>
                                    <td>{{ $item->name ?? '-' }}</td>
                                    <td>{{ $item->type ?? '-' }}</td>
                                    <td>{{ $item->tax_number ?? '-' }}</td>
                                    <td>
                                        {{ $item->bank_account ?? '-' }} <br>
                                        {{ $item->bank_number ?? '-' }}
                                    </td>
                                    <td>{{ $item->contact_person ?? '-' }}</td>
                                    <td>{{ $item->phone ?? '-' }}</td>
                                    <td>{{ $item->email ?? '-' }}</td>
                                    <td class="text-wrap">
                                        {{ 
                                            ($item->address ?? '-') . ', ' .
                                            ($item->city ?? '-') . ', ' . 
                                            ($item->province ?? '-') . ', ' . 
                                            ($item->country ?? '-') . ', ' . 
                                            ($item->postal_code ?? '-')
                                        }}
                                    </td>
                                    <td>
                                        <form action="{{ route('supplier.restore', encrypt($item->id)) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-warning me-1" type="submit">
                                                <i class='bx bx-refresh'></i>
                                                Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<div class="fab-wrapper">
  <div class="fab-container" id="fabMenu">
    <a href="{{ route('supplier.create') }}" class="fab-btn fab-dark">
      <i class="bx bx-plus"></i>
    </a>
    {{-- <a href="{{ route('customer.edit', 1) }}" class="fab-btn fab-warning">
      <i class="bx bx-pencil"></i>
    </a> --}}
    <button class="fab-btn fab-main" onclick="toggleFab()">
      <i id="fabIcon" class="bx bx-expand"></i>
    </button>
  </div>
</div>
<x-modal-confirm-delete></x-modal-confirm-delete>
