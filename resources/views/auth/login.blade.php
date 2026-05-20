@php $duralux = asset('duralux/assets'); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Sitenor Rejang Lebong</title>
    <link rel="shortcut icon" href="{{ $duralux }}/images/favicon.ico">
    <link rel="stylesheet" href="{{ $duralux }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ $duralux }}/vendors/css/vendors.min.css">
    <link rel="stylesheet" href="{{ $duralux }}/css/theme.min.css">
</head>
<body>
    <main class="auth-minimal-wrapper">
        <div class="auth-minimal-inner">
            <div class="minimal-card-wrapper">
                <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                    <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                        <img src="{{ $duralux }}/images/logo-abbr.png" alt="Sitenor" class="img-fluid">
                    </div>
                    <div class="card-body p-sm-5">
                        <h2 class="fs-20 fw-bolder mb-4">Login Admin</h2>
                        <h4 class="fs-13 fw-bold mb-2">Sitenor Kabupaten Rejang Lebong</h4>
                        <p class="fs-12 fw-medium text-muted">Masuk untuk mengelola data tenaga dan cabang olahraga.</p>

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="w-100 mt-4 pt-2">
                            @csrf
                            <div class="mb-4">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" required autofocus>
                                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                    <label class="custom-control-label c-pointer" for="remember_me">Ingat saya</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="fs-11 text-primary">Lupa password?</a>
                                @endif
                            </div>
                            <div class="mt-5">
                                <button type="submit" class="btn btn-lg btn-primary w-100">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ $duralux }}/vendors/js/vendors.min.js"></script>
    <script src="{{ $duralux }}/js/common-init.min.js"></script>
</body>
</html>

