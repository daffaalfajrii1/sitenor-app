@extends('cabor.layouts.app')
@section('title', 'Edit Pelatih')
@section('content')
@include('admin.components.page-header', ['title' => 'Edit Pelatih', 'crumbs' => [['label' => 'Pelatih', 'url' => cabor_route('cabor.pelatih.index')], ['label' => 'Edit']]])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ cabor_route('cabor.pelatih.update', $pelatih) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
@include('admin.pelatih._form', ['hideCaborSelect' => true, 'pelatih' => $pelatih])
<div class="mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ cabor_route('cabor.pelatih.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
