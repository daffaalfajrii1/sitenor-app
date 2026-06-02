@extends('admin.layouts.app')
@section('title', 'Super Admin')

@section('content')
@include('admin.components.page-header', [
    'title' => 'Super Admin',
    'crumbs' => [['label' => 'Super Admin']],
    'actions' => '<a href="'.route('admin.super-admin.create').'" class="btn btn-primary"><i class="feather-plus me-2"></i>Tambah Super Admin</a>',
])
<div class="main-content">
    @include('admin.components.list-filters', [
        'showCabor' => false,
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
                            <th>Terdaftar</th>
                            <th class="text-end" style="min-width:180px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($superAdmins as $admin)
                        <tr>
                            <td class="text-muted">{{ $superAdmins->firstItem() + $loop->index }}</td>
                            <td>
                                @if ($admin->avatarUrl())
                                    <img src="{{ $admin->avatarUrl() }}" width="40" height="40" class="rounded-circle object-fit-cover" alt="">
                                @else
                                    <span class="avatar-text avatar-sm bg-primary text-white d-inline-flex align-items-center justify-content-center">
                                        <i class="feather-shield"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="sitenor-cell-name">
                                {{ $admin->name }}
                                @if ($admin->id === auth()->id())
                                    <span class="badge bg-light text-dark border ms-1">Anda</span>
                                @endif
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td class="text-muted fs-12">{{ $admin->created_at?->format('d M Y') }}</td>
                            <td class="text-end">
                                <x-admin.components.table-actions>
                                    <a href="{{ route('admin.super-admin.edit', $admin) }}" class="btn btn-sm btn-light-brand">Edit</a>
                                    @if ($admin->id !== auth()->id())
                                        <form action="{{ route('admin.super-admin.destroy', $admin) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus super admin ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    @endif
                                </x-admin.components.table-actions>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                Belum ada super admin.
                                <a href="{{ route('admin.super-admin.create') }}" class="d-block mt-2 fw-semibold">Tambah super admin pertama</a>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">{{ $superAdmins->links() }}</div>
        </div>
    </div>
</div>
@endsection
