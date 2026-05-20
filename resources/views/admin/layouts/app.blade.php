@php
    $duralux = asset('duralux/assets');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Sitenor Rejang Lebong</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ $duralux }}/images/favicon.ico" />
    <link rel="stylesheet" href="{{ $duralux }}/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ $duralux }}/vendors/css/vendors.min.css" />
    @stack('styles')
    <link rel="stylesheet" href="{{ $duralux }}/css/theme.min.css" />
    <link rel="stylesheet" href="{{ asset('css/admin-sitenor.css') }}" />
</head>
<body>
    @include('admin.layouts.partials.sidebar')

    @include('admin.layouts.partials.header')

    <main class="nxl-container">
        <div class="nxl-content">
            @include('admin.components.alert')
            @yield('content')
        </div>
    </main>

    @include('admin.layouts.partials.scripts')
    @stack('scripts')
</body>
</html>


