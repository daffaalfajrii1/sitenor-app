@extends('admin.layouts.app')

@section('title', 'Cabang Olahraga')

@section('content')
    @include('admin.components.page-header', [
        'title' => 'Cabang Olahraga',
        'crumbs' => [
            ['label' => 'Cabang Olahraga'],
        ],
        'actions' => '<a href="'.route('admin.cabor.create').'" class="btn btn-primary"><i class="feather-plus me-2"></i>Tambah Cabor</a>',
    ])

    <div class="main-content">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Cabor</th>
                                <th class="d-none d-lg-table-cell text-center">Atlet</th>
                                <th class="d-none d-lg-table-cell text-center">Pelatih</th>
                                <th class="d-none d-lg-table-cell text-center">Wasit</th>
                                <th class="d-none d-lg-table-cell text-center">Prestasi</th>
                                <th class="text-center">Status</th>
                                <th class="text-end" style="min-width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cabors as $cabor)
                                <tr>
                                    <td><code class="fs-12">{{ $cabor->kode }}</code></td>
                                    <td>
                                        <div class="fw-semibold">{{ $cabor->name }}</div>
                                        <div class="d-lg-none fs-11 text-muted mt-1">
                                            Atlet {{ $cabor->atlets_count }}
                                            · Prestasi {{ $cabor->prestasis_count }}
                                        </div>
                                    </td>
                                    <td class="d-none d-lg-table-cell text-center">{{ $cabor->atlets_count }}</td>
                                    <td class="d-none d-lg-table-cell text-center">{{ $cabor->pelatihs_count }}</td>
                                    <td class="d-none d-lg-table-cell text-center">{{ $cabor->wasits_count }}</td>
                                    <td class="d-none d-lg-table-cell text-center">
                                        <a href="{{ route('admin.prestasi.index', ['cabor_id' => $cabor->id]) }}" class="text-decoration-none fw-semibold">
                                            {{ $cabor->prestasis_count }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $cabor->is_active ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                                            {{ $cabor->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex flex-nowrap align-items-center justify-content-end gap-2">
                                            <a href="{{ route('admin.cabor.edit', $cabor) }}" class="btn btn-sm btn-light-brand text-nowrap">Edit</a>
                                            <form action="{{ route('admin.cabor.destroy', $cabor) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus cabor {{ $cabor->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger text-nowrap">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        Belum ada cabang olahraga.
                                        <a href="{{ route('admin.cabor.create') }}" class="d-block mt-2">Tambah cabor pertama</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($cabors->hasPages())
                    <div class="p-3 border-top">{{ $cabors->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
