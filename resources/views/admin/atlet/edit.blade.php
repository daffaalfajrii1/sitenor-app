@extends('admin.layouts.app')
@section('title', 'Edit Atlet')
@section('content')
@include('admin.components.page-header', ['title' => 'Edit: '.$atlet->name, 'breadcrumb' => '<li class="breadcrumb-item"><a href="'.route('admin.atlet.index').'">Atlet</a></li><li class="breadcrumb-item">Edit</li>'])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ route('admin.atlet.update', $atlet) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
@include('admin.atlet._form', ['item' => $atlet])
<div class="mt-4"><button type="submit" class="btn btn-primary">Perbarui</button><a href="{{ route('admin.atlet.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form>
@if($atlet->prestasis->isNotEmpty())
<hr><h6 class="fw-bold">Prestasi</h6><ul class="list-group">@foreach($atlet->prestasis as $p)<li class="list-group-item d-flex justify-content-between"><span>{{ $p->title }} ({{ $p->level }})</span><a href="{{ route('admin.prestasi.edit', $p) }}" class="btn btn-sm btn-light-brand">Edit</a></li>@endforeach</ul>
@endif
</div></div></div>
@endsection
