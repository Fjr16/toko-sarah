@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">
                                General Settings
                            </h5>
                        </div>
                        <div class="card-body mt-3">
                            <form action="{{ route('pengaturan/sistem.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md mb-4">
                                        <label for="nama-toko" class="form-label">Nama Toko</label>
                                        <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $item->company_name ?? '') }}" id="nama-toko" placeholder="Nama Toko"/>
                                    </div>
                                    <div class="col-md mb-4">
                                            <label for="email-toko" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="company_email" value="{{ old('company_email', $item->company_email ?? '') }}" id="email-toko" placeholder="name@example.com"/>
                                    </div>
                                    <div class="col-md mb-4">
                                        <label for="telp-toko" class="form-label">Telp / HP</label>
                                        <input type="text" class="form-control" name="company_phone" value="{{ old('company_phone', $item->company_phone ?? '') }}" id="telp-toko" placeholder="+62xxxxxxxxx" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md mb-4">
                                        <label for="currency_id" class="form-label">Mata Uang Standard</label>
                                        <select class="form-select" id="currency_id" name="currency_id" aria-label="Default select example">
                                            @foreach ($data as $curr)
                                                <option {{ (old('currency_id', $item->currency_id) === $curr->id ? 'selected' : ($loop->first  ? 'selected' : '')) }} value="{{ $curr->id }}">{{ $curr->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md mb-4">
                                        <label for="default-currency-position" class="form-label">Posisi Mata Uang</label>
                                        <select class="form-select" id="default-currency-position" name="currency_position_default" aria-label="Default select example">
                                            @foreach ($position as $index => $pos)
                                                <option {{ (old('currency_position_default', $item->currency_position_default) === $index ? 'selected' : ($loop->first ? 'selected' : '')) }} value="{{ $index }}">{{ $pos ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md mb-4">
                                        <label for="notification-email" class="form-label">Email Notifikasi</label>
                                        <input type="text" class="form-control" name="notification_email" id="notification-email" placeholder="email@test.com" value="{{ old('notification_email', $item->notification_email ?? '') }}"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md mb-4">
                                        <label for="alamat-toko" class="form-label">Alamat Toko</label>
                                        <input type="text" class="form-control" name="company_address" id="alamat-toko" value="{{ old('company_address', $item->company_address ?? '') }}"/>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md">
                                        <button class="btn btn-sm btn-primary" type="submit"><i class="bx bx-check"></i> Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card position-relative">
                        <div class="overlay">
                            <div class="overlay-content">
                                <h5 class="text-white text-uppercase">Segera Hadir ...</h5>
                            </div>
                        </div>
                        <div class="card-header bg-primary">
                            <h5 class="mb-0 text-white">
                                Mail Settings
                            </h5>
                        </div>
                        <div class="card-body mt-3">
                            <form action="" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md mb-4">
                                        <label for="mail-mailer" class="form-label text-uppercase text-uppercase">Mail_Mailer</label>
                                        <input type="text" class="form-control" name="mail_mailer" id="mail-mailer" value="smtp"/>
                                    </div>
                                    <div class="col-md mb-4">
                                            <label for="mail_host" class="form-label text-uppercase">Mail_Host</label>
                                            <input type="text" class="form-control" name="mail_host" id="mail_host" value="mailhog"/>
                                    </div>
                                    <div class="col-md mb-4">
                                        <label for="mail_port" class="form-label text-uppercase">Mail_port</label>
                                        <input type="number" class="form-control" name="mail_port" id="mail_port" value="1025" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md mb-4">
                                        <label for="mail-mailer" class="form-label text-uppercase text-uppercase">Mail_Mailer</label>
                                        <input type="text" class="form-control" name="mail_mailer" id="mail-mailer" value="smtp"/>
                                    </div>
                                    <div class="col-md mb-4">
                                        <label for="mail_username" class="form-label text-uppercase">Mail_Username</label>
                                        <input type="text" class="form-control" name="mail_username" id="mail_username"/>
                                    </div>
                                    <div class="col-md mb-4">
                                        <label for="mail_password" class="form-label text-uppercase">Mail_Password</label>
                                        <input type="password" class="form-control" name="mail_password" id="mail_password"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md mb-4">
                                        <label for="mail_encryption" class="form-label text-uppercase">Mail_Encryption</label>
                                        <input type="text" class="form-control" name="mail_encryption" id="mail_encryption"/>
                                    </div>
                                    <div class="col-md mb-4">
                                        <label for="mail_from_address" class="form-label text-uppercase">Mail_From_Address</label>
                                        <input type="text" class="form-control" name="mail_from_address" id="mail_from_address"/>
                                    </div>
                                    <div class="col-md mb-4">
                                        <label for="mail_from_name" class="form-label text-uppercase">Mail_From_Name</label>
                                        <input type="text" class="form-control" name="mail_from_name" id="mail_from_name" value="Toko Sarah"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md mt-3">
                                        <button class="btn btn-sm btn-primary" type="submit"><i class="bx bx-check"></i> Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-modal-confirm-delete></x-modal-confirm-delete>
