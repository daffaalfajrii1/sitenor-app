@php
    $cabor = auth()->user()?->cabor;
    $showProfileSetup = auth()->user()?->needsProfileCompletion();
@endphp
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <x-panel-sidebar-brand :href="cabor_route('cabor.dashboard')" />
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                @if($cabor)
                <li class="nxl-item nxl-caption"><label>{{ $cabor->name }}</label></li>
                @endif

                <li class="nxl-item nxl-caption"><label>Menu Utama</label></li>
                <li class="nxl-item {{ request()->routeIs('cabor.dashboard') ? 'active' : '' }}">
                    <a href="{{ cabor_route('cabor.dashboard') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>

                @if($showProfileSetup)
                <li class="nxl-item {{ request()->routeIs('cabor.profile.complete*') ? 'active' : '' }}">
                    <a href="{{ cabor_route('cabor.profile.complete') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user-check"></i></span>
                        <span class="nxl-mtext">Lengkapi Profil</span>
                    </a>
                </li>
                @endif

                <li class="nxl-item nxl-caption"><label>Data Cabor Saya</label></li>
                <li class="nxl-item {{ request()->routeIs('cabor.atlet.*') ? 'active' : '' }}">
                    <a href="{{ cabor_route('cabor.atlet.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user"></i></span>
                        <span class="nxl-mtext">Atlet</span>
                    </a>
                </li>
                <li class="nxl-item {{ request()->routeIs('cabor.prestasi.*') ? 'active' : '' }}">
                    <a href="{{ cabor_route('cabor.prestasi.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-award"></i></span>
                        <span class="nxl-mtext">Prestasi Atlet</span>
                    </a>
                </li>
                <li class="nxl-item {{ request()->routeIs('cabor.pelatih.*') ? 'active' : '' }}">
                    <a href="{{ cabor_route('cabor.pelatih.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-users"></i></span>
                        <span class="nxl-mtext">Pelatih</span>
                    </a>
                </li>
                <li class="nxl-item {{ request()->routeIs('cabor.wasit.*') ? 'active' : '' }}">
                    <a href="{{ cabor_route('cabor.wasit.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-shield"></i></span>
                        <span class="nxl-mtext">Wasit</span>
                    </a>
                </li>
                <li class="nxl-item {{ request()->routeIs('cabor.juri.*') ? 'active' : '' }}">
                    <a href="{{ cabor_route('cabor.juri.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-eye"></i></span>
                        <span class="nxl-mtext">Juri</span>
                    </a>
                </li>

                <li class="nxl-item nxl-caption"><label>Konten</label></li>
                <li class="nxl-item {{ request()->routeIs('cabor.artikel.*') ? 'active' : '' }}">
                    <a href="{{ cabor_route('cabor.artikel.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">Artikel</span>
                    </a>
                </li>

                <li class="nxl-item nxl-caption"><label>Akun</label></li>
                <li class="nxl-item {{ request()->routeIs('cabor.profile.edit') || request()->routeIs('cabor.profile.update') || request()->routeIs('cabor.profile.password') ? 'active' : '' }}">
                    <a href="{{ cabor_route('cabor.profile.edit') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-settings"></i></span>
                        <span class="nxl-mtext">Profil Saya</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
