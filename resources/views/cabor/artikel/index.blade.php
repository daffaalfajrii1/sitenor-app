@extends('cabor.layouts.app')
@section('title', 'Artikel')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Artikel',
    'crumbs' => [['label' => 'Artikel']],
    'actions' => '<a href="'.cabor_route('cabor.artikel.create').'" class="btn btn-primary btn-sm">Tambah Artikel</a>',
])
<div class="main-content">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table sitenor-data-table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <x-table-no-th />
                            <th>Judul</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($artikels as $a)
                            <tr>
                                <x-table-no-td :index="$loop->index" :paginator="$artikels" />
                                <td class="sitenor-cell-name">{{ $a->title }}</td>
                                <td>
                                    <span class="badge {{ $a->is_published ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }}">
                                        {{ $a->is_published ? 'Terbit' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex flex-nowrap gap-2">
                                        <a href="{{ cabor_route('cabor.artikel.edit', $a) }}" class="btn btn-sm btn-light-brand">Edit</a>
                                        <form action="{{ cabor_route('cabor.artikel.destroy', $a) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus artikel ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada artikel</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($artikels->hasPages())
                <div class="p-3 border-top">{{ $artikels->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
