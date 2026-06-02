@php
    $duralux = asset('duralux/assets');
    $site = $siteSettings ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') — {{ $site->app_name ?? 'Sitenor' }}</title>
    <link rel="icon" href="{{ $site?->tabIconUrl() ?? $duralux.'/images/favicon.ico' }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ $duralux }}/vendors/css/vendors.min.css" />
    <link rel="stylesheet" href="{{ asset('css/public-sitenor.css') }}?v={{ filemtime(public_path('css/public-sitenor.css')) }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="sitenor-public-body d-flex flex-column min-vh-100">
    @include('public.partials.header')

    <main class="sitenor-public-main flex-grow-1">
        @yield('content')
    </main>

    <x-sitenor-footer variant="public" />

    @include('public.partials.offcanvas-nav')

    <button type="button" class="sitenor-scroll-top" id="scrollTopBtn" aria-label="Ke atas">
        <i class="feather-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/public-nav.js') }}?v={{ filemtime(public_path('js/public-nav.js')) }}"></script>
    <script>
        document.getElementById('scrollTopBtn')?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    </script>
    @stack('scripts')
</body>
</html>
