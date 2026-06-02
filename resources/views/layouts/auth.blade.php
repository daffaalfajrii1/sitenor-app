@php
    $duralux = asset('duralux/assets');
    $site = $siteSettings ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — {{ $site->app_name ?? 'Sitenor' }}</title>
    <link rel="icon" href="{{ $site?->tabIconUrl() ?? $duralux.'/images/favicon.ico' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $duralux }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ $duralux }}/vendors/css/vendors.min.css">
    <link rel="stylesheet" href="{{ $duralux }}/css/theme.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-sitenor.css') }}">
    @stack('styles')
</head>
<body class="sitenor-auth-body d-flex flex-column min-vh-100 p-0">
    <div class="sitenor-auth-scene" aria-hidden="true">
        <div class="sitenor-auth-scene__mesh"></div>
        <div class="sitenor-auth-scene__orb sitenor-auth-scene__orb--1"></div>
        <div class="sitenor-auth-scene__orb sitenor-auth-scene__orb--2"></div>
        <div class="sitenor-auth-scene__orb sitenor-auth-scene__orb--3"></div>
        <svg class="sitenor-auth-wave sitenor-auth-wave--1" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="rgba(255,255,255,0.12)" d="M0,192L60,197.3C120,203,240,213,360,208C480,203,600,181,720,181.3C840,181,960,203,1080,213.3C1200,224,1320,224,1380,224L1440,224L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path>
        </svg>
        <svg class="sitenor-auth-wave sitenor-auth-wave--2" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="rgba(255,255,255,0.06)" d="M0,256L48,245.3C96,235,192,213,288,202.7C384,192,480,192,576,208C672,224,768,256,864,261.3C960,267,1056,245,1152,234.7C1248,224,1344,224,1392,224L1440,224L1440,320L1392,320C1344,320,1200,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <main class="sitenor-auth-main flex-grow-1 d-flex align-items-center @yield('auth-main-class')">
        @yield('content')
    </main>

    <x-sitenor-footer variant="auth" />
    <script src="{{ $duralux }}/vendors/js/vendors.min.js"></script>
    <script>window.Swal = window.Swal || window.Sweetalert2;</script>
    @include('components.auth-swal')
    @stack('scripts')
</body>
</html>
