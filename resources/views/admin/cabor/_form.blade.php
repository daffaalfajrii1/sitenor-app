@php
    $item = $cabor ?? null;
    $isEdit = (bool) $item;
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label for="kode" class="fw-semibold">Kode Unik *</label>
        <input
            type="text"
            id="kode"
            name="kode"
            class="form-control text-uppercase @error('kode') is-invalid @enderror"
            value="{{ old('kode', $item?->kode ?? ($suggestedKode ?? '')) }}"
            placeholder="CAB-2026-001"
            required
            @if($isEdit) readonly @endif
        >
        <div class="form-text">
            @if ($isEdit)
                Kode tidak dapat diubah setelah dibuat.
            @else
                ID unik cabang olahraga. Format disarankan: CAB-TAHUN-001
            @endif
        </div>
        @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
        <label for="name" class="fw-semibold">Nama Cabang Olahraga *</label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $item?->name) }}"
            placeholder="Contoh: Sepak Bola"
            required
        >
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $item?->is_active ?? true))>
            <label for="is_active" class="form-check-label">Aktif</label>
        </div>
    </div>

    <div class="col-12">
        <label for="description" class="fw-semibold">Deskripsi</label>
        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Keterangan singkat cabang olahraga">{{ old('description', $item?->description) }}</textarea>
    </div>

    <div class="col-md-6">
        <label for="logo" class="fw-semibold">Logo <span class="text-muted fw-normal">(opsional)</span></label>
        <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
        <div class="form-text">Format: JPG, PNG. Maks. 2 MB.</div>
        @if ($item?->logo)
            <div class="mt-2">
                <img src="{{ Storage::url($item->logo) }}" height="64" alt="Logo {{ $item->name }}" class="rounded border">
            </div>
        @endif
    </div>
</div>
