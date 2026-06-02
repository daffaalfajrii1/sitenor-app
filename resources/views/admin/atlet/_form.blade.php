@php
    $i = $item ?? $atlet ?? null;
    $birthForAge = old('birth_date', $i?->birth_date?->format('Y-m-d'));
    $agePreviewValue = '';
    if ($birthForAge) {
        try {
            $agePreviewValue = \Illuminate\Support\Carbon::parse($birthForAge)->age.' tahun';
        } catch (\Throwable) {
            $agePreviewValue = '';
        }
    }
@endphp
<div class="row g-3">
    @if (empty($hideCaborSelect))
    <div class="col-md-6"><label class="fw-semibold">Cabor *</label><select name="cabor_id" class="form-control" required>@foreach($cabors ?? [] as $c)<option value="{{ $c->id }}" @selected(old('cabor_id', $i?->cabor_id)==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
    @endif
    <div class="col-md-6"><label class="fw-semibold">Nama *</label><input type="text" name="name" class="form-control" value="{{ old('name', $i?->name) }}" required></div>
    <div class="col-md-4">
        <label class="fw-semibold">Tanggal Lahir</label>
        <input type="date" name="birth_date" id="atlet-birth-date" class="form-control" value="{{ old('birth_date', $i?->birth_date?->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}">
    </div>
    <div class="col-md-4">
        <label class="fw-semibold">Umur</label>
        <input type="text" id="atlet-age-preview" class="form-control bg-light" readonly placeholder="Otomatis dari tanggal lahir" value="{{ $agePreviewValue }}">
        <small class="text-muted">Dihitung otomatis saat tanggal lahir diisi</small>
    </div>
    <div class="col-md-4"><label class="fw-semibold">Jenis Kelamin</label><select name="gender" class="form-control"><option value="">-</option><option value="laki-laki" @selected(old('gender', $i?->gender)=='laki-laki')>Laki-laki</option><option value="perempuan" @selected(old('gender', $i?->gender)=='perempuan')>Perempuan</option></select></div>
    <div class="col-md-4"><label class="fw-semibold">Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $i?->phone) }}"></div>
    <div class="col-md-4"><label class="fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $i?->email) }}"></div>
    <div class="col-md-4"><label class="fw-semibold">Foto</label><input type="file" name="photo" class="form-control" accept="image/*">@if($i?->photo)<img src="{{ Storage::url($i->photo) }}" class="mt-2" height="60" alt="">@endif</div>
    <div class="col-12"><label class="fw-semibold">Alamat</label><textarea name="address" class="form-control" rows="2">{{ old('address', $i?->address) }}</textarea></div>
    <div class="col-12"><label class="fw-semibold">Bio</label><textarea name="bio" class="form-control" rows="3">{{ old('bio', $i?->bio) }}</textarea></div>
    <div class="col-12"><div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $i?->is_active ?? true))><label class="form-check-label">Aktif</label></div></div>
</div>
@push('scripts')
<script>
(function () {
    const birthInput = document.getElementById('atlet-birth-date');
    const agePreview = document.getElementById('atlet-age-preview');
    if (!birthInput || !agePreview) return;

    function calcAge(isoDate) {
        const birth = new Date(isoDate + 'T12:00:00');
        const today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age >= 0 ? age : null;
    }

    function updateAgePreview() {
        const value = birthInput.value;
        if (!value) {
            agePreview.value = '';
            return;
        }
        const age = calcAge(value);
        agePreview.value = age !== null ? age + ' tahun' : '';
    }

    birthInput.addEventListener('input', updateAgePreview);
    birthInput.addEventListener('change', updateAgePreview);
    if (birthInput.value) {
        updateAgePreview();
    }
})();
</script>
@endpush
