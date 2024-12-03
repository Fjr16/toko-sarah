<!doctype html>
<html lang="en">
  <head>
  	<title>{{ $title }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">


	</head>
	<body class="img row" style="background-image: url(assets/img/bg.jpg);">
	{{-- <body class="img row"> --}}
        <div class="col-md-12 img body" style="background: overlay;">
            <section class="ftco-section">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div>
                                @if (session()->has('error'))
                                <div class="alert alert-dark d-flex mb-4" role="alert">
                                    <span class="small">{{ session('error') }} !</span>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-12 text-center mb-4">
                                <h2 class="heading-section mb-4">TOKO SARAH KOSMETIK</h2>
                            </div>
                            <div class="login-wrap p-0">
                                <form action="{{ route('login') }}" class="signin-form" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Username" name="username" value="{{ old('username') }}" required>
                                    </div>
                                    <div class="form-group">
                                    <input id="password-field" type="password" class="form-control" placeholder="Password" name="password" required>
                                    <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <script src="{{ asset('assets/guest/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/guest/js/popper.js') }}"></script>
        <script src="{{ asset('assets/guest/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/guest/js/main.js') }}"></script>
        <script>
            window.setTimeout(function() {
                $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
                    $(this).remove();
                });
            }, 2000);
        </script>

	</body>
</html>

