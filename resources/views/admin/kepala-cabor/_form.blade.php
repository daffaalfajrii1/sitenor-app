@php $u = $kepalaCabor ?? null; @endphp
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
        <label class="fw-semibold">Cabang Olahraga *</label>
        <select name="cabor_id" class="form-control" required>
            <option value="">Pilih cabor</option>
            @foreach($cabors as $c)
                <option value="{{ $c->id }}" @selected(old('cabor_id', $u?->cabor_id) == $c->id)>{{ $c->name }} @if($c->kode)({{ $c->kode }})@endif</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Telepon</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $u?->phone) }}">
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
        @if($u?->avatarUrl())
            <img src="{{ $u->avatarUrl() }}" class="mt-2 rounded" height="64" alt="">
            <div class="form-check mt-2">
                <input type="checkbox" name="remove_avatar" value="1" class="form-check-input" id="remove_avatar">
                <label for="remove_avatar" class="form-check-label">Hapus foto</label>
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Instagram</label>
        <input type="text" name="instagram" class="form-control" value="{{ old('instagram', $u?->instagram) }}">
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Facebook</label>
        <input type="text" name="facebook" class="form-control" value="{{ old('facebook', $u?->facebook) }}">
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">YouTube</label>
        <input type="text" name="youtube" class="form-control" value="{{ old('youtube', $u?->youtube) }}">
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">TikTok</label>
        <input type="text" name="tiktok" class="form-control" value="{{ old('tiktok', $u?->tiktok) }}">
    </div>
    <div class="col-12">
        <label class="fw-semibold">Bio / Keterangan</label>
        <textarea name="bio" class="form-control" rows="3">{{ old('bio', $u?->bio) }}</textarea>
    </div>
</div>
