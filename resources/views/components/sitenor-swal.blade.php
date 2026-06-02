@if (session('swal') || $errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swal === 'undefined') {
        return;
    }

    const swal = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary m-1',
            cancelButton: 'btn btn-light m-1',
        },
        buttonsStyling: false,
    });

    @if (session('swal'))
    swal.fire(@json(session('swal')));
    @endif

    @if ($errors->any() && !session('swal'))
    swal.fire({
        icon: 'error',
        title: 'Periksa data Anda',
        html: @json(implode('<br>', $errors->all())),
        confirmButtonText: 'OK',
    });
    @endif
});
</script>
@endif
