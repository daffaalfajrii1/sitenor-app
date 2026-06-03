@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @include('admin.components.page-header', [
        'title' => 'Dashboard Sitenor',
        'crumbs' => [['label' => 'Dashboard']],
    ])

    <div class="main-content">
        {{-- Ringkasan angka --}}
        <div class="row g-3 mb-4">
            @foreach ([
                ['label' => 'Cabang Olahraga', 'value' => $stats['cabor'], 'icon' => 'feather-flag', 'route' => 'admin.cabor.index', 'soft' => 'primary'],
                ['label' => 'Atlet', 'value' => $stats['atlet'], 'icon' => 'feather-user', 'route' => 'admin.atlet.index', 'soft' => 'info'],
                ['label' => 'Pelatih', 'value' => $stats['pelatih'], 'icon' => 'feather-users', 'route' => 'admin.pelatih.index', 'soft' => 'success'],
                ['label' => 'Wasit', 'value' => $stats['wasit'], 'icon' => 'feather-shield', 'route' => 'admin.wasit.index', 'soft' => 'warning'],
                ['label' => 'Juri', 'value' => $stats['juri'], 'icon' => 'feather-eye', 'route' => 'admin.juri.index', 'soft' => 'secondary'],
                ['label' => 'Kepala Cabor', 'value' => $stats['kepala_cabor'], 'icon' => 'feather-user-check', 'route' => 'admin.kepala-cabor.index', 'soft' => 'primary'],
                ['label' => 'Total Prestasi', 'value' => $stats['prestasi'], 'icon' => 'feather-award', 'route' => 'admin.prestasi.index', 'soft' => 'danger'],
                ['label' => 'Prestasi '.$currentYear, 'value' => $stats['prestasi_tahun_ini'], 'icon' => 'feather-trending-up', 'route' => 'admin.prestasi.index', 'soft' => 'danger'],
                ['label' => 'Artikel Terbit', 'value' => $stats['artikel_terbit'], 'icon' => 'feather-file-text', 'route' => 'admin.artikel.released', 'soft' => 'info'],
                ['label' => 'Artikel Draft', 'value' => $stats['artikel_draft'], 'icon' => 'feather-edit-3', 'route' => 'admin.artikel.index', 'soft' => 'warning'],
                ['label' => 'Pengumuman', 'value' => $stats['pengumuman'], 'icon' => 'feather-download', 'route' => 'admin.pengumuman.index', 'soft' => 'secondary'],
            ] as $stat)
                <div class="col-xxl-2 col-lg-3 col-md-4 col-6">
                    <a href="{{ route($stat['route']) }}" class="text-decoration-none text-reset">
                        <div class="card sitenor-dash-stat h-100 border-0 shadow-sm">
                            <div class="card-body py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="sitenor-dash-stat__icon bg-soft-{{ $stat['soft'] }} text-{{ $stat['soft'] }}">
                                        <i class="{{ $stat['icon'] }}"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-muted fs-12 d-block text-truncate">{{ $stat['label'] }}</span>
                                        <span class="fs-22 fw-bolder lh-1">{{ number_format($stat['value']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="row g-4 mb-4">
            {{-- Prestasi per tahun --}}
            <div class="col-lg-5">
                <div class="card h-100 sitenor-table-card">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Prestasi per Tahun</h5>
                        <a href="{{ route('admin.prestasi.index') }}" class="btn btn-sm btn-light-brand">Lihat semua</a>
                    </div>
                    <div class="card-body">
                        @forelse($prestasiByYear as $row)
                            @php $max = max($prestasiByYear->max('total'), 1); $pct = round(($row->total / $max) * 100); @endphp
                            <div class="sitenor-dash-bar-row mb-3">
                                <div class="d-flex justify-content-between fs-12 mb-1">
                                    <span class="fw-semibold">{{ $row->year }}</span>
                                    <span class="text-muted">{{ $row->total }} prestasi</span>
                                </div>
                                <div class="progress sitenor-dash-progress" style="height:8px">
                                    <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted mb-0 text-center py-4">Belum ada data prestasi per tahun.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Prestasi per cabor (tahun berjalan) --}}
            <div class="col-lg-7">
                <div class="card h-100 sitenor-table-card">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="mb-0">Prestasi per Cabor (per Tahun)</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table sitenor-data-table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <x-table-no-th />
                                        <th>Cabor</th>
                                        <th>Tahun</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($prestasiByCaborYear as $row)
                                    <tr>
                                        <x-table-no-td :index="$loop->index" />
                                        <td class="fw-semibold">{{ $row->cabor_name }}</td>
                                        <td>{{ $row->year }}</td>
                                        <td class="text-end">
                                            <span class="sitenor-count-badge bg-soft-primary text-primary">{{ $row->total }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan per cabor --}}
        <div class="card sitenor-table-card mb-4">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">Statistik Data per Cabang Olahraga</h5>
                <a href="{{ route('admin.cabor.index') }}" class="btn btn-sm btn-light-brand">Kelola Cabor</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table sitenor-data-table table-hover mb-0 align-middle">
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
                        @forelse($caborSummary as $cabor)
                            <tr>
                                <x-table-no-td :index="$loop->index" />
                                <td class="sitenor-cell-name">{{ $cabor->name }}</td>
                                <td class="text-center">{{ $cabor->atlets_count }}</td>
                                <td class="text-center">{{ $cabor->pelatihs_count }}</td>
                                <td class="text-center">{{ $cabor->wasits_count }}</td>
                                <td class="text-center">{{ $cabor->juris_count }}</td>
                                <td class="text-center">
                                    <span class="sitenor-count-badge {{ $cabor->prestasis_count ? 'bg-soft-primary text-primary' : 'bg-soft-secondary text-secondary' }}">
                                        {{ $cabor->prestasis_count }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada cabang olahraga.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Artikel rilis terbaru --}}
            <div class="col-lg-6">
                <div class="card h-100 sitenor-table-card">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Artikel Rilis Terbaru</h5>
                        <a href="{{ route('admin.artikel.released') }}" class="btn btn-sm btn-primary">Monitor Rilis</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($recentPublishedArtikels as $artikel)
                                <a href="{{ route('admin.artikel.edit', $artikel) }}" class="list-group-item list-group-item-action px-4 py-3">
                                    <div class="d-flex justify-content-between gap-2">
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-truncate">{{ $artikel->title }}</div>
                                            <div class="fs-12 text-muted">
                                                {{ $artikel->cabor?->name ?? 'Umum' }}
                                                · {{ $artikel->published_at?->format('d M Y') ?? '—' }}
                                            </div>
                                        </div>
                                        <span class="badge bg-soft-success text-success flex-shrink-0">Terbit</span>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center text-muted py-4">Belum ada artikel terbit.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Prestasi terbaru --}}
            <div class="col-lg-6">
                <div class="card h-100 sitenor-table-card">
                    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Prestasi Terbaru</h5>
                        <a href="{{ route('admin.prestasi.index') }}" class="btn btn-sm btn-light-brand">Lihat semua</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($recentPrestasis as $prestasi)
                                <div class="list-group-item px-4 py-3">
                                    <div class="fw-semibold">{{ $prestasi->title }}</div>
                                    <div class="fs-12 text-muted">
                                        {{ $prestasi->atlet?->name ?? '—' }}
                                        · {{ $prestasi->atlet?->cabor?->name ?? '—' }}
                                        · {{ $prestasi->year ?? '—' }}
                                        @if($prestasi->rank)
                                            · {{ $prestasi->rank }}
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">Belum ada prestasi.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card sitenor-table-card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">Cabang Olahraga Terbaru</h5>
                <a href="{{ route('admin.cabor.create') }}" class="btn btn-sm btn-primary">Tambah Cabor</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table sitenor-data-table table-hover mb-0">
                        <thead>
                            <tr>
                                <x-table-no-th />
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th class="d-none d-md-table-cell">Ditambahkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentCabors as $cabor)
                                <tr>
                                    <x-table-no-td :index="$loop->index" />
                                    <td><code>{{ $cabor->kode ?? '-' }}</code></td>
                                    <td>{{ $cabor->name }}</td>
                                    <td>
                                        <span class="badge {{ $cabor->is_active ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                                            {{ $cabor->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $cabor->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada cabang olahraga.
                                        <a href="{{ route('admin.cabor.create') }}">Tambah sekarang</a>
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
