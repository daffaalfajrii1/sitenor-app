@extends('cabor.layouts.app')
@section('title', 'Tambah Juri')
@section('content')
@include('admin.components.page-header', ['title' => 'Tambah Juri', 'crumbs' => [['label' => 'Juri', 'url' => cabor_route('cabor.juri.index')], ['label' => 'Tambah']]])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ cabor_route('cabor.juri.store') }}" method="POST" enctype="multipart/form-data">@csrf
@include('admin.juri._form', ['hideCaborSelect' => true])
<div class="mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ cabor_route('cabor.juri.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
