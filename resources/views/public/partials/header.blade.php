<header class="sitenor-public-header bg-white">
    <nav class="navbar navbar-expand-lg sitenor-public-navbar py-0">
        <div class="container sitenor-public-navbar__inner">
            <x-sitenor-brand
                :href="route('public.home')"
                :show-name="true"
                variant="public"
                class="sitenor-public-navbar__brand navbar-brand me-0"
            />

            {{-- Desktop: menu horizontal + tombol auth --}}
            <div class="sitenor-public-navbar__collapse" id="sitenorPublicNavbarDesktop">
                <div class="sitenor-public-navbar__menu mx-lg-auto">
                    @include('public.partials.nav-links')
                </div>
                <div class="sitenor-public-navbar__actions">
                    @include('public.partials.auth-actions')
                </div>
            </div>

            {{-- Mobile: tombol menu (hanya tampil di layar kecil) --}}
            <button
                type="button"
                id="sitenorPublicNavToggle"
                class="sitenor-mobile-menu-btn d-lg-none"
                data-bs-toggle="offcanvas"
                data-bs-target="#sitenorPublicNav"
                aria-controls="sitenorPublicNav"
                aria-expanded="false"
                aria-label="Buka menu navigasi"
            >
                <span class="sitenor-hamburger" aria-hidden="true">
                    <span class="sitenor-hamburger__line"></span>
                    <span class="sitenor-hamburger__line"></span>
                    <span class="sitenor-hamburger__line"></span>
                </span>
            </button>
        </div>
    </nav>
</header>
