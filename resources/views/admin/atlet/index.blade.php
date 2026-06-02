@extends('admin.layouts.app')
@section('title', 'Atlet')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Data Atlet',
    'crumbs' => [['label' => 'Atlet']],
    'actions' => view('admin.components.excel-toolbar', [
        'module' => 'atlet',
        'createRoute' => route('admin.atlet.create'),
        'createLabel' => 'Tambah Atlet',
    ])->render(),
])
<div class="main-content">
    @include('admin.components.list-filters', [
        'cabors' => $cabors,
        'showGender' => true,
        'searchPlaceholder' => 'Cari nama...',
    ])
    <div class="card sitenor-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table sitenor-data-table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Nama</th>
                            <th>Cabor</th>
                            <th>Jenis Kelamin</th>
                            <th class="text-center" style="width:80px">Umur</th>
                            <th>Telepon</th>
                            <th class="text-center" style="width:90px">Prestasi</th>
                            <th class="text-center" style="width:90px">Status</th>
                            <th class="text-end" style="min-width:220px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($atlets as $atlet)
                        <tr>
                            <td class="text-muted">{{ $atlets->firstItem() + $loop->index }}</td>
                            <td class="sitenor-cell-name">{{ $atlet->name }}</td>
                            <td>{{ $atlet->cabor?->name ?? '-' }}</td>
                            <td>{{ $atlet->gender ? ucfirst($atlet->gender) : '-' }}</td>
                            <td class="text-center">{{ $atlet->age_label }}</td>
                            <td>{{ $atlet->phone ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.prestasi.atlet', $atlet) }}" class="sitenor-count-badge bg-soft-primary text-primary text-decoration-none" title="Lihat prestasi">
                                    {{ $atlet->prestasis_count }}
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $atlet->is_active ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                                    {{ $atlet->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <x-admin.components.table-actions>
                                    <a href="{{ route('admin.prestasi.atlet', $atlet) }}" class="btn btn-sm btn-light-brand">Prestasi</a>
                                    <a href="{{ route('admin.prestasi.create', ['atlet_id' => $atlet->id]) }}" class="btn btn-sm btn-primary">+ Prestasi</a>
                                    <a href="{{ route('admin.atlet.edit', $atlet) }}" class="btn btn-sm btn-light-brand">Edit</a>
                                    <form action="{{ route('admin.atlet.destroy', $atlet) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus atlet ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </x-admin.components.table-actions>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                Belum ada data atlet.
                                <a href="{{ route('admin.atlet.create') }}" class="d-block mt-2 fw-semibold">Tambah atlet pertama</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">{{ $atlets->links() }}</div>
        </div>
    </div>
</div>
@endsection
