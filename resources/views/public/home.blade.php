@extends('layouts.public.app')

@section('title', 'Beranda')

@section('content')
<section class="sitenor-public-hero">
    <div class="container position-relative" style="z-index:1">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="sitenor-public-hero__badge">{{ $siteSettings->app_name ?? 'Sitenor' }} · Rejang Lebong</span>
                <h1 class="sitenor-public-hero__title">
                    <span>{{ $siteSettings->app_name ?? 'Sitenor' }}</span><br>
                    Kabupaten Rejang Lebong
                </h1>
                <p class="sitenor-public-hero__lead">
                    Portal data tenaga olahraga, prestasi atlet, dan informasi keolahragaan Kabupaten Rejang Lebong.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('public.statistik') }}" class="btn btn-danger btn-lg px-4">Lihat Statistik</a>
                    <a href="{{ route('public.atlet.index') }}" class="btn btn-outline-dark btn-lg px-4">Data Atlet</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="sitenor-public-hero__illus-wrap">
                    <img
                        src="{{ asset('images/hero-atlet-juara.png') }}"
                        alt="Atlet juara di podium"
                        class="sitenor-public-hero__illus-img"
                        width="320"
                        height="320"
                        loading="eager"
                    />
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-4" style="margin-top:-2rem;position:relative;z-index:2">
    <div class="sitenor-public-stat-grid">
        @foreach ([
            ['label' => 'Cabang Olahraga', 'value' => $summary['cabor'], 'route' => 'public.statistik'],
            ['label' => 'Atlet', 'value' => $summary['atlet'], 'route' => 'public.atlet.index'],
            ['label' => 'Pelatih', 'value' => $summary['pelatih'], 'route' => 'public.pelatih.index'],
            ['label' => 'Wasit & Juri', 'value' => $summary['wasit_juri'], 'route' => 'public.wasit-juri.index'],
            ['label' => 'Prestasi', 'value' => $summary['prestasi'], 'route' => 'public.prestasi.index'],
            ['label' => 'Nasional', 'value' => $summary['prestasi_nasional'], 'route' => 'public.prestasi.index'],
            ['label' => 'Internasional', 'value' => $summary['prestasi_internasional'], 'route' => 'public.prestasi.index'],
            ['label' => 'Artikel', 'value' => $summary['artikel'], 'route' => 'public.artikel.index'],
        ] as $s)
            <a href="{{ route($s['route']) }}" class="sitenor-public-stat">
                <div class="sitenor-public-stat__num">{{ number_format($s['value']) }}</div>
                <div class="sitenor-public-stat__label">{{ $s['label'] }}</div>
            </a>
        @endforeach
    </div>
</section>

<section class="container pb-5">
    <div class="sitenor-chart-section">
        <h2 class="sitenor-chart-section__title">Grafik Entitas per Cabang Olahraga — Rejang Lebong</h2>
        <div class="sitenor-chart-panel">
            <canvas id="chartCaborEntities" height="120"></canvas>
        </div>
    </div>

    <div class="sitenor-chart-section">
        <h2 class="sitenor-chart-section__title">Grafik per Level Lisensi & Prestasi</h2>
        <div class="sitenor-chart-pies">
            <div class="sitenor-chart-pie-card">
                <h3>Lisensi Pelatih</h3>
                <canvas id="chartPelatihLevel"></canvas>
            </div>
            <div class="sitenor-chart-pie-card">
                <h3>Lisensi Wasit & Juri</h3>
                <canvas id="chartWasitJuriLevel"></canvas>
            </div>
            <div class="sitenor-chart-pie-card">
                <h3>Prestasi Atlet (Level Kejuaraan)</h3>
                <canvas id="chartPrestasiLevel"></canvas>
            </div>
        </div>
    </div>

    @if ($latestArtikels->isNotEmpty())
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 fw-bold mb-0">Artikel Terbaru</h2>
            <a href="{{ route('public.artikel.index') }}" class="btn btn-sm btn-outline-danger">Semua artikel</a>
        </div>
        <div class="row g-3">
            @foreach ($latestArtikels as $artikel)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('public.artikel.show', $artikel) }}" class="sitenor-artikel-card">
                    <div class="sitenor-artikel-card__body">
                        <div class="sitenor-artikel-card__meta">
                            {{ $artikel->cabor?->name ?? 'Umum' }}
                            · {{ $artikel->published_at?->format('d M Y') }}
                        </div>
                        <div class="sitenor-artikel-card__title">{{ $artikel->title }}</div>
                        <p class="text-muted fs-12 mb-0 text-truncate">{{ $artikel->excerpt }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
(function () {
    const caborData = @json($entitiesPerCabor);
    const colors = ['#2563eb','#64748b','#22c55e','#eab308','#a855f7','#ef4444','#06b6d4','#f97316'];

    if (document.getElementById('chartCaborEntities') && caborData.length) {
        new Chart(document.getElementById('chartCaborEntities'), {
            type: 'bar',
            data: {
                labels: caborData.map(r => r.name),
                datasets: [
                    { label: 'Atlet', data: caborData.map(r => r.atlet), backgroundColor: '#2563eb' },
                    { label: 'Pelatih', data: caborData.map(r => r.pelatih), backgroundColor: '#22c55e' },
                    { label: 'Wasit & Juri', data: caborData.map(r => r.wasit_juri), backgroundColor: '#eab308' },
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    x: { stacked: false, ticks: { maxRotation: 45, minRotation: 45 } },
                    y: { beginAtZero: true, title: { display: true, text: 'Jumlah' } }
                }
            }
        });
    }

    function pieChart(id, dataObj) {
        const el = document.getElementById(id);
        if (!el) return;
        const labels = Object.keys(dataObj);
        const values = Object.values(dataObj);
        if (!labels.length) return;
        new Chart(el, {
            type: 'pie',
            data: {
                labels,
                datasets: [{ data: values, backgroundColor: colors }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.label + ': ' + ctx.parsed + ' (' + Math.round(ctx.parsed / values.reduce((a,b)=>a+b,0) * 100) + '%)'
                        }
                    }
                }
            }
        });
    }

    pieChart('chartPelatihLevel', @json($pelatihByLevel));
    pieChart('chartWasitJuriLevel', @json($wasitJuriByLevel));
    pieChart('chartPrestasiLevel', @json($prestasiByLevel));
})();
</script>
@endpush
