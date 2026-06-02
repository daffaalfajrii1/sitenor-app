/**
 * Modal detail wasit / juri / pelatih — halaman publik.
 */
(function () {
    'use strict';

    function initPersonModal() {
        var modalEl = document.getElementById('sitenorPersonModal');
        if (!modalEl || typeof bootstrap === 'undefined') {
            return;
        }

        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        var bodyEl = document.getElementById('sitenorPersonModalBody');
        var titleEl = document.getElementById('sitenorPersonModalLabel');

        document.querySelectorAll('[data-person-modal]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var url = btn.getAttribute('data-url');
                var name = btn.getAttribute('data-name') || 'Detail';

                if (!url || !bodyEl) {
                    return;
                }

                titleEl.textContent = name;
                bodyEl.innerHTML =
                    '<div class="text-center py-5 text-muted">' +
                    '<div class="spinner-border text-danger" role="status"></div>' +
                    '<p class="mt-2 mb-0 fs-14">Memuat data...</p></div>';

                modal.show();

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'text/html',
                    },
                })
                    .then(function (res) {
                        if (!res.ok) {
                            throw new Error('Gagal memuat');
                        }
                        return res.text();
                    })
                    .then(function (html) {
                        bodyEl.innerHTML = html;
                    })
                    .catch(function () {
                        bodyEl.innerHTML =
                            '<div class="alert alert-danger mb-0">Gagal memuat detail. Silakan coba lagi.</div>';
                    });
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPersonModal);
    } else {
        initPersonModal();
    }
})();
