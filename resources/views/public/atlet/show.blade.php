@extends('layouts.public.app')
@section('title', $atlet->name)

@section('content')
<div class="sitenor-public-page">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('public.atlet.index') }}">Data Atlet</a></li>
                <li class="breadcrumb-item active">{{ $atlet->name }}</li>
            </ol>
        </nav>

        <div class="sitenor-atlet-hero">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    @if ($atlet->photo)
                        <img src="{{ Storage::url($atlet->photo) }}" alt="{{ $atlet->name }}" class="sitenor-atlet-hero__photo">
                    @else
                        <div class="sitenor-atlet-hero__photo d-flex align-items-center justify-content-center bg-white bg-opacity-25 fs-1 fw-bold">
                            {{ strtoupper(substr($atlet->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="col">
                    <span class="badge bg-white bg-opacity-25 mb-2">{{ $atlet->cabor?->name }}</span>
                    <h1 class="h2 fw-bold mb-1">{{ $atlet->name }}</h1>
                    <p class="mb-0 opacity-90">
                        {{ $atlet->age_label !== '—' ? 'Umur '.$atlet->age_label : '' }}
                        @if ($atlet->gender) · {{ ucfirst($atlet->gender) }} @endif
                        · {{ $atlet->prestasis->count() }} prestasi
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="sitenor-public-card p-4">
                    <h2 class="h5 fw-bold mb-3">Prestasi</h2>
                    @forelse ($atlet->prestasis as $p)
                        <div class="list-group-item border rounded mb-2 p-3 sitenor-prestasi-timeline">
                            <div class="d-flex flex-wrap justify-content-between gap-2 mb-1">
                                <strong>{{ $p->title }}</strong>
                                <span class="badge sitenor-level-badge sitenor-level-{{ $p->level }}">
                                    {{ $levels[$p->level] ?? $p->level }}
                                </span>
                            </div>
                            <div class="text-muted fs-12">
                                @if ($p->event_name) {{ $p->event_name }} · @endif
                                Tahun {{ $p->year ?? '—' }}
                                @if ($p->rank) · {{ $p->rank }} @endif
                                @if ($p->location) · {{ $p->location }} @endif
                            </div>
                            @if ($p->description)
                                <p class="mb-0 mt-2 fs-14">{{ $p->description }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada prestasi tercatat.</p>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                @if ($levelCounts->isNotEmpty())
                <div class="sitenor-public-card p-4 mb-3">
                    <h3 class="h6 fw-bold">Ringkasan Level</h3>
                    @foreach ($levels as $key => $label)
                        @if (($levelCounts[$key] ?? 0) > 0)
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span>{{ $label }}</span>
                                <strong>{{ $levelCounts[$key] }}</strong>
                            </div>
                        @endif
                    @endforeach
                </div>
                @endif
                <a href="{{ route('public.prestasi.index', ['search' => $atlet->name]) }}" class="btn btn-outline-danger w-100">
                    Lihat di daftar prestasi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
