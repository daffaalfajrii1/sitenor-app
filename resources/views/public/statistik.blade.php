@extends('layouts.public.app')

@section('title', 'Statistik')

@section('content')
<div class="sitenor-public-page">
    <div class="container">
        <div class="sitenor-public-page-header">
            <h1>Statistik Keolahragaan</h1>
            <p>Data agregat cabang olahraga Kabupaten Rejang Lebong — atlet, pelatih, wasit & juri, serta prestasi.</p>
        </div>

        @include('public.partials.statistik-filter', ['search' => $search])

        <div class="sitenor-public-stat-grid mb-4">
            @foreach ([
                ['Atlet', $summary['atlet']],
                ['Pelatih', $summary['pelatih']],
                ['Wasit & Juri', $summary['wasit_juri']],
                ['Prestasi', $summary['prestasi']],
                ['Prestasi Nasional', $summary['prestasi_nasional']],
                ['Prestasi Internasional', $summary['prestasi_internasional']],
            ] as [$label, $value])
                <div class="sitenor-public-stat" style="cursor:default">
                    <div class="sitenor-public-stat__num">{{ number_format($value) }}</div>
                    <div class="sitenor-public-stat__label">{{ $label }}</div>
                </div>
            @endforeach
        </div>

        <div class="sitenor-chart-section">
            <h2 class="sitenor-chart-section__title">Grafik per Cabang Olahraga</h2>
            <div class="sitenor-chart-panel">
                <canvas id="chartCaborBar" height="100"></canvas>
            </div>
        </div>

        <div class="sitenor-chart-section">
            <h2 class="sitenor-chart-section__title">Grafik per Level</h2>
            <div class="sitenor-chart-pies">
                <div class="sitenor-chart-pie-card"><h3>Pelatih</h3><canvas id="piePelatih"></canvas></div>
                <div class="sitenor-chart-pie-card"><h3>Wasit & Juri</h3><canvas id="pieWasitJuri"></canvas></div>
                <div class="sitenor-chart-pie-card"><h3>Prestasi (Internasional / Nasional / …)</h3><canvas id="piePrestasi"></canvas></div>
            </div>
        </div>

        @if ($prestasiByYear->isNotEmpty())
        <div class="sitenor-chart-section">
            <h2 class="sitenor-chart-section__title">Prestasi per Tahun</h2>
            <div class="sitenor-chart-panel">
                <canvas id="chartPrestasiYear"></canvas>
            </div>
        </div>
        @endif

        <div class="sitenor-public-card mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h2 class="h5 fw-bold mb-0">Statistik per Cabor</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <x-table-no-th />
                            <th>Cabor</th>
                            <th class="text-center">Atlet</th>
                            <th class="text-center">Pelatih</th>
                            <th class="text-center">Wasit</th>
                            <th class="text-center">Juri</th>
                            <th class="text-center">Prestasi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($caborSummary as $cabor)
                        <tr>
                            <x-table-no-td :index="$loop->index" />
                            <td class="fw-semibold">
                                {{ $cabor->name }}
                                @if ($cabor->kode)
                                    <span class="text-muted fw-normal fs-12 d-block">{{ $cabor->kode }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $cabor->atlet_count }}</td>
                            <td class="text-center">{{ $cabor->pelatih_count }}</td>
                            <td class="text-center">{{ $cabor->wasit_count }}</td>
                            <td class="text-center">{{ $cabor->juri_count }}</td>
                            <td class="text-center">{{ $cabor->prestasis_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                @if (filled($search))
                                    Tidak ada cabang olahraga yang cocok dengan &ldquo;{{ $search }}&rdquo;.
                                @else
                                    Belum ada data cabang olahraga.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="sitenor-public-card">
            <div class="card-header bg-white border-bottom py-3">
                <h2 class="h5 fw-bold mb-0">Prestasi per Cabor (Internasional & Nasional)</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <x-table-no-th />
                            <th>Cabor</th>
                            <th class="text-center">Internasional</th>
                            <th class="text-center">Nasional</th>
                            <th class="text-center">Provinsi</th>
                            <th class="text-center">Kabupaten</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($prestasiPerCabor as $row)
                        <tr>
                            <x-table-no-td :index="$loop->index" />
                            <td class="fw-semibold">{{ $row->cabor_name }}</td>
                            <td class="text-center">{{ $row->internasional }}</td>
                            <td class="text-center">{{ $row->nasional }}</td>
                            <td class="text-center">{{ $row->provinsi }}</td>
                            <td class="text-center">{{ $row->kabupaten }}</td>
                            <td class="text-center fw-bold">{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                @if (filled($search))
                                    Tidak ada prestasi untuk pencarian &ldquo;{{ $search }}&rdquo;.
                                @else
                                    Belum ada data prestasi.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('public.partials.chart-init', [
    'entitiesPerCabor' => $entitiesPerCabor,
    'pelatihByLevel' => $pelatihByLevel,
    'wasitJuriByLevel' => $wasitJuriByLevel,
    'prestasiByLevel' => $prestasiByLevel,
    'prestasiByYear' => $prestasiByYear,
    'mode' => 'full',
])
@endpush
