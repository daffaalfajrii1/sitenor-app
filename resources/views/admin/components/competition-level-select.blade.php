@php
    $fieldName = $name ?? 'level';
    $fieldId = $id ?? $fieldName;
    $current = old($fieldName, $selected ?? null);
    $levels = \App\Models\Prestasi::levelOptions();
    if ($current && ! isset($levels[$current])) {
        $normalized = \App\Models\Prestasi::normalizeLevel($current);
        if ($normalized && isset($levels[$normalized])) {
            $current = $normalized;
        }
    }
@endphp
<label class="fw-semibold" for="{{ $fieldId }}">Level</label>
<select name="{{ $fieldName }}" id="{{ $fieldId }}" class="form-control">
    <option value="">— Pilih level —</option>
    @foreach ($levels as $value => $label)
        <option value="{{ $value }}" @selected($current === $value)>{{ $label }}</option>
    @endforeach
</select>
