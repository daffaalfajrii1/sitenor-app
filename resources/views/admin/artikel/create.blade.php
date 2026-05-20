@extends('admin.layouts.app')
@section('content')
@include('admin.components.page-header',['title'=>'Tambah Artikel'])
<div class="main-content"><div class="card"><div class="card-body"><form action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data">@csrf @include('admin.artikel._form')<button class="btn btn-primary mt-3">Simpan</button></form></div></div></div>
@endsection
