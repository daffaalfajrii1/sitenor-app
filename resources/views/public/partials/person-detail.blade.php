@php
    $typeLabel = match ($type) {
        'wasit' => 'Wasit',
        'juri' => 'Juri',
        'pelatih' => 'Pelatih',
        default => 'Profil',
    };
@endphp

<div class="sitenor-person-detail">
    <div class="row g-4 align-items-start">
        <div class="col-md-4 text-center">
            <div class="sitenor-person-detail__photo-wrap mx-auto">
                @if ($person->photoUrl())
                    <img src="{{ $person->photoUrl() }}" alt="{{ $person->name }}" />
                @else
                    <span class="sitenor-person-detail__initial">{{ $person->photoInitial() }}</span>
                @endif
            </div>
            <span class="badge sitenor-person-detail__type mt-3">{{ $typeLabel }}</span>
        </div>
        <div class="col-md-8">
            <h4 class="sitenor-person-detail__name mb-1">{{ $person->name }}</h4>
            <p class="text-muted mb-3">
                <i class="bi bi-flag me-1"></i>{{ $person->cabor?->name ?? '—' }}
            </p>

            <dl class="sitenor-person-detail__dl">
                @foreach ($fields as $label => $value)
                    <div class="sitenor-person-detail__row">
                        <dt>{{ $label }}</dt>
                        <dd>{{ $value ?: '—' }}</dd>
                    </div>
                @endforeach
            </dl>

            @if (! empty($person->bio))
                <div class="sitenor-person-detail__bio mt-3">
                    <h6 class="fw-bold fs-14 text-uppercase text-muted mb-2">Bio</h6>
                    <p class="mb-0">{{ $person->bio }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
