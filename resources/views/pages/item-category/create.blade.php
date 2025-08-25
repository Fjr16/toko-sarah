@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">{{ $title ?? 'undefined' }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" id="category_form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <input type="hidden" class="form-control form-control-md" name="category_id" value="{{ $item?->id ?? null }}"/>
                            <label for="nama-kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control form-control-md" id="nama-kategori" name="name" placeholder="Input nama kategori disini" value="{{ old('name', $item?->name ?? null) }}" required />
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <div class="">
                                @foreach ($status as $stts)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" value="{{ $stts->value }}" name="status" id="status-{{ $stts->value }}" {{ isset($item) ? ($item->status === $stts->value ? 'checked' : '') : ($stts->name == 'active' ? 'checked' : '') }}>
                                        <label class="form-check-label" for="status-{{ $stts->value }}">
                                        {{ $stts->value }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4 border-top">
                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('kategori/barang.index') }}" class="btn btn-md btn-danger me-2"><i class="bx bx-left-arrow"></i> Kembali</a>
                            <button type="button" id="category_submit" class="btn btn-md btn-success"><i class="bx bx-file"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('category_submit').addEventListener('click', async function(){
                const form = document.getElementById('category_form');
                const data = Object.fromEntries(new FormData(form).entries());
                const url = `{{ route('kategori/barang.store') }}`;

                let res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN' : "{{ csrf_token() }}",
                        'Content-Type' : 'application/json'
                    },
                    body : JSON.stringify(data),
                });

                if (!res.ok) {
                    notify('error', res.status);
                }

                let result = await res.json();

                if (result.status) {
                    notify('success', result.message);
                    form.reset();
                    setTimeout(() => {
                        window.location.href = "{{ route('kategori/barang.index') }}";
                    }, 1800);
                }else{
                    console.log(result.message);
                    notify('error', result.message.slice(0,150));
                }
            });
        </script>
    @endpush
@endsection
