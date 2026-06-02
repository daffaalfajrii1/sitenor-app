@php $u = $user ?? auth()->user(); @endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="fw-semibold">Nama *</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $u->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Email *</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $u->email) }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @if($u->cabor)
    <div class="col-12">
        <label class="fw-semibold">Cabang olahraga</label>
        <input type="text" class="form-control" value="{{ $u->cabor->name }}" disabled readonly>
    </div>
    @endif
    <div class="col-md-6">
        <label class="fw-semibold">Telepon {{ ($requirePhone ?? false) ? '*' : '' }}</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $u->phone) }}" {{ ($requirePhone ?? false) ? 'required' : '' }}>
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Foto profil {{ ($requireAvatar ?? false) ? '*' : '' }}</label>
        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*" {{ ($requireAvatar ?? false) && ! $u->avatar ? 'required' : '' }}>
        @if($u->avatarUrl())
            <img src="{{ $u->avatarUrl() }}" class="mt-2 rounded" height="72" alt="Foto profil">
            @if(!($requireAvatar ?? false))
            <div class="form-check mt-2">
                <input type="checkbox" name="remove_avatar" value="1" class="form-check-input" id="remove_avatar">
                <label for="remove_avatar" class="form-check-label">Hapus foto</label>
            </div>
            @endif
        @endif
        @error('avatar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Instagram</label>
        <input type="text" name="instagram" class="form-control" value="{{ old('instagram', $u->instagram) }}" placeholder="https://instagram.com/...">
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Facebook</label>
        <input type="text" name="facebook" class="form-control" value="{{ old('facebook', $u->facebook) }}">
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">YouTube</label>
        <input type="text" name="youtube" class="form-control" value="{{ old('youtube', $u->youtube) }}">
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">TikTok</label>
        <input type="text" name="tiktok" class="form-control" value="{{ old('tiktok', $u->tiktok) }}">
    </div>
    <div class="col-12">
        <label class="fw-semibold">Bio / keterangan</label>
        <textarea name="bio" class="form-control" rows="3" placeholder="Bio singkat (opsional)">{{ old('bio', $u->bio) }}</textarea>
    </div>
</div>
