@if (session('swal') || $errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const swalApi = window.Swal || window.Sweetalert2;
    if (!swalApi) {
        return;
    }

    const swal = swalApi.mixin({
        customClass: {
            popup: 'sitenor-auth-swal',
            confirmButton: 'btn sitenor-auth-submit px-4',
        },
        buttonsStyling: false,
    });

    @if (session('swal'))
    swal.fire(@json(session('swal')));
    @elseif ($errors->any())
    @php
        $firstKey = $errors->keys()[0] ?? null;
        $firstMessage = $errors->first() ?? '';

        $emailTaken = str_contains($firstMessage, 'terdaftar')
            || str_contains($firstMessage, 'sudah dipakai')
            || str_contains($firstMessage, 'already been taken');

        $title = request()->is('login')
            ? 'Gagal masuk'
            : match ($firstKey) {
                'email' => $emailTaken ? 'Email sudah dipakai' : 'Periksa email Anda',
                'cabor_id' => 'Cabor sudah punya kepala cabor',
                default => 'Periksa data Anda',
            };
    @endphp
    swal.fire({
        icon: 'error',
        title: @json($title),
        html: @json(implode('<br>', $errors->all())),
        confirmButtonText: 'Mengerti',
    });
    @endif
});
</script>
@endif
