@extends('admin.layouts.app')
@section('content')
@include('admin.components.page-header',['title'=>'Tambah Prestasi'])
<div class="main-content"><div class="card"><div class="card-body"><form action="{{ route('admin.prestasi.store') }}" method="POST">@csrf @include('admin.prestasi._form')<button class="btn btn-primary mt-3">Simpan</button></form></div></div></div>
@endsection
