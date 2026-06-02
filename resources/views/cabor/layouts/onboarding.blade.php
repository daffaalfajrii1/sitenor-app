@php $duralux = asset('duralux/assets'); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lengkapi Profil') — {{ $siteSettings->app_name ?? 'Sitenor' }}</title>
    <link rel="icon" href="{{ ($siteSettings ?? null)?->tabIconUrl() ?? $duralux.'/images/favicon.ico' }}" />
    <link rel="stylesheet" href="{{ $duralux }}/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ $duralux }}/vendors/css/vendors.min.css" />
    <link rel="stylesheet" href="{{ $duralux }}/css/theme.min.css" />
    <link rel="stylesheet" href="{{ asset('css/admin-sitenor.css') }}" />
</head>
<body class="sitenor-onboarding-body">
    <header class="sitenor-onboarding-header">
        <x-sitenor-brand :href="cabor_route('cabor.profile.complete')" :show-name="true" />
    </header>

    <main class="sitenor-onboarding-main container py-4">
        @include('admin.components.alert')
        @yield('content')
    </main>

    <script src="{{ $duralux }}/vendors/js/vendors.min.js"></script>
    <script src="{{ $duralux }}/js/common-init.min.js"></script>
    @include('components.sitenor-swal')
    @stack('scripts')
</body>
</html>
