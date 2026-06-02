@props([
    'cancelUrl' => null,
    'submitLabel' => 'Simpan',
    'cancelLabel' => 'Batal',
])

<div {{ $attributes->merge(['class' => 'sitenor-form-actions mt-4']) }}>
    <button type="submit" class="btn btn-primary sitenor-btn-action">
        <i class="feather-save me-2"></i>{{ $submitLabel }}
    </button>
    @if ($cancelUrl)
        <a href="{{ $cancelUrl }}" class="btn btn-light sitenor-btn-action">{{ $cancelLabel }}</a>
    @endif
    {{ $slot ?? '' }}
</div>
