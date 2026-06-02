/**
 * Menu mobile halaman publik (Bootstrap offcanvas).
 */
(function () {
    'use strict';

    var BODY_OPEN_CLASS = 'sitenor-public-nav-open';

    function initPublicNav() {
        var offcanvasEl = document.getElementById('sitenorPublicNav');
        var toggleBtn = document.getElementById('sitenorPublicNavToggle');

        if (!offcanvasEl || !toggleBtn) {
            return;
        }

        if (typeof bootstrap === 'undefined' || !bootstrap.Offcanvas) {
            return;
        }

        var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);

        offcanvasEl.addEventListener('show.bs.offcanvas', function () {
            document.body.classList.add(BODY_OPEN_CLASS);
            toggleBtn.setAttribute('aria-expanded', 'true');
            toggleBtn.classList.add('is-active');
        });

        offcanvasEl.addEventListener('hidden.bs.offcanvas', function () {
            document.body.classList.remove(BODY_OPEN_CLASS);
            toggleBtn.setAttribute('aria-expanded', 'false');
            toggleBtn.classList.remove('is-active');
        });

        offcanvasEl.querySelectorAll('.sitenor-public-nav__link[href]').forEach(function (link) {
            var href = link.getAttribute('href');

            if (!href || href === '#') {
                return;
            }

            link.addEventListener('click', function () {
                if (offcanvasEl.classList.contains('show')) {
                    offcanvas.hide();
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPublicNav);
    } else {
        initPublicNav();
    }
})();
