@extends('admin.layouts.app')
@section('title', 'Edit Juri')
@section('content')
@include('admin.components.page-header', ['title' => 'Edit Juri'])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ route('admin.juri.update', $juri) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
@include('admin.juri._form', ['item' => $juri])
<div class="mt-4"><button type="submit" class="btn btn-primary">Perbarui</button><a href="{{ route('admin.juri.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
