@extends('cabor.layouts.app')
@section('title', 'Edit Atlet')
@section('content')
@include('admin.components.page-header', ['title' => 'Edit Atlet', 'crumbs' => [['label' => 'Atlet', 'url' => cabor_route('cabor.atlet.index')], ['label' => 'Edit']]])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ cabor_route('cabor.atlet.update', $atlet) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
@include('admin.atlet._form', ['hideCaborSelect' => true, 'hideNik' => true, 'atlet' => $atlet])
<div class="mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ cabor_route('cabor.atlet.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
