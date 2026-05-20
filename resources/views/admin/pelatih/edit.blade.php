@extends('admin.layouts.app')
@section('title', 'Edit Pelatih')
@section('content')
@include('admin.components.page-header', ['title' => 'Edit Pelatih'])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ route('admin.pelatih.update', $pelatih) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
@include('admin.pelatih._form', ['item' => $pelatih])
<div class="mt-4"><button type="submit" class="btn btn-primary">Perbarui</button><a href="{{ route('admin.pelatih.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
