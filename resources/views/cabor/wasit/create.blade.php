@extends('cabor.layouts.app')
@section('title', 'Tambah Wasit')
@section('content')
@include('admin.components.page-header', ['title' => 'Tambah Wasit', 'crumbs' => [['label' => 'Wasit', 'url' => cabor_route('cabor.wasit.index')], ['label' => 'Tambah']]])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ cabor_route('cabor.wasit.store') }}" method="POST" enctype="multipart/form-data">@csrf
@include('admin.wasit._form', ['hideCaborSelect' => true])
<div class="mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ cabor_route('cabor.wasit.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
