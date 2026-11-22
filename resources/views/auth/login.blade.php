<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('adminlte/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h4"><b>{{ config('app.name') }}</b></a>
            <div class="text-muted small">{{ config('app.instansi') }}</div>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Silakan masuk untuk memulai sesi Anda</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-7">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Ingat Saya</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
</body>
</html>