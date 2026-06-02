@extends('layouts.public.app')
@section('title', 'Prestasi Atlet')

@section('content')
<div class="sitenor-public-page">
    <div class="container">
        <div class="sitenor-public-page-header">
            <h1>Prestasi Atlet</h1>
            <p>Rekam jejak prestasi atlet — nasional, internasional, dan tingkat lainnya.</p>
        </div>

        @include('public.partials.data-filter', [
            'cabors' => $cabors,
            'levels' => $levels,
            'searchPlaceholder' => 'Cari atlet / kejuaraan...',
        ])

        <div class="row g-4">
            @forelse ($prestasis as $p)
                @php $atlet = $p->atlet; @endphp
                <div class="col-md-6 col-lg-4">
                    <article class="sitenor-prestasi-card-public">
                        <div class="sitenor-prestasi-card-public__top">
                            <div class="sitenor-prestasi-card-public__avatar">
                                @if ($atlet?->photoUrl())
                                    <img src="{{ $atlet->photoUrl() }}" alt="{{ $atlet->name }}" loading="lazy" />
                                @else
                                    <span>{{ $atlet?->photoInitial() ?? '?' }}</span>
                                @endif
                            </div>
                            <div class="sitenor-prestasi-card-public__head">
                                <h3 class="sitenor-prestasi-card-public__atlet">{{ $atlet?->name ?? '—' }}</h3>
                                <p class="sitenor-prestasi-card-public__cabor mb-0">
                                    <i class="feather-flag"></i> {{ $atlet?->cabor?->name ?? '—' }}
                                </p>
                            </div>
                        </div>
                        <div class="sitenor-prestasi-card-public__body">
                            <h4 class="sitenor-prestasi-card-public__title">{{ $p->title }}</h4>
                            @if ($p->event_name)
                                <p class="sitenor-prestasi-card-public__event mb-2">{{ $p->event_name }}</p>
                            @endif
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge sitenor-level-badge sitenor-level-{{ $p->level }}">
                                    {{ $levels[$p->level] ?? $p->level }}
                                </span>
                                @if ($p->year)
                                    <span class="badge bg-light text-dark border">{{ $p->year }}</span>
                                @endif
                                @if ($p->rank)
                                    <span class="badge bg-warning-subtle text-dark">{{ $p->rank }}</span>
                                @endif
                            </div>
                            @if ($atlet)
                                <a href="{{ route('public.atlet.show', $atlet) }}" class="btn btn-sm btn-outline-danger w-100">
                                    Profil & Prestasi Atlet
                                </a>
                            @endif
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="sitenor-public-empty">
                        <i class="feather-award"></i>
                        <p>Belum ada prestasi.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @include('public.partials.pagination', ['paginator' => $prestasis])
    </div>
</div>
@endsection
