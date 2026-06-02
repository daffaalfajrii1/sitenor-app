@php
    $mobile = $mobile ?? false;
    $dataActive = request()->routeIs('public.atlet.*', 'public.pelatih.*', 'public.wasit-juri.*', 'public.prestasi.*');
    $navClass = $mobile ? 'sitenor-public-nav sitenor-public-nav--mobile' : 'sitenor-public-nav';
    $navTag = $mobile ? 'nav' : 'div';
@endphp
<{{ $navTag }} class="{{ $navClass }}">
    <a href="{{ route('public.home') }}" class="sitenor-public-nav__link {{ request()->routeIs('public.home') ? 'active' : '' }}">
        <i class="bi bi-house-door"></i>
        <span>Beranda</span>
    </a>
    <a href="{{ route('public.statistik') }}" class="sitenor-public-nav__link {{ request()->routeIs('public.statistik') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line"></i>
        <span>Statistik</span>
    </a>

    @if ($mobile)
        <span class="sitenor-public-nav__group-label">Data</span>
        <a href="{{ route('public.atlet.index') }}" class="sitenor-public-nav__link {{ request()->routeIs('public.atlet.*') ? 'active' : '' }}">Data Atlet</a>
        <a href="{{ route('public.pelatih.index') }}" class="sitenor-public-nav__link {{ request()->routeIs('public.pelatih.*') ? 'active' : '' }}">Data Pelatih</a>
        <a href="{{ route('public.wasit-juri.index') }}" class="sitenor-public-nav__link {{ request()->routeIs('public.wasit-juri.*') ? 'active' : '' }}">Data Wasit & Juri</a>
        <a href="{{ route('public.prestasi.index') }}" class="sitenor-public-nav__link {{ request()->routeIs('public.prestasi.*') ? 'active' : '' }}">Prestasi Atlet</a>
    @else
        <div class="dropdown">
            <a
                href="#"
                class="sitenor-public-nav__link dropdown-toggle {{ $dataActive ? 'active' : '' }}"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <i class="bi bi-database"></i>
                <span>Data</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('public.atlet.index') }}">Data Atlet</a></li>
                <li><a class="dropdown-item" href="{{ route('public.pelatih.index') }}">Data Pelatih</a></li>
                <li><a class="dropdown-item" href="{{ route('public.wasit-juri.index') }}">Data Wasit & Juri</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('public.prestasi.index') }}">Prestasi Atlet</a></li>
            </ul>
        </div>
    @endif

    <a href="{{ route('public.artikel.index') }}" class="sitenor-public-nav__link {{ request()->routeIs('public.artikel.*') ? 'active' : '' }}">
        <i class="bi bi-file-text"></i>
        <span>Artikel</span>
    </a>
    <a href="{{ route('public.unduh.index') }}" class="sitenor-public-nav__link {{ request()->routeIs('public.unduh.*') ? 'active' : '' }}">
        <i class="bi bi-download"></i>
        <span>Unduh</span>
    </a>
</{{ $navTag }}>
