@extends('layouts.auth.main')

@section('content')
    <div class="card">
        <div class="card-header mb-4 border-bottom">
            <h4 class="m-0 p-0">Selamat Datang {{ Auth::user()->role ?? '' }}</h4>
        </div>
        <div class="card-body">
            <form action="" method="">
                <div class="row"></div>
            </form>
        </div>
    </div>
@endsection