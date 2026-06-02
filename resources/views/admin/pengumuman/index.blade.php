@extends('admin.layouts.app')
@section('title', 'Pengumuman')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Pengumuman',
    'crumbs' => [['label' => 'Pengumuman']],
    'actions' => '<a href="'.route('admin.pengumuman.create').'" class="btn btn-primary"><i class="feather-plus me-2"></i>Upload Pengumuman</a>',
])
<div class="main-content">
    @include('admin.components.list-filters', [
        'showCabor' => false,
        'showStatus' => false,
        'showPublished' => true,
        'searchPlaceholder' => 'Cari judul...',
    ])
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Judul</th>
                            <th>File</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($pengumumans as $p)
                        <tr>
                            <td>{{ $pengumumans->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold">{{ $p->title }}</td>
                            <td class="text-truncate" style="max-width:200px">{{ $p->file_name }}</td>
                            <td>
                                <span class="badge {{ $p->is_published ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}">
                                    {{ $p->is_published ? 'Terbit' : 'Draft' }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ Storage::url($p->file_path) }}" target="_blank" class="btn btn-sm btn-light-brand">Unduh</a>
                                <a href="{{ route('admin.pengumuman.edit', $p) }}" class="btn btn-sm btn-light-brand">Edit</a>
                                <form action="{{ route('admin.pengumuman.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengumuman ini?')">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">Hapus</button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengumuman.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $pengumumans->links() }}</div>
        </div>
    </div>
</div>
@endsection
