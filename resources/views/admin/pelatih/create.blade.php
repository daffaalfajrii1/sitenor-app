@extends('admin.layouts.app')
@section('title', 'Tambah Pelatih')
@section('content')
@include('admin.components.page-header', ['title' => 'Tambah Pelatih'])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ route('admin.pelatih.store') }}" method="POST" enctype="multipart/form-data">@csrf
@include('admin.pelatih._form')
<div class="mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ route('admin.pelatih.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
