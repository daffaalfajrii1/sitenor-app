@extends('admin.layouts.app')
@section('content')
@include('admin.components.page-header',['title'=>'Edit Pengumuman'])
<div class="main-content"><div class="card"><div class="card-body"><form action="{{ route('admin.pengumuman.update',$pengumuman) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.pengumuman._form',['item'=>$pengumuman])<button class="btn btn-primary mt-3">Perbarui</button></form></div></div></div>
@endsection
