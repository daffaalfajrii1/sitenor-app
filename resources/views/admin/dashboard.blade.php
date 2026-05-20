@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @include('admin.components.page-header', [
        'title' => 'Dashboard Sitenor',
        'crumbs' => [
            ['label' => 'Dashboard'],
        ],
    ])

    <div class="main-content">
        <div class="row g-3 mb-4">
            @foreach ([
                ['label' => 'Cabang Olahraga', 'value' => $stats['cabor'], 'icon' => 'feather-flag', 'route' => 'admin.cabor.index'],
                ['label' => 'Atlet', 'value' => $stats['atlet'], 'icon' => 'feather-user', 'route' => 'admin.atlet.index'],
                ['label' => 'Pelatih', 'value' => $stats['pelatih'], 'icon' => 'feather-users', 'route' => 'admin.pelatih.index'],
                ['label' => 'Wasit', 'value' => $stats['wasit'], 'icon' => 'feather-shield', 'route' => 'admin.wasit.index'],
                ['label' => 'Artikel', 'value' => $stats['artikel'], 'icon' => 'feather-file-text', 'route' => 'admin.artikel.index'],
                ['label' => 'Pengumuman', 'value' => $stats['pengumuman'], 'icon' => 'feather-download', 'route' => 'admin.pengumuman.index'],
            ] as $stat)
                <div class="col-xxl-2 col-lg-4 col-md-4 col-6">
                    <a href="{{ route($stat['route']) }}" class="text-decoration-none text-reset">
                        <div class="card stretch stretch-full h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-text avatar-lg rounded">
                                        <i class="{{ $stat['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted fs-12 d-block">{{ $stat['label'] }}</span>
                                        <span class="fs-20 fw-bolder">{{ $stat['value'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">Cabang Olahraga Terbaru</h5>
                <a href="{{ route('admin.cabor.create') }}" class="btn btn-sm btn-primary">Tambah Cabor</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th class="d-none d-md-table-cell">Ditambahkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentCabors as $cabor)
                                <tr>
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
                                    <td colspan="4" class="text-center text-muted py-4">
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

