@php $duralux = asset('duralux/assets'); @endphp
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('admin.dashboard') }}" class="b-brand">
                <img src="{{ $duralux }}/images/logo-full.png" alt="Sitenor" class="logo logo-lg" />
                <img src="{{ $duralux }}/images/logo-abbr.png" alt="Sitenor" class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption"><label>Menu Utama</label></li>

                <li class="nxl-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>

                <li class="nxl-item nxl-caption"><label>Data Olahraga</label></li>

                <li class="nxl-item {{ request()->routeIs('admin.cabor.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.cabor.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-flag"></i></span>
                        <span class="nxl-mtext">Cabang Olahraga</span>
                    </a>
                </li>

                <li class="nxl-item {{ request()->routeIs('admin.atlet.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.atlet.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user"></i></span>
                        <span class="nxl-mtext">Atlet</span>
                    </a>
                </li>

                <li class="nxl-item {{ request()->routeIs('admin.prestasi.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.prestasi.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-award"></i></span>
                        <span class="nxl-mtext">Prestasi Atlet</span>
                    </a>
                </li>

                <li class="nxl-item {{ request()->routeIs('admin.pelatih.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pelatih.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-users"></i></span>
                        <span class="nxl-mtext">Pelatih</span>
                    </a>
                </li>

                <li class="nxl-item {{ request()->routeIs('admin.wasit.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.wasit.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-shield"></i></span>
                        <span class="nxl-mtext">Wasit</span>
                    </a>
                </li>

                <li class="nxl-item {{ request()->routeIs('admin.juri.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.juri.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-eye"></i></span>
                        <span class="nxl-mtext">Juri</span>
                    </a>
                </li>

                <li class="nxl-item nxl-caption"><label>Konten</label></li>

                <li class="nxl-item {{ request()->routeIs('admin.artikel.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.artikel.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">Artikel</span>
                    </a>
                </li>

                <li class="nxl-item {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pengumuman.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-download"></i></span>
                        <span class="nxl-mtext">Pengumuman</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


