@extends('admin.layouts.app')
@section('title','Artikel')
@section('content')
@include('admin.components.page-header',['title'=>'Artikel','breadcrumb'=>'<li class="breadcrumb-item">Artikel</li>','actions'=>'<a href="'.route('admin.artikel.create').'" class="btn btn-primary"><i class="feather-plus me-2"></i>Tambah</a>'])
<div class="main-content"><div class="card"><div class="card-body p-0"><table class="table table-hover mb-0"><thead><tr><th>Judul</th><th>Cabor</th><th>Status</th><th>Aksi</th></tr></thead><tbody>@forelse($artikels as $a)<tr><td>{{ $a->title }}</td><td>{{ $a->cabor?->name ?? 'Umum' }}</td><td>{{ $a->is_published ? 'Terbit' : 'Draft' }}</td><td><a href="{{ route('admin.artikel.edit',$a) }}" class="btn btn-sm btn-light-brand">Edit</a><form action="{{ route('admin.artikel.destroy',$a) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form></td></tr>@empty<tr><td colspan="4" class="text-center py-4 text-muted">Belum ada artikel</td></tr>@endforelse</tbody></table><div class="p-3">{{ $artikels->links() }}</div></div></div></div>
@endsection
