@props([
    'href' => '#',
])

@php
    $duralux = asset('duralux/assets');
    $site = $siteSettings ?? null;
    $appName = $site?->app_name ?? 'Sitenor';
    $logoFull = $site?->logoUrl() ?? $duralux.'/images/logo-full.png';
    $logoAbbr = $site?->logoUrl() ?? $duralux.'/images/logo-abbr.png';
@endphp

<a href="{{ $href }}" class="b-brand sitenor-panel-brand text-decoration-none">
    <span class="sitenor-panel-brand__logo-wrap">
        <img src="{{ $logoFull }}" alt="{{ $appName }}" class="logo logo-lg sitenor-panel-brand__logo" />
        <img src="{{ $logoAbbr }}" alt="{{ $appName }}" class="logo logo-sm sitenor-panel-brand__logo" />
    </span>
    <span class="sitenor-panel-brand__name" title="{{ $appName }}">{{ $appName }}</span>
</a>
