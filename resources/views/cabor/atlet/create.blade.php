@extends('cabor.layouts.app')
@section('title', 'Tambah Atlet')
@section('content')
@include('admin.components.page-header', ['title' => 'Tambah Atlet', 'crumbs' => [['label' => 'Atlet', 'url' => cabor_route('cabor.atlet.index')], ['label' => 'Tambah']]])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ cabor_route('cabor.atlet.store') }}" method="POST" enctype="multipart/form-data">@csrf
@include('admin.atlet._form', ['hideCaborSelect' => true, 'hideNik' => true])
<div class="mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ cabor_route('cabor.atlet.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
