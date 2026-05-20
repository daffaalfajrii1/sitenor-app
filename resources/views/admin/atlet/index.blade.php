@extends('admin.layouts.app')
@section('title', 'Atlet')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Data Atlet',
    'breadcrumb' => '<li class="breadcrumb-item">Atlet</li>',
    'actions' => '<a href="'.route('admin.atlet.create').'" class="btn btn-primary"><i class="feather-plus me-2"></i>Tambah Atlet</a>',
])
<div class="main-content">
    <div class="card mb-3"><div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-4"><select name="cabor_id" class="form-control"><option value="">Semua Cabor</option>@foreach($cabors as $c)<option value="{{ $c->id }}" @selected(request('cabor_id')==$c->id)>{{ $c->name }}</option>@endforeach</select></div>
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Cari nama..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><button class="btn btn-light-brand w-100">Filter</button></div>
        </form>
    </div></div>
    <div class="card"><div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Nama</th><th>Cabor</th><th>NIK</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                @forelse ($atlets as $atlet)
                <tr>
                    <td>{{ $atlet->name }}</td>
                    <td>{{ $atlet->cabor?->name }}</td>
                    <td>{{ $atlet->nik ?? '-' }}</td>
                    <td><span class="badge {{ $atlet->is_active ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">{{ $atlet->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <a href="{{ route('admin.prestasi.create', ['atlet_id' => $atlet->id]) }}" class="btn btn-sm btn-light-brand">Prestasi</a>
                        <a href="{{ route('admin.atlet.edit', $atlet) }}" class="btn btn-sm btn-light-brand">Edit</a>
                        <form action="{{ route('admin.atlet.destroy', $atlet) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus atlet ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data atlet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $atlets->links() }}</div>
    </div></div>
</div>
@endsection


