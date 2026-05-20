@php $i = $item ?? null; @endphp
<div class="row g-3">
<div class="col-md-6"><label class="fw-semibold">Cabor *</label><select name="cabor_id" class="form-control" required>@foreach($cabors as $c)<option value="{{ $c->id }}" @selected(old('cabor_id', $i?->cabor_id)==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="fw-semibold">Nama *</label><input type="text" name="name" class="form-control" value="{{ old('name', $i?->name) }}" required></div>
<div class="col-md-4"><label class="fw-semibold">No. Lisensi</label><input type="text" name="license_number" class="form-control" value="{{ old('license_number', $i?->license_number) }}"></div>
<div class="col-md-4"><label class="fw-semibold">Level</label><input type="text" name="level" class="form-control" value="{{ old('level', $i?->level) }}"></div>
<div class="col-md-4"><label class="fw-semibold">Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $i?->phone) }}"></div>
<div class="col-md-4"><label class="fw-semibold">Foto</label><input type="file" name="photo" class="form-control" accept="image/*">@if($i?->photo)<img src="{{ Storage::url($i->photo) }}" class="mt-2" height="60">@endif</div>
<div class="col-md-4"><label class="fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $i?->email) }}"></div><div class="col-12"><label class="fw-semibold">Bio</label><textarea name="bio" class="form-control" rows="3">{{ old('bio', $i?->bio) }}</textarea></div>
<div class="col-12"><div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $i?->is_active ?? true))><label class="form-check-label">Aktif</label></div></div>
</div>
