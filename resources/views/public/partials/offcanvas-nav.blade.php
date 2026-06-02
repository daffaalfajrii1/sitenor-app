<div
    class="offcanvas offcanvas-end sitenor-public-offcanvas"
    tabindex="-1"
    id="sitenorPublicNav"
    aria-labelledby="sitenorPublicNavLabel"
    data-bs-scroll="false"
    data-bs-backdrop="true"
>
    <div class="offcanvas-header sitenor-public-offcanvas__header">
        <p class="sitenor-public-offcanvas__title mb-0" id="sitenorPublicNavLabel">Menu</p>
        <button
            type="button"
            class="btn-close sitenor-public-offcanvas__close"
            data-bs-dismiss="offcanvas"
            aria-label="Tutup menu"
            id="sitenorPublicNavClose"
        ></button>
    </div>
    <div class="offcanvas-body sitenor-public-offcanvas__body d-flex flex-column">
        @include('public.partials.nav-links', ['mobile' => true])
        <div class="sitenor-public-offcanvas__actions">
            @include('public.partials.auth-actions', ['stacked' => true, 'mobileMenu' => true])
        </div>
    </div>
</div>
