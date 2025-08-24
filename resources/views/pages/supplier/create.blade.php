@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">{{ $title ?? '-' }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" id="supplier_form">
                <div class="row">
                    <input type="hidden" class="form-control form-control-md" id="supplier_id" name="supplier_id" value="{{ isset($item) ? encrypt($item->id) : null }}"/>
                    <div class="col-md mb-3">
                        <label for="nama_supplier" class="form-label">Nama *</label>
                        <input type="text" class="form-control form-control-md" id="nama_supplier" name="name" placeholder="Nama Supplier" value="{{ old('name', $item?->name ?? null) }}" required />
                    </div>
                    <div class="col-md mb-3">
                        <label for="type" class="form-label">Jenis *</label>
                        <select name="type" id="type" class="form-control form-control-md" required>
                            @foreach ($types as $type)
                                <option value="{{ $type->value }}" @selected(old('type', $item?->type ?? null) === $type->value)>{{ $type->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md mb-3">
                        <label for="company_name" class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control form-control-md" id="company_name" name="company_name" placeholder="Nama Perusahaan" value="{{ old('company_name', $item?->company_name ?? null) }}" />
                    </div>
                    
                    <div class="col-md mb-3">
                        <label for="tax_number" class="form-label">Identitas Pajak (NPWP)</label>
                        <input type="text" class="form-control form-control-md" id="tax_number" name="tax_number" placeholder="NPWP" value="{{ old('tax_number', $item?->tax_number ?? null) }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" class="form-control form-control-md" id="contact_person" name="contact_person" placeholder="supplier@gmail.com" value="{{ old('contact_person', $item?->contact_person ?? null) }}"/>
                    </div>
                    <div class="col-md mb-3">
                        <label for="phone" class="form-label">Telp / HP</label>
                        <input type="text" class="form-control form-control-md" id="phone" name="phone" placeholder="+62xx-xxxx-xxxx" value="{{ old('phone', $item?->phone ?? null) }}" required />
                    </div>
                    <div class="col-md mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control form-control-md" id="email" name="email" placeholder="supplier@gmail.com" value="{{ old('email', $item?->email ?? null) }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="city" class="form-label">Kota</label>
                        <input type="text" class="form-control form-control-md" id="city" name="city" placeholder="Nama Kota" value="{{ old('city', $item?->city ?? null) }}" />
                    </div>
                    <div class="col-md mb-3">
                        <label for="province" class="form-label">Provinsi</label>
                        <input type="text" class="form-control form-control-md" id="province" name="province" placeholder="Nama Provinsi" value="{{ old('province', $item?->province ?? null) }}"/>
                    </div>
                    <div class="col-md mb-3">
                        <label for="country" class="form-label">Negara</label>
                        <input type="text" class="form-control form-control-md" id="country" name="country" placeholder="Nama Negara" value="{{ old('country', $item?->country ?? null) }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control form-control-md" id="alamat" name="address" placeholder="alamat supplier" value="{{ old('address', $item?->address ?? null) }}"/>
                    </div>
                    <div class="col-md mb-3">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control form-control-md" id="postal_code" name="postal_code" placeholder="Kode Pos" value="{{ old('postal_code', $item?->postal_code ?? null) }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="bank_account" class="form-label">Nama Rekening</label>
                        <input type="text" class="form-control form-control-md" id="bank_account" name="bank_account" placeholder="Nama rekening" value="{{ old('bank_account', $item?->bank_account ?? null) }}"/>
                    </div>
                    <div class="col-md mb-3">
                        <label for="bank_number" class="form-label">Nomor Rekening</label>
                        <input type="text" class="form-control form-control-md" id="bank_number" name="bank_number" placeholder="Nomor rekening" value="{{ old('bank_number', $item?->bank_number ?? null) }}"/>
                    </div>
                </div>
                <div class="row">
                    <label for="status" class="form-label">Status Supplier</label>
                    <div class="col-md mb-3">
                        @foreach ($status as $stts)    
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="status_{{ $stts->value }}" value="{{ $stts->value }}" {{ (isset($item) ? ($item?->status === $stts->value ? 'checked' : '') : ($stts->value === 'Active' ? 'checked' : '')) }}>
                            <label class="form-check-label" for="status_{{ $stts->value }}">
                                {{ $stts->value }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-12 mt-4 border-top">
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('supplier.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                        <button type="button" class="btn btn-md btn-success" id="supplier_submit"><i class="bx bx-file"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('supplier_submit').addEventListener('click', async function (){
            const form = document.getElementById('supplier_form');
            const data = Object.fromEntries(new FormData(form).entries());
            const url = `{{ route('supplier.store') }}`;
            let res = await fetch(url, {
                method:'POST',
                headers: {
                    'X-CSRF-TOKEN' : "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                },
                body:JSON.stringify(data),
            });

            if (!res.ok) {
                notify('error', res.status);
            }

            let result = await res.json();

            if (result.status) {
                notify('success', result.message);
                form.reset();
                setTimeout(() => {
                    window.location.href= "{{ route('supplier.index') }}";
                }, 1800);
            }else{
                console.log(result.message);
                notify('error', result.message.slice(0,200));
            }
        });

    </script>
    @endpush
@endsection