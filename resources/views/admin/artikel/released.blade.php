@extends('admin.layouts.app')
@section('title', 'Artikel Rilis')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Monitor Artikel Rilis',
    'crumbs' => [
        ['label' => 'Artikel', 'url' => route('admin.artikel.index')],
        ['label' => 'Rilis'],
    ],
    'actions' => '<a href="'.route('admin.artikel.index').'" class="btn btn-light-brand me-2">Semua Artikel</a><a href="'.route('admin.artikel.create').'" class="btn btn-primary"><i class="feather-plus me-2"></i>Tambah Artikel</a>',
])
<div class="main-content">
    <div class="alert alert-light border mb-3 d-flex align-items-center gap-2">
        <i class="feather-info"></i>
        <span>Halaman ini menampilkan artikel yang sudah <strong>diterbitkan</strong> di sistem.</span>
    </div>
    @include('admin.components.list-filters', [
        'cabors' => $cabors,
        'showStatus' => false,
        'showPublished' => false,
        'searchPlaceholder' => 'Cari judul artikel...',
    ])
    <div class="card sitenor-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table sitenor-data-table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Judul</th>
                            <th>Cabor</th>
                            <th>Penulis</th>
                            <th>Tanggal Rilis</th>
                            <th class="text-end" style="min-width:120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($artikels as $a)
                        <tr>
                            <td class="text-muted">{{ $artikels->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="sitenor-cell-name">{{ $a->title }}</div>
                                @if($a->excerpt)
                                    <div class="text-muted fs-12 text-truncate" style="max-width:320px">{{ $a->excerpt }}</div>
                                @endif
                            </td>
                            <td>{{ $a->cabor?->name ?? 'Umum' }}</td>
                            <td>{{ $a->user?->name ?? '—' }}</td>
                            <td>
                                @if($a->published_at)
                                    <span class="d-block">{{ $a->published_at->format('d M Y') }}</span>
                                    <span class="text-muted fs-12">{{ $a->published_at->format('H:i') }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-end">
                                <x-admin.components.table-actions>
                                    <a href="{{ route('admin.artikel.edit', $a) }}" class="btn btn-sm btn-light-brand">Edit</a>
                                </x-admin.components.table-actions>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Belum ada artikel yang diterbitkan.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">{{ $artikels->links() }}</div>
        </div>
    </div>
</div>
@endsection
