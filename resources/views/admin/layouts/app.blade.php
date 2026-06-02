@php
    $duralux = asset('duralux/assets');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $siteSettings->app_name ?? 'Sitenor' }}</title>
    <link rel="icon" href="{{ ($siteSettings ?? null)?->tabIconUrl() ?? $duralux.'/images/favicon.ico' }}" />
    <link rel="stylesheet" href="{{ $duralux }}/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ $duralux }}/vendors/css/vendors.min.css" />
    @stack('styles')
    <link rel="stylesheet" href="{{ $duralux }}/css/theme.min.css" />
    <link rel="stylesheet" href="{{ asset('css/admin-sitenor.css') }}" />
</head>
<body class="sitenor-panel-body">
    @include('admin.layouts.partials.sidebar')

    @include('admin.layouts.partials.header')

    <main class="nxl-container sitenor-panel-container">
        <div class="nxl-content flex-grow-1">
            @include('admin.components.alert')
            @yield('content')
        </div>
        <x-sitenor-footer />
    </main>

    @include('admin.layouts.partials.scripts')
    @include('components.sitenor-swal')
    @stack('scripts')
</body>
</html>


