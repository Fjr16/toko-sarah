@extends('layouts.auth2.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">{{ $title ?? '-' }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" id="customer_form">
                <div class="row">
                    <input type="hidden" class="form-control form-control-md" id="customer_id" name="customer_id" value="{{ isset($item) ? encrypt($item->id) : null }}"/>
                    <div class="col-md mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control form-control-md" id="nik" name="nik" value="{{ old('nik', $item?->nik ?? '') }}" required />
                    </div>
                    <div class="col-md mb-3">
                        <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control form-control-md" id="nama_pelanggan" name="name" placeholder="Nama Pelanggan" value="{{ old('name', $item?->name ?? '') }}" required />
                    </div>
                    <div class="col-md mb-3">
                        <label for="gender" class="form-label">Jenis Kelamin</label>
                        <select name="gender" id="gender" class="form-control form-control-md">
                            @foreach ($genders as $gender)
                                <option value="{{ $gender->value }}" @selected(old('gender', $item?->gender ?? '') === $gender->value)>{{ $gender->value }}</option>
                                {{-- <option value="{{ $gender->value }}">{{ $gender->value }}</option> --}}
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control form-control-md" id="email" name="email" placeholder="customer@gmail.com" value="{{ old('email', $item?->email ?? '') }}"/>
                    </div>
                    <div class="col-md mb-3">
                        <label for="phone" class="form-label">HP / WA</label>
                        <input type="text" class="form-control form-control-md" id="phone" name="phone" placeholder="+62xx-xxxx-xxxx" value="{{ old('phone', $item?->phone ?? '') }}" required />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="country" class="form-label">Negara</label>
                        <input type="text" class="form-control form-control-md" id="country" name="country" placeholder="Nama Negara" value="{{ old('country', $item?->country ?? '') }}"/>
                    </div>
                    <div class="col-md mb-3">
                        <label for="province" class="form-label">Provinsi</label>
                        <input type="text" class="form-control form-control-md" id="province" name="province" placeholder="Nama Provinsi" value="{{ old('province', $item?->province ?? '') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="city" class="form-label">Kota</label>
                        <input type="text" class="form-control form-control-md" id="city" name="city" placeholder="Nama Kota" value="{{ old('city', $item?->city ?? '') }}" />
                    </div>
                    <div class="col-md mb-3">
                        <label for="subdistrict" class="form-label">Kecamatan / Kelurahan</label>
                        <input type="text" class="form-control form-control-md" id="subdistrict" name="subdistrict" placeholder="Nama Kelurahan" value="{{ old('subdistrict', $item?->subdistrict ?? '') }}" />
                    </div>
                    <div class="col-md mb-3">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control form-control-md" id="postal_code" name="postal_code" placeholder="Kode Pos" value="{{ old('postal_code', $item?->postal_code ?? '') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control form-control-md" id="alamat" name="address" placeholder="alamat pelanggan" value="{{ old('address', $item?->address ?? '') }}"/>
                    </div>
                </div>
                <div class="col-md-12 mt-4 border-top">
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('customer.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                        <button type="button" class="btn btn-md btn-success" id="customer_submit"><i class="bx bx-file"></i> Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('customer_submit').addEventListener('click', async function (){
            const form = document.getElementById('customer_form');
            const data = Object.fromEntries(new FormData(form).entries());
            const url = `{{ route('customer.store') }}`;
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
                    window.location.href= "{{ route('customer.index') }}";
                }, 1800);
            }else{
                console.log(result.message);
                notify('error', result.message.slice(0,200));
            }
        });

    </script>
    @endpush
@endsection