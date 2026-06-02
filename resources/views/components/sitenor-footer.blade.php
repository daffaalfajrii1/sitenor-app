@props([
    'variant' => 'panel',
])

@php
    $site = $siteSettings ?? null;
@endphp

@if ($variant === 'public')
    <footer class="sitenor-public-footer">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-5 col-lg-4">
                    <x-sitenor-brand :href="route('public.home')" :show-name="true" variant="public" class="mb-3" />
                    @if ($site?->tagline)
                        <p class="sitenor-public-footer__tagline mb-0">{{ $site->tagline }}</p>
                    @endif
                </div>

                @if ($site && ($site->address || $site->email || $site->phone))
                    <div class="col-md-4 col-lg-4">
                        <h6 class="sitenor-public-footer__heading">Kontak</h6>
                        <ul class="sitenor-public-footer__list mb-0">
                            @if ($site->address)
                                <li>
                                    <i class="feather-map-pin"></i>
                                    <span>{{ $site->address }}</span>
                                </li>
                            @endif
                            @if ($site->email)
                                <li>
                                    <i class="feather-mail"></i>
                                    <a href="mailto:{{ $site->email }}">{{ $site->email }}</a>
                                </li>
                            @endif
                            @if ($site->phone)
                                <li>
                                    <i class="feather-phone"></i>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $site->phone) }}">{{ $site->phone }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif

                @if ($site && ($site->instagram || $site->facebook || $site->youtube))
                    <div class="col-md-3 col-lg-4">
                        <h6 class="sitenor-public-footer__heading">Media Sosial</h6>
                        <div class="sitenor-public-footer__social">
                            @if ($site->instagram)
                                <a href="{{ $site->socialUrl($site->instagram) }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                    <i class="feather-instagram"></i>
                                </a>
                            @endif
                            @if ($site->facebook)
                                <a href="{{ $site->socialUrl($site->facebook) }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                    <i class="feather-facebook"></i>
                                </a>
                            @endif
                            @if ($site->youtube)
                                <a href="{{ $site->socialUrl($site->youtube) }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                                    <i class="feather-youtube"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="sitenor-public-footer__bottom">
            <div class="container py-3 text-center">
                <p class="sitenor-public-footer__copyright mb-1">{{ $site?->copyrightLine() ?? '© '.date('Y').' Sitenor' }}</p>
                <p class="sitenor-public-footer__partner mb-0">{{ $site?->partnerLine() }}</p>
            </div>
        </div>
    </footer>
@else
    <footer @class([
        'sitenor-site-footer',
        'sitenor-site-footer--auth' => $variant === 'auth',
        'sitenor-site-footer--public' => $variant === 'public',
    ])>
        <p class="sitenor-site-footer__copyright mb-1">
            {{ $site?->copyrightLine() ?? '© '.date('Y').' Sitenor' }}
        </p>
        <p class="sitenor-site-footer__partner mb-0">
            {{ $site?->partnerLine() }}
        </p>
    </footer>
@endif
