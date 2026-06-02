@php
    $i = $item ?? $prestasi ?? null;
    $currentRank = old('rank', $i?->rank);
    $isOtherRank = $currentRank && !\App\Models\Prestasi::isStandardRank($currentRank);
    $rankSelect = $isOtherRank ? '__other__' : $currentRank;
    $rankCustom = old('rank_custom', $isOtherRank ? $currentRank : '');
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="fw-semibold">Atlet *</label>
        <select name="atlet_id" id="prestasi-atlet-select" class="form-select" required>
            <option value="">Pilih atlet</option>
            @foreach($atlets as $a)
                <option value="{{ $a->id }}" @selected(old('atlet_id', $i?->atlet_id ?? $selectedAtletId ?? null) == $a->id)>
                    {{ $a->name }}@if(($showCaborInAtletSelect ?? true) && $a->cabor?->name) — {{ $a->cabor->name }}@endif
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Judul Prestasi *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $i?->title) }}" required placeholder="Contoh: Kejuaraan Porprov 2025">
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Nama Event</label>
        <input type="text" name="event_name" class="form-control" value="{{ old('event_name', $i?->event_name) }}" placeholder="Nama kejuaraan / event">
    </div>
    <div class="col-md-3">
        <label class="fw-semibold">Level *</label>
        <select name="level" class="form-select" required>
            @foreach($levels as $k => $v)
                <option value="{{ $k }}" @selected(old('level', $i?->level ?? 'kabupaten') == $k)>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="fw-semibold">Peringkat</label>
        <select name="rank" id="prestasi-rank-select" class="form-select">
            <option value="">— Pilih peringkat —</option>
            @foreach(\App\Models\Prestasi::rankOptions() as $value => $label)
                <option value="{{ $value }}" @selected($rankSelect === $value)>{{ $label }}</option>
            @endforeach
            <option value="__other__" @selected($rankSelect === '__other__')>Lainnya (isi manual)</option>
        </select>
    </div>
    <div class="col-md-3 {{ $rankSelect === '__other__' ? '' : 'd-none' }}" id="prestasi-rank-custom-wrap">
        <label class="fw-semibold">Peringkat lainnya</label>
        <input type="text" name="rank_custom" id="prestasi-rank-custom" class="form-control" value="{{ $rankCustom }}" placeholder="Contoh: Harapan I, Best Player">
    </div>
    <div class="col-md-3">
        <label class="fw-semibold">Tahun</label>
        <input type="number" name="year" class="form-control" value="{{ old('year', $i?->year) }}" min="1900" max="2100">
    </div>
    <div class="col-md-3">
        <label class="fw-semibold">Lokasi</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $i?->location) }}">
    </div>
    <div class="col-12">
        <label class="fw-semibold">Keterangan</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $i?->description) }}</textarea>
    </div>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var select = document.getElementById('prestasi-rank-select');
    var wrap = document.getElementById('prestasi-rank-custom-wrap');
    var custom = document.getElementById('prestasi-rank-custom');
    if (!select || !wrap) return;
    function toggle() {
        var isOther = select.value === '__other__';
        wrap.classList.toggle('d-none', !isOther);
        if (custom) custom.required = isOther;
    }
    select.addEventListener('change', toggle);
    toggle();
});
</script>
@endpush
@endonce
