<header class="sitenor-public-header bg-white">
    <nav class="navbar navbar-expand-lg sitenor-public-navbar py-0">
        <div class="container">
            <x-sitenor-brand
                :href="route('public.home')"
                :show-name="true"
                variant="public"
                class="sitenor-public-navbar__brand navbar-brand me-0"
            />

            {{-- Mobile: buka offcanvas --}}
            <button
                id="sitenorPublicNavToggle"
                class="navbar-toggler sitenor-public-navbar__toggler d-lg-none ms-auto"
                type="button"
                aria-controls="sitenorPublicNav"
                aria-expanded="false"
                aria-label="Buka menu"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Desktop: menu horizontal (Bootstrap navbar-expand-lg) --}}
            <div class="collapse navbar-collapse sitenor-public-navbar__collapse" id="sitenorPublicNavbarDesktop">
                <div class="sitenor-public-navbar__menu mx-lg-auto">
                    @include('public.partials.nav-links')
                </div>
                <div class="sitenor-public-navbar__actions">
                    @include('public.partials.auth-actions')
                </div>
            </div>
        </div>
    </nav>
</header>
