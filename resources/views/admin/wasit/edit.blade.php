@extends('admin.layouts.app')
@section('title', 'Edit Wasit')
@section('content')
@include('admin.components.page-header', ['title' => 'Edit Wasit'])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ route('admin.wasit.update', $wasit) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
@include('admin.wasit._form', ['item' => $wasit])
<div class="mt-4"><button type="submit" class="btn btn-primary">Perbarui</button><a href="{{ route('admin.wasit.index') }}" class="btn btn-light ms-2">Batal</a></div>
</form></div></div></div>
@endsection
