@extends('admin.layouts.app')
@section('title', 'Tambah Juri')
@section('content')
@include('admin.components.page-header', ['title' => 'Tambah Juri'])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ route('admin.juri.store') }}" method="POST" enctype="multipart/form-data">@csrf
@include('admin.juri._form')
<div class="mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ route('admin.juri.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
