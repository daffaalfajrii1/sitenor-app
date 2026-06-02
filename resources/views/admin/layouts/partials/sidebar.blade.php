@php $duralux = asset('duralux/assets'); @endphp
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <x-panel-sidebar-brand :href="route('admin.dashboard')" />
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

                <li class="nxl-item {{ request()->routeIs('admin.kepala-cabor.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.kepala-cabor.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user-check"></i></span>
                        <span class="nxl-mtext">Kepala Cabor</span>
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

                <li class="nxl-item {{ request()->routeIs('admin.artikel.index', 'admin.artikel.create', 'admin.artikel.edit') ? 'active' : '' }}">
                    <a href="{{ route('admin.artikel.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">Artikel</span>
                    </a>
                </li>

                <li class="nxl-item {{ request()->routeIs('admin.artikel.released') ? 'active' : '' }}">
                    <a href="{{ route('admin.artikel.released') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-send"></i></span>
                        <span class="nxl-mtext">Artikel Rilis</span>
                    </a>
                </li>

                <li class="nxl-item {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pengumuman.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-download"></i></span>
                        <span class="nxl-mtext">Pengumuman</span>
                    </a>
                </li>

                <li class="nxl-item nxl-caption"><label>Sistem</label></li>

                <li class="nxl-item {{ request()->routeIs('admin.super-admin.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.super-admin.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-shield"></i></span>
                        <span class="nxl-mtext">Super Admin</span>
                    </a>
                </li>

                <li class="nxl-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.edit') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-settings"></i></span>
                        <span class="nxl-mtext">Pengaturan Website</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>


