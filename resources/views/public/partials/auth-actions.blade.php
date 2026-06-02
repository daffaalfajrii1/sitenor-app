@php
    $stacked = $stacked ?? false;
    $mobileMenu = $mobileMenu ?? false;
    $btnSize = $mobileMenu ? '' : 'btn-sm';
    $btnClass = $stacked ? 'w-100' : '';
@endphp
@auth
    <a href="{{ auth_home_redirect() }}" class="btn {{ $btnSize }} btn-danger fw-semibold text-white {{ $btnClass }}">
        <i class="feather-grid me-1"></i> Panel Saya
    </a>
    <form method="POST" action="{{ route('logout') }}" class="{{ $stacked ? 'w-100' : 'd-inline' }}">
        @csrf
        <button type="submit" class="btn {{ $btnSize }} btn-outline-secondary fw-semibold {{ $btnClass }}">
            Keluar
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="btn {{ $btnSize }} btn-outline-danger fw-semibold {{ $btnClass }}">Login</a>
    @if (Route::has('register'))
        <a href="{{ route('register') }}" class="btn {{ $btnSize }} btn-danger fw-semibold text-white {{ $btnClass }}">
            Daftar Kepala Cabor
        </a>
    @endif
@endauth
