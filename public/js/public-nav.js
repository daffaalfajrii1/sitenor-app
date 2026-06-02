/**
 * Menu mobile halaman publik (offcanvas).
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

        function openMenu() {
            offcanvasEl.classList.add('show');
            document.body.classList.add(BODY_OPEN_CLASS);
            toggleBtn.setAttribute('aria-expanded', 'true');

            var backdrop = document.createElement('div');
            backdrop.className = 'offcanvas-backdrop fade show sitenor-offcanvas-backdrop';
            backdrop.id = 'sitenorOffcanvasBackdrop';
            document.body.appendChild(backdrop);

            backdrop.addEventListener('click', closeMenu);
        }

        function closeMenu() {
            offcanvasEl.classList.remove('show');
            document.body.classList.remove(BODY_OPEN_CLASS);
            toggleBtn.setAttribute('aria-expanded', 'false');

            document.getElementById('sitenorOffcanvasBackdrop')?.remove();
        }

        function toggleMenu() {
            if (offcanvasEl.classList.contains('show')) {
                closeMenu();
            } else {
                openMenu();
            }
        }

        if (typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
            var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);

            toggleBtn.addEventListener('click', function () {
                offcanvas.toggle();
            });

            offcanvasEl.addEventListener('show.bs.offcanvas', function () {
                document.body.classList.add(BODY_OPEN_CLASS);
                toggleBtn.setAttribute('aria-expanded', 'true');
            });

            offcanvasEl.addEventListener('hidden.bs.offcanvas', function () {
                document.body.classList.remove(BODY_OPEN_CLASS);
                toggleBtn.setAttribute('aria-expanded', 'false');
            });
        } else {
            toggleBtn.addEventListener('click', toggleMenu);

            var closeBtn = document.getElementById('sitenorPublicNavClose');
            if (closeBtn) {
                closeBtn.addEventListener('click', closeMenu);
            }
        }

        offcanvasEl.querySelectorAll('.sitenor-public-nav__link[href]').forEach(function (link) {
            if (link.getAttribute('href') === '#') {
                return;
            }

            link.addEventListener('click', function () {
                if (offcanvasEl.classList.contains('show')) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
                        bootstrap.Offcanvas.getInstance(offcanvasEl)?.hide();
                    } else {
                        closeMenu();
                    }
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
