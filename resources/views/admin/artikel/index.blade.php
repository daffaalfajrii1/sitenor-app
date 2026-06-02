@extends('admin.layouts.app')
@section('title', 'Artikel')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Artikel',
    'crumbs' => [['label' => 'Artikel']],
    'actions' => '<a href="'.route('admin.artikel.create').'" class="btn btn-primary"><i class="feather-plus me-2"></i>Tambah Artikel</a>',
])
<div class="main-content">
    @include('admin.components.list-filters', [
        'cabors' => $cabors,
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
                            <th>Cabor</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($artikels as $a)
                        <tr>
                            <td>{{ $artikels->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold">{{ $a->title }}</td>
                            <td>{{ $a->cabor?->name ?? 'Umum' }}</td>
                            <td>
                                <span class="badge {{ $a->is_published ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}">
                                    {{ $a->is_published ? 'Terbit' : 'Draft' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <x-admin.components.table-actions>
                                    <a href="{{ route('admin.artikel.edit', $a) }}" class="btn btn-sm btn-light-brand">Edit</a>
                                    <form action="{{ route('admin.artikel.destroy', $a) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </x-admin.components.table-actions>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada artikel.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $artikels->links() }}</div>
        </div>
    </div>
</div>
@endsection
