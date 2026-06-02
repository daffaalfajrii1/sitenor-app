@extends('cabor.layouts.app')
@section('title', 'Edit Wasit')
@section('content')
@include('admin.components.page-header', ['title' => 'Edit Wasit', 'crumbs' => [['label' => 'Wasit', 'url' => cabor_route('cabor.wasit.index')], ['label' => 'Edit']]])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ cabor_route('cabor.wasit.update', $wasit) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
@include('admin.wasit._form', ['hideCaborSelect' => true, 'wasit' => $wasit])
<div class="mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ cabor_route('cabor.wasit.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
