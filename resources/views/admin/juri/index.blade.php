@extends('admin.layouts.app')
@section('title', 'Juri')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Data Juri',
    'crumbs' => [['label' => 'Juri']],
    'actions' => view('admin.components.excel-toolbar', [
        'module' => 'juri',
        'createRoute' => route('admin.juri.create'),
        'createLabel' => 'Tambah Juri',
    ])->render(),
])
<div class="main-content">
    @include('admin.components.list-filters', ['cabors' => $cabors, 'searchPlaceholder' => 'Cari nama / lisensi...'])
    <div class="card sitenor-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table sitenor-data-table table-hover mb-0 align-middle">
                    <thead><tr>
                        <th style="width:50px">No</th>
                        <th>Nama</th>
                        <th>Cabor</th>
                        <th>Lisensi</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr></thead>
                    <tbody>
                    @forelse ($juris as $item)
                    <tr>
                        <td>{{ $juris->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $item->name }}</td>
                        <td>{{ $item->cabor?->name }}</td>
                        <td>{{ $item->license_number ?? '-' }}</td>
                        <td>{{ $item->level_label }}</td>
                        <td><span class="badge {{ $item->is_active ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="text-end">
                            <x-admin.components.table-actions>
                                <a href="{{ route('admin.juri.edit', $item) }}" class="btn btn-sm btn-light-brand">Edit</a>
                                <form action="{{ route('admin.juri.destroy', $item) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">Hapus</button></form>
                            </x-admin.components.table-actions>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data juri.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">{{ $juris->links() }}</div>
        </div>
    </div>
</div>
@endsection
