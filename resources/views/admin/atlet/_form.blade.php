@php $i = $item ?? $atlet ?? null; @endphp
<div class="row g-3">
    <div class="col-md-6"><label class="fw-semibold">Cabor *</label><select name="cabor_id" class="form-control" required>@foreach($cabors as $c)<option value="{{ $c->id }}" @selected(old('cabor_id', $i?->cabor_id)==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="fw-semibold">Nama *</label><input type="text" name="name" class="form-control" value="{{ old('name', $i?->name) }}" required></div>
    <div class="col-md-4"><label class="fw-semibold">NIK</label><input type="text" name="nik" class="form-control" value="{{ old('nik', $i?->nik) }}"></div>
    <div class="col-md-4"><label class="fw-semibold">Tanggal Lahir</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $i?->birth_date?->format('Y-m-d')) }}"></div>
    <div class="col-md-4"><label class="fw-semibold">Jenis Kelamin</label><select name="gender" class="form-control"><option value="">-</option><option value="laki-laki" @selected(old('gender', $i?->gender)=='laki-laki')>Laki-laki</option><option value="perempuan" @selected(old('gender', $i?->gender)=='perempuan')>Perempuan</option></select></div>
    <div class="col-md-4"><label class="fw-semibold">Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $i?->phone) }}"></div>
    <div class="col-md-4"><label class="fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $i?->email) }}"></div>
    <div class="col-md-4"><label class="fw-semibold">Foto</label><input type="file" name="photo" class="form-control" accept="image/*">@if($i?->photo)<img src="{{ Storage::url($i->photo) }}" class="mt-2" height="60" alt="">@endif</div>
    <div class="col-12"><label class="fw-semibold">Alamat</label><textarea name="address" class="form-control" rows="2">{{ old('address', $i?->address) }}</textarea></div>
    <div class="col-12"><label class="fw-semibold">Bio</label><textarea name="bio" class="form-control" rows="3">{{ old('bio', $i?->bio) }}</textarea></div>
    <div class="col-12"><div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $i?->is_active ?? true))><label class="form-check-label">Aktif</label></div></div>
</div>
