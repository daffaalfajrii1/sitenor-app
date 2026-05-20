@extends('admin.layouts.app')
@section('title', 'Tambah Atlet')
@section('content')
@include('admin.components.page-header', ['title' => 'Tambah Atlet', 'breadcrumb' => '<li class="breadcrumb-item"><a href="'.route('admin.atlet.index').'">Atlet</a></li><li class="breadcrumb-item">Tambah</li>'])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ route('admin.atlet.store') }}" method="POST" enctype="multipart/form-data">@csrf
@include('admin.atlet._form')
<div class="mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ route('admin.atlet.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
