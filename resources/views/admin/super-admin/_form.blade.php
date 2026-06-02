@php $u = $superAdmin ?? null; @endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="fw-semibold">Nama *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $u?->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Email *</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $u?->email) }}" required>
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Password {{ $u ? '(kosongkan jika tidak diubah)' : '*' }}</label>
        <input type="password" name="password" class="form-control" {{ $u ? '' : 'required' }}>
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" {{ $u ? '' : 'required' }}>
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Foto Profil</label>
        <input type="file" name="avatar" class="form-control" accept="image/*">
        @if ($u?->avatarUrl())
            <img src="{{ $u->avatarUrl() }}" class="mt-2 rounded" height="64" alt="">
            <div class="form-check mt-2">
                <input type="checkbox" name="remove_avatar" value="1" class="form-check-input" id="remove_avatar_sa">
                <label for="remove_avatar_sa" class="form-check-label">Hapus foto</label>
            </div>
        @endif
    </div>
</div>
