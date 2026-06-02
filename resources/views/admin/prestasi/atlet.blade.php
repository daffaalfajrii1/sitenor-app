@extends('admin.layouts.app')
@section('title', 'Prestasi — '.$atlet->name)

@section('content')
@include('admin.components.page-header', [
    'title' => 'Prestasi Atlet',
    'crumbs' => [
        ['label' => 'Prestasi Atlet', 'url' => route('admin.prestasi.index')],
        ['label' => $atlet->name],
    ],
    'actions' => '<a href="'.route('admin.prestasi.create', ['atlet_id' => $atlet->id]).'" class="btn btn-primary"><i class="feather-plus me-2"></i>Tambah Prestasi</a>',
])

<div class="main-content sitenor-prestasi-atlet-page">
    {{-- Hero atlet --}}
    <div class="sitenor-prestasi-hero card border-0 mb-4">
        <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center g-4">
                <div class="col-auto">
                    <div class="sitenor-prestasi-hero__avatar">
                        @if($atlet->photo)
                            <img src="{{ Storage::url($atlet->photo) }}" alt="{{ $atlet->name }}">
                        @else
                            <span class="sitenor-prestasi-hero__initial">{{ strtoupper(substr($atlet->name, 0, 1)) }}</span>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge sitenor-prestasi-hero__cabor">{{ $atlet->cabor?->name ?? '—' }}</span>
                        @if($atlet->gender)
                            <span class="badge bg-white bg-opacity-25 text-white">{{ ucfirst($atlet->gender) }}</span>
                        @endif
                        <span class="badge {{ $atlet->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $atlet->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <h2 class="sitenor-prestasi-hero__name mb-1">{{ $atlet->name }}</h2>
                    <p class="sitenor-prestasi-hero__meta mb-0">
                        @if($atlet->phone)<span><i class="feather-phone me-1"></i>{{ $atlet->phone }}</span>@endif
                        @if($atlet->email)<span class="ms-3"><i class="feather-mail me-1"></i>{{ $atlet->email }}</span>@endif
                    </p>
                </div>
                <div class="col-12 col-lg-auto">
                    <div class="sitenor-prestasi-hero__stat text-center">
                        <div class="sitenor-prestasi-hero__stat-num">{{ $atlet->prestasis->count() }}</div>
                        <div class="sitenor-prestasi-hero__stat-label">Total Prestasi</div>
                    </div>
                </div>
            </div>
            @if($atlet->prestasis->isNotEmpty())
            <div class="sitenor-prestasi-level-pills mt-4 pt-3 border-top border-white border-opacity-25">
                @foreach($levels as $key => $label)
                    @php $count = $levelCounts[$key] ?? 0; @endphp
                    @if($count > 0)
                        <span class="sitenor-level-pill sitenor-level-pill--{{ $key }}">
                            {{ $label }} <strong>{{ $count }}</strong>
                        </span>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Daftar prestasi --}}
    @if($atlet->prestasis->isEmpty())
        <div class="card sitenor-table-card border-0">
            <div class="card-body text-center py-5 px-4">
                <div class="sitenor-prestasi-empty-icon mx-auto mb-3">
                    <i class="feather-award"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Belum ada prestasi</h5>
                <p class="text-muted mb-4 mx-auto" style="max-width: 400px">
                    Catat pencapaian {{ $atlet->name }} di kejuaraan kabupaten, provinsi, nasional, atau internasional.
                </p>
                <a href="{{ route('admin.prestasi.create', ['atlet_id' => $atlet->id]) }}" class="btn btn-primary btn-lg">
                    <i class="feather-plus me-2"></i>Tambah Prestasi Pertama
                </a>
            </div>
        </div>
    @else
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h6 class="fw-bold text-dark mb-0"><i class="feather-award text-primary me-2"></i>Riwayat Prestasi</h6>
            <span class="text-muted fs-12">{{ $atlet->prestasis->count() }} data</span>
        </div>
        <div class="row g-3">
            @foreach($atlet->prestasis as $p)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card sitenor-prestasi-card h-100 border-0">
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                                <span class="sitenor-level-pill sitenor-level-pill--{{ $p->level }} sitenor-level-pill--sm">
                                    {{ ucfirst($p->level) }}
                                </span>
                                @if($p->year)
                                    <span class="badge bg-light text-dark fw-semibold">{{ $p->year }}</span>
                                @endif
                            </div>
                            <h5 class="sitenor-prestasi-card__title mb-1">{{ $p->title }}</h5>
                            @if($p->event_name)
                                <p class="text-muted fs-12 mb-2"><i class="feather-flag me-1"></i>{{ $p->event_name }}</p>
                            @endif
                            <ul class="list-unstyled fs-12 text-muted mb-0 mt-auto pt-3">
                                @if($p->rank)
                                    <li class="mb-1"><i class="feather-star me-2 text-warning"></i>Peringkat: <strong class="text-dark">{{ $p->rank }}</strong></li>
                                @endif
                                @if($p->location)
                                    <li><i class="feather-map-pin me-2"></i>{{ $p->location }}</li>
                                @endif
                            </ul>
                            @if($p->description)
                                <p class="fs-12 text-muted mt-2 mb-0 border-top pt-2">{{ Str::limit($p->description, 120) }}</p>
                            @endif
                            <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                                <a href="{{ route('admin.prestasi.edit', $p) }}" class="btn btn-sm btn-light-brand flex-grow-1">Edit</a>
                                <form action="{{ route('admin.prestasi.destroy', $p) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Hapus prestasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('admin.prestasi.index') }}" class="btn btn-light-brand">
            <i class="feather-arrow-left me-2"></i>Kembali ke Daftar Atlet
        </a>
    </div>
</div>
@endsection
