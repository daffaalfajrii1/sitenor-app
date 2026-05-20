@php $user = auth()->user(); @endphp
<header class="nxl-header">
    <div class="header-wrapper">
        <div class="header-left d-flex align-items-center gap-4">
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button"><i class="feather-align-left"></i></a>
                <a href="javascript:void(0);" id="menu-expend-button" style="display: none"><i class="feather-arrow-right"></i></a>
            </div>
        </div>

        <div class="header-right ms-auto sitenor-header-profile">
            <div class="sitenor-profile-wrap">
                <a href="javascript:void(0);" class="d-flex align-items-center gap-2 text-decoration-none sitenor-profile-toggle">
                    <span class="d-none d-md-inline text-muted fs-12 text-truncate sitenor-profile-name">{{ $user->name }}</span>
                    @if ($user->avatarUrl())
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="sitenor-profile-avatar rounded-circle flex-shrink-0">
                    @else
                        <span class="avatar-text avatar-md bg-primary text-white flex-shrink-0 sitenor-profile-avatar-fallback">
                            <i class="feather-user"></i>
                        </span>
                    @endif
                </a>

                <div class="sitenor-profile-menu shadow">
                    <div class="sitenor-profile-menu-header">
                        <div class="fw-bold text-truncate">{{ $user->name }}</div>
                        <div class="fs-12 text-muted text-truncate">{{ $user->email }}</div>
                    </div>
                    <a href="{{ route('admin.profile.edit') }}" class="sitenor-profile-menu-item">
                        <i class="feather-edit-3 me-2"></i> Edit Profil
                    </a>
                    <div class="sitenor-profile-menu-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sitenor-profile-menu-item sitenor-profile-logout w-100 border-0 bg-transparent text-start">
                            <i class="feather-log-out me-2"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
