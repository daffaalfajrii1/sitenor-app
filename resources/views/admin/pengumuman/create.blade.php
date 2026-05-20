@extends('admin.layouts.app')
@section('content')
@include('admin.components.page-header',['title'=>'Upload Pengumuman'])
<div class="main-content"><div class="card"><div class="card-body"><form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">@csrf @include('admin.pengumuman._form')<button class="btn btn-primary mt-3">Upload</button></form></div></div></div>
@endsection
