@extends('admin.layouts.app')
@section('title', 'Kepala Cabor')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Kepala Cabor',
    'crumbs' => [['label' => 'Kepala Cabor']],
    'actions' => '<a href="'.route('admin.kepala-cabor.create').'" class="btn btn-primary"><i class="feather-plus me-2"></i>Tambah Kepala Cabor</a>',
])
<div class="main-content">
    @include('admin.components.list-filters', [
        'cabors' => $cabors,
        'showStatus' => false,
        'searchPlaceholder' => 'Cari nama atau email...',
    ])
    <div class="card sitenor-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table sitenor-data-table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th style="width:56px">Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Cabor</th>
                            <th>Telepon</th>
                            <th class="text-end" style="min-width:180px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($kepalaCabors as $kc)
                        <tr>
                            <td class="text-muted">{{ $kepalaCabors->firstItem() + $loop->index }}</td>
                            <td>
                                @if($kc->avatarUrl())
                                    <img src="{{ $kc->avatarUrl() }}" width="40" height="40" class="rounded-circle object-fit-cover" alt="">
                                @else
                                    <span class="avatar-text avatar-sm bg-primary text-white d-inline-flex align-items-center justify-content-center"><i class="feather-user"></i></span>
                                @endif
                            </td>
                            <td class="sitenor-cell-name">{{ $kc->name }}</td>
                            <td>{{ $kc->email }}</td>
                            <td>{{ $kc->cabor?->name ?? '-' }}</td>
                            <td>{{ $kc->phone ?? '-' }}</td>
                            <td class="text-end">
                                <x-admin.components.table-actions>
                                    <a href="{{ route('admin.kepala-cabor.edit', $kc) }}" class="btn btn-sm btn-light-brand">Edit</a>
                                    <form action="{{ route('admin.kepala-cabor.destroy', $kc) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus kepala cabor ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </x-admin.components.table-actions>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                Belum ada kepala cabor.
                                <a href="{{ route('admin.kepala-cabor.create') }}" class="d-block mt-2 fw-semibold">Tambah kepala cabor pertama</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">{{ $kepalaCabors->links() }}</div>
        </div>
    </div>
</div>
@endsection
