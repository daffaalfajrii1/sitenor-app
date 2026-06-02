@props([
    'name',
    'photoUrl' => null,
    'initial' => '?',
    'cabor' => null,
    'meta' => [],
    'badge' => null,
    'href' => null,
    'modalUrl' => null,
    'actionLabel' => 'Lihat Detail',
])

<article {{ $attributes->merge(['class' => 'sitenor-person-card']) }}>
    <div class="sitenor-person-card__media">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $name }}" loading="lazy" />
        @else
            <span class="sitenor-person-card__initial" aria-hidden="true">{{ $initial }}</span>
        @endif
        @if ($badge)
            <span class="sitenor-person-card__badge">{{ $badge }}</span>
        @endif
    </div>
    <div class="sitenor-person-card__body">
        <h3 class="sitenor-person-card__name">{{ $name }}</h3>
        @if ($cabor)
            <p class="sitenor-person-card__cabor">
                <i class="feather-flag"></i> {{ $cabor }}
            </p>
        @endif
        @if (count($meta))
            <ul class="sitenor-person-card__meta">
                @foreach ($meta as $item)
                    @if (! empty($item['label']) && $item['label'] !== '—')
                        <li>
                            @if (! empty($item['icon']))
                                <i class="{{ $item['icon'] }}"></i>
                            @endif
                            <span>{{ $item['label'] }}</span>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
        <div class="sitenor-person-card__actions">
            @if ($href)
                <a href="{{ $href }}" class="btn btn-sm btn-danger w-100">{{ $actionLabel }}</a>
            @elseif ($modalUrl)
                <button
                    type="button"
                    class="btn btn-sm btn-danger w-100"
                    data-person-modal
                    data-url="{{ $modalUrl }}"
                    data-name="{{ $name }}"
                >
                    {{ $actionLabel }}
                </button>
            @endif
        </div>
    </div>
</article>
