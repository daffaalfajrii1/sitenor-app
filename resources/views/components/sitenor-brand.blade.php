@props([
    'href' => null,
    'showName' => true,
    'size' => 'md',
    'variant' => 'default',
])

@php
    $site = $siteSettings ?? null;
    $appName = $site->app_name ?? 'Sitenor';
    $logoUrl = $site?->logoUrl();
    $imgHeight = $size === 'sm' ? '28px' : '36px';
    $tag = $href ? 'a' : 'div';
    $brandClass = 'sitenor-brand d-flex align-items-center gap-2 text-decoration-none';
    if ($variant === 'public') {
        $brandClass .= ' sitenor-brand--public';
    }
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => $brandClass]) }}
>
    @if ($logoUrl)
        <img
            src="{{ $logoUrl }}"
            alt="{{ $appName }}"
            class="sitenor-brand-logo"
            style="max-height: {{ $imgHeight }}; width: auto; object-fit: contain;"
        />
    @endif
    @if ($showName)
        <span class="sitenor-brand-name">{{ $appName }}</span>
    @endif
</{{ $tag }}>
